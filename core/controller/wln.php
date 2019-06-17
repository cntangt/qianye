<?php

// 万里牛同步业务
class wln extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	// 订单状态同步，获取ERP订单状态
	public function statusAction()
	{
		$list = $this->db->setTableName('order')->getAll('status < 60 and status > 10'); // 查询待签收及之前状态订单
		$this->log(0, '待同步状态：' . count($list), '订单状态同步');
		if ($list) foreach ($list as $o) {
			$data['shop_type'] = '100';
			$data['shop_nick'] = $this->site_config['wln_shop'];
			$data['trade_ids'] = $o['erporderid'];
			$query = $this->get_query($data);

			$res = $this->http_get($this->site_config['wln_domain'] . 'trades/erp/status?' . $query);
			if ($res['success']) {
				$res = json_decode($res['response'], true)['statuses'][0];
				// 待发货：10，待揽收：20，待配送：30，配送中：40，待签收：50，已签收：60，已完成（评价）：70
				$status = 10;
				// 0：订单审核，1:打单配货，2：验货，3：称重，4：待发货，5：财审，8：已发货，9：交易成功，10：交易关闭
				switch ($res['status']) {
					case 0:
						$status = 10;
						return;
					case 1:
					case 2:
					case 3:
						$status = 20;
						break;
					case 4:
						$status = 30;
						break;
					case 5:
						return;
					case 8:
						$status = 40;
						break;
					default:
						$status = 50;
						break;
				}
				if ($status == $o['status']) return;
				$succ = $this->db->setTableName('order')->update([
					'expressname' => $res['express'],
					'expresscode' => $res['express_code'],
					'expressno' => $res['waybill'],
					'status' => $status
				], 'id = ?', $o['id']);
				$this->log($o['id'], $res['waybill'] . ' | ' . $res['tid'] . ' | ' . $res['status'], '状态同步成功');
			} else {
				$this->log($o['id'], $res['error_msg'], '状态同步失败');
			}
		}
	}

	// 订单同步，推送订单数据到ERP
	public function orderAction()
	{
		$list = $this->db->setTableName('vi_order')->getAll('status = ?', 10);
		$this->log(0, '待同步订单：' . count($list), '订单同步');
		if ($list) foreach ($list as $o) {
			$items = $this->db->setTableName('order_item')->getAll('orderid = ?', $o['id']);
			$is = array();
			foreach ($items as $i) {
				array_push($is, [
					'tradeID' => $o['id'],
					'orderID' => $i['id'],
					'itemID' => $i['sku'],
					'itemTitle' => $i['productname'],
					'skuTitle' => $i['productname'],
					'status' => 0, // 待审核订单
					'price' => 0,
					'size' => $i['quantity'],
					'snapshot' => '#',
					'imageUrl' => '#',
					'payment' => 0
				]);
			}
			$data['trades'] = json_encode([[
				'tradeID' => $o['id'],
				'shopNick' => $this->site_config['wln_shop'],
				'status' => 2,
				'createTime' => $o['createtime'] * 1000,
				'modifyTime' => $o['createtime'] * 1000,
				'shippingType' => 0,
				'totalFee' => 0,
				'postFee' => 0,
				'buyer' => $o['name'],
				'receiverName' => $o['contact'],
				'receiverProvince' => $o['province'],
				'receiverCity' => $o['city'],
				'receiverArea' => $o['area'],
				'receiverAddress' => $o['address'],
				'receiverMobile' => $o['mobile'],
				'hasRefund' => 0,
				'orders' => $is
			]]);
			$query = $this->get_query($data);
			$res = $this->http_post($this->site_config['wln_domain'] . 'trades/open', $query);
			if ($res['success']) {
				$ids = json_decode($res['response'], true);
				$erporderid = $ids[0]; // 推送订单返回erp订单编号
				// 待发货：10，待揽收：20，待配送：30，配送中：40，待签收：50，已签收：60，已完成（评价）：70
				$this->db->setTableName('order')->update(['erporderid' => $erporderid, 'status' => 20], 'id = ?', $o['id']); // 修改订单为待配送
				$this->log($o['id'], $erporderid, '订单同步成功');
			} else {
				$this->log($o['id'], $res['error_msg'], '订单同步失败');
			}
		}
	}

	private function get_query($data)
	{
		$data['app_key'] = $this->site_config['wln_key'];
		$data['format'] = 'json';
		$data['timestamp'] = time() . '621';
		ksort($data);
		$query = '';
		$str = $this->site_config['wln_secret'];
		foreach ($data as $key => $value) {
			$str .= $key . $value;
			$query .= $key . '=' . urlencode($value) . '&';
		}
		$str .= $this->site_config['wln_secret'];
		$query .= 'sign=' . strtoupper(md5($str));
		return $query;
	}

	private function log($target, $content, $type)
	{
		$this->db->setTableName('log')->insert(['type' => $type, 'target' => $target, 'content' => $content]);
	}
}

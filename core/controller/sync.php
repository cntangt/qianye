<?php

// 万里牛同步业务
class sync extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	// 订单状态同步，获取ERP订单状态
	public function statusAction()
	{
		$list = $this->db->setTableName('order')->getAll('status = ?', 30);
		$this->log(0, '待同步状态：' . count($list), '订单状态同步');
		if ($list) foreach ($list as $o) {
			$data['shop_type'] = '100';
			$data['shop_nick'] = $this->site_config['wln_shop'];
			$data['trade_ids'] = $o['erporderid'];
			$query = $this->get_query($data);

			$res = $this->http_post($this->site_config['wln_domain'] . 'trades/erp/status', $query);
			if ($res['success']) {
				$this->db->setTableName('order')->update([
					'expressname' => $res['express'],
					'expresscode' => $res['express_code'],
					'expressno' => $res['waybill']
				], 'id = ?', $o['id']);
				$this->log($o['d'], $res['waybill'] . ' | ' . $res['tid'], '状态同步成功');
			} else {
				$this->log($o['d'], $res['error_msg'], '状态同步失败');
			}
		}
	}

	// 订单同步，推送订单数据到ERP
	public function orderAction()
	{
		$list = $this->db->setTableName('order')->getAll('status = ?', 10);
		$this->log(0, '待同步订单：' . count($list), '订单同步');
		if ($list) foreach ($list as $o) {
			$items = $this->db->setTableName('order_item') . getAll('orderid = ?', $o['id']);
			$is = array();
			foreach ($items as $i) {
				array_push($is, [
					'tradeID' => $o['id'],
					'orderID' => $i('id'),
					'itemID' => $i['sku'],
					'itemTitle' => $i['productname'],
					'skuTitle' => $i['productname'],
					'status' => 2,
					'price' => 0,
					'size' => $i['quantity'],
					'snapshot' => '#',
					'imageUrl' => '#',
					'payment' => 0
				]);
			}
			$data['trades'] = json_encode([
				'tradeID' => $o['id'],
				'shopNick' => $this->site_config['wln_shop'],
				'status' => 2,
				'createTime' => $o['createtime'] . '621',
				'modifyTime' => $o['createtime'] . '621',
				'shippingType' => 0,
				'totalFee' => 0,
				'postFee' => 0,
				'buyer' => $o['customername'],
				'receiverName' => $o['contact'],
				'receiverProvince' => $o['province'],
				'receiverCity' => $o['city'],
				'receiverArea' => $o['area'],
				'receiverAddress' => $o['address'],
				'receiverMobile' => $o['phone'],
				'hasRefund' => 0,
				'orders' => $is
			]);
			$query = $this->get_query($data);
			$res = $this->http_post($this->site_config['wln_domain'] . 'trades/open', $query);
			if ($res['success']) {
				$erporderid = $res['iddddddddddddddddd']; // 推送订单返回erp订单编号
				// 待发货：10，待揽收：20，待配送：30，配送中：40，待签收：50，已签收：60，已完成（评价）：70
				$this->db->setTableName('order')->update(['erporderid' => $erporderid, 'status' => 30], 'id = ?', $o['id']); // 修改订单为待配送
				$this->log($o['d'], $erporderid, '订单同步成功');
			}
			else {
				$this->log($o['d'], $res['error_msg'], '订单同步失败');
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
			$query .= $key . '=' . $value . '&';
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

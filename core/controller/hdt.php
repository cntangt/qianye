<?php
// 汇递通同步业务
class hdt extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	// 订单状态同步，获取ERP订单状态
	public function statusAction()
	{
		$list = $this->db->setTableName('order')->getAll('status < 60 and status > 10', null, 'status,erporderid'); // 查询待签收及之前状态订单
		$this->log(0, '待同步状态：' . count($list), '订单状态同步');
		$ids = array();
		$dict = array();
		if ($list) foreach ($list as $o) {
			$dict[$o['erporderid']] = $o['status'];
			array_push($ids, $o['erporderid']);
			if (count($ids) == 10) {
				$this->update_status($ids, $dict);
				$ids = array();
			} else {
				continue;
			}
		}
		if (count($ids) > 0) {
			$this->update_status($ids, $dict);
		}
	}

	/**
	 * 批量更新订单状态
	 */
	private function update_status($ids, $dict)
	{
		$xml = simplexml_load_file($this->site_config['hdt_status'] . join($ids, ','));
		foreach ($xml as $key => $value) {
			$s = (string)$value->order_status;
			$erporderid = (string)$value->order_id;
			if ($s) {
				// 待发货：10，待揽收：20，待配送：30，配送中：40，待签收：50，已签收：60，已完成（评价）：70
				//-------------------------------------------------------------------------------------
				// SA	提货中	配送公司未提货入库	TB SB CANCEL
				// TB	配送中转中	货物已到配送公司分拣中心，尚未到配送站点	SB COB TC SC CANCEL
				//30 SB	待配送	货物在配送站点，未出库	COB DS TC SC COC CANCEL
				// COB	配送中/取货中	货物出库配送中  或者退货订单取货途中	DF DS TC SC CANCEL
				// DF	订单已妥投	送货订单妥投	TB SB CANCEL
				// DS	滞留	订单滞留，有待再次配送	DF TC SC CANCEL
				// TC	退货中转中	送货单: (部份)拒收  退换货单: 退换货成功 正在中转回分拣中心	TB SB SC COC CANCEL
				// SC	待退货	到达分拣中心	TB SB COC CANCEL
				// TD	返签中转中	签收件送货成功 正在中转回分拣中心	TB SB SD COC CANCEL
				// SD	待返签	签收面单到达分拣中心	TB SB COC CANCEL
				// RF	退货完成	退货完成	最终状态
				// CANCEL	取消	发货和数据错误
				// 订单取消	最终状态
				$status = 10;
				switch ($s) {
					case 'SA':
						$status = 10;
						break;
					case 'TB':
					case 'SB':
						$status = 30;
						break;
					case 'COB':
					case 'DS':
						$status = 40;
						break;
					case 'DF':
					case 'TC':
					case 'SC':
					case 'TD':
					case 'SD':
						$status = 50;
						break;
					case 'RF':
					case 'CANCEL':
						$status = -10;
						break;
					default:
						$status = 30;
						break;
				}
				if ($status == $dict[(string)$value->order_id] || $status == 10) continue;
				$msg = null;
				foreach ($xml->order->logs as $log) {
					foreach ($log as $k) {
						$msg = (string)$k->desc;
					}
				}
				$succ = $this->db->setTableName('order')->update([
					// 'expressname' => $res['express'],
					// 'expresscode' => $res['express_code'],
					// 'expressno' => $res['waybill'],
					'status' => $status
				], 'erporderid = ?', $erporderid);
				$this->log($erporderid, $msg, '状态同步成功');
			} else {
				$this->log($erporderid, '未获取到订单配送信息', '状态同步失败');
			}
		}
	}

	// 订单同步，推送订单数据到ERP
	public function orderAction()
	{
		$list = $this->db->setTableName('order')->getAll('status = ?', 10);
		$this->log(0, '待同步订单：' . count($list), '订单同步');
		if ($list) foreach ($list as $o) {
			$oid = sprintf("XD%'05d", $o['id']);
			$items = $this->db->setTableName('order_item')->getAll('orderid = ?', $o['id']);
			$packages = array();
			foreach ($items as $i) {
				$goods = [[
					'ID' => $i['id'],
					'GoodsID' => $i['id'],
					'GoodsName' => $i['productname'],
					'QTY' => $i['quantity']
				]];
				$package['PackageID'] = $o['id'] . '-' . $i['id'];
				$package['Goods'] = $goods;
				array_push($packages, $package);
			}
			$data = [[
				'SendOrderID' => $oid,
				'OrderType' => 70,
				'OrderValue' => 0,
				'GetValue' => 0,
				'PayType' => '',
				'linkMan' => $o['contact'],
				'HandsetNO' => $o['mobile'],
				'Address' => $o['address'],
				'PackWeight' => '0',
				'StockoutDate' => date('Y-m-d H:i:s', $o['createtime']),
				'FreshFlag' => 0,
				'ProvinceName' => $o['province'],
				'CityName' => $o['city'],
				'AreaName' => $o['area'],
				'Package' => $packages
			]];
			$res = $this->hdt($data);
			if (!$res) {
				$this->log($o['id'], '请求结果为空', '订单同步失败');
			} else if ($res['Success']) {
				$erporderid = $oid; //没有返回ID，只有写入自己ID
				// 待发货：10，待揽收：20，待配送：30，配送中：40，待签收：50，已签收：60，已完成（评价）：70
				$this->db->setTableName('order')->update(['erporderid' => $erporderid, 'status' => 20], 'id = ?', $o['id']); // 修改订单为待配送
				$this->log($o['id'], $erporderid, '订单同步成功');
			} else {
				$this->log($o['id'], $res['Remark'], '订单同步失败');
			}
		}
	}

	private function hdt($data)
	{
		$postdata['comid'] = $this->site_config['hdt_comid'];
		$postdata['data'] = $data;
		$jsondata = json_encode($postdata);
		$jsondata = urlencode($jsondata);
		$sign = md5(sprintf('jsondata=%s&key=%s', $jsondata, $this->site_config['hdt_appkey']));
		return $this->http_post($this->site_config['hdt_order'], sprintf('jsondata=%s&key=%s', $jsondata, $sign));
	}

	private function log($target, $content, $type)
	{
		$this->db->setTableName('log')->insert(['type' => $type, 'target' => $target, 'content' => $content]);
	}
}

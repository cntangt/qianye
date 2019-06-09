<?php

class ip extends Base
{
	// 当前用户信息
	private $user = null;

	public function __construct()
	{
		parent::__construct();

		$token = $_SERVER['HTTP_TOKEN'];

		$user = $this->cache->get('ip:' . $token);
		if ($user) {
			return;
		}

		$loginInfo = $this->cache->get('wx:' . $token);
		if ($loginInfo) {
			$user = $this->db->setTableName('customer') . getOne('openid = ?', $loginInfo['openid']);
			$this->cache->set('ip:' . $token, $user);
			return;
		}

		$this->json(null, false, '用户登录信息过期');
	}

	// 激活卡券
	public function activecarddAction()
	{
		$card = null;
		$qrcode = $this->post('qrcode');
		if ($qrcode) {
			$card = $this->db->setTableName('card')->getOne('qrcode = ?', $qrcode);
		} else {
			$code = $this->post('code');
			$pass = $this->post('pass');
			if (empty($code) || empty($pass)) {
				$this->json(null, false, '卡号或者密码为空');
			}
			$card = $this->db->setTableName('card')->getOne('code = ? and pass = ?', [$code, $pass]);
		}

		if ($card) {
			$ct = $this->db->setTableName('card_type')->getOne('id = ?', $card['cardtypeid']);
			if (!$ct) {
				$this->json(null, false, '卡券类型不存在，不能激活');
			}
			if ($ct['isvalid'] == 0) {
				$this->json(null, false, '卡券类型已经作废，不能激活');
			}
			if ($ct['begintime'] > time()) {
				$this->json(null, false, '卡券激活开始时间为：' . date('Y-m-d', $ct['begintime']) . '，不能激活');
			}
			if ($ct['endtime'] < time()) {
				$this->json(null, false, '卡券激活截止时间为：' . date('Y-m-d', $ct['endtime']) . '，不能激活');
			}
			$ctis = $this->db->setTableName('card_type_item')->getAll('cardtypeid = ?', $ct['id']);
			if (!$ctis) {
				$this->json(null, false, '未绑定卡券类型商品，不能激活');
			}
			$sql = 'INSERT INTO `xiao_card_item`(`cardid`, `sku`, `cardtypeid`, `productname`, `quantity`, `validquantity`) VALUES ';
			$vals = array();
			foreach ($ctis as $i) {
				array_push($vals, sprintf("(%s,'%s',%s,'%s',%s,%s)", $card['id'], $i['sku'], $card['cardtypeid'], $i['productname'], $i['quantity'], $i['quantity']));
			}
			// 添加卡券类型明细到激活卡券明细
			if (!$this->db->execute($sql . join(',', $vals))) {
				$this->json(null, false, '激活卡券明细失败，请重试');
			}
			// 设置卡券激活用户信息
			if (!$this->db->setTableName('card')->update(['customermobile' => $this->user['mobile'], 'customerid' => $this->user['id']], 'id = ?', $card['id'])) {
				$this->db->setTableName('card_item')->delete('cardid = ?', $card['id']);
				$this->json(null, false, '激活卡券失败，请重试');
			} else {
				$this->json(null, true, '激活卡券成功');
			}
		} else {
			$this->json(null, false, '未找到激活卡券，请确认卡券编码和密码');
		}
	}
}

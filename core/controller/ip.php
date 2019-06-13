<?php

class ip extends Base
{
	// 当前用户信息
	protected $user = null;

	public function __construct()
	{
		parent::__construct();

		$token = $_SERVER['HTTP_TOKEN'];

		$this->user = $this->cache->get('ip:' . $token);
		if ($this->user) {
			return;
		}

		$loginInfo = $this->cache->get('wx:' . $token);
		if ($loginInfo) {
			$this->user = $this->db->setTableName('customer')->getOne('openid = ?', $loginInfo['openid']);
			$this->cache->set('ip:' . $token, $this->user);
			//验证成功,再次刷新token避免操作中途过期
			$this->cache->set('wx:' . $_SERVER['HTTP_TOKEN'], $loginInfo);
			return;
		}

		$this->json(null, false, '用户登录信息过期');
	}

	// 激活卡券
	public function activecardAction()
	{
		$uts = $this->cache->get('useractivetimes:' . $this->user['id']);
		if ($uts > 100) {
			$this->json(null, false, '检测到风险操作，请24小时后重试');
		}
		$uts += 1;
		$this->cache->set('useractivetimes:' . $this->user['id'], $uts, 3600 * 24);

		$card = null;
		$qrcode = null; //  暂时禁用二维码激活 $this->post('qrcode');
		if ($qrcode) {
			$card = $this->db->setTableName('card')->getOne('qrcode = ?', $qrcode);
		} else {
			$code = $this->post('code');
			$pass = $this->post('pass');
			if (empty($code) || empty($pass)) {
				$this->json(null, false, '卡号或者密码为空');
			}
			$times = $this->cache->get('cardactivetimes:' . $code);
			if ($times > 10) {
				$this->json(null, false, '卡券激活次数超出限制，请24小时后重试');
			}
			$times += 1;
			$this->cache->set('cardactivetimes:' . $code, $times, 3600 * 24);
			$card = $this->db->setTableName('card')->getOne('code = ? and pass = ?', [$code, $pass]);
		}

		if ($card) {
			switch ($card['status']) {
				case 10:
					$this->json(null, false, '未销售的卡券不能激活');
				case 30:
					$this->json(null, false, '已经激活的卡券不能激活');
				case 40:
					$this->json(null, false, '已作废的卡券不能激活');
			}
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
			if (!$this->db->setTableName('card')->update([
				'customermobile' => $this->user['mobile'],
				'customerid' => $this->user['id'],
				'status' => 30,
				'activetime' => time(),
				'exptime' => time() + $ct['vailddays'] * 24 * 3600
			], 'id = ?', $card['id'])) {
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

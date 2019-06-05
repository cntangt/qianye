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

	public function demoAction()
	{
		$this->json($this->user, true);
	}
}

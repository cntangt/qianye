<?php
include_once SDK_DIR . 'wx/wxBizDataCrypt.php';

class api extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function wxloginAction()
	{
		$code = $this->get('code');

		$res = $this->http_get(sprintf(
			'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
			$this->site_config['wx_appid'],
			$this->site_config['wx_secret'],
			$code
		));

		if (isset($res['errcode'])) {
			$this->json(null, false, '微信登录失败，请重试');
		}

		$key = md5($code);
		$this->cache->set('wx:' . $key, $res);

		$user = $this->db->setTableName('customer')->getOne('openid = ?', $res['openid'], 'nickname,mobile,headimg');

		$data['token'] = $key;
		if ($user) {
			$data['user'] = $user;
		} else {
			$data['user'] = null;
		}

		$this->json($data, true);
	}

	public function wxregAction()
	{
		$loginInfo = $this->get_lgoinInfo();

		$user = $this->db->setTableName('customer')->getOne('openid = ?', $loginInfo['openid']);
		if ($user) {
			$this->json(null, true, '用户已经存在');
		} else {
			$addRes = $this->db->setTableName('customer')->insert([
				'name' => $this->post('nickName'),
				'nickname' => $this->post('nickName'),
				'openid' =>  $loginInfo['openid'],
				'unionid' => isset($loginInfo['unionid']) ? $loginInfo['unionid'] : '',
				'headimg' => $this->post('avatarUrl'),
				'createtime' => time()
			]);
			if ($addRes) {
				$this->json(null, true, '绑定用户成功');
			} else {
				$this->json(null, false, '绑定用户失败');
			}
		}
	}

	public function activeAction()
	{
		$loginInfo = $this->get_lgoinInfo();
		$pc = new WXBizDataCrypt($this->site_config['wx_appid'], $loginInfo['session_key']);
		$errCode = $pc->decryptData($this->post('encryptedData'), $this->post('iv'), $json);

		if ($errCode == 0) {
			$data = json_decode($json, true);
			$mobile = $data['purePhoneNumber'];
			$customer = $this->db->setTableName('customer')->getOne('openid = ?', $loginInfo['openid']);
			if ($customer) {
				$this->db->setTableName('customer')->update(['mobile' => $mobile], 'id = ?', $customer['id']);
				$this->json(null, true, '绑定用户手机成功');
			} else {
				$this->json(null, false, '用户不存在');
			}
		} else {
			$this->json(null, false, '获取手机号码失败');
		}
	}

	public function checkcodeAction()
	{
		$api    = xiaocms::load_class('image');
		$width  = $this->get('width');
		$height = $this->get('height');
		$api->checkcode($width, $height);
	}

	public function checknoAction()
	{
		$data = $this->post('data');

		if (empty($data['pre'])) {
			$this->json(null, false, '请输入卡号前缀');
		}
		if (empty($data['no'])) {
			$this->json(null, false, '请输入自增号');
		}
		if (empty($data['qua'])) {
			$this->json(null, false, '请输入生成卡号数量');
		}

		$len = strlen($data['no']);
		$min = intval($data['no']);
		$max = $min + intval($data['qua']) - 1;
		$len = max($len, strlen($max));
		$pre = strtoupper($data['pre']);

		$mins = $pre . str_pad($min, $len, '0', STR_PAD_LEFT);
		$maxs = $pre . str_pad($max, $len, '0', STR_PAD_LEFT);

		if ($this->db->setTableName('card')->count('codepre = ? and codelen= ? and codeno >= ? and codeno <= ?', [$pre, $len, $min, $max]) > 0) {
			$this->json(null, false, '存在重复卡号');
		}

		$this->json($mins . ' - ' . $maxs, true);
	}

	public function checkcountAction()
	{
		$data = $this->post('data');

		if (empty($data['pre'])) {
			$this->json(0, false, '请输入卡号前缀');
		}
		if (empty($data['begin'])) {
			$this->json(0, false, '请输入起始卡号');
		}
		if (empty($data['end'])) {
			$this->json(0, false, '请输入结尾卡号');
		}

		$pre = strtoupper($data['pre']);
		$min = intval($data['begin']);
		$max = intval($data['end']);

		if ($min > $max) {
			$this->json(0, false, '起始卡号不能大于结尾卡号');
		}

		$len = strlen($data['end']);

		$mins = $pre . str_pad($min, $len, '0', STR_PAD_LEFT);
		$maxs = $pre . str_pad($max, $len, '0', STR_PAD_LEFT);

		$count = $this->db->setTableName('card')->count('codepre = ? and codelen= ? and codeno >= ? and codeno <= ?', [$pre, $len, $min, $max]);

		$this->json($count, true, sprintf('%s%s - %s%s 匹配%d条', $pre, str_pad($min, $len, '0', STR_PAD_LEFT), $pre, str_pad($max, $len, '0', STR_PAD_LEFT), $count));
	}

	public function checktokenAction(){
		$loginInfo = $this->get_lgoinInfo();
		$this->json(null,true);
	}
	private function get_lgoinInfo()
	{
		$loginInfo = $this->cache->get('wx:' . $_SERVER['HTTP_TOKEN']);

		if (!$loginInfo) {
			$this->json(null, false, '微信登录过期，请重新登录',-1);
		}else{
			//验证成功,再次刷新token避免操作中途过期
			$this->cache->set('wx:' . $_SERVER['HTTP_TOKEN'], $loginInfo);
		}

		return $loginInfo;
	}
}

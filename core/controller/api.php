<?php

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

		$key = md5($code);
		$this->cache->set('wx:' . $key, $res);

		$this->json($key, true);
	}
	public function ajaxkwAction()
	{
		$subject = $this->post('data');
		if (empty($subject)) exit('');
		$data = @implode('', file('http://keyword.discuz.com/related_kw.html?ics=utf-8&ocs=utf-8&title=' . rawurlencode($subject) . '&content=' . rawurlencode($subject)));
		if ($data) {
			$parser = xml_parser_create();
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parse_into_struct($parser, $data, $values, $index);
			xml_parser_free($parser);
			$kws = array();
			foreach ($values as $valuearray) {
				if ($valuearray['tag'] == 'kw' || $valuearray['tag'] == 'ekw') {
					$kws[] = trim($valuearray['value']);
				}
			}
			echo implode(',', $kws);
		}
	}

	public function userAction()
	{
		if (!defined('XIAOCMS_MEMBER')) exit();
		ob_start();
		$this->view->display('member/user.html');
		$html = ob_get_contents();
		ob_clean();
		$html = addslashes(str_replace(array("\r", "\n", "\t"), array('', '', ''), $html));
		echo 'document.write("' . $html . '");';
	}

	public function hitsAction()
	{
		$id   = (int)$this->get('id');
		if (empty($id))	exit;
		$data = $this->db->setTableName('content')->find($id, 'hits');
		$hits = $data['hits'] + 1;
		$this->db->setTableName('content')->update(array('hits' => $hits), 'id=?', $id);
		echo "document.write('$hits');";
	}

	public function pinyinAction()
	{
		echo word2pinyin($this->post('name'));
	}

	public function indexAction()
	{
		$cards = $this->db->setTableName('card')->findAll();
		$this->json($cards);
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
}

<?php
class card extends Admin
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction()
	{
		if ($this->isajax) {

			return;
		}
		include $this->admin_tpl('card_index');
	}

	public function buildAction()
	{
		$arr = $this->pass(['len' => $this->get('len'), 'type' => $this->get('type')], 1000);
	
		return;
		if ($this->isajax) {
			$data = $this->post('data');

			$this->json(null, true);
		}
		$list = $this->db->setTableName('vi_card_type')->where('canbuild = 1 and isvalid = 1 and endtime > ?', time())->getAll();

		include $this->admin_tpl('card_build');
	}

	private function pass($data, $count)
	{
		$len = $data['len'];
		$str = '';
		switch ($data['type']) {
			case 10:
				$str = '0123456789';
				break;
			case 20:
				$str = 'QWERTYUIOPLKJHGFDSAZXCVBNM';
				break;
			default:
				$str = '0123456789QWERTYUIOPLKJHGFDSAZXCVBNM';
				break;
		}

		$max = strlen($str) - 1;
		$arr = array();
		for ($j = 0; $j < $count; $j++) {
			$rr = '';
			for ($i = 0; $i < $len; $i++) {
				$rr .=	substr($str, mt_rand(0, $max), 1);
			}
			$arr[$j] = $rr;
		}
		return $arr;
	}
}

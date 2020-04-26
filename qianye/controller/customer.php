<?php
class customer extends Admin
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction()
	{
		if ($this->isajax) {
			$page = $this->get('page');
			$size = 15;
			$data = $this->condition();
			$total = $this->db->setTableName('customer')->count($data['where'], $data['values']);
			$list = $this->db->setTableName('vi_customer')->pageLimit($page, $size)->getAll($data['where'], $data['values'], null, 'id DESC');
			$pagelist = xiaocms::load_class('pager');
			$pagelist = $pagelist->total($total)->url(url('customer/index', $data['data']) . '&page=[page]')->ext(true)->num($size)->page($page)->output();

			include $this->admin_tpl('customer_list');
			return;
		}

		include $this->admin_tpl('customer_index');
	}

	// public function exportAction()
	// {
	// 	$data = $this->condition();
	// 	$list = $this->db->setTableName('vi_wealth')->getAll(
	// 		$data['where'],
	// 		$data['values'],
	// 		"id,name,mobile,productname,validquantity,from_unixtime(exptime,'%Y-%m-%d')",
	// 		'id desc'
	// 	);
	// 	exportToExcel(date('YmdHis') . '会员财富.csv', ['编号', '会员名称', '手机号码', '商品名称', '剩余数量', '提货有效期'], $list);
	// }

	private function condition()
	{
		$mobile = $this->get('mobile');
		$name = $this->get('name');
		$superior = $this->get('superior');

		$where = array();
		$values = array();
		$data = array();

		if ($mobile) {
			array_push($where, 'mobile = ?');
			array_push($values, $mobile);
			$data['mobile'] = $mobile;
		}
		if ($name) {
			array_push($where, 'name like ?');
			array_push($values, '%' . $name . '%');
			$data['name'] = $name;
		}
		if ($superior) {
			array_push($where, 'superior = ?');
			array_push($values, $superior);
			$data['superior'] = $superior;
		}

		return ['where' => $where, 'values' => $values, 'data' => $data];
	}
}

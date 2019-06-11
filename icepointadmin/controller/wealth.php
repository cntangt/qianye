<?php
class wealth extends Admin
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
			$total = $this->db->setTableName('vi_wealth')->count($data['where'], $data['values']);
			$list = $this->db->setTableName('vi_wealth')->pageLimit($page, $size)->getAll($data['where'], $data['values'], null, 'id DESC');
			$pagelist = xiaocms::load_class('pager');
			$pagelist = $pagelist->total($total)->url(url('wealth/index', $data['data']) . '&page=[page]')->ext(true)->num($size)->page($page)->output();

			include $this->admin_tpl('wealth_list');
			return;
		}

		include $this->admin_tpl('wealth_index');
	}

	public function editAction()
	{
		if ($this->ispost) {
			$quantity = $this->post('quantity');
			$id = $this->post('id');
			if ($quantity < 1) $this->json(null, false, '增加数量不能小于1');
			$data = $this->db->setTableName('card_item')->find($id);
			if ($data) {
				$succ =	$this->db->setTableName('card_item')->update(['validquantity' => $data['validquantity'] + $quantity], 'id = ?', $id);
				if ($succ) {
					$this->json(null, true, '增加可提数量成功');
				}
			}
			$this->json(null, false, '增加可提数量失败');
		}

		$id = $this->get('id');
		$data = $this->db->setTableName('card_item')->getOne('id = ?', $id);
		include $this->admin_tpl('wealth_edit');
	}

	public function exportAction()
	{
		$data = $this->condition();
		$list = $this->db->setTableName('vi_wealth')->getAll(
			$data['where'],
			$data['values'],
			"id,name,mobile,productname,validquantity,from_unixtime(exptime,'%Y-%m-%d')",
			'id desc'
		);
		exportToExcel(date(YmdHis) . '会员财富.csv', ['编号', '会员名称', '手机号码', '商品名称', '剩余数量', '提货有效期'], $list);
	}

	private function condition()
	{
		$mobile = $this->get('mobile');
		$pdname = $this->get('pdname');

		$where = array();
		$values = array();
		$data = array();

		if ($mobile) {
			array_push($where, 'mobile = ?');
			array_push($values, $mobile);
			$data['mobile'] = $mobile;
		}
		if ($pdname) {
			array_push($where, 'productname like ?');
			array_push($values, '%' . $pdname . '%');
			$data['pdname'] = $pdname;
		}

		return ['where' => $where, 'values' => $values, 'data' => $data];
	}
}

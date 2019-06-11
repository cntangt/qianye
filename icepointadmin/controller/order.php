<?php
class order extends Admin
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
			$status = $this->get('status');
			$area = $this->get('area');
			$mobile = $this->get('mobile');
			$pdname = $this->get('pdname');
			$begin = $this->get('begin');
			$end = $this->get('end');
			$where = array();
			$values = array();
			if ($status) {
				array_push($where, 'status = ?');
				array_push($values, $status);
			}
			if ($area) {
				array_push($where, 'area = ?');
				array_push($values, $area);
			}
			if ($mobile) {
				array_push($where, 'mobile = ?');
				array_push($values, $mobile);
			}
			if ($pdname) {
				array_push($where, 'productname like ?');
				array_push($values, '%' . $pdname . '%');
			}
			if ($begin) {
				array_push($where, 'createtime > ?');
				array_push($values, strtotime($begin));
			}
			if ($end) {
				array_push($where, 'createtime < ?');
				array_push($values, strtotime($end) + 3600 * 24);
			}
			$total = $this->db->setTableName('vi_order')->count($where, $values);
			$list = $this->db->setTableName('vi_order')->pageLimit($page, $size)->getAll($where, $values, null, 'id DESC');
			$pagelist = xiaocms::load_class('pager');
			$pagelist = $pagelist->total($total)->url(url('order/index', [
				'status' => $status,
				'area' => $area,
				'mobile' => $mobile,
				'pdname' => $pdname,
				'begin' => $begin,
				'end' => $end
			]) . '&page=[page]')->ext(true)->num($size)->page($page)->output();

			include $this->admin_tpl('order_list');
			return;
		}

		include $this->admin_tpl('order_index');
	}
}

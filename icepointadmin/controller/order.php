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
			$data = $this->condition();
			$total = $this->db->setTableName('vi_order')->count($data['where'], $data['values']);
			$list = $this->db->setTableName('vi_order')->pageLimit($page, $size)->getAll($data['where'], $data['values'], null, 'id DESC');
			$pagelist = xiaocms::load_class('pager');
			$pagelist = $pagelist->total($total)->url(url('order/index', $data['data']) . '&page=[page]')->ext(true)->num($size)->page($page)->output();

			include $this->admin_tpl('order_list');
			return;
		}

		include $this->admin_tpl('order_index');
	}

	public function closeAction()
	{
		$id = $this->get('id');
		$order = $this->db->setTableName('order')->find($id);
		if ($order) {
			if ($order['status'] != 10) {
				$this->json(null, false, '仅待发货订单可以关闭');
			}
			$succ = $this->db->setTableName('order')->update(['status' => -10], 'id = ?', $id);
			if ($succ) {
				$this->json(null, true, '关闭提货单成功');
			}
			$this->json(null, false, '关闭提货单失败');
		}
	}

	public function exportAction()
	{
		$data = $this->condition();
		$list = $this->db->setTableName('vi_order')->getAll(
			$data['where'],
			$data['values'],
			"id,name,customermobile,productname,quantity,contact,address,mobile,from_unixtime(createtime,'%Y-%m-%d'),case when status=10 then '待发货' when status=20 then '待揽收' when status=30 then '待配送' when status=40 then '配送中' when status=50 then '待签收' when status=60 then '已签收' when status=70 then '已完成' when status=-10 then '关闭' else '其它' end",
			'id desc'
		);
		exportToExcel(date('YmdHis') . '提货记录.csv', ['提货单号', '提货会员', '会员电话', '提货商品', '提货数量', '收货人', '收货地址', '收货电话', '提货时间', '状态'], $list);
	}

	private function condition()
	{
		$status = $this->get('status');
		$area = $this->get('area');
		$mobile = $this->get('mobile');
		$pdname = $this->get('pdname');
		$begin = $this->get('begin');
		$end = $this->get('end');

		$where = array();
		$values = array();
		$data = array();

		if ($status) {
			array_push($where, 'status = ?');
			array_push($values, $status);
			$data['status'] = $status;
		}
		if ($area) {
			array_push($where, 'area = ?');
			array_push($values, $area);
			$data['area'] = $area;
		}
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
		if ($begin) {
			array_push($where, 'createtime > ?');
			array_push($values, strtotime($begin));
			$data['begin'] = $begin;
		}
		if ($end) {
			array_push($where, 'createtime < ?');
			array_push($values, strtotime($end) + 3600 * 24);
			$data['end'] = $end;
		}

		return ['where' => $where, 'values' => $values, 'data' => $data];
	}
}

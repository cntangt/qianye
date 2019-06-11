<?php
class comment extends Admin
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
			$total = $this->db->setTableName('vi_comment')->count($data['where'], $data['values']);
			$list = $this->db->setTableName('vi_comment')->pageLimit($page, $size)->getAll($data['where'], $data['values'], null, 'id DESC');
			$pagelist = xiaocms::load_class('pager');
			$pagelist = $pagelist->total($total)->url(url('comment/index', $data['data']) . '&page=[page]')->ext(true)->num($size)->page($page)->output();

			include $this->admin_tpl('comment_list');
			return;
		}

		include $this->admin_tpl('comment_index');
	}

	public function exportAction()
	{
		$data = $this->condition();
		$list = $this->db->setTableName('vi_comment')->getAll(
			$data['where'],
			$data['values'],
			"orderid,name,mobile,case when isreceive=1 then '是' else '否' end,case when isdestination=1 then '是' else '否' end,case when attitude=10 then '热情' when attitude=20 then '消极' else '未知' end,case when clothing=10 then '正规' when clothing=20 then '随意' else '未知' end,from_unixtime(createtime,'%Y-%m-%d')",
			'id desc'
		);
		exportToExcel(date(YmdHis) . '评价列表.csv', ['订单编号', '会员名称', '提货手机', '收货', '到位', '态度', '着装', '评价时间'], $list);
	}

	private function condition()
	{
		$mobile = $this->get('mobile');
		$orderid = $this->get('orderid');

		$where = array();
		$values = array();
		$data = array();

		if ($mobile) {
			array_push($where, 'mobile = ?');
			array_push($values, $mobile);
			$data['mobile'] = $mobile;
		}
		if ($orderid) {
			array_push($where, 'orderid = ?');
			array_push($values, $orderid);
			$data['orderid'] = $orderid;
		}

		return ['where' => $where, 'values' => $values, 'data' => $data];
	}
}

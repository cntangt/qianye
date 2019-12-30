<?php

class product extends Admin
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
			$total = $this->db->setTableName('product')->count($data['where'], $data['values']);
			$list = $this->db->setTableName('product')->pageLimit($page, $size)->getAll($data['where'], $data['values'], null, 'id DESC');
			$pagelist = xiaocms::load_class('pager');
			$pagelist = $pagelist->total($total)->url(url('product/index', $data['data']) . '&page=[page]')->ext(true)->num($size)->page($page)->output();
			$kvs = $this->db->setTableName('kv')->getAll(null, null, null, null);
			$imgdomain = '';
			foreach ($kvs as $key => $value) {
				if ($value['key'] == 'img') {
					$imgdomain = $value['value'];
				}
			}
			include $this->admin_tpl('product_list');
			return;
		}

		include $this->admin_tpl('product_index');
	}

	public function delAction()
	{
		$id = $this->get('id');
		$data = $this->db->setTableName('product')->delete('id = ?', $id);
		$this->json(null, true, '删除商品成功');
	}

	public function addAction()
	{
		if ($this->ispost) {
			$filename = $this->savefile();
			if ($filename == null) {
				$filename = '';
			}
			$succ =	$this->db->setTableName('product')->insert(['title' => $this->post('title'), 'sku' => $this->post('sku'), 'thumb' => $filename]);
			if ($succ) {
				echo '添加商品成功';
				exit;
			}
			echo '添加商品失败';
			exit;
		}

		$data = ['title' => '新增商品', 'id' => 0, 'sku' => date('YmdHis'), 'action' => 'add'];
		include $this->admin_tpl('product_edit');
	}

	public function editAction()
	{
		if ($this->ispost) {
			$id = $this->post('id');
			$data = $this->db->setTableName('product')->find($id);
			if ($data) {
				$filename = $this->savefile();
				if ($filename == null) {
					$filename = $data['thumb'];
				}
				$succ =	$this->db->setTableName('product')->update(['title' => $this->post('title'), 'sku' => $this->post('sku'), 'thumb' => $filename], 'id = ?', $id);
				if ($succ) {
					echo '修改商品成功';
					exit;
				}
			}
			echo '修改商品失败';
			exit;
		}

		$id = $this->get('id');
		$data = $this->db->setTableName('product')->getOne('id = ?', $id);
		$data['action'] = 'edit';
		include $this->admin_tpl('product_edit');
	}

	private function savefile()
	{
		if ($_FILES['file']['size'] == 0) {
			return null;
		}
		$type = $_FILES["file"]["type"];

		if (stripos($type, 'image') === false) {
			echo '请上传图片格式';
			exit;
		}

		if ($_FILES["file"]["size"] > 1024 * 500) {
			echo '请上传小于500K的缩略图';
			exit;
		}
		$filename = sprintf('%s.%s', date('YmdHis'), pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
		$savepath = sprintf('%s/%s/%s', XIAOCMS_PATH, 'thumbs', $filename);
		move_uploaded_file($_FILES["file"]["tmp_name"], $savepath);
		return $filename;
	}

	private function condition()
	{
		$pdname = $this->get('name');

		$where = array();
		$values = array();
		$data = array();

		if ($pdname) {
			array_push($where, 'title like ?');
			array_push($values, '%' . $pdname . '%');
			$data['pdname'] = $pdname;
		}

		return ['where' => $where, 'values' => $values, 'data' => $data];
	}
}

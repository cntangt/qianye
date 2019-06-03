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
			$page = $this->get('page');
			$size = 15;
			$this->list_where();
			$total = $this->db->count();
			$this->list_where();
			$list = $this->db->pageLimit($page, $size)->getAll(null, null, null, 'id DESC');
			$pagelist = xiaocms::load_class('pager');
			$pagelist = $pagelist->total($total)->url(url('card/index', [
				'status' => $this->get('status'),
				'ctid' => $this->get('ctid'),
				'mobile' => $this->get('mobile'),
				'code' => $this->get('code')
			]) . '&page=[page]')->ext(true)->num($size)->page($page)->output();

			include $this->admin_tpl('card_list');
			return;
		}

		$types = $this->db->setTableName('vi_card_type')->getAll(null, null, 'id,name,description');
		include $this->admin_tpl('card_index');
	}

	private function list_where()
	{
		$status = $this->get('status');
		$ctid = $this->get('ctid');
		$mobile = $this->get('mobile');
		$code = $this->get('code');
		$this->db->setTableName('card');
		if ($status != '') {
			$this->db->where('status = ?', $status);
		}
		if ($ctid) {
			$this->db->where('cardtypeid = ?', $ctid);
		}
		if ($mobile) {
			$this->db->where('customermobile = ?', $mobile);
		}
		if ($code) {
			$this->db->where('code = ?', $code);
		}
	}

	public function buildAction()
	{
		if ($this->ispost) {
			$data = $this->post('data');
			$cardtype = $this->db->setTableName('card_type')->find($data['ctid']);
			if ($cardtype == false) {
				$this->json(null, false, '请选择卡券类型');
			}
			$qua = $data['qua']; // 生成数量
			$len = strlen($data['no']); // 定义卡券字符长度
			$min = intval($data['no']); // 获取最小卡券号
			$max = $min + intval($data['qua']); //计算最大卡券号
			$len = max($len, strlen($max)); // 重新计算卡券字符长度
			$pre = strtoupper($data['pre']); // 卡券前缀
			$pass = $this->pass($data['passlen'], $data['passtype'], $data['qua']); // 密码数组
			try {
				$sql = "INSERT INTO `xiao_card`(`cardtypeid`, `cardtypename`, `status`, `code`, `pass`, `qrcode`, `codepre`, `codeno`, `codelen`, `createtime`, `createby`) VALUES ";
				for ($i = 0; $i < $qua; $i++) {
					$no = $i + $min;
					$code = $pre . str_pad($no, $len, '0', STR_PAD_LEFT);
					$sql .= sprintf(
						"(%s,'%s',%s,'%s','%s','%s','%s',%s,%s,%s,'%s'),",
						$data['ctid'],
						$cardtype['name'],
						10,
						$code,
						$pass[$i],
						sha1($code . $pass[$i]),
						$pre,
						$no,
						$len,
						time(),
						$this->admin['realname']
					);
				}
				$sql = rtrim($sql, ',');
				$this->db->execute($sql);
			} catch (Exception $e) {
				$this->json(null, false, '保存卡券数据失败：' . $e->getMessage());
			}
			$this->json(null, true);
		}

		$list = $this->db->setTableName('vi_card_type')->where('canbuild = 1 and isvalid = 1 and endtime > ?', time())->getAll();

		include $this->admin_tpl('card_build');
	}

	private function pass($len, $type, $count)
	{
		$str = '';
		switch ($type) {
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

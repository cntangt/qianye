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
			$this->db->where('code like ?', '%' . $code . '%');
		}
	}

	public function buildAction()
	{
		if ($this->ispost) {
			$data = $this->post('data');
			$cardtype = $this->db->setTableName('card_type')->find($data['ctid']);
			$cardtypeitems = $this->db->setTableName('card_type_item')->getAll('cardtypeid = ?', $data['ctid']);
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
				$vals = array();
				for ($i = 0; $i < $qua;) {
					$no = $i + $min;
					$code = $pre . str_pad($no, $len, '0', STR_PAD_LEFT);
					array_push(
						$vals,
						sprintf(
							"(%s,'%s',%s,'%s','%s','%s','%s',%s,%s,%s,'%s')",
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
						)
					);
					$i++;
					if ($i % 100 == 0) {
						$this->db->execute($sql . join(',', $vals));
						$vals = array();
					}
				}
				if (count($vals) > 0) {
					$this->db->execute($sql . join(',', $vals));
				}
			} catch (Exception $e) {
				$this->json(null, false, '保存卡券数据失败：' . $e->getMessage());
			}
			$this->json(null, true);
		}

		$list = $this->db->setTableName('vi_card_type')->where('canbuild = 1 and isvalid = 1 and endtime > ?', time())->getAll();

		include $this->admin_tpl('card_build');
	}

	public function exportAction()
	{
		$this->list_where();
		$list = $this->db->getAll(
			null,
			null,
			"id,cardtypename,code,pass,customermobile,from_unixtime(createtime,'%Y-%m-%d'),createby,from_unixtime(saletime,'%Y-%m-%d'),saleby,from_unixtime(activetime,'%Y-%m-%d'),from_unixtime(exptime,'%Y-%m-%d'),case when status=10 then '未销售' when status=20 then '销售' when status=30 then '激活' when status=40 then '失效' else '其它' end",
			'id desc'
		);
		exportToExcel(date('YmdHis') . '卡券列表.csv', ['ID', '卡券类型', '卡券编号', '卡券密码', '会员手机', '创建时间', '创建人', '销售时间', '销售人', '激活时间', '过期时间', '状态'], $list);
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

	public function disableAction()
	{
		if ($this->ispost) {
			$data = $this->post('data');
			$count = 0;
			if (isset($data)) {
				$count = $this->db->setTableName('card')->update(['status' => 40], 'codepre = ? and codelen= ? and codeno >= ? and codeno <= ?', $this->batch_data());
			} else {
				$count = $this->db->setTableName('card')->update(['status' => 40], 'id = ?', $this->get('id'));
			}
			$this->json($count, $count > 0);
		}

		$url = url('card/disable');
		$title = '批量作废卡券';

		include $this->admin_tpl('card_batch');
	}

	public function saleAction()
	{
		if ($this->ispost) {
			$data = $this->post('data');
			$count = 0;
			if (isset($data)) {
				$count = $this->db->setTableName('card')->update(['status' => 20], 'codepre = ? and codelen= ? and codeno >= ? and codeno <= ? and status = 10', $this->batch_data());
			} else {
				$count = $this->db->setTableName('card')->update(['status' => 20], 'id = ? and status = 10', $this->get('id'));
			}
			$this->json($count, $count > 0);
		}

		$url = url('card/sale');
		$title = '批量销售卡券';

		include $this->admin_tpl('card_batch');
	}

	private function batch_data()
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

		return [$pre, $len, $min, $max];
	}
}

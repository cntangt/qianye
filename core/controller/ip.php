<?php

class ip extends Base
{
	// 当前用户信息
	protected $user = null;

	public function __construct()
	{
		parent::__construct();

		$token = $_SERVER['HTTP_TOKEN'];

		$this->user = $this->cache->get('ip:' . $token);
		if ($this->user && !empty($this->user['mobile'])) {
			return;
		}

		$loginInfo = $this->cache->get('wx:' . $token);
		if ($loginInfo) {
			$this->user = $this->db->setTableName('customer')->getOne('openid = ?', $loginInfo['openid']);
			$this->cache->set('ip:' . $token, $this->user);
			//验证成功,再次刷新token避免操作中途过期
			$this->cache->set('wx:' . $_SERVER['HTTP_TOKEN'], $loginInfo);
			return;
		}

		$this->json(null, false, '用户登录信息过期', -1);
	}

	// 激活卡券
	public function activecardAction()
	{
		if (empty($this->user['mobile'])) {
			$this->json(null, false, '未绑定手机，请绑定后再进行激活');
		}
		$uts = $this->cache->get('useractivetimes:' . $this->user['id']);
		if ($uts > 100) {
			$this->json(null, false, '检测到风险操作，请24小时后重试');
		}
		$uts += 1;
		$this->cache->set('useractivetimes:' . $this->user['id'], $uts, 3600 * 24);

		$card = null;
		$qrcode = null; //  暂时禁用二维码激活 $this->post('qrcode');
		if ($qrcode) {
			$card = $this->db->setTableName('card')->getOne('qrcode = ?', $qrcode);
		} else {
			$code = $this->post('code');
			$pass = $this->post('pass');
			if (empty($code) || empty($pass)) {
				$this->json(null, false, '卡号或者密码为空');
			}
			$times = $this->cache->get('cardactivetimes:' . $code);
			if ($times > 10) {
				$this->json(null, false, '卡券激活次数超出限制，请24小时后重试');
			}
			$times += 1;
			$this->cache->set('cardactivetimes:' . $code, $times, 3600 * 24);
			$card = $this->db->setTableName('card')->getOne('code = ? and pass = ?', [$code, $pass]);
		}

		if ($card) {
			switch ($card['status']) {
				case 10:
					$this->json(null, false, '未销售的卡券不能激活');
				case 30:
					$this->json(null, false, '已经激活的卡券不能激活');
				case 40:
					$this->json(null, false, '已作废的卡券不能激活');
			}
			$ct = $this->db->setTableName('card_type')->getOne('id = ?', $card['cardtypeid']);
			if (!$ct) {
				$this->json(null, false, '卡券类型不存在，不能激活');
			}
			if ($ct['isvalid'] == 0) {
				$this->json(null, false, '卡券类型已经作废，不能激活');
			}
			if ($ct['begintime'] > time()) {
				$this->json(null, false, '卡券激活开始时间为：' . date('Y-m-d', $ct['begintime']) . '，不能激活');
			}
			if ($ct['endtime'] < time()) {
				$this->json(null, false, '卡券激活截止时间为：' . date('Y-m-d', $ct['endtime']) . '，不能激活');
			}
			$ctis = $this->db->setTableName('card_type_item')->getAll('cardtypeid = ?', $ct['id']);
			if (!$ctis) {
				$this->json(null, false, '未绑定卡券类型商品，不能激活');
			}
			$sql = 'INSERT INTO `xiao_card_item`(`cardid`, `sku`, `cardtypeid`, `productname`, `quantity`, `validquantity`) VALUES ';
			$vals = array();
			foreach ($ctis as $i) {
				array_push($vals, sprintf("(%s,'%s',%s,'%s',%s,%s)", $card['id'], $i['sku'], $card['cardtypeid'], $i['productname'], $i['quantity'], $i['quantity']));
			}
			// 添加卡券类型明细到激活卡券明细
			if (!$this->db->execute($sql . join(',', $vals))) {
				$this->json(null, false, '激活卡券明细失败，请重试');
			}
			// 设置卡券激活用户信息
			if (!$this->db->setTableName('card')->update([
				'customermobile' => $this->user['mobile'],
				'customerid' => $this->user['id'],
				'status' => 30,
				'activetime' => time(),
				'exptime' => time() + $ct['vailddays'] * 24 * 3600
			], 'id = ?', $card['id'])) {
				$this->db->setTableName('card_item')->delete('cardid = ?', $card['id']);
				$this->json(null, false, '激活卡券失败，请重试');
			} else {
				$this->json(null, true, '激活卡券成功');
			}
		} else {
			$this->json(null, false, '未找到激活卡券，请确认卡券编码和密码');
		}
	}

	// 新增地址
	public function addaddressAction()
	{
		$uts = $this->cache->get('useractivetimes:' . $this->user['id']);
		if ($uts > 100) {
			$this->json(null, false, '检测到风险操作，请24小时后重试');
		}
		$uts += 1;
		$this->cache->set('useractivetimes:' . $this->user['id'], $uts, 3600 * 24);
		$result = $this->checkaddressAction();
		if ($result['succ']) {
			$data = $result['val'];
			$arrayattr = [
				'name' => $data['name'],
				'customerid' => $this->user['id'],
				'province' => $data['province'],
				'city' =>  $data['city'],
				'area' => $data['area'],
				'address' => $data['address'],
				'createtime' => time(),
				'isDefault' => 0,
				'mobile' => $data['mobile'],
			];
			$addRes = $this->db->setTableName('customer_address')->insert($arrayattr);
			if ($addRes) {
				$this->json(null, true, '新增成功');
			} else {
				$this->json(null, false, '新增失败');
			}
		} else {
			$this->json($result . data, $result . succ, $result . msg, $result . code);
		}
	}

	// 修改地址
	public function editaddressAction()
	{
		$uts = $this->cache->get('useractivetimes:' . $this->user['id']);
		if ($uts > 100) {
			$this->json(null, false, '检测到风险操作，请24小时后重试');
		}
		$uts += 1;
		$this->cache->set('useractivetimes:' . $this->user['id'], $uts, 3600 * 24);
		$result = $this->checkaddressAction();
		if ($result['succ']) {
			$data = $result['val'];
			$arrayattr = [
				'name' => $data['name'],
				'customerid' => $this->user['id'],
				'province' => $data['province'],
				'city' =>  $data['city'],
				'area' => $data['area'],
				'address' => $data['address'],
				'createtime' => time(),
				'isDefault' => 0,
				'mobile' => $data['mobile'],
			];
			$updateRes = $this->db->setTableName('customer_address')->update($arrayattr, 'id = ?', $data['id']);
			if ($updateRes) {
				$this->json(null, true, '修改成功');
			} else {
				$this->json(null, false, '修改失败');
			}
		} else {
			$this->json($result . data, $result . succ, $result . msg, $result . code);
		}
	}
	//获取地址列表
	public function getaddresslistAction()
	{
		$uid = $this->user['id'];
		$list = $this->db->setTableName('customer_address')->getAll('customerid = ?', $uid);
		$this->json($list, true, null);
	}

	//获取地址单个
	public function getaddressAction()
	{
		$address =	$this->db->setTableName('customer_address')->getOne('customerid = ?', $this->user['id'], null, 'isDefault DESC,id DESC');
		if ($address != null) {
			$this->json($address, true);
		}
		$this->json(null, true, null);
	}

	//检查地址数据有效性
	public function checkaddressAction()
	{
		$data['id'] = $this->post('id');
		$data['name'] = $this->post('name');
		$data['mobile'] = $this->post('mobile');
		$data['province'] = $this->post('province');
		$data['city'] = $this->post('city');
		$data['area'] = $this->post('area');
		$data['address'] = $this->post('address');

		if ($data['id'] < 0) {
			return	$this->getjson(null, false, '地址信息有误');
		}
		if (empty($data['name'])) {
			return	$this->getjson(null, false, '请输入收货人姓名');
		}
		if (empty($data['mobile']) || !preg_match("/^1\d{10}$/", $data['mobile'])) {
			return	$this->getjson(null, false, '请输入收货人手机号');
		}
		if (empty($data['province']) || $data['province'] != '重庆') {
			return	$this->getjson(null, false, '目前仅支持大重庆地区,请重新选择');
		}
		if (empty($data['city']) || $data['city'] != '重庆市') {
			return	$this->getjson(null, false, '目前仅支持大重庆地区,请重新选择');
		}
		if (empty($data['area'])) {
			return	$this->getjson(null, false, '请选择完整的区域');
		}
		if (empty($data['address'])) {
			return	$this->getjson(null, false, '请输入完整的地址');
		}

		return $this->getjson($data, true);
	}

	//获取商品列表
	public function getproductsAction()
	{
		$products =	$this->db->setTableName('vi_wealth_valid')->getAll('customerid = ?', $this->user['id']);
		$this->json($products, true, null);
	}

	//创建订单
	public function createorderAction()
	{
		// //1.创建订单
		$products = json_decode($_POST['data']);
		$orderProduct = array_filter($products, function ($item) {
			return $item->pickcount > 0;
		});
		if ($orderProduct == null) $this->json(null, false, "请选择提货商品");

		$address = json_decode($_POST['address']);
		if ($address == null) $this->json(null, false, "请选择收货地址");
		//添加订单
		$orderarray = [
			'customerid' => $this->user['id'], 'createtime' => time(), 'contact' => $address->name, 'mobile' => $address->mobile, 'address' => $address->address, 'province' => $address->province, 'city' => $address->city, 'area' => $address->area, 'status' => 10, 'remark' => '',
		];
		$addOrderRes = $this->db->setTableName('order')->insert($orderarray, true);
		if ($addOrderRes == null || $addOrderRes < 0) {
			$this->json(null, false, "创建订单失败");
		}
		try {
			foreach ($orderProduct as $key => $value) {
				$addRes = $this->db->setTableName('order_item')->insert([
					'sku' => $value->sku,
					'orderid' => $addOrderRes,
					'cardid' => $value->cardid,
					'cardtypeid' => $value->cardtypeid,
					'productname' => $value->productname,
					'quantity' => $value->pickcount,
				]);
				if (!$addRes) {
					$this->db->setTableName('order')->delete('id = ?', $addOrderRes);
					$this->json(null, false, '创建订单失败');
				}
				//2.修改数量
				if (!$this->db->setTableName('card_item')->update([
					'validquantity' => ($value->validquantity - $value->pickcount),
				], 'id = ?', $value->carditemid)) {
					$this->db->setTableName('order')->delete('id = ?', $addOrderRes);
					$this->db->setTableName('order_item')->delete('orderid = ?', $addOrderRes);
					$this->json(null, false, '创建订单失败');
				}
			}
		} catch (Exception $e) {
			$this->db->setTableName('order')->delete('id = ?', $addOrderRes);
			$this->json(null, false, '创建订单失败' . $e->getMessage());
		}
		$orderNo = $this->db->setTableName('order')->getOne('id = ?', $addOrderRes);
		$this->json($orderNo, true, "创建订单成功");
	}

	//获取订单
	public function getorderAction()
	{
		$status = $this->get('status');
		$list = null;
		//待签收
		if ($status == 50) {
			$list =	$this->db->setTableName('order')->getAll('customerid = ? and status != 60 and status != 70 and status!=-10', $this->user['id']);
		} else if ($status == 60) { //待评价
			$list =	$this->db->setTableName('order')->getAll('customerid = ? and status= 60', $this->user['id']);
		} else if ($status == 70) { //已完成
			$list =	$this->db->setTableName('order')->getAll('customerid = ? and status= 70', $this->user['id']);
		} else {
			$list =	$this->db->setTableName('order')->getAll('customerid = ? ', $this->user['id']);
		}

		if (!$list) {
			$list = null;
		} else {
			foreach ($list as $key => $value) {
				$products = $this->db->setTableName('order_item')->getAll('orderid = ?', $value['id']);
				foreach ($products as $productkey => $productsitem) {
					$product = $this->db->setTableName('product')->getOne('sku = ?', $productsitem['sku']);
					$productsitem['thumb'] = $product['thumb'];
					$productsitem['subtitle'] = $product['subtitle'];
					$products[$productkey] = $productsitem;
				}
				$value['products'] = $products;
				$list[$key] = $value;
			}
		}
		$this->json($list, true);
	}
	//确认收货
	public function comfirmorderAction()
	{
		$id = $this->get('id');
		if ($id > 0) {
			$order = $this->db->setTableName('order')->getOne('id = ?', $this->post('orderid'));
			if ($order && $order . customerid == $this->user['id']) {
				$res =	$this->db->setTableName('order')->update([
					'status' => 60,
				], 'id = ? and status = 50', $id);
				if ($res) {
					$this->json(null, true, "确认订单成功");
				} else {
					$this->json(null, false, "确认订单失败,请确认状态是否正确");
				}
			}else{
				$this->json(null, false, "确认订单错误");
			}
		} else {
			$this->json(null, false, "确认订单失败");
		}
	}
	//提交评价
	public function commitordercommentAction()
	{
		$order = $this->db->setTableName('order')->getOne('id = ?', $this->post('orderid'));
		if ($order && $order->customerid == $this->user['id']) {
			$addRes = $this->db->setTableName('comment')->insert([
				'orderid' => $this->post('orderid'),
				'isontime' => $this->post('isontime') == "true" ? true : false,
				'iscontact' => $this->post('iscontact') == "true" ? true : false,
				'isdestination' => $this->post('isdestination') == "true" ? true : false,
				'isattitude' => $this->post('isattitude') == "true" ? true : false,
				'isclothing' => $this->post('isclothing') == "true" ? true : false,
				'createtime' => time()
			]);
			if ($addRes) {
				if (!$this->db->setTableName('order')->update([
					'status' => 70,
				], 'id = ? and status = 60', $this->post('orderid'))) {
					$this->db->setTableName('comment')->delete('orderid = ?', $this->post('orderid'));
					$this->json(null, false, '评价失败');
				}
				$this->json(null, true, '评价成功');
			} else {
				$this->json(null, false, '评价失败');
			}
		} else {
			$this->json(null, false, "评价订单错误");
		}
	}
	//获取用户订单数量
	public function selectordercountAction()
	{
		//待签收
		$waitorders =	$this->db->setTableName('order')->getAll('customerid = ? and status != 60 and status != 70 and status!=-10', $this->user['id']);
		//待评价
		$commentorders =	$this->db->setTableName('order')->getAll('customerid = ? and status= 60', $this->user['id']);
		$result["waitcount"] = count($waitorders);
		$result["commentcount"] = count($commentorders);
		$order = $this->db->setTableName('order')->getOne('customerid = ? ', $this->user['id'], null, 'id DESC');
		if ($order) {
			$result["lastorder"] = $order;
		} else {
			$result["lastorder"] = null;
		}
		$this->json($result);
	}

	public function getsettingconfigAction()
	{
		$list =	$this->db->setTableName('kv')->getAll(null, null);
		$this->json($list);
	}
	//重写返回json(返回对象)
	protected function getjson($val, $succ = true, $msg = null, $code = 0)
	{
		$result['succ'] = $succ;
		$result['msg'] = $msg;
		$result['code'] = $code;
		$result['val'] = $val;
		return $result;
	}

	/**
	 * 用户可用财富
	 * @return order_item[]
	 */
	public function wealthAction()
	{
		$list =	$this->db->setTableName('vi_wealth_valid')->getAll('customerid = ?', $this->user['id'], 'cardid,carditemid,productname,quantity,validquantity,cardtypename,thumb');
		if (!$list) {
			$list = null;
		}

		$this->json($list, true);
	}
}

<?php
class cardtype extends Admin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        // 为了方便权限管理，利用ajax请求头判断部分页请求
        if ($this->isajax) {
            $page = $this->get('page');
            $size = 15;
            $this->list_where();
            $total = $this->db->count();
            $this->list_where();
            $list = $this->db->pageLimit($page, $size)->getAll(null, null, null, 'id DESC');
            $pagelist = xiaocms::load_class('pager');
            $pagelist = $pagelist->total($total)->url(url('cardtype/index', ['name' => $this->get('name')]) . '&page=[page]')->ext(true)->num($size)->page($page)->output();

            include $this->admin_tpl('cardtype_list');
            return;
        }

        // 单独框架页请求
        $key = $this->get('key');
        $token = $this->yz_acc_token();
        $client = new \Youzan\Open\Client($token);

        $method = 'youzan.items.onsale.get';
        $apiVersion = '3.0.0';

        $res = $client->get($method, $apiVersion, ['q' => $key]);
        $data = $res['data']['items'];
        $list = array();
        for ($i = 0; $i < count($data); $i++) {
            $list[$i]['id'] = $data[$i]['item_id'];
            $list[$i]['text'] = $data[$i]['title'];
        }
        $json = json_encode($list);
        include $this->admin_tpl('cardtype_index');
    }

    private function list_where()
    {
        $this->db->setTableName('vi_card_type');

        $name = $this->get('name');
        $canedit = $this->get('canedit');
        if (!empty($name)) $this->db->where('name like ?', '%' . $name . '%');
        if ($canedit != '') $this->db->where('canedit = ?', $canedit == 'true');
    }

    // 添加卡券类型
    public function addAction()
    {
        // post请求保存数据
        if ($this->ispost) {
            $data = $this->post('data');

            $data['createtime'] = time();
            $data['createby'] = $this->admin['username'];
            $data['begintime'] = strtotime($data['begintime']);
            $data['endtime'] = strtotime($data['endtime']);
            $this->db->setTableName('card_type')->insert($data);

            // json方法会自动退出当前请求
            $this->json(null, true);
        }

        // 初始化值
        $data['begintime'] = strtotime('today');
        $data['endtime'] = strtotime('+1month');
        $data['vailddays'] = 30;
        $data['id'] = 0;
        $url = url('cardtype/add');
        $title = '添加卡券';

        include $this->admin_tpl('cardtype_add');
    }

    // 修改卡券类型
    public function editAction()
    {
        if ($this->ispost) {

            $data = $this->post('data');

            $data['updatetime'] = time();
            $data['updateby'] = $this->admin['username'];
            $data['begintime'] = strtotime($data['begintime']);
            $data['endtime'] = strtotime($data['endtime']);
            $this->db->setTableName('card_type')->update($data, 'id = ?', $this->post('id'));

            // json方法会自动退出当前请求
            $this->json(null, true);
        }

        $id = $this->get('id');
        $data = $this->db->setTableName('card_type')->getOne('id = ?', $id);
        $url = url('cardtype/edit');
        $title = '修改卡券 - ' . $data['name'];
        include $this->admin_tpl('cardtype_add');
    }

    // 商品绑定管理
    public function pdlistAction()
    {
        if ($this->ispost) {
            $id = $this->post('id');
            $data = $this->post('data');
            $count = $this->db->setTableName('card')->count('cardtypeid = ?', $id);
            if ($count > 0) {
                $this->json(null, false, '已生成卡券，不能修改');
            }
            $this->db->setTableName('card_type_item')->delete('cardtypeid = ?', $id);
            foreach ($data as $t) {
                $t['cardtypeid'] = $id;
                $this->db->setTableName('card_type_item')->insert($t);
            }

            $this->json(null, true);
        }
        
        $list = $this->db->setTableName('card_type_item')->where('cardtypeid=?', $this->get('id'))->getAll();
        if (count($list) == 0) {
            $list[0]['sku'] = '';
            $list[0]['quantity'] = 1;
        }
        include $this->admin_tpl('cardtype_pdlist');
    }

    // 导出数据
    public function exportAction()
    {
        $list = $this->db->setTableName('vi_card_type')->getAll(null, null, "id,name,description,from_unixtime(begintime,'%Y-%m-%d'),from_unixtime(endtime,'%Y-%m-%d'),vailddays,case when canedit=0 then '是' else '否' end canedit");
        exportToExcel(date(YmdHis) . '卡券类型.csv', ['编号', '名称', '商品', '开始时间', '结束时间', '有期天', '生成卡'], $list);
    }
}

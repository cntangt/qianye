<?php
//require_once SDK_DIR . '\yz\vendor\autoload.php';

class cardtype extends Admin
{

    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        //$type = 'silent';
        //$keys['kdt_id'] = '42559182';

        //$accessToken = (new \Youzan\Open\Token($this->site_config['yz_client_id'], $this->site_config['yz_client_secret']))->getToken($type, $keys);
        //$tem = $accessToken;
        include $this->admin_tpl('cardtype_index');
    }

    public function listAction()
    {
        $data = $this->post('data');
        $this->list_where($data);
        $total = $this->db->count();
        $this->list_where($data);
        $list = $this->db->pageLimit($data['page'], 15)->getAll(null, null, null, 'id DESC');
        $pagelist = xiaocms::load_class('pager');
	    $pagelist = $pagelist->total($total)->url(url('content/index', null) . '&page=[page]')->ext(true)->num(15)->page($data['page'])->output();

        include $this->admin_tpl('cardtype_list');
    }

    private function list_where($data)
    {
        $this->db->setTableName('card_type');

        if (!empty($data['name'])) $this->db->where('name like ?','%'.$data['name'].'%');
    }

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
        $data['begintime'] = date('Y-m-d', strtotime('today'));
        $data['endtime'] = date('Y-m-d', strtotime('+1month'));
        $data['vailddays'] = 30;

        include $this->admin_tpl('cardtype_add');
    }
}

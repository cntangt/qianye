<?php
require_once SDK_DIR . '\yz\vendor\autoload.php';

class cardtype extends Admin
{

	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction()
	{
		$type = 'silent';
		$keys['kdt_id'] = '42559182';

		$accessToken = (new \Youzan\Open\Token($this->site_config['yz_client_id'], $this->site_config['yz_client_secret']))->getToken($type, $keys);
		$tem=$accessToken;
        /*
	    if ($this->post('listorder')) {
        foreach ($this->post('listorder') as $catid => $value) {
        $this->db->setTableName('category')->update(array('listorder'=>$value), 'catid=?' , $catid);
        }
        $this->cacheAction();
        $html = '<script type="text/javascript">parent.document.getElementById(\'leftMain\').src =\' ?c=index&a=tree\';</script>';
        $this->show_message('设置成功'. $html, 1);
	    }
		$this->tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$this->tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$categorys = array();
		$cats =  $this->db->setTableName('category')->findAll(null,'listorder DESC,catid ASC');
		$types = array(1 => '',2 => '<font color="blue">单页面</font>',3 => '<font color="red">外部连接</font>');
		if(!empty($cats)) {
        foreach($cats as $r) {
        $r['modelname'] = @$this->content_model[$r['modelid']]['modelname'];
        $r['str_manage'] = '<a href="'.url('category/add', array('catid'=>$r['catid'])).'" >添加子栏目</a> | <a href="'.url('category/edit', array('catid'=>$r['catid'])).'">编辑</a> | <a href="javascript:confirmurl(\''.url('category/del', array('catid'=>$r['catid'])).'\',\''.'确定删除 『 '.$r['catname'].' 』栏目吗？ '.'\')">删除</a>';
        $r['typename'] = $types[$r['typeid']];
        $r['display'] = $r['ismenu'] ? '是' : '<font color="red">否</font>';
        $r['catname'] = "<a href='" . $this->view->get_category_url($r)."' target='_blank'>".$r['catname']."</a>";
        $categorys[$r['catid']] = $r;
        }
		}
		$str  = "<tr>
        <td align='left'><input name='listorder[\$catid]' type='text' size='1' value='\$listorder' class='input-text-c'></td>
        <td align='left'>\$catid</td>
        <td >\$spacer\$catname</td>
        <td>\$typename\$modelname</td>
        <td>\$items</td>
        <td>\$display</td>
        <td >\$str_manage</td>
        </tr>";
		$this->tree->init($categorys);
		$categorys = $this->tree->get_tree(0, $str);
         */
		include $this->admin_tpl('cardtype_list');
	}

	public function addAction()
	{
        // post请求保存数据
        if ($this->ispost)
        {
            $data = $this->post('data');

            $data['createtime'] = time();
            $data['createby'] = $this->admin['username'];
            $data['begintime'] = strtotime($data['begintime']);
            $data['endtime'] = strtotime($data['endtime']);
            $this->db->setTableName('card_type')->insert($data);

            // json方法会自动退出当前请求
            $this->json(null,true);
        }

        // 初始化值
        $data['begintime']=date('Y-m-d',strtotime('today'));
        $data['endtime']=date('Y-m-d',strtotime('+1month'));
        $data['vailddays']=30;

		include $this->admin_tpl('cardtype_add');
	}
}
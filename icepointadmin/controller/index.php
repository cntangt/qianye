<?php

class index extends Admin
{

    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        $left_width = $this->admin['left_width'] ? $this->admin['left_width'] : 150;
        include $this->admin_tpl('index');
    }

    public function myAction()
    {
        $userid = $this->admin['userid'];
        if ($this->ispost) {
            $data = $this->post('data');

            $user = $this->db->setTableName('admin')->find($userid);
            if ($user && $user['password'] == md5(md5($data['old']))) {
                $user['password'] =  md5(md5($data['new']));
                unset($user['userid']);
                $this->db->setTableName('admin')->update($user, 'userid = ?', $userid);
            } else {
                $this->show_message('原始密码错误', 2);
            }
            // if (!empty($data['password'])) {
            //     if (strlen($data['password']) < 6) $this->show_message('密码最少6位数', 2);
            //     $data['password'] = md5(md5($data['password']));
            // } else {
            //     unset($data['password']);
            // }
            // if ($data['auth']) unset($data['auth']);
            // if ($data['roleid']) unset($data['roleid']);
            // $this->db->setTableName('admin')->update($data, 'userid=?', $userid);
            // $data = array();
            foreach ($this->db->setTableName('admin')->findAll() as $t) {
                unset($t['password']);
                $data[$t['userid']] = $t;
            }
            set_cache('admin', $data);
            $this->show_message('修改成功', 1);
        }
        $data   = $this->db->setTableName('admin')->find($userid);
        include $this->admin_tpl('my');
    }
}

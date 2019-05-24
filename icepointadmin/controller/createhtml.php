<?php

class createhtml extends Admin {
    
    public function __construct() {
        parent::__construct();
		$this->show_message('生成静态功能是收费的 单独售价:200元',2,  'http://www.xiaocms.com/buy/');
	}
}
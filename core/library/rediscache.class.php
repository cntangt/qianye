<?php
if (!defined('IN_XIAOCMS')) exit();

class rediscache
{
    private $cache;

    public function __construct(){
        $this->cache=new Redis();
        $params = xiaocms::load_config('database');
        if (!is_array($params)) exit('数据库配置文件不存在');
        $this->cache->connect($params['redis_host'],$params['redis_port']);
        $this->cache->auth($params['redis_auth']);
    }

    function __destruct() {
        $this->cache->close();
    }

    public function get($key)
    {
        return json_decode($this->cache->get($key));
    }

    public function set($key,$val,$exp=3600){
        $this->cache->set($key,json_encode($val),$exp);
    }

    public function del($key)
    {
        $this->cache->delete($key);
    }
}
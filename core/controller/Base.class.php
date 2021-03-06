<?php
if (!defined('IN_XIAOCMS')) exit();

require_once SDK_DIR . 'yz/vendor/autoload.php';

abstract class Base
{

    protected $db;
    protected $cookie;
    protected $session;
    protected $site_config;
    protected $cache;
    protected $ispost;
    protected $isajax;

    public function __construct()
    {
        if (get_magic_quotes_runtime()) @set_magic_quotes_runtime(0);
        if (get_magic_quotes_gpc()) {
            $_POST = $this->strip_slashes($_POST);
            $_GET = $this->strip_slashes($_GET);
            $_SESSION = $this->strip_slashes($_SESSION);
            $_COOKIE = $this->strip_slashes($_COOKIE);
        }
        if (defined('XIAOCMS_ADMIN') || defined('XIAOCMS_MEMBER')) {
            define('SITE_PATH', self::get_a_url());
        } else {
            define('SITE_PATH', self::get_base_url());
        }
        $this->db = xiaocms::load_class('Model');
        $this->cookie = xiaocms::load_class('cookie');
        $this->session = xiaocms::load_class('session');
        $this->site_config = xiaocms::load_config('config');
        $this->cache = xiaocms::load_class('rediscache');
        $this->ispost = $_SERVER['REQUEST_METHOD'] == 'POST';
        $this->isajax = isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest";
    }

    public function show_message($msg, $status = 2, $url = HTTP_REFERER, $time = 1800)
    {
        include CORE_PATH . 'img' . DIRECTORY_SEPARATOR . 'message' . DIRECTORY_SEPARATOR . 'xiaocms_msg.tpl.php';
        exit;
    }

    protected function get_user_ip($default = '0.0.0.0')
    {
        $keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
        foreach ($keys as $key) {
            if (!isset($_SERVER[$key]) || !$_SERVER[$key]) {
                continue;
            }
            return htmlspecialchars($_SERVER[$key]);
        }
        return $default;
    }

    public static function get($string)
    {
        if (!isset($_GET[$string])) return null;
        if (!is_array($_GET[$string])) return htmlspecialchars(trim($_GET[$string]));
        return null;
    }

    public static function post($string)
    {
        if (!isset($_POST[$string])) return null;
        if (!is_array($_POST[$string])) return htmlspecialchars(trim($_POST[$string]));
        $postArray = self::array_map_htmlspecialchars($_POST[$string]);
        return $postArray;
    }

    protected static function array_map_htmlspecialchars($string)
    {
        foreach ($string as $key => $value) {
            $string[$key] = is_array($value) ? self::array_map_htmlspecialchars($value) : htmlspecialchars(trim($value));
        }
        return $string;
    }

    public static function get_http_host()
    {
        $http_host = strtolower($_SERVER['HTTP_HOST']);
        $secure = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 1 : 0;
        return ($secure ? 'https://' : 'http://') . $http_host;
    }

    public static function get_base_url()
    {
        $url = str_replace(array('\\', '//'), '/', $_SERVER['SCRIPT_NAME']);
        $po = strripos($url, '/');
        return substr($url, 0, $po + 1);
    }

    public static function get_a_url()
    {
        $url = str_replace(array('\\', '//'), '/', $_SERVER['SCRIPT_NAME']);
        $po = strripos($url, '/');
        $url = substr($url, 0, $po);
        $po = strripos($url, '/');
        return substr($url, 0, $po + 1);
    }

    protected function redirect($url)
    {
        if (!$url) return false;
        if (!headers_sent()) header("Location:" . $url);
        else echo '<script type="text/javascript">location.href="' . $url . '";</script>';
        exit();
    }

    protected static function strip_slashes($string)
    {
        if (!$string) return $string;
        if (!is_array($string)) return stripslashes($string);
        foreach ($string as $key => $value) {
            $string[$key] = self::strip_slashes($value);
        }
        return $string;
    }

    protected function checkCode($value)
    {
        $code = $this->session->get('checkcode');
        $value = strtolower($value);
        $this->session->delete('checkcode');
        return $code === $value ? true : false;
    }

    protected function watermark($file)
    {
        if (!$this->site_config['site_watermark']) return false;
        $image = xiaocms::load_class('image');
        $image->watermark($file, $this->site_config['site_watermark_pos']);
    }

    protected function json($val, $succ = true, $msg = null, $code = 0)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['succ' => $succ, 'msg' => $msg, 'code' => $code, 'val' => $val]);
        exit();
    }

    protected function yz_acc_token()
    {
        $type = 'silent';
        $keys['kdt_id'] = $this->site_config['yz_store_id'];

        $token = $this->cache->get('yz:acc_token');
        if ($token) {
            return $token;
        }

        $accessToken = (new \Youzan\Open\Token($this->site_config['yz_client_id'], $this->site_config['yz_client_secret']))->getToken($type, $keys);

        $this->cache->set('yz:acc_token', $accessToken['access_token'], 3600);

        return $accessToken['access_token'];
    }

    protected function http_get($url)
    {
        $curl = curl_init(); //初始化
        curl_setopt($curl, CURLOPT_URL, $url); //设置抓取的url
        curl_setopt($curl, CURLOPT_HEADER, 0); //设置为0不返回请求头信息
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 跳过https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($curl); //执行命令
        curl_close($curl); //关闭URL请求
        return json_decode($data, true); //返回获得的数据
    }

    protected function http_post($url, $data)
    {
        $curl = curl_init(); //初始化
        curl_setopt($curl, CURLOPT_URL, $url); //设置抓取的url
        curl_setopt($curl, CURLOPT_HEADER, 0); //设置为0不返回请求头信息
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 跳过https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1); //设置post方式提交
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['content-type' => 'application/x-www-form-urlencoded']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); //设置post数据，
        $data = curl_exec($curl); //执行命令
        curl_close($curl); //关闭URL请求
        return json_decode($data, true); //返回获得的数据
    }
}

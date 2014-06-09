<?php
/**
 *  session memcache 存储类
 */
class session_memcache {
    private  $lifetime = 1800;
    private  $memcache;
    private  $config;
    private  $sessionname = "WHTYCITY2";

    /**
 * 构造函数
 * 
 */
    public function __construct() {
    $this->memcache = new Memcache;
        /*'memcache' => array (
        'hostname' => '127.0.0.1',
        'port' => 11211,
        ),*/
        $this->config = wxcity_base::load_config('cache','memcache');
        $this->memcache->connect($this->config['hostname'],$this->config['port']) or die ("Could not connect");
        $this->lifetime = wxcity_base::load_config('system','session_ttl');
        session_name($this->sessionname);
        session_set_save_handler(array(&$this,'open'), array(&$this,'close'), array(&$this,'read'), array(&$this,'write'), array(&$this,'destroy'), array(&$this,'gc'));
        session_start();//dump(session_id());exit;
    }
/**
 * session_set_save_handler  open方法
 * @param $save_path
 * @param $session_name
 * @return true
 */
    public function open($save_path, $session_name) {

    return true;
    }
/**
 * session_set_save_handler  close方法
 * @return bool
 */
    public function close() {
        return true;
    } 
/**
 * 读取session_id
 * session_set_save_handler  read方法
 * @return string 读取session_id
 */
    public function read($id) {
        $value = $this->memcache->get($id);
    return $value;
    } 
/**
 * 写入session_id 的值
 * 
 * @param $id session
 * @param $data 值
 * @return mixed query 执行结果
 */
    public function write($id, $data) {
        return $this->memcache->set($id, $data, false, $this->lifetime);
    }
/** 
 * 删除指定的session_id
 * 
 * @param $id session
 * @return bool
 */
    public function destroy($id) {
        return $this->memcache->delete($id);
    }
/**
 * @return bool
 */
   public function gc($maxlifetime) {
    return true;
    }
}
?>
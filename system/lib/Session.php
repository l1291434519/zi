<?php


/**
 * @link http://yige.org/php/
 * session 数据库存储类
 */

class Session {

    private static $session_id      = 0;
    private static $session_data    = array();
    private static $is_update       = FALSE;
    private static $is_del          = FALSE;
    private static $is_gc           = FALSE;
    private static $dbo             = NULL;     //数据库连接句柄
    private static $gc_max_time     = 1440;
    private static $table           = 'sessions';
    private static $pre_key         = 'yige.org';//session 密钥
    //捆绑使用哈
    private static $gc_rate_de      = 100;//代表分母
    private static $gc_rate_co      = 20;//代表分子

    private static $path            = '/';//保存路径
    private static $domain          = null; //域
    private static $secure          = false;//默认
    private static $httponly        = false;//默认
    /**
     *  获取数据库句柄  私有
     */
    private static function open()
    {
        if (!self::$dbo)
        {
            self::$dbo = Db::factory();
        }
        return TRUE;
    }
    /**
     * 设置
     * */
    public static function set($key, $val=NULL)
    {

        self::open();
        $data = self::read();
        if ($data === FALSE)
        {
            $data = array();
        }
        if (!$val &amp;&amp; is_array($key))
        {
            $data = $key;
        }
        else if ($val &amp;&amp; is_string($key))
        {
            $data[$key] = $val;
        }
        self::write($data);
        self::close();
    }
    /**
     *获取值 
     * 
     */
    public static function get($key=NULL) {
        self::open();
        self::$session_data = self::read();
        $ret = '';
        if (!$key) {
            $ret = self::$session_data;
        } else if(is_array(self::$session_data) &amp;&amp; isset(self::$session_data[$key])) {
            $ret = self::$session_data[$key];
        }
        self::update();
        self::close();
        return $ret;
    }
    /**
     * 删除或者重置
     * */
    public static function del($key)
    {
        if (!self::$is_del)
        {
            self::open();

            $val = self::read();

            if (isset($val[$key]))
            {
                unset($val[$key]);
            }

            $session_id     = self::$session_id;
            $session_data   = serialize($val);
            $session_expire = TIME + self::get_gc_maxtime();
            self::$dbo-&gt;query(&quot;update &quot;.self::$table.&quot; set value='$session_data', expiry='$session_expire' where session_id='$session_id'&quot;);
            self::close();
        }
        self::$is_del = TRUE;
    }
    /**
     * 销毁
     * 
     * */
    public static function destroy()
    {
        $session_id         = self::get_session_id();
        $_COOKIE['WBSID']   = '';

        self::open();
        self::$dbo-&gt;query(&quot;delete from &quot;.self::$table.&quot; where session_id='$session_id'&quot;);
        self::close();
    }


    /**
     * 读取  私有
     * */
    private static function read()
    {
        $session_id = self::$session_id;
        if (!$session_id) {
            $session_id = self::get_session_id();
        }

        if (!$session_id) return array();

        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? md5($_SERVER['HTTP_USER_AGENT']) : '';
        $client_ip  = Fun::getIp();
        $session_expire = TIME - self::get_gc_maxtime();

        $rs = self::$dbo-&gt;fetchRow(&quot;select session_id, value, agent, ip from &quot;.self::$table.&quot;
            where session_id='$session_id' and expiry&gt;'$session_expire'&quot;);

        if (!$rs || $rs['agent'] != $user_agent || $rs['ip'] != $client_ip)
        {
            return FALSE;
        }

        self::$session_id = $rs['session_id'];
        return unserialize($rs['value']);
    }
    /**
     * session 写入   私有
     * */
    private static function write(array $session_data)
    {
        $session_id = self::$session_id;

        if (!$session_id)
        {
            $session_id = self::get_session_id();
        }

        $session_expire = TIME + self::get_gc_maxtime();

        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? md5($_SERVER['HTTP_USER_AGENT']) : '';
        $client_ip  = Fun::getIp();

        $session_data = serialize($session_data);

        if (self::$session_id &amp;&amp; self::$session_id === $session_id)
        {
            self::$dbo-&gt;query(&quot;update &quot;.self::$table.&quot; set value='$session_data', expiry='$session_expire', agent='$user_agent', ip='$client_ip' where session_id='$session_id'&quot;);
        }
        else
        {
            self::$session_id = $session_id = self::create_session_id();
            self::$dbo-&gt;query(&quot;insert into &quot;.self::$table.&quot;(session_id, value, expiry, agent, ip)
                values('$session_id', '$session_data', '$session_expire', '$user_agent', '$client_ip')&quot;);
        }
        return true;
    }
    /**
     * session 更新   私有
     * */
    private static function update()
    {
        if (!self::$is_update)
        {
            $session_id = self::$session_id;
            $session_expire = TIME + self::get_gc_maxtime();
            self::$dbo-&gt;query(&quot;update &quot;.self::$table.&quot; set expiry='$session_expire' where session_id='$session_id'&quot;);
        }
        self::$is_update = TRUE;
    }

    private static function close()
    {
        if (!self::$is_gc &amp;&amp; mt_rand(1, self::$gc_rate_de)%self::$gc_rate_co == 0)
        {
            self::gc();
        }
        self::$is_gc = TRUE;
    }
    /**
     * 过期session 清除  随机触发
     * */
    private static function gc()
    {

        $session_expire = TIME - self::get_gc_maxtime();
        self::$dbo-&gt;query(&quot;delete from &quot;.self::$table.&quot; where expiry&lt;'$session_expire'&quot;);
    }

    private static function get_session_id()
    {
        if (isset($_COOKIE['WBSID']) &amp;&amp; strlen($_COOKIE['WBSID'])==32)
        {
            $sid = $_COOKIE['WBSID'];
            setcookie('WBSID', $sid, TIME + self::get_gc_maxtime(), self::$path, self::$domain, self::$secure, self::$httponly);
            return $sid;
        }
        return null;
    }

    private static function create_session_id()
    {
        $sid = self::get_session_id();
        if (!$sid)
        {
            $sid = Fun::getIp() . TIME . microtime(TRUE) . mt_rand(mt_rand(0, 100), mt_rand(100000, 90000000));
            $sid = md5(self::$pre_key . $sid);
            setcookie('WBSID', substr($sid, 0, 32), TIME + self::get_gc_maxtime(), self::$path, self::$domain, self::$secure, self::$httponly);
        }
        return $sid;
    }

    public static function get_gc_maxtime()
    {
        return  self::$gc_max_time;
    }
}
?>
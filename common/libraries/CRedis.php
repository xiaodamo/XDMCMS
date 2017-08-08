<?php

/*
 * hosts 必须是 2^n 台
 * 首先将key归类到0~255共256个slice
 * 然后根据hosts来选择redis，便于扩展
 * hosts = 1:  h1 0~255
 * hosts = 2:  h1 0~127 h2 128~255
 * hosts = 4:  h1 0~63  h2 64~127  h3 128~191 h4 192~255
 * hosts = 8:  h1 0~31  h2 32~63   h3 64~95   h4 96~127 ...
 *
 * NOTE: 目前不支持script
 * */
class _credis_cluster
{
    private $redis   = array();
    private $hosts   = array();
    private $slices  = 0;
    private $mslices = 256;
    private $hslices = 0;
     
    public function __construct($hosts)
    {
        $this->hosts   = $hosts;
        $this->slices  = count($hosts);
        $this->hslices = intval($this->mslices/$this->slices);
        $this->redis[-1] = new _credis_error;
    }

    public function __call($name,$arguments)
    {
        return call_user_func_array(array($this->redis[$this->slice($arguments[0])],$name),$arguments);
    }

    public function slice_call($key,$name,$arguments)
    {
        return call_user_func_array(array($this->redis[$this->slice($key)],$name),$arguments);
    }

    private function slice($key)
    {
        if( is_string($key) )
        {
            $slice = intval((crc32($key)%$this->mslices)/$this->hslices);
        }
        else
        {
            $slice = 0;
        }
        if( isset($this->redis[$slice]) )
        {
            if( $this->redis[$slice] )
            {
                return $slice;
            }
            else
            {
                return -1;
            }
        }

        if( ($this->redis[$slice] = new _credis($this->hosts[$slice]['host'],$this->hosts[$slice]['port'])) )
        {
            return $slice;
        }
        return -1;
    }
}

class _credis extends Redis
{
    var $connected = false;

    public function __construct($host,$port)
    {
        parent::__construct();
        if( parent::connect($host,$port) )
        {
            $this->connected = true;
        }
        else
        {
            log_message('error',"cRedis connectto $host:$port failed." );
        }
    }

    public function slice_call($key,$name,$arguments)
    {
        return call_user_func_array(array($this,$name),$arguments);
    }

    /**  直接缓存 数组 
     *
     * @param  string $key    缓存key
     *
     * @paranm array  $value  值(数组)
     *
     * @param  int    $expire 缓存时间 
     *
     */
    public function setArray($key, array $value, $expire=60) {
        if ($key == '') {
            return false;
        }
        $value = serialize($value);
        return $this->SETEX($key, $expire, $value);
    }
    /**
     * 取缓存 (数组) 
     *
     * @param string $key 缓存key
     *
     */
    public function getArray($key) {
        if (!$key) {
            return false;
        }
        $value = $this->GET($key);
        $value = unserialize($value);
        return $value;
    }
    /**
     * 批量设置缓存
     *
     * @param array  $data   缓存数据 缓存格式为 array(key => data)
     *
     * @param string $keyfix 缓存key前缀
     * 
     * @param  int   $expire 缓存时间 
     *
     *
     */
    public function setRLists($data, $keyfix, $expire=60) {
        if (!$data || !$keyfix) {
            return false;
        }
        $result  = array();
        $keylist = array();
        foreach ($data as $kk => $dd) {
            $key          = sprintf($keyfix."%s", $kk);
            $result[$key] = serialize($dd);
            $keylist[]    = $key;
        }
        //批量缓存
        $res = $this->MSET($result);
        //设置key生存周期
        foreach ($keylist as $key) {
           $this->EXPIRE($key, $expire);
        }
        return $res;
    }
    /**
     * 批量获得缓存中数据
     *
     * @param array  缓存的key
     *
     * @param string 缓存key前缀  
     *
     */
    public function getRLists($keys, $keyfix) {
        if (!$keys || !$keyfix) {
            return false;
        }
        $keylist = array();
        foreach ($keys as $key) {
            $tmp_key   = sprintf($keyfix."%s", $key);
            $keylist[] = $tmp_key;
            $keylist2[$tmp_key] = $key;
        }
        $data    = $this->MGET($keylist);
        $lostkey = array();
        $result  = array();
        foreach ($data as $kk => $val) {
            $key = $keylist[$kk];
            $key = str_replace($keyfix, "", $key);
            //没有该key
            if ($val === false) {
                $lostkey[] = $key;
                continue;
            }
            $val = unserialize($val);
            $result[$key] = $val; 
        }
        return array('result' => $result, 'keys' => $lostkey);
    }
    /**
     * 批量设置缓存用hash
     *
     * @param array  $data   缓存数据 缓存格式为 array(key => data)`
     *
     * @param string $keyfix 缓存key前缀
     * 
     * @param  int   $expire 缓存时间   
     *
     *
     */
    public function setHashLists($data, $keyfix, $expire=60) {
        if (!$data || !$keyfix) {
            return false;
        }
        $result  = array();
        foreach ($data as $kk => $dd) {
            $result[$kk] = serialize($dd);
        }
        //批量缓存
        $res = $this->hMset($keyfix, $result);
        //设置key生存周期
        $this->EXPIRE($keyfix, $expire);
        return $res;
    }
    /**
     * 批量获得缓存中数据用hash
     *
     * @param array  缓存的key
     *
     * @param string 缓存key前缀  
     *
     */
    public function getHashLists(&$keys, $keyfix) {
        if (!$keys || !$keyfix) {
            return false;
        }
        $data    = $this->hMGet($keyfix,$keys);
        $lostkey = array();
        $result  = array();
        foreach ($data as $kk => $val) {
            //没有该key
            if ($val === false) {
                $lostkey[] = $kk;
                continue;
            }
            $val = unserialize($val);
            $result[$kk] = $val; 
        }
        $keys = $lostkey;
        return $result;
    }
}

class _credis_error
{
    public function __call($name,$arguments)
    {
        return false;
    }
}

class credis
{
    private static $redis_config = NULL;
    public static $_connector = array();
    public static $redis = array();

    public function __get($property)
    {
        if( !isset(self::$redis[$property]) )
        {
            if( !self::$redis_config )
            {
                $CI =& get_instance();
                $CI->config->load('redis');
                self::$redis_config = $CI->config->item('redis');
            }
            if( !isset(self::$redis_config[$property]) )
            {
                /* 配置文件不存在，缓存错误 */
                self::$redis[$property] = false;
            }
            else
            {
                if( isset(self::$redis_config[$property]['hosts']) && self::$redis_config[$property]['hosts'] )
                {
                    self::$redis[$property] = new _credis_cluster(self::$redis_config[$property]['hosts']);
                    return self::$redis[$property];
                }

                $key = self::$redis_config[$property]['host'] . ':' . self::$redis_config[$property]['port'];
                if( ! isset(self::$_connector[$key]) )
                {
                    self::$_connector[$key] = new _credis(self::$redis_config[$property]['host'],self::$redis_config[$property]['port']);
                    if( !self::$_connector[$key]->connected )
                    {
                        self::$_connector[$key] = new _credis_error;
                    }
                }
                self::$redis[$property] = self::$_connector[$key];
            }
        }
        return self::$redis[$property];
    }

    public function clear()
    {
        self::$_connector = array();
        self::$redis = array();
    }
}

?>

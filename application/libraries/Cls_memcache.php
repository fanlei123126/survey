<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cls_memcache {
    private $memcache = null;
    private $option = 'memServer:11211';//get all key时使用
    function __construct()
    {
        //$this->memcache = new Memcache();
        //@$this->memcache->connect('memServer', 11211) or $this->memcache=null;
    }
    
    /**
     * 存入值
     * 
     * @param String $key 键
     * @param Mixed  $val 值
     * @param Integer $time 保存几秒钟时间
     */
    function set($key, $val, $time=300)
    {
        if($this->memcache)
        {
            $this->memcache->set($key, $val, 0, $time);
        }
    }
    
    /**
     * 取值
     * 
     * @param String $key 键
     * @return
     */
    function get($key)
    {
        $returnval = FALSE;
        if($this->memcache)
        {
            $returnval = $this->memcache->get($key);
        }
        
        return $returnval;
    }
    
    /**
     * 根据KEY删除一个缓存
     * 
     * @param String $key 键
     * @param Integer $time 几秒钟之内删除
     * @return
     */
    function delete_bykey($key, $time=0)
    {
        $is_deleted = FALSE;
        if($this->memcache)
        {
            $time = intval($time);
            $is_deleted = $this->memcache->delete($key, $time);
        }
        return $is_deleted;
    }
    
    /**
     * 根据Key更新缓存
     * 
     * @param String $key 键
     * @param Mixed  $val 值
     * @param Integer $time 保存几秒钟时间
     */
    function update_bykey($key, $val, $time=300)
    {
        if($this->memcache)
        {
            $this->memcache->delete($key, 0);//立即删除
            $this->memcache->set($key, $val, 0, $time);
        }
    }
    
    /**
     * 清除
     * @return
     */
    function clean()
    {
        $is_clear = false;
        if($this->memcache)
        {
            $is_clear = $this->memcache->flush();
        }
        
        return $is_clear;
    }
    
    function __destruct()
    {
        
        if($this->memcache)
        {
            $this->memcache->close();   
        }
    }
    
    /**
     * @name 获得memcache中所有的KEY
     */
    function list_key()
    {
        $keys = array();
        if($this->memcache)
        {
            $all_items = $this->memcache->getExtendedStats('items'); 
            if(isset($all_items[$this->option]['items'])) 
            { 
                $items = $all_items[$this->option]['items'];
                foreach ($items as $number => $item) 
                { 
                    $str = $this->memcache->getExtendedStats('cachedump', $number, 0); 
                    $line = $str[$this->option]; 
                    if(is_array($line) && count($line) > 0)
                    {
                        foreach($line as $key => $value) 
                        {
                            $keys[] = $key; 
                        } 
                    } 
                } 
            }
            $keys = array_unique($keys);
        }
        return $keys;
    }
}
?>
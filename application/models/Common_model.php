<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

// ----------------------------------------------------------------------

class Common_model extends CI_Model
{
    private $utf8Db = null;
    function __construct()
    {
        //parent::__construct();
    }

    // ------------------------------------------------------------------
    /**
     * @name 连接数据库方法
     */
    private function connect_to_database()
    {
        if(empty($this->utf8Db))
        {
            $this->utf8Db = $this->load->database('keyike',TRUE);
        }
    }

    // ------------------------------------------------------------------

    /**
     * @name   获得总记录数
     *
     * @param  string table_name 数据表名
     * @param  array  where_arr  where条件
     *
     * @return int
     */
    function get_result_count($table_name='', $where_arr = array(), $escape = TRUE)
    {
        $this->connect_to_database();
        if(is_array($where_arr) && !empty($where_arr))
        {
            $this->utf8Db->where($where_arr, NULL, $escape);
        }
        $count = $this->utf8Db->count_all_results($table_name);
        return $count;
    }

    // ------------------------------------------------------------------

    /**
     * @name 获得记录
     *
     * @param  string table_name 数据表名
     * @param  array  where_arr  where条件
     * @param  int    size       mysql limit size (size=1 返回单个Object数据)
     * @param  int    offset     mysql limit offset
     * @param  string order_str  排序字符串
     * @param  array  select_arr 查询字段列表
     * @param  string result_type 返回结果的类型 'object'-对象；‘array’-数组
     *
     * @return $size>1：array(Object)；$size=1：Object
     */
    function get_result($table_name='', $where_arr = array(), $size=10, $offset=0, $order_str='', $select_arr=array(), $result_type='object', $where_escape = TRUE)
    {
        $this->connect_to_database();
        if(is_array($where_arr) && !empty($where_arr))
        {
            $this->utf8Db->where($where_arr, NULL, $where_escape);
        }

        if(is_array($select_arr) && !empty($select_arr))
        {
            $this->utf8Db->select($select_arr);
        }

        if(!empty($order_str))
        {
            $this->utf8Db->order_by($order_str);
        }

        $size = intval($size);
        $offset = intval($offset);

        if($size > 1)
        {
            $this->utf8Db->limit($size, $offset);
        }

        $q = $this->utf8Db->get($table_name);

        $res = null;
        if($size == 1)
        {
            $res = strtolower($result_type) == 'object' ? $q->row() : $q->row_array();
        }
        else
        {
            $res = strtolower($result_type) == 'object' ? $q->result() : $q->result_array();
        }
        return $res;
    }

    // ------------------------------------------------------------------

    /**
     * @name 插入数据
     *
     * @param string table_name 数据表名
     * @param array  param      插入数据
     *
     * @return int
     */
    function insert_data($table_name='', $param = array())
    {
        $this->connect_to_database();
        $id = 0;
        if(is_array($param) && !empty($param))
        {
            $is_added = $this->utf8Db->insert($table_name, $param);
            if($is_added)
            {
                $id = $this->utf8Db->insert_id();
            }
        }
        return $id;
    }

    // ------------------------------------------------------------------

    /**
     * @name 批量插入数据
     *
     * @param string table_name 数据表名
     * @param array  param      插入数据
     *  s
     * @return int
     */
    function batch_insert_data($table_name='', $param = array())
    {
        $this->connect_to_database();
        $is_succ = 0;
        if(is_array($param) && !empty($param))
        {
            $is_succ = $this->utf8Db->insert_batch($table_name, $param);
        }
        return $is_succ;
    }

    // ------------------------------------------------------------------

    /**
     * @name 更新数据
     *
     * @param string table_name 数据表名
     * @param array param       要更新的数据
     * @param array where_arr   更新条件
     * @param bool  escape      是否开启mysql过滤模式,默认TRUE-开启
     * @return bool
     */
    function upt_data($table_name='', $param=array(), $where_arr=array(),$escape=TRUE)
    {
        $this->connect_to_database();
        if(is_array($where_arr) && !empty($where_arr))
        {
            $this->utf8Db->where($where_arr, NULL, $escape);
        }

        if(is_array($param) && !empty($param))
        {
            foreach($param as $key=>$val)
            {
                $this->utf8Db->set($key, $val ,$escape);
            }
        }

        $is_upted = $this->utf8Db->update($table_name);
        return $is_upted;
    }

    // ------------------------------------------------------------------

    /**
     * @name 删除数据
     *
     * @param string table_name 数据表名
     * @param array where_arr   删除条件
     *
     * @return bool
     */
    function del_data($table_name='', $where_arr=array())
    {
        $this->connect_to_database();
        if(is_array($where_arr) && !empty($where_arr))
        {
            $this->utf8Db->where($where_arr);
        }

        $is_del = $this->utf8Db->delete($table_name);
        return $is_del;
    }

    // ------------------------------------------------------------------

    function __destruct()
    {
        if($this->utf8Db)
        {
            $this->utf8Db->close();
            unset($this->utf8Db);
        }
    }

    // ------------------------------------------------------------------
}
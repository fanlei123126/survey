<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

/**
 * 账户变化明细日志
*/
Class Account_log_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->tableName = "`account_log`";
        $this->tableId = "account_log_id";
    }

    /**
     * @name 创建
    */
    function CreateLog($param=array()){
        if($param){
            $kList = array();
            $vList = array();
            foreach($param as $k => $v){
                $kList[] = $k;
                $vList[] = "'".$v."'";
            }
            $sql = "insert into ".$this->tableName." (".implode(",", $kList).") values (".implode(",", $vList).")";
            $this->db->query($sql);
            return true;
        }
        return false;
    }

    /**
     * @name 分页获取账户日志信息
     * @param int $first
     * @param int $pageSize
     * @param array $condition
     * @return array
    */
    function getLogList($first=0, $pageSize=10, $condition=array()){
        $result = array();
        if($first>=0 && $pageSize>=0){
            $sql = "select * from ".$this->tableName." where status=1 order by  limit ".intval($first).",".intval($pageSize);
            $result = $this->db->query($sql)->result_array();
        }
        return $result;
    }

    /**
     * @name 获取账户日志总数
     * @param array $condition
     * @return int
    */
    function getLogTotal($condition=array()){
        $sql = "select count(*) as total from ".$this->tableName." where status=1 ";
        $result = $this->db->query($sql)->row_array();
        return isset($result['total'])?$result['total']:0;
    }

}
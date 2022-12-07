<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

/**
 * 文件信息
 */
Class File_list_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
        $this->tableName = "`file_list`";
        $this->tableId = "file_id";
    }

    /**
     * @name 根据文件ID获取文件信息
     * @param int $fileId
     * @return array
    */
    function getFileInfoById($fileId=0){
        $result = array();
        if($fileId>0){
            $sql = "select * from ".$this->tableName." where file_id=".intval($fileId);
            $result = $this->db->query($sql)->row_array();
        }
        return $result;
    }

    /**
     * @name 根据账号ID获取文件信息集合
     * @param int $accountId
     * @return array
    */
    function getFileListByAccountId($accountId=0){
        $result = array();
        if($accountId>0){
            $sql = "select * from ".$this->tableName." where account_id=".intval($accountId);
            $result = $this->db->query($sql)->result_array();
        }
        return $result;
    }

    /**
     * @name 创建文件
     * @param array $param
     * @return boolean
     */
    function CreateFile($param=array()){
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

}
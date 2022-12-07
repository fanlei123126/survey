<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Account_detail_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`account_detail`";
        $this->tableId = "account_detail_id";
    }

    /**
     * @name 根据账号ID获取账户扩展信息
     * @param int $accountId
     * @return array
    */
    function getAccountDetailById($accountId=0){
        if($accountId>0){
            $sql = "select * from ".$this->tableName." where account_id=".intval($accountId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

}
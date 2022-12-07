<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Account_list_model extends MY_Model{


    function __construct(){
        parent::__construct();
        $this->tableName = "`account`";
        $this->tableId = "account_id";
    }

    /**
     * @name 根据条件获取账号集合总数
     * @param array $condition
     * @return array
    */
    function getAccountTotal($condition=array()){
        $whereArr = array();
        $whereStr = "";
        if(isset($condition['name'])){
            $whereArr[] = " account_name like ".$this->db->escape($condition['name']);
        }
        if(isset($condition['r_name'])){
            $whereArr[] = " real_name like ".$this->db->escape($condition['r_name']);
        }

        if(isset($condition['role_id'])){
            $whereArr[] = " role_id = ".intval($condition['role_id']);
        }

        if($whereArr) {
            $whereStr = " where status=1 and ".implode(" and ", $whereArr);
        }else{
            $whereStr = " where status=1 ";
        }

        $sql = "select count(*) as total from ".$this->tableName.$whereStr." order by ".$this->tableId." desc";
        $result = $this->db->query($sql)->row_array();
        return isset($result['total'])?$result['total']:0;
    }

    /**
     * @name 根据条件分页获取账号集合
     * @param array $condition
     * @param int $first
     * @param int $pageSize
     * @return array
    */
    function getAccountListForPage($condition=array(), $first=0, $pageSize=10){
        $result = array();
        if($first>=0 && $pageSize>0){
            $whereArr = array();
            if(isset($condition['name'])){
                $whereArr[] = " account_name like ".$this->db->escape($condition['name']);
            }
            if(isset($condition['r_name'])){
                $whereArr[] = " real_name like ".$this->db->escape($condition['r_name']);
            }

            if(isset($condition['role_id'])){
                $whereArr[] = " role_id = ".intval($condition['role_id']);
            }

            if($whereArr) {
                $whereStr = " where status=1 and ".implode(" and ", $whereArr);
            }else{
                $whereStr = " where status=1 ";
            }

            $sql = "select * from ".$this->tableName.$whereStr." order by ".$this->tableId." desc limit ".intval($first).",".intval($pageSize);
            //log_message("error", "[Account_list_model-getAccountListForPage]SQL：".$sql);
            $list = $this->db->query($sql)->result_array();
          //  $this->load->model(array('system_role_model'));
            foreach($list as $res){
                $role = $this->system_role_model->getRoleInfoById($res['role_id']);
                $result[] = array(
                    'Id' => $res['account_id'],
                    'name' => $res['account_name'],
                    'real_name' => $res['real_name'],
                    'mobile' => $res['mobile'],
                    'createTime' => date("Y-m-d H:i:s", $res['create_time']),
                    'role_id' =>  $res['role_id'],
                    'status' => $res['status']==0?"无效":"有效",
                );
            }
        }
        return $result;
    }

    /**
     * @name 根据账号ID获取账号信息
     * @param int $accountId
     * @param int $status
     * @return array
    */
    function getAccountById($accountId=0, $status=-1){
        if($accountId>0){
            $whereStr = "";
            if($status>=0){
                $whereStr = " status=".$status." and ";
            }
            $sql = "select * from ".$this->tableName." where ".$whereStr.$this->tableId."=".intval($accountId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 根据用户姓名查询用户信息
     * @param string $userName
     * @return array
    */
    function searchUserList($userName=''){
        $result = array();
        $whereStr = "";
        if($userName){
            $whereStr = " and real_name like '%".$this->db->escape_like_str($userName)."%'";
        }
        $sql = "select * from ".$this->tableName." where status=1 ".$whereStr." order by ".$this->tableId." desc limit 10";
        $list = $this->db->query($sql)->result_array();
        if($list){
            foreach($list as $organ){
                $result[] = array(
                    'Id'=>$organ['account_id'],
                    'name'=>$organ['real_name']
                );
            }
        }
        return $result;
    }

    /**
     * @name 根据账号ID获取账号信息
     * @param string $userName
     * @param int $status
     * @param int $organId
     * @return array
     */
    function getAccountByName($userName=0, $status=-1){
        if($userName){
            $whereStr = "";
            if($status>=0){
                $whereStr .= " status=".$status." and ";
            }

            $sql = "select * from ".$this->tableName." where ".$whereStr." account_name=".$this->db->escape($userName);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 根据角色ID获取用户信息列表
     * @param int $roleId
     * @return array
    */
    function getAccountListByRoleId($roleId=0){
        $result = array();
        $whereStr = "";
        if($roleId){
            $whereStr = " and role_id = ".intval($roleId);
        }
        $sql = "select * from ".$this->tableName." where status=1 ".$whereStr." order by ".$this->tableId." desc limit 10";
        $list = $this->db->query($sql)->result_array();
        if($list){
            foreach($list as $organ){
                $result[] = array(
                    'Id'=>$organ['account_id'],
                    'name'=>$organ['real_name']
                );
            }
        }
        return $result;
    }

    /**
     * @name 根据时间段获取注册的账户总数
     * @param string $firstDay
     * @param string $lastDay
     * @return int
    */
    function getAccountTotalByDays($firstDay='', $lastDay=''){
        if($firstDay && $lastDay){
            $sql = "select count(*) as total from ".$this->tableName." where status=1 and (create_time between ".strtotime($firstDay)." and ".strtotime($lastDay).") order by create_time desc";
            $total = $this->db->query($sql)->row_array();
            return isset($total['total'])?$total['total']:0;
        }
        return 0;
    }

    /**
     * @name 获取所有注册账户总数
     * @return int
    */
    function getAllAccountTotal(){
        $sql = "select count(*) as total from ".$this->tableName." where status=1 order by create_time desc";
        $total = $this->db->query($sql)->row_array();
        return isset($total['total'])?$total['total']:0;
    }

}
<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class System_role_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`system_role`";
        $this->tableId = "system_role_id";
    }

    /**
     * @name 根据角色ID获取角色信息
     * @param int $roleId
     * @param int $status
     * @return array
    */
    function getRoleInfoById($roleId=0, $status=-1){
        if($roleId>0){
            $whereStr = "";
            if($status>=0){
                $whereStr = " status=".$status." and ";
            }
            $sql = "select * from ".$this->tableName." where ".$whereStr.$this->tableId."=".intval($roleId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 根据筛选条件获取角色集合数量
     * @param array $condition
     * @return array
    */
    function getRoleTotal($condition=array()){
        $whereArr = array();
        $whereStr = "";
        if(isset($condition['role_name'])){
            $whereArr[] = " role_name like ".$this->db->escape($condition['role_name']);
        }
        if($whereArr) {
            $whereStr = " where status=1 and system_id=".$this->systemId." and ".implode(" and ", $whereArr);
        }else{
            $whereStr = " where status=1 and system_id=".$this->systemId;
        }

        $sql = "select count(*) as total from ".$this->tableName.$whereStr." order by ".$this->tableId." desc";
        $result = $this->db->query($sql)->row_array();
        return isset($result['total'])?intval($result['total']):0;
    }

    /**
     * @name  根据筛选条件分页获取角色集合
     * @param array $condition
     * @param int $first
     * @param int $pageSize
     * @return array
    */
    function getRoleListForPage($condition=array(), $first=0, $pageSize=10){
        $result = array();
        if($first>=0 && $pageSize>0){
            $whereArr = array();
            $whereStr = "";
            if(isset($condition['role_name'])){
                $whereArr[] = " role_name like ".$this->db->escape($condition['role_name']);
            }
            if($whereArr) {
                $whereStr = " where status=1 and system_id=".$this->systemId." and ".implode(" and ", $whereArr);
            }else{
                $whereStr = " where status=1 and system_id=".$this->systemId;
            }

            $sql = "select * from ".$this->tableName.$whereStr." order by ".$this->tableId." desc limit ".intval($first).",".intval($pageSize);
            $list = $this->db->query($sql)->result_array();
            foreach($list as $role){
                $result[] = array(
                    'Id' => $role['system_role_id'],
                    'name' => $role['system_role_name'],
                    'code' => $role['system_role_code'],
                    'isAdmin' => $role['is_admin']
                );
            }
        }
        return $result;
    }

}
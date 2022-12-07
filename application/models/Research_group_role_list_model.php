<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Research_group_role_list_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->tableName = "`research_group_role_list`";
        $this->tableId = "research_group_role_id";
    }

    /**
     * @name 获取课题角色集合
     * @return array()
    */
    function getGroupRoleList(){
        $sql = "select * from ".$this->tableName." where status=1";
        return $this->db->query($sql)->result_array();
    }

    /**
     * @name 根据课题角色ID获取基础信息
     * @param int $groupRoleId
     * @return array
    */
    function getGroupRoleById($groupRoleId=0){
        if($groupRoleId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($groupRoleId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

}
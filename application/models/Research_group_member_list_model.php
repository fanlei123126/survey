<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Research_group_member_list_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->tableName = "`research_group_member_list`";
        $this->tableId = "research_group_member_id";
    }

    /**
     * @name 根据课题组ID获取所有组员信息
     * @param int $groupId
     * @return array
    */
    function getGroupMemberListByGroupId($groupId=0){
        if($groupId>0){
            $sql = "select * from ".$this->tableName." where research_group_id=".intval($groupId);
            return $this->db->query($sql)->result_array();
        }
        return array();
    }

    /**
     * @name 根据用户ID获取所有关联的课题ID
     * @param int $accountId
     * @return array
    */
    function getGroupMemberListByAccountId($accountId=0){
        if($accountId>0){
            $sql = "select * from ".$this->tableName." where account_id=".intval($accountId);
            return $this->db->query($sql)->result_array();
        }
        return array();
    }

}
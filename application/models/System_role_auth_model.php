<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class System_role_auth_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->tableName = "`system_role_auth`";
        $this->tableId = "auth_id";
    }

    /**
     * @name 根据用户ID和角色ID获取用户的角色权限
     * @param int $ystemId
     * @param int $roleId
     * @param int $menuId
     * @return array
    */
    function getAuthByRoleId($systemId=0, $roleId=0, $menuId=0){
        $result = array();
        if($systemId && $roleId && $menuId){
            $sql = "select * from ".$this->tableName." where system_id=".$systemId." and system_role_id=".intval($roleId)." and system_menu_id=".intval($menuId);
            $result = $this->db->query($sql)->row_array();
        }
        return $result;
    }

    /**
     * @name 根据系统ID和角色ID移除权限关联数据
     * @param int $sytemId
     * @param int $roleId
     * @return boolean
    */
    function removeRoleAuthByRoleId($sytemId=0, $roleId=0){
        $status = false;
        if($sytemId && $roleId){
            $sql = 'delete from '.$this->tableName." where system_id=".intval($sytemId)." and system_role_id=".intval($roleId);
            $status = $this->db->query($sql);
        }
        return $status;
    }

}
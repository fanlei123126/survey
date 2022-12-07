<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class System_menu_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`system_menu`";
        $this->tableId = "system_menu_id";
    }

    /**
     * @name 根据角色ID获取菜单信息
     * @param int $roleId
     * @return array
    */
    function getMenuListByRoleId($roleId=0){
        $result = array();
        if($roleId>0){
            $list = $this->getMenuListByUpid(0);

            foreach ($list as $i => $v){
                //根据角色权限判断是否显示菜单信息
                $children = $this->getMenuListByUpid($v['system_menu_id']);
                $childrenList = array();
                if($children) {
                    foreach ($children as $index => $childrenTree) {
                        if($childrenTree){
                            $childrenList[] = array(
                                'id'=>$childrenTree['system_menu_id'],
                                'name'=>$childrenTree['system_menu_name'],
                                'link'=>$childrenTree['menu_link'],
                                'icon'=>$childrenTree['icon']
                            );
                        }

                    }
                }
                $result[] = array(
                    'id'=>$v['system_menu_id'],
                    'name'=>$v['system_menu_name'],
                    'link'=>$v['menu_link'],
                    'icon'=>$v['icon'],
                    'children'=>$childrenList
                );
            }
        }
        return $result;
    }

    /**
     * @name 根据父级ID获取菜单信息集合
     * @param int $systemId
     * @param int $upid
     * @return array
    */
    function getMenuListByUpid($upid=0){
        $sql = "select * from ".$this->tableName." where status=1 and up_id=".intval($upid)." order by sort_id asc,system_menu_id asc";
        return $this->db->query($sql)->result_array();
    }

    /**
     * @name 根据菜单ID获取菜单信息
     * @param int $menuId
     * @return array
    */
    function getMenuInfoById($menuId=0){
        if($menuId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($menuId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

}
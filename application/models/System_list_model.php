<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class System_list_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`system_list`";
        $this->tableId = "system_id";
    }

    /**
     * @name 分页获取系统列表集合
     * @param int $first
     * @param int $pageSize
     * @return array
    */
    function getSystemList($first=0, $pageSize=10){
        $result = array();
        if($first>=0 && $pageSize>0){
            $sql = "select * from ".$this->tableName." order by ".$this->tableId." limit ".intval($first).",".intval($pageSize);
            $list = $this->db->query($sql)->result_array();
            if($list){
                foreach($list as $system){
                    $result[] = array(
                        'Id' => $system['system_id'],
                        'code' => $system['system_code'],
                        'name' => $system['system_name'],
                        'link' => $system['system_link'],
                        'status' => $system['status']==1?"有效":"无效"
                    );
                }
            }
        }
        return $result;
    }

    /**
     * @name  获取系统集合总数
     * @return int
    */
    function getSystemTotal(){
        $sql = "select count(*) as total from ".$this->tableName." order by ".$this->tableId;
        $result = $this->db->query($sql)->row_array();
        return isset($result['total'])?intval($result['total']):0;
    }

    /**
     * @name 根据系统ID获取系统信息
     * @param int $systemId
     * @return array
    */
    function getSystemById($systemId=0){
        if($systemId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($systemId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 获取所有系统信息集合
     * @return array
    */
    function getAllSystemList(){
        $result = array();
        $sql = "select * from ".$this->tableName." order by ".$this->tableId;
        $list = $this->db->query($sql)->result_array();
        if($list){
            foreach($list as $system){
                $result[] = array(
                    'Id' => $system['system_id'],
                    'code' => $system['system_code'],
                    'name' => $system['system_name'],
                    'link' => $system['system_link'],
                    'status' => $system['status']==1?"有效":"无效"
                );
            }
        }
        return $result;
    }

}
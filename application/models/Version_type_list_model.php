<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Version_type_list_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`version_type_list`";
        $this->tableId = "version_type_id";
    }

    /**
     * @name 分页获取版本类别列表集合
     * @param int $first
     * @param int $pageSize
     * @return array
     */
    function getVersionTypeList($first=0, $pageSize=10){
        $result = array();
        if($first>=0 && $pageSize>0){
            $sql = "select * from ".$this->tableName." where status=1 order by version_type_id limit ".intval($first).",".intval($pageSize);
            $list = $this->db->query($sql)->result_array();
            if($list){
                foreach($list as $res){
                    $result[] = array(
                        'Id' => $res['version_type_id'],
                        'name' => $res['version_type_name']
                    );
                }
            }
        }
        return $result;
    }

    /**
     * @name  获取版本类别集合总数
     * @return int
     */
    function getVersionTypeTotal(){
        $sql = "select count(*) as total from ".$this->tableName." order by version_type_id";
        $result = $this->db->query($sql)->row_array();
        return isset($result['total'])?intval($result['total']):0;
    }

    /**
     * @name 根据ID获取版本类别信息
     * @param int $versionTypeId
     * @return array()
    */
    function getVersionTypeInfoById($versionTypeId=0){
        $result = array();
        if($versionTypeId>0) {
            $sql = "select * from " . $this->tableName . " where version_type_id=".intval($versionTypeId);
            $result = $this->db->query($sql)->row_array();
        }
        return $result;
    }

    /**
     * @name 获取所有版本类别列表集合
     * @return array
     */
    function getAllVersionTypeList(){
        $result = array();
        $sql = "select * from ".$this->tableName." where status=1 order by version_type_id desc";
        $list = $this->db->query($sql)->result_array();
        if($list){
            foreach($list as $res){
                $result[] = array(
                    'Id' => $res['version_type_id'],
                    'name' => $res['version_type_name']
                );
            }
        }
        return $result;
    }


}
<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Organ_build_list_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`organ_build_list`";
        $this->tableId = "organ_build_id";
    }

    /**
     * @name 根据机构ID获取所有机构的实验大楼信息
     * @param int $organId
     * @return array
    */
    function getOrganBuildListByOrganId($organId=0){
        if($organId>0) {
            $sql = "select * from " . $this->tableName ." where organ_id=" .intval($organId). " order by " . $this->tableId . " desc";
            return $this->db->query($sql)->result_array();
        }
        return array();
    }

    /**
     * @name 根据实验大楼ID获取基础信息
     * @param int $organBuildID
     * @return array
    */
    function getOrganBuildInfo($organBuildID=0){
        if($organBuildID>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($organBuildID);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

}
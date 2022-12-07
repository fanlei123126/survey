<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Organ_laboratory_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`organ_laboratory`";
        $this->tableId = "organ_laboratory_id";
    }

    /**
     * @name 获取机构实验室集合
     * @return array
    */
    function getOrganLabList($organBuildId=0){
        if($organBuildId>0) {
            $sql = "select * from " . $this->tableName ." where organ_build_id=".intval($organBuildId). " order by " . $this->tableId . " desc";
            return $this->db->query($sql)->result_array();
        }
    }

    /**
     * @name 根据实验室ID获取信息
     * @param int $organLabId
     * @return array
    */
    function getOrganLabById($organLabId=0){
        if($organLabId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($organLabId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

}
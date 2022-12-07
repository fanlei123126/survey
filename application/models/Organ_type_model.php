<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Organ_type_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`organ_type`";
        $this->tableId = "organ_type_id";
    }

    /**
     * @name 获取单位类别信息集合
     * @return array
    */
    function getOrganTypeList(){
        $sql = "select * from ".$this->tableName." order by ".$this->tableId." desc";
        return $this->db->query($sql)->result_array();
    }

    /**
     * @name 根据单位类别ID获取单位类别信息
     * @param int $organTypeId
     * @return array
    */
    function getOrganTypeById($organTypeId=0){
        if($organTypeId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($organTypeId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

}
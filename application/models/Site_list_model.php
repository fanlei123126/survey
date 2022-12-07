<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Site_list_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`site_list`";
        $this->tableId = "site_id";
    }

    /**
     * @name 根据站点ID获取站点信息
     * @param int $siteId
     * @return array
    */
    function getSiteInfoById($siteId=0){
        if($siteId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($siteId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 根据系统ID获取站点信息
     * @param int $systemId
     * @return array
    */
    function getSiteInfoBySId($systemId=0){
        if($systemId>0){
            $sql = "select * from ".$this->tableName." where system_id=".intval($systemId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

}
<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Theme_list_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`theme_list`";
        $this->tableId = "theme_id";
    }


    /**
     * @name 根据模版ID获取模版信息
     * @param int $themeId
     * @return array
    */
    function getThemeInfoById($themeId=0){
        if($themeId>0){
            $sql = "select * from ".$this->tableName." where theme_id=".intval($themeId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 获取所有的模版列表
     * @return array
    */
    function getAllThemeList(){
        $sql = "select * from ".$this->tableName." order by theme_id desc ";
        return $this->db->query($sql)->row_array();
    }

}
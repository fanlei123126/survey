<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Terminal_type_list_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`terminal_type_list`";
        $this->tableId = "terminal_type_id";
    }

    /**
     * @name 根据终端类别ID获取终端类别信息
     * @param int $terminalTypeId
     * @return array
     */
    function getTerminalTypeById($terminalTypeId=0){
        if($terminalTypeId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($terminalTypeId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 获取终端类别信息集合
     * @return array
    */
    function getTerminalList(){
        $result = array();
        $sql = "select * from ".$this->tableName." order by ".$this->tableId." desc";
        $list = $this->db->query($sql)->result_array();
        if($list){
            foreach($list as $res){
                $result[] = array(
                    'Id' => $res['terminal_type_id'],
                    'name' => $res['terminal_type_name']
                );
            }
        }
        return $result;
    }

    /**
     * @name 根据终端类别名称获取终端类别信息
     * @param int getTerminalTypeByName
     * @return array
     */
    function getTerminalTypeByName($terminalTypeName=''){
        if($terminalTypeName){
            $sql = "select * from ".$this->tableName." where terminal_type_name=".$this->db->escape($terminalTypeName);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

}
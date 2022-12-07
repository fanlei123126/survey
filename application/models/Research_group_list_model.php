<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Research_group_list_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->tableName = "`research_group_list`";
        $this->tableId = "research_group_id";
    }

    /**
     * @name 根据时间段获取课题组信息总数
     * @param string $firstDay
     * @param string $lastDay
     * @return int
    */
    function getResearchGroupTotalByDays($firstDay='', $lastDay=''){
        if($firstDay && $lastDay){
            $sql = "select count(*) as total from ".$this->tableName." where status=1 and (create_time between ".strtotime($firstDay)." and ".strtotime($lastDay).") order by create_time desc";
            $total = $this->db->query($sql)->row_array();
            return isset($total['total'])?$total['total']:0;
        }
        return 0;
    }

    /**
     * @name 获取课题组信息总数
     * @return int
    */
    function getResearchGroupTotal(){
        $sql = "select count(*) as total from ".$this->tableName." where status=1 order by create_time desc";
        $total = $this->db->query($sql)->row_array();
        return isset($total['total'])?$total['total']:0;
    }

    /**
     * @name 根据课题ID获取课题信息
     * @param int $researchGroupId
     * @return array
    */
    function getRearchGroupInfoById($researchGroupId=0){
        if($researchGroupId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".$researchGroupId;
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

}
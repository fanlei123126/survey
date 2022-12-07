<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Organization_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`organization`";
        $this->tableId = "organ_id";
    }

    /**
     * @name 分页获取机构信息
     * @param array $condition
     * @param int $first
     * @param int $pageSize
     * @return array
    */
    function getOrganListForPage($condition=array(), $first=0, $pageSize=10){
        $result = array();
        if($first>=0 && $pageSize>0){
            if(isset($condition['up_id'])){
                $whereArr[] = " up_id = ".intval($condition['up_id']);
            }
            if($whereArr) {
                $whereStr = " where status=1 and ".implode(" and ", $whereArr);
            }else{
                $whereStr = " where status=1 ";
            }

            $sql = "select * from ".$this->tableName.$whereStr." order by organ_id desc limit ".intval($first).",".intval($pageSize);
            $list = $this->db->query($sql)->result_array();
            $this->load->model(array('organ_type_model','city_list_model'));
            foreach($list as $res){
                $organTypeInfo = $this->organ_type_model->getOrganTypeById($res['organ_type_id']);
                $cityInfo = $this->city_list_model->getCityById($res['city_id']);
                $accountInfo = $this->account_list_model->getAccountById($res['admin_account_id']);
                $result[] = array(
                    'Id' => $res['organ_id'],
                    'code' => $res['organ_code'],
                    'name' => $res['organ_name'],
                    'organType' => isset($organTypeInfo['organ_type_name'])?$organTypeInfo['organ_type_name']:"",
                    'cityName' => isset($cityInfo['city_name'])?$cityInfo['city_name']:"",
                    'serviceStartTime' => date("Y-m-d", $res['service_start_date']),
                    'serviceEndTime' => date("Y-m-d", $res['service_end_date']),
                    'siteName' => $res['site_name'],
                    'isAdmin' => $res['is_admin'],
                    'showBtn' => $res['admin_account_id']>1?1:0,
                    'adminName' => isset($accountInfo['real_name'])?$accountInfo['real_name']:""
                );
            }
        }
        return $result;
    }

    /**
     * @name 获取所有的机构总数
     * @param array $condition
     * @return int
    */
    function getOrganTotal($condition=array())
    {
        if(isset($condition['up_id'])){
            $whereArr[] = " up_id = ".intval($condition['up_id']);
        }
        if($whereArr) {
            $whereStr = " where status=1 and ".implode(" and ", $whereArr);
        }else{
            $whereStr = " where status=1 ";
        }

        $sql = "select count(*) as total from ".$this->tableName.$whereStr." order by organ_id desc";
        $res = $this->db->query($sql)->row_array();
        return isset($res['total'])?intval($res['total']):0;
    }

    /**
     * @name 根据机构ID获取机构信息
     * @param int $organId
     * @return array
    */
    function getOrganInfoById($organId=0){
        if($organId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($organId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 根据关键词搜索单位名称
     * @param string $organName
     * @parma boolea $is_admin
     * @return array()
    */
    function searchOrganName($organName="", $is_admin=false){
        $result = array();
        $whereStr = "";
        if($organName){
            $whereStr .= " and organ_name like '%".$this->db->escape_like_str($organName)."%'";
        }
        if($is_admin){
            $whereStr .= " and is_admin=0";
        }
        $sql = "select * from ".$this->tableName." where status=1 ".$whereStr." order by ".$this->tableId." desc limit 10";
        $list = $this->db->query($sql)->result_array();
        if($list){
            foreach($list as $organ){
                $result[] = array(
                    'Id'=>$organ['organ_id'],
                    'name'=>$organ['organ_name']
                );
            }
        }
        return $result;
    }

    /**
     * @name 根据机构名称获取机构信息
     * @param string $organName
     * @return array
     */
    function getOrganInfoByName($organName="", $status=-1){
        if($organName){
            $whereStr = "";
            if($status>=0){
                $whereStr = " status=".$status." and ";
            }
            $sql = "select * from ".$this->tableName." where ".$whereStr." organ_name=".$this->db->escape($organName);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 根据时间段获取注册的机构总数
     * @param string $firstDay
     * @param string $lastDay
     * @return int
     */
    function getOrganTotalByDays($firstDay='', $lastDay=''){
        if($firstDay && $lastDay){
            $sql = "select count(*) as total from ".$this->tableName." where status=1 and (create_time between ".strtotime($firstDay)." and ".strtotime($lastDay).") order by create_time desc";
            $total = $this->db->query($sql)->row_array();
            return isset($total['total'])?$total['total']:0;
        }
        return 0;
    }

    /**
     * @name 获取所有注册机构总数
     * @return int
     */
    function getAllOrganTotal(){
        $sql = "select count(*) as total from ".$this->tableName." where status=1 order by create_time desc";
        $total = $this->db->query($sql)->row_array();
        return isset($total['total'])?$total['total']:0;
    }

}
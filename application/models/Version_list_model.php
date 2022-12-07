<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Version_list_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`version_list`";
        $this->tableId = "version_id";
    }

    /**
     * @name 分页获取版本列表集合
     * @param array $condition
     * @param int $first
     * @param int $pageSize
     * @return array
     */
    function getVersionList($condition=array(), $first=0, $pageSize=10){
        $result = array();
        if($first>=0 && $pageSize>0){
            $whereArr = array();
            $whereStr = "";
            if(isset($condition['name'])){
                $whereArr[] = " version_name like ".$this->db->escape($condition['name']);
            }
            if($whereArr) {
                $whereStr = " where status=1 and ".implode(" and ", $whereArr);
            }else{
                $whereStr = " where status=1";
            }

            $sql = "select * from ".$this->tableName.$whereStr." order by version_id limit ".intval($first).",".intval($pageSize);
            $list = $this->db->query($sql)->result_array();
            if($list){
                $this->load->model('version_type_list_model');
                foreach($list as $res){
                    $versionTypeInfo = $this->version_type_list_model->getVersionTypeInfoById($res['version_type_id']);
                    $result[] = array(
                        'Id' => $res['version_id'],
                        'code' => $res['version_code'],
                        'name' => $res['version_name'],
                        'typeName' => $versionTypeInfo['version_type_name'],
                        'time' => $res['online_time']?date("Y-m-d", $res['online_time']):"",
                        'status' => $res['status']==0?"无效":"有效"
                    );
                }
            }
        }
        return $result;
    }

    /**
     * @name  获取版本集合总数
     * @return int
     */
    function getVersionTotal($condition=array()){
        $whereArr = array();
        $whereStr = "";
        if(isset($condition['name'])){
            $whereArr[] = " version_name like ".$this->db->escape($condition['name']);
        }
        if($whereArr) {
            $whereStr = " where status=1 and ".implode(" and ", $whereArr);
        }else{
            $whereStr = " where status=1 ";
        }

        $sql = "select count(*) as total from ".$this->tableName.$whereStr." order by version_id";
        $result = $this->db->query($sql)->row_array();
        return isset($result['total'])?intval($result['total']):0;
    }

    /**
     * @name 根据版本ID获取版本信息
     * @param int $versionId
     * @return array
    */
    function getVersionById($versionId=0){
        if($versionId>0){
            $sql = "select * from ".$this->tableName." where version_id=".intval($versionId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 搜索版本号获取版本信息集合
     * @param string  $versionName
     * @return array
    */
    function searchVersionList($versionName=""){
        $result = array();
        $whereStr = "";
        if($versionName){
            $whereStr = " and (version_code like '%".$this->db->escape_like_str($versionName)."%' or version_name like '%".$this->db->escape_like_str($versionName)."%')";
        }
        $sql = "select * from ".$this->tableName." where status=1 ".$whereStr." order by ".$this->tableId." desc limit 10";
        $list = $this->db->query($sql)->result_array();
        if($list){
            foreach($list as $organ){
                $result[] = array(
                    'Id'=>$organ['version_id'],
                    'code'=>$organ['version_code'],
                    'name'=>$organ['version_name']
                );
            }
        }
        return $result;
    }

}
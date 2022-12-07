<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class City_list_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->tableName = "`city_list`";
        $this->tableId = "city_id";
    }

    /**
     * @name 分页获取城市列表集合
     * @param int $first
     * @param int $pageSize
     * @return array
     */
    function getCityList($first=0, $pageSize=10){
        $result = array();
        if($first>=0 && $pageSize>0){
            $sql = "select * from ".$this->tableName." where status=1 order by ".$this->tableId." limit ".intval($first).",".intval($pageSize);
            $list = $this->db->query($sql)->result_array();
            if($list){
                foreach($list as $res){
                    $result[] = array(
                        'Id' => $res['device_id'],
                        'code' => $res['device_code'],
                        'name' => $res['device_name'],
                        'remark' => $res['remark']
                    );
                }
            }
        }
        return $result;
    }

    /**
     * @name  获取城市集合总数
     * @return int
     */
    function getCityTotal(){
        $sql = "select count(*) as total from ".$this->tableName." where status=1 order by ".$this->tableId;
        $result = $this->db->query($sql)->row_array();
        return isset($result['total'])?intval($result['total']):0;
    }

    /**
     * @name 根据城市ID获取城市信息
     * @param int $deviceId
     * @return array
     */
    function getCityById($cityId=0){
        if($cityId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($cityId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 获取所有城市信息集合
     * @return array
    */
    function getAllCityList(){
        $sql = "select * from ".$this->tableName." where status=1 order by ".$this->tableId;
        return $this->db->query($sql)->result_array();
    }

    /**
     * @name 根据城市ID获取城市信息
     * @param string $cityName
     * @return array
     */
    function getCityByName($cityName=''){
        if($cityName){
            $sql = "select * from ".$this->tableName." where city_name=".$this->db->escape($cityName);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

}
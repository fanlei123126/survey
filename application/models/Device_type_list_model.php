<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Device_type_list_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`device_type_list`";
        $this->tableId = "device_type_id";
    }

    /**
     * @name 分页获取系统列表集合
     * @param int $first
     * @param int $pageSize
     * @param int $upid
     * @return array
     */
    function getDeviceTypeList($first=0, $pageSize=10, $upid=0){
        $result = array();
        if($first>=0 && $pageSize>0){
            $sql = "select * from ".$this->tableName." where status=1 and upid=".$upid." order by device_type_id limit ".intval($first).",".intval($pageSize);
            $list = $this->db->query($sql)->result_array();
            if($list){
                foreach($list as $res){
                    $result[] = array(
                        'Id' => $res['device_type_id'],
                        'code' => $res['device_type_code'],
                        'name' => $res['device_type_name'],
                        'remark' => $res['remark']
                    );
                }
            }
        }
        return $result;
    }

    /**
     * @name  获取系统集合总数
     * @param int $upid
     * @return int
     */
    function getDeviceTypeTotal($upid=0){
        $sql = "select count(*) as total from ".$this->tableName." where status=1 and upid=".$upid." order by device_type_id";
        $result = $this->db->query($sql)->row_array();
        return isset($result['total'])?intval($result['total']):0;
    }

    /**
     * @name 根据设备类别ID获取设备类别信息
     * @param int $deviceTypeId
     * @return array
    */
    function getDeviceTypeById($deviceTypeId=0){
        if($deviceTypeId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($deviceTypeId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 根据设备类别名称获取设备类别信息
     * @param string $deviceTypeName
     * @return array
     */
    function getDeviceTypeByName($deviceTypeName=0){
        if($deviceTypeName>0){
            $sql = "select * from ".$this->tableName." where device_type_name=".$this->db->escape($deviceTypeName);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

}
<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Device_list_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->tableName = "`device_list`";
        $this->tableId = "device_id";
    }

    /**
     * @name 分页获取系统列表集合
     * @param int $first
     * @param int $pageSize
     * @return array
     */
    function getDeviceList($first=0, $pageSize=10){
        $result = array();
        if($first>=0 && $pageSize>0){
            $sql = "select * from ".$this->tableName." where status=1 order by device_type_id limit ".intval($first).",".intval($pageSize);
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
     * @name  获取系统集合总数
     * @return int
     */
    function getDeviceTotal(){
        $sql = "select count(*) as total from ".$this->tableName." where status=1 order by ".$this->tableId;
        $result = $this->db->query($sql)->row_array();
        return isset($result['total'])?intval($result['total']):0;
    }

    /**
     * @name 根据设备类别ID获取设备类别信息
     * @param int $deviceId
     * @return array
     */
    function getDeviceById($deviceId=0){
        if($deviceId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($deviceId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 根据设备名称搜索设备
     * @param string $deviceName
     * @return array
    */
    function searchDeviceList($deviceName=""){
        $result = array();
        $whereStr = "";
        if($deviceName){
            $whereStr = " and device_name like '%".$this->db->escape_like_str($deviceName)."%'";
        }
        $sql = "select * from ".$this->tableName." where status=1 ".$whereStr." order by ".$this->tableId." desc limit 10";
        $list = $this->db->query($sql)->result_array();
        if($list){
            foreach($list as $organ){
                $result[] = array(
                    'Id'=>$organ['device_id'],
                    'name'=>$organ['device_name']
                );
            }
        }
        return $result;
    }

    /**
     * @name 根据设备名称搜索设备
     * @param string $deviceName
     * @param int $organId
     * @return array
     */
    function searchDeviceListByDeviceName($deviceName="", $organId=0){
        $result = array();
        $whereStr = "";
        if($organId>0){
            $whereStr .= " and organ_id=".intval($organId);
        }
        if($deviceName){
            $whereStr .= " and device_name like '%".$this->db->escape_like_str($deviceName)."%'";
        }
        $sql = "select * from ".$this->tableName." where status=1 ".$whereStr." order by ".$this->tableId." desc";
        log_message("error", "[device_list_model-searchDeviceListByDeviceName]SQL:".$sql);
        $list = $this->db->query($sql)->result_array();
        if($list){
            $this->load->model(array('terminal_list_model', 'terminal_control_device_model'));
            foreach($list as $device){
                $controlInfo = $this->terminal_control_device_model->getControlInfoByDeviceId($device['device_id']);
                if(!$controlInfo) {
                    $result[] = array(
                        'Id' => $device['device_id'],
                        'name' => $device['device_name']
                    );
                }
            }
        }
        return $result;
    }



    /**
     * @name 根据时间段获取注册的设备总数
     * @param string $firstDay
     * @param string $lastDay
     * @param int $runStatus
     * @param int $serviceStatus
     * @return int
     */
    function getDeviceTotalByDays($firstDay='', $lastDay='', $runStatus=0, $serviceStatus=1){
        if($firstDay && $lastDay){
            $whereStr = "";
            switch($runStatus){
                case 1:
                    $whereStr = " and run_status=0 ";
                    break;
                case 2:
                    $whereStr = " and run_status=1 ";
                    break;
                default:

                    break;
            }
            switch($serviceStatus){
                case 1:
                    $whereStr .= " and service_status=0 ";
                    break;
                case 2:
                    $whereStr .= " and service_status=1 ";
                    break;
                default:

                    break;
            }
            $sql = "select count(*) as total from ".$this->tableName." where status=1 and (create_time between ".strtotime($firstDay)." and ".strtotime($lastDay).")".$whereStr." order by create_time desc";
            $total = $this->db->query($sql)->row_array();
            return isset($total['total'])?$total['total']:0;
        }
        return 0;
    }

    /**
     * @name 获取所有注册设备总数
     * @param int $runStatus
     * @param int $serviceStatus
     * @return int
     */
    function getAllDeviceTotal($runStatus=0, $serviceStatus=0){
        $whereStr = "";
        switch($runStatus){
            case 1:
                $whereStr .= " and run_status=0 ";
                break;
            case 2:
                $whereStr .= " and run_status=1 ";
                break;
            default:

                break;
        }
        switch($serviceStatus){
            case 1:
                $whereStr .= " and service_status=0 ";
                break;
            case 2:
                $whereStr .= " and service_status=1 ";
                break;
            default:

                break;
        }
        $sql = "select count(*) as total from ".$this->tableName." where status=1 ".$whereStr." order by create_time desc";
        $total = $this->db->query($sql)->row_array();
        return isset($total['total'])?$total['total']:0;
    }

}
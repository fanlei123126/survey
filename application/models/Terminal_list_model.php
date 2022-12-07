<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Terminal_list_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->tableName = "`terminal_list`";
        $this->tableId = "terminal_id";
    }

    /**
     * @name 根据条件获取终端集合总数
     * @param array $condition
     * @return array
    */
    function getTerminalTotal($condition=array()){
        $whereArr = array();
        $whereStr = "";
        if(isset($condition['name'])){
            $whereArr[] = " terminal_name like ".$this->db->escape($condition['name']);
        }if(isset($condition['r_name'])){
            $whereArr[] = " terminal_code like ".$this->db->escape($condition['code']);
        }
        if($whereArr) {
            $whereStr = " where status=1 and ".implode(" and ", $whereArr);
        }else{
            $whereStr = " where status=1 ";
        }

        $sql = "select count(*) as total from ".$this->tableName.$whereStr." order by terminal_id desc";
        $result = $this->db->query($sql)->row_array();
        return isset($result['total'])?intval($result['total']):0;
    }

    /**
     * @name 根据条件分页获取终端集合总数
     * @param array $condition
     * @param int $first
     * @param int $pageSize
     * @return array
    */
    function getTerminalListForPage($condition=array(), $first=0, $pageSize=10){
        $result = array();
        if($first>=0 && $pageSize>0) {
            $whereArr = array();
            $whereStr = "";
            if(isset($condition['name'])){
                $whereArr[] = " terminal_name like ".$this->db->escape($condition['name']);
            }if(isset($condition['r_name'])){
                $whereArr[] = " terminal_code like ".$this->db->escape($condition['code']);
            }
            if($whereArr) {
                $whereStr = " where status=1 and ".implode(" and ", $whereArr);
            }else{
                $whereStr = " where status=1 ";
            }

            $sql = "select * from ".$this->tableName.$whereStr." order by terminal_id desc limit ".intval($first).",".intval($pageSize);
            $list = $this->db->query($sql)->result_array();
            if($list){
                $this->load->model(array('terminal_type_list_model','device_list_model', 'version_list_model','terminal_control_device_model'));
                foreach($list as $terminal){
                    $deviceInfo = array();
                    $terminalTypeInfo = $this->terminal_type_list_model->getTerminalTypeById($terminal['terminal_type_id']);
                    $deviceId = $this->terminal_control_device_model->getControlById($terminal['terminal_id']);
                    if($deviceId>0) {
                        $deviceInfo = $this->device_list_model->getDeviceById($deviceId);
                    }
                    $versionInfo = $this->version_list_model->getVersionById($terminal['version_id']);
                    $serviceStatus = "";
                    switch($terminal['service_status']){
                        case '0':
                            $serviceStatus = "关闭";
                            break;
                        case '1':
                            $serviceStatus = "启用";
                            break;
                        case '2':
                            $serviceStatus = "维护中";
                            break;
                        case '3':
                            $serviceStatus = "报废";
                            break;
                    }
                    $result[] = array(
                        'Id' => $terminal['terminal_id'],
                        'code' => $terminal['terminal_code'],
                        'name' => $terminal['terminal_name'],
                        'typeName' => isset($terminalTypeInfo['terminal_type_name'])?$terminalTypeInfo['terminal_type_name']:"",
                        'deviceName' => isset($deviceInfo['device_name'])?$deviceInfo['device_name']:"",
                        'versionName' => isset($versionInfo['version_name'])?$versionInfo['version_name']:"",
                        'license' => $terminal['license'],
                        'ip' => $terminal['ip'],
                        'token' => $terminal['token'],
                        'serviceTime' => date("Y-m-d", $terminal['service_start_time'])." - ".date('Y-m-d', $terminal['service_end_time']),
                        'serviceStatus' => $serviceStatus,
                        'runStatus' => $terminal['run_status']?"通信正常":"通信断开"
                    );
                }
            }
        }
        return $result;
    }

    /**
     * @name 根据终端ID获取终端信息
     * @param int $terminalId
     * @return array
    */
    function getTerminalById($terminalId=0){
        if($terminalId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($terminalId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 根据IP获取终端
     * @param string $ip
     * @return array
    */
    function getTerminalInfoByIP($ip=""){
        if($ip){
            $sql = "select * from ".$this->tableName." where ip=".$this->db->escape($ip);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 根据授权码获取终端信息
     * @param string $license
     * @return array
    */
    function getTerminalInfoByLicense($license=""){
        if($license){
            $sql = "select * from ".$this->tableName." where license=".$this->db->escape($license);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

}
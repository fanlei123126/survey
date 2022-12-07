<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class Terminal_control_device_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->tableName = "`terminal_control_device`";
        $this->tableId = "control_device_id";
    }

    /**
     * @name 根据设备ID获取终端ID
     * @param int $terminalId
     * @return int
    */
    function getControlById($terminalId=0){
        if($terminalId>0){
            $sql = "select * from ".$this->tableName." where terminal_id=".intval($terminalId);
            $info = $this->db->query($sql)->row_array();
            return isset($info['device_id'])?$info['device_id']:0;
        }
        return 0;
    }

    /**
     * @name 根据设备ID获取终端信息
     * @param int $deviceId
     * @return int
    */
    function getTerminalIdByDeviceId($deviceId=0){
        if($deviceId>0){
            $sql = "select * from ".$this->tableName." where device_id=".intval($deviceId);
            $info = $this->db->query($sql)->row_array();
            return isset($info['terminal_id'])?$info['terminal_id']:0;
        }
        return 0;
    }

    /**
     * @name 根据终端ID获取关联的设备信息
     * @param int $terminalId
     * @return array
    */
    function getControlInfoByTerminalId($terminalId=0){
        if($terminalId>0){
            $sql = "select * from ".$this->tableName." where terminal_id=".intval($terminalId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 根据设备ID获取关联的终端信息
     * @param int $deviceId
     * @return array
    */
    function getControlInfoByDeviceId($deviceId=0){
        if($deviceId>0){
            $sql = "select * from ".$this->tableName." where device_id=".intval($deviceId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

}
<?php if( ! defined('BASEPATH'))  exit('No direct script access allowed');

Class System_log_model extends MY_Model
{

    //操作类别：0=登录；-1=注销
    private $operate = array(
        '1' => '表单提交',
        '2' => '数据传输'
    );
    //系统版块：1=系统配置；2=账号管理；3=机构管理；4=角色权限；5=设备管理；6=终端管理；7=软件版本
    private $sectionType = array(
        '1' => '系统配置',
        '2' => '账号管理',
        '3' => '机构管理',
        '4' => '角色权限',
        '5' => '设备管理',
        '6' => '终端管理',
        '7' => '软件版本',
        '8' => '登录注销'
    );
    //数据操作：1=查询；2=创建；3=更新；4=删除
    private $curd = array(
        '1' => '查询',
        '2' => '创建',
        '3' => '更新',
        '4' => '删除'
    );
    private $AccountTableName = "`account_list`";

    function __construct(){
        parent::__construct();
        $this->tableName = "`system_log`";
        $this->tableId = "system_log_id";
    }

    /**
     * @name 根据条件获取系统日志集合总数
     * @param array $condition
     * @return int
    */
    function getLogTotal($condition=array()){
        $whereStr = "";
        $whereList = array();
        if($condition['username']){
            $whereList[] = " user.realname like ".$this->db->escape($condition['username']);
        }
        if($condition['mobile']){
            $whereList[] = " user.mobile=".$this->db->escape($condition['mobile']);
        }
        if($whereList){
            $whereStr = " where ".implode(" and ", $whereList);
        }

        $sql = "select count(*) as total from ".$this->tableName." as log left join ".$this->AccountTableName." as user on log.account_id=user.account_id ".$whereStr." order by log.system_log_id desc";
        $result = $this->db->query($sql)->row_array();
        return isset($result['total'])?intval($result['total']):0;
    }

    /**
     * @name 根据条件分页获取系统日志集合
     * @param int $first
     * @param int $pageSize
     * @param array $condition
     * @return array
    */
    function getLogListForPage($first=0, $pageSize=10, $condition=array()){
        $result = array();
        if($first>=0 && $pageSize>0){
            $whereStr = "";
            $whereList = array();
            if($condition['username']){
                $whereList[] = " user.realname like ".$this->db->escape($condition['username']);
            }
            if($condition['mobile']){
                $whereList[] = " user.mobile=".$this->db->escape($condition['mobile']);
            }
            if($whereList){
                $whereStr = " where ".implode(" and ", $whereList);
            }

            $sql = "select * from ".$this->tableName." as  log left join ".$this->AccountTableName." as user on log.account_id=user.account_id ".$whereStr." order by log.system_log_id desc limit ".intval($first).",".intval($pageSize);
            $list = $this->db->query($sql)->result_array();
            foreach($list as $i){
                $result[] = array(
                    'Id' => $i['system_log_id'],
                    'userName' => $i['real_name'],
                    'title' => $i['title'],
                    'remark' => $i['remark'],
                    'createTime' => date("Y-m-d H:i:s", $i['create_time']),
                    'systemType' => $this->systemType[$i['system_type_id']],
                    'operateType' => $this->operate[$i['operate_type']],
                    'sectionType' => $this->sectionType[$i['section_type']],
                    'dataType' => $this->curd[$i['curd']]
                );
            }
        }
        return $result;
    }

    /**
     * @name 根据日志ID获取日志信息
     * @param int $logId
     * @return array
    */
    function getLogInfoById($logId=0){
        if($logId>0){
            $sql = "select * from ".$this->tableName." where ".$this->tableId."=".intval($logId);
            return $this->db->query($sql)->row_array();
        }
        return array();
    }

    /**
     * @name 新增系统日志
     * @param int $systemLogTypeId
     * @param int $accountId
     * @param int $roleId
     * @param string $title
     * @param string $remark
     * @param int $operateTypeId
     * @param int $sectionType
     * @param int $curd
     * @return boolean
    */
    function addLog($systemLogTypeId=0, $accountId=0, $roleId=0, $title="", $remark="", $operateTypeId=0, $sectionType=0, $curd=0){
        $data = array(
            'system_log_type_id' => $systemLogTypeId,
            'account_id' => $accountId,
            'role_id' => $roleId,
            'title' => $title,
            'remark' => $remark,
            'create_time' => time(),
            'system_type_id' => 1,
            'operate_type' => $operateTypeId,
            'section_type' => $sectionType,
            'curd' => $curd
        );

        $kList = array();
        $vList = array();
        foreach($data as $k => $v){
            $kList[] = $k;
            $vList[] = "'".$v."'";
        }
        $sql = "insert into ".$this->tableName." (".implode(',', $kList).") values (".implode(',', $vList).")";
        return $this->db->query($sql);
    }

}
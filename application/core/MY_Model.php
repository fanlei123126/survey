<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    public $db;
    public $tableName;
    public $tableId;
    public $systemId=1;

    function __construct()
    {
        parent::__construct();
        if(!$this->db)
        {
            $CI =& get_instance();
            if(isset($CI->db) && $CI->db){
            }else{
                $CI->db = $this->load->database('default', true);
            }
            $this->db = $CI->db;
        }
    }


    /**
     * @name delete 删除数据库中的数据
     * @param int 数据表的主键ID
     * @return boolean 是否删除成功
     */
    function delete($tableId=0){
        $sql = 'DELETE FROM '.$this->tableName." where ".$this->tableId."=".intval($tableId);
        $status = $this->db->query($sql);
        return $status;
    }

    /**
     * @name update 修改数据库中的数据
     * @param int $tableId 主键ID
     * @param array $data 修改的数据
     * @return boolean 是否修改成功
     */
    function update($tableId=0, $data=array()){
        $status = 0;
        if($tableId>0 && $data){
            $status = $this->db->update($this->tableName, $data, array($this->tableId =>intval($tableId)));
        }
        return $status;
    }

    /**
     * @name add 新增数据
    */
    function add($param=array()){

        if($param){
            $this->db->insert($this->tableName, $param);
            return $this->db->insert_id();
        }
        return 0;
    }

    /**
     * @name trans_start 开启事务  CI智能的事务系统
     */
    function trans_start(){
        $this->db->trans_start();
    }


    /**
     * @name trans_complete 事务完成  CI智能的事务系统
     */
    function trans_complete(){
        $this->db->trans_complete();
    }

    /**
     * @name transStatus 获得事务状态
     */
    function trans_status(){
        return $this->db->trans_status();
    }


    /**
     * @name trans_begin 手动开启事务
     */
    function trans_begin(){
        $this->db->trans_begin();
    }

    /**
     * @name trans_commit 手动提交事务
     */
    function trans_commit(){
        $this->db->trans_commit();
    }

    /**
     * @name trans_rollback 手动回滚事务
     */
    function trans_rollback(){
        $this->db->trans_rollback();
    }


    function __destruct()
    {
        if($this->db)
        {
            unset($this->db);
        }
    }
}
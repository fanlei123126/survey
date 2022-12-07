<?php
/**
 * 系统日志
*/
class Cls_log
{
    public $operateType = 0;
    public $operateMenu = 0;
    private $systemId = 1;
    private $CI;


    function _construct(){
        $this->CI = & get_instance();
        $this->load->model(array('system_log_model'));
    }

    /**
     * @name 增加账户的消费日志
     * @param int $accountId 账号ID
     * @param int $lastPayment 支付前金额
     * @param int $nowPayment 支付后金额
     * @param int $payTime 支付时间
     * @param int $price 支付的金额
     * @param int $payType 支付状态
     * @param int $bespeakId 预约ID
     * @param int $deivceId 设备ID
     * @return boolean
    */
    function addAccountLog($accountId=0, $lastPayment=0, $nowPayment=0, $payTime=0, $price=0, $status=0, $payType=0, $bespeakId=0, $deivceId=0){
        $logData = array(
            'account_id' => $accountId,
            'last_payment',
            'now_payment',
            'payment_time',
            'price',
            'status',
            'payment_type',
            'bespeak_id',
            'device_id'
        );
        $this->CI->load->model('account_log_model');
    }

    /**
     * @name 增加系统日志
     * @param int $accountId
     * @param int $operateId
     * @param int $operateType
     * @param string $siud
     * @param string $title
     * @param string $remark
    */
    function addSystemLog($accountId=0, $operateId=0, $operateType=0, $siud='', $title="", $remark=""){

    }

    function _destruct(){}
}
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name 会员管理
 */
class CostPaymentOrder extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->data['page_url'] = BASE_SITE_URL."/payment_order/CostPaymentOrder";
        $this->data['ajax_url'] = BASE_SITE_URL."/ajaxApi";
        $this->load->model(
            array(
                'account_list_model',
                'system_list_model',
                'system_role_model'
            )
        );
    }

    /**
     * @name 用户管理页面
     * @param string $pageType
     * @param int $id
     */
    function index(){
        display('payment_order/cost_payment_order_list', $this->data);
    }

    //添加预算单界面
    function  add(){
        display('payment_order/cost_payment_order_add', $this->data);
    }
    //添加物料
    function  update(){
        display('payment_order/cost_payment_order_update', $this->data);
    }
}
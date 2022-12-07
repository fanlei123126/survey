<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name 会员管理
 */
class CashierProject extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->data['page_url'] = BASE_SITE_URL."/cashier_management/cashierProject";
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
        display('cashier_management/project_payment_order_list', $this->data);
    }

    //添加预算单界面
    function audit(){
        display('cashier_management/project_payment_order_audit', $this->data);
    }
    //添加物料
    function material_add(){
        display('cashier_management/material_add', $this->data);
    }

    //添加物料
    function invoice_add(){
        display('cashier_management/invoice_add', $this->data);
    }
    //添加物料
    function cost_add(){
        display('cashier_management/cost_add', $this->data);
    }

}
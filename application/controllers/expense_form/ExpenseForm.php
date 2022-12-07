<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name 会员管理
 */
class ExpenseForm extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->data['page_url'] = BASE_SITE_URL."/expense_form/expenseForm";
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
        display('expense_form/expense_list', $this->data);
    }

    //添加预算单界面
    function add(){
        display('expense_form/expense_list_add', $this->data);
    }
    //添加物料
    function material_add(){
        display('expense_form/material_add', $this->data);
    }

}
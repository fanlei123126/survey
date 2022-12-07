<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name 会员管理
 */
class LogisticsDispatch extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->data['page_url'] = BASE_SITE_URL."/logistics_dispatch/logisticsDispatch";
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
        display('logistics_dispatch/logistics_dispatch_list', $this->data);
    }

    //添加预算单界面
    function  add(){
        display('logistics_dispatch/logistics_dispatch_add', $this->data);
    }

    //添加物料
    function  update(){
        display('logistics_dispatch/logistics_dispatch_update', $this->data);
    }
}
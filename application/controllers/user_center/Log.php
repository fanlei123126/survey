<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name 日志管理
 */
class Log extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->data['page_url'] = BASE_SITE_URL."/user_center/log/";
        $this->load->model(
            array(
                'system_list_model',
                'device_type_list_model',
                'version_list_model',
                'version_type_list_model',
                'terminal_list_model',
                'site_list_model'
            )
        );
    }

    /**
     * @name 日志管理页面
     */
    function index(){
        display($this->tpnamePath.'log_list', $this->data);
    }

    /**
     * @name 获取日志信息集合
     */
    function get_log_list(){}

}
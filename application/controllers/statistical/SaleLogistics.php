<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name 物流与销售
 */
class SaleLogistics extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->data['page_url'] = BASE_SITE_URL."/statistical/sale_logistics/";
        $this->data['ajax_url'] = BASE_SITE_URL."/ajaxApi";
        $this->load->model(
            array(
                'account_list_model',
            )
        );
    }

    /**
     * @name 用户管理页面
     * @param string $pageType
     * @param int $id
     */
    function index(){
        display('statistical/sale_logistics', $this->data);
    }

}
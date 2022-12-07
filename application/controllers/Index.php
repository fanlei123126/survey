<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name 默认首页
*/
class Index extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->data['ajax_url'] = BASE_SITE_URL."/ajaxApi/";
        $this->load->model(
            array(
                'account_list_model',
                'account_detail_model',
                'organization_model',
                'device_list_model',
                'research_group_list_model'
            )
        );

    }

    /**
     * @name 默认登录页面
    */
    function index(){

//        if($this->user_id>0) {
//            $this->load->model('system_menu_model');
//            $this->data['menuList'] = $this->system_menu_model->getMenuListByRoleId($this->role_id);
//            display('index', $this->data);
//        } else{
//            $this->data['url'] = verifyData($_GET,"url","");
//            $this->login();
//        }
        $this->load->view('home/index.html');
    }

    /**
     * @name 登录页面
    */
    function login(){
        if($this->user_id>0)
        {
            $this->index();
        }else{
           $this->load->view('home/login.html');
        }
    }

    function register(){
        $this->load->view('home/register.html');
    }

    function policy(){
        $this->load->view('home/policy.html');
    }

    function terms(){
        $this->load->view('home/terms.html');
    }

    function resetPwd(){
        $this->load->view('home/resetPwd.html');
    }

    /**
     * @name 默认主界面
    */
    function main(){
        if($this->user_id>0) {
            display('main', $this->data);
        }else{
            $this->login();
        }
    }



}

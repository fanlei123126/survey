<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 后台管理的 基础controller
 * 需要配置参数
 * 	$login_skip_url_list 无需登录验证的url列表
 * 	$session_arr 从session中获取值的类变量
 */
class My_Controller extends CI_Controller
{
   // public $login_ses_key = "scientific";
    public $tpnamePath = 'user_center/';
    public $cache_timeout = 86400;
    //子类可配置项
    //无需登录验证的url
    protected $login_skip_url_list = array('login');

    public $user_id = 0;//用户ID
    public $user_name = "";//账号名称
    public $role_id = 0;//角色ID
    public $role_name = "";//角色名称

    //public $login_status = 0;

    public $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array(
                'common_helper'
            )
        );
        $this->load->model(
            array('account_list_model')
        );
        $this->load->library('session');

       $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file') );
       // $this->load->helper('common');
//
        //$this->load->driver('cache');
       //  $this->load->driver('redis');

        $this->data['ajax_url'] = BASE_SITE_URL."/ajaxApi";

        date_default_timezone_set('Asia/Shanghai');
        ini_set('date.timezone','Asia/Shanghai');
        $this->load->library('session');
        $this->init_session();
    }


    /**
     * @name ajax_check_login Ajax数据调用接口时验证登录状态
     */
    function ajax_check_login(){
        if(!$this->user_id){
            $data = array('code' => -99,'msg' => '登录超时');
            output_str(json_encode($data));
        }
    }

    /**
     * @name check_login 验证登录状态
     */
    function check_login()
    {
        if(!$this->user_id){
            display('login', $this->data);
        }
    }

    /**
     * @name init_session 初始化获取用户登录信息
     */
    function init_session()
    {
        $token = "123";//getMyCookie("token")??

        if($token){
            $response_result =  $this->cache->get($token);
           // var_dump($response_result);exit;
            if($response_result)
            {
                $userinfo = json_decode($response_result,true);

//                $this->load->model(
//                    array(
//                        'system_role_model'
//                    )
//                );
//                    $role_info = $this->system_role_model->getRoleInfoById($userinfo['role_id']);

                $this->user_id = isset($userinfo['account_id'])?$userinfo['account_id']:0;
                $this->user_name = isset($userinfo['account_name'])?$userinfo['account_name']:"";
                $this->role_id = isset($userinfo['role_id'])?$userinfo['role_id']:0;

                } else
                {
                    delMyCookie("token");
                    header("redirectUrl: index/login");
                    header("enableRedirect:true");
                    header("login_status:0");//登陆过期
                }
            }else{
                delMyCookie("token");
            }

    }

    function showError($heading="", $message=""){
        if(!$heading){
            $heading = "数据获取失败";
        }
        if(!$message){
            $message = "尊敬的用户，数据获取失败，请联系运营管理员";
        }
        $this->data['heading'] = $heading;
        $this->data['message'] = $message;
        $this->load->view('errors/html/error_404', $this->data);
    }



    function __destruct()
    {

    }
}
?>
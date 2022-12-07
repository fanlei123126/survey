<?php

/**
 * 基础的controller 给其他 My_controller继承的
 * 需要配置参数
 * 	$login_skip_url_list 无需登录验证的url列表
 * 	$session_arr 从session中获取值的类变量
 */
class Common_Controller extends CI_Controller
{
    public $user_info = array();

    //子类可配置项
    //无需登录验证的url
    protected $login_skip_url_list = array();
    //session保存内容
    protected $session_arr = array(
    );

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('common');

        date_default_timezone_set('Asia/Shanghai');
        ini_set('date.timezone','Asia/Shanghai');
        $this->load->library('session');
        $this->init_session();
    }


    /**
     * @name init_session 初始化获取用户登录信息
     */
    function init_session()
    {

        $userinfo = $this->session->userdata($this->login_ses_key);

        if($userinfo)
        {
            //已登录
            foreach($this->session_arr as $k=>$v){
                $this->$k = verify_data($userinfo, $v);
            }
            $this->user_info = $userinfo;
        }else{
            $is_skip = false;
            $request_url = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:"";
            $query_string = isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:"";
            if($query_string && strpos($request_url, '?'.$query_string) !==false){
                //去除?及后面的参数
                $request_url = substr($request_url, 1, strpos($request_url, $query_string)-2);
            }

            //是否需要跳过登录验证
            $action_list = explode('/', $request_url);
            foreach($this->login_skip_url_list as $v){
                if(in_array($v, $action_list)){
                    $is_skip = true;
                    break;
                }
            }
            if($is_skip){
                return;
            }
            //登录验证失败以后
            //请求 带 index 或者根目录请求 定义为 跳页面，其他均为接口
            $request_host = isset($_SERVER['HTTP_HOST'])?HTTP_TYPE.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']:"";

            if(in_array('admin.php', $action_list) || in_array('admin.php?', $action_list)
                || $request_host == BASE_URL
                || $request_host == BASE_URL.'/'){
                $this->check_login();
            }else{
                $this->ajax_check_login();
            }
        }
    }

    /**
     * @name ajax_check_login 验证Ajax的登录状态
     */
    protected function ajax_check_login()
    {
    }

    /**
     * @name check_login 验证登录状态
     */
    protected function check_login()
    {
    }


    function __destruct()
    {

    }
}
?>
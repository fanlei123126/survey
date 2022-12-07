<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name 会员管理
 */
class Member extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->data['page_url'] = BASE_SITE_URL."/user_center/member/";
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
    function index($pageType="list", $id=0){
        switch($pageType) {
            case 'list':
                display('user_center/user_list', $this->data);
                break;
            case 'add':
             //   $this->data['systemList'] = $this->system_list_model->getAllSystemList();
                display('user_center/user_list_add', $this->data);
                break;
            case 'update':
                if(!$id){
                    $heading = "数据获取失败-1";
                    $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                    showError($heading, $message);
                }else {
                    $accountInfo = $this->account_list_model->getAccountById($id);
                    if (!$accountInfo) {
                        $heading = "数据获取失败-2";
                        $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                        showError($heading, $message);
                    } else {
                        $this->data['systemList'] = $this->system_list_model->getAllSystemList();
                        $this->data['data'] = $accountInfo;
                        display('user_center/user_list_update', $this->data);
                    }
                }
                break;
            case 'system_rule':
                if(!$id){
                    $heading = "数据获取失败-1";
                    $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                    showError($heading, $message);
                }else {
                    $accountInfo = $this->account_list_model->getAccountById($id);
                    if (!$accountInfo) {
                        $heading = "数据获取失败-2";
                        $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                        showError($heading, $message);
                    } else {
                        $this->data['systemList'] = $this->system_list_model->getAllSystemList();
                        $this->data['accountInfo'] = $accountInfo;
                        display('user_center/user_system_rule', $this->data);
                    }
                }

                break;
        }
    }

}
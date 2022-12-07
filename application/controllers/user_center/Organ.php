<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name 单位管理
 */
class Organ extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->data['page_url'] = BASE_SITE_URL."/user_center/organ/";
        $this->load->model(
            array(
                'system_list_model',
                'device_type_list_model',
                'version_list_model',
                'version_type_list_model',
                'terminal_list_model',
                'site_list_model',
                'city_list_model',
                'organization_model',
                'organ_type_model',
                'account_list_model'
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
                display($this->tpnamePath.'organ_list', $this->data);
                break;
            case 'add':
                $this->data['cityList'] = $this->city_list_model->getAllCityList();
                $this->data['organTypeList'] = $this->organ_type_model->getOrganTypeList();
                display($this->tpnamePath.'organ_list_add', $this->data);
                break;
            case 'update':
                if(!$id){
                    $heading = "数据获取失败-1";
                    $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                    $this->showError($heading, $message);
                }else {
                    $organInfo = $this->organization_model->getOrganInfoById($id);
                    if (!$organInfo) {
                        $heading = "数据获取失败-2";
                        $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                        $this->showError($heading, $message);
                    } else {
                        //var_dump($organInfo);die;
                        $this->data['adminUser'] = $this->account_list_model->getAccountById($organInfo['admin_account_id']);
                        $this->data['cityList'] = $this->city_list_model->getAllCityList();
                        $this->data['organTypeList'] = $this->organ_type_model->getOrganTypeList();
                        $this->data['data'] = $organInfo;
                        display($this->tpnamePath.'organ_list_update', $this->data);
                    }
                }
                break;
            case 'edit_admin':
                if(!$id){
                    $heading = "数据获取失败-1";
                    $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                    $this->showError($heading, $message);
                }else {
                    $organInfo = $this->organization_model->getOrganInfoById($id);
                    if (!$organInfo) {
                        $heading = "数据获取失败-2";
                        $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                        $this->showError($heading, $message);
                    } else {
                        $this->data['data'] = $organInfo;
                        $this->data['organId'] = $id;
                        display($this->tpnamePath.'organ_edit_admin', $this->data);
                    }
                }
                break;
        }
    }

}
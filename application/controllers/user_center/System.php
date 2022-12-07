<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name 系统配置
 */
class System extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->data['page_url'] = BASE_SITE_URL."/user_center/system";
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

    //******************** 页面加载 *********************//
    /**
     * @name 系统类别
    */
    function system_type(){
        display($this->tpnamePath.'system/system_type', $this->data);
    }

    /**
     * @name 设备类别
     * @param string $pageType
     * @param int $id
    */
    function device_type($pageType="list", $id=0){
        switch($pageType) {
            case 'list':
                if($id>0) {
                    $this->data['deviceTypeInfo'] = $this->device_type_list_model->getDeviceTypeById($id);
                }
                $this->data['upid'] = $id;
                display($this->tpnamePath.'system/device_type_list', $this->data);
                break;
            case 'add':
                $this->data['upid'] = $id;
                display($this->tpnamePath.'system/device_type_add', $this->data);
                break;
            case 'update':
                if(!$id){
                    $heading = "数据获取失败-1";
                    $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                    $this->showError($heading, $message);
                }else {
                    $this->load->model(array('device_type_list_model'));
                    $deviceTypeInfo = $this->device_type_list_model->getDeviceTypeById($id);
                    if(!$deviceTypeInfo){
                        $heading = "数据获取失败-2";
                        $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                        $this->showError($heading, $message);
                    }else {
                        $this->data['data'] = $deviceTypeInfo;
                        display($this->tpnamePath . 'system/device_type_update', $this->data);
                    }
                }
                break;
        }
    }

    /**
     * @name 版本管理
     * @param string $pageType
     * @param int $id
    */
    function version_list($pageType="list", $id=0){
        $this->load->model(array('version_list_model', 'version_type_list_model'));
        switch($pageType) {
            case 'list':
                $this->data['versionTypeList'] = $this->version_type_list_model->getAllVersionTypeList();
                display($this->tpnamePath.'system/version_list', $this->data);
                break;
            case 'add':
                $this->data['versionTypeList'] = $this->version_type_list_model->getAllVersionTypeList();
                display($this->tpnamePath.'system/version_list_add', $this->data);
                break;
            case 'update':
                if(!$id){
                    $heading = "数据获取失败-1";
                    $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                    $this->showError($heading, $message);
                }else {
                    $versionInfo = $this->version_list_model->getVersionById($id);
                    if (!$versionInfo) {
                        $heading = "数据获取失败-2";
                        $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                        $this->showError($heading, $message);
                    } else {
                        $this->data['versionTypeList'] = $this->version_type_list_model->getAllVersionTypeList();
                        $this->data['data'] = $versionInfo;
                        display($this->tpnamePath . 'system/version_list_update', $this->data);
                    }
                }
                break;
        }
    }

    /**
     * @name 版本类别管理
     * @param string $pageType
     * @param int $id
     */
    function version_type_list($pageType="list", $id=0){
        switch($pageType) {
            case 'list':
                $this->load->model(array('version_type_list_model'));
                $this->data['versionTypeList'] = $this->version_type_list_model->getAllVersionTypeList();
                display($this->tpnamePath.'system/version_type_list', $this->data);
                break;
        }
    }

    /**
     * @name 终端管理
     * @param string $pageType
     * @param int $id
    */
    function terminal_list($pageType="list", $id=0){
        $this->load->model(array('terminal_type_list_model','device_list_model', 'version_list_model', 'terminal_control_device_model','organization_model','organ_build_list_model','organ_laboratory_model'));
        switch($pageType) {
            case 'list':
                display($this->tpnamePath.'system/terminal_list', $this->data);
                break;
            case 'add':
                $this->data['terminalTypeList'] = $this->terminal_type_list_model->getTerminalList();
                display($this->tpnamePath.'system/terminal_list_add', $this->data);
                break;
            case 'update':
                if(!$id){
                    $heading = "数据获取失败-1";
                    $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                    $this->showError($heading, $message);
                }else {
                    $this->data['data'] = $terminalInfo = $this->terminal_list_model->getTerminalById($id);
                    if (!$terminalInfo) {
                        $heading = "数据获取失败-2";
                        $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                        $this->showError($heading, $message);
                    } else {
                        $this->data['terminalTypeList'] = $this->terminal_type_list_model->getTerminalList();
                        $this->data['deviceId'] = $this->terminal_control_device_model->getControlById($id);
                        $this->data['organInfo'] = $this->organization_model->getOrganInfoById($terminalInfo['organ_id']);
                        $this->data['organBuildList'] = array();
                        $organBuildList = $this->organ_build_list_model->getOrganBuildListByOrganId($terminalInfo['organ_id']);
                        foreach ($organBuildList as $i){
                            if($i['status']==1) {
                                $this->data['organBuildList'][] = $i;
                            }
                        }
                        $this->data['organLaboratoryList'] = $this->organ_laboratory_model->getOrganLabList($terminalInfo['organ_build_id']);

                        display($this->tpnamePath . 'system/terminal_list_update', $this->data);
                    }
                }
                break;
        }
    }

    /**
     * @name 站点配置
     */
    function site_info(){
        $this->load->model('site_list_model');
        $systemId = 1;
        $this->data['siteInfo'] = $this->site_list_model->getSiteInfoBySId($systemId);
        $this->data['Id'] = $systemId;
        display($this->tpnamePath.'system/site_info', $this->data);
    }


}
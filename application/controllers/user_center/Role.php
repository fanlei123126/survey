<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name 角色权限
 */
class Role extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->data['page_url'] = BASE_SITE_URL."/user_center/role/";
        $this->data['ajax_url'] = BASE_SITE_URL."/ajaxApi";
        $this->load->model(
            array(
                'system_list_model',
                'device_type_list_model',
                'version_list_model',
                'version_type_list_model',
                'terminal_list_model',
                'site_list_model',
                'account_list_model',
                'system_role_auth_model',
                'system_menu_model',
                'system_role_model'
            )
        );
    }

    /**
     * @name 角色管理页面
     * @param string $pageType
     * @param int $id
     */
    function index($pageType="list", $id=0){
        switch($pageType) {
            case 'list':
                display($this->tpnamePath.'role_list', $this->data);
                break;
            case 'add':
                display($this->tpnamePath.'role_list_add', $this->data);
                break;
            case 'update':
                if(!$id){
                    $heading = "数据获取失败-1";
                    $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                    showError($heading, $message);
                }else {
                    $this->load->model(array('system_role_model'));
                    $roleInfo = $this->system_role_model->getRoleInfoById($id);
                    if(!$roleInfo){
                        $heading = "数据获取失败-2";
                        $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                        showError($heading, $message);
                    }else {
                        $this->data['data'] = $roleInfo;
                        display($this->tpnamePath.'role_list_update', $this->data);
                    }
                }

                break;
            case 'auth_edit':
                if(!$id){
                    $heading = "数据获取失败-1";
                    $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                    showError($heading, $message);
                }else {
                    $this->load->model(array('system_role_model'));
                    $roleInfo = $this->system_role_model->getRoleInfoById($id);
                    if (!$roleInfo) {
                        $heading = "数据获取失败-2";
                        $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                        showError($heading, $message);
                    } else {
                        $menu = array();
                        $menu_tree = $this->system_menu_model->getMenuListByRoleId(MEMBER_SYSTEM_ID, $this->role_id);
                        foreach ($menu_tree as $v) {
                            $arr = array('text' => $v['name'], 'id' => $v['id']);
                            $temp = $this->system_role_auth_model->getAuthByRoleId(MEMBER_SYSTEM_ID, $id, $v['id']);
                            if ($temp) {
                                $arr['state'] = array('checked' => true);
                            }
                            if (isset($v['children'])) {
                                $nodes = array();
                                foreach ($v['children'] as $v2) {
                                    $node_arr = array('text' => $v2['name'], 'id' => $v2['id']);
                                    $temp2 = $this->system_role_auth_model->getAuthByRoleId(MEMBER_SYSTEM_ID, $id, $v['id']);
                                    if ($temp2) {
                                        $node_arr['state'] = array('checked' => true);
                                    }
                                    $nodes[] = $node_arr;
                                }
                                $arr['nodes'] = $nodes;
                            }
                            $menu[] = $arr;
                        }
                        //echo "<pre>";
                        //var_dump($menu);die;
                        $this->data['node_data'] = $menu;
                        $this->data['role_id'] = $id;
                        display($this->tpnamePath . 'role_auth', $this->data);
                    }
                }
                break;
            case 'user_list':
                if(!$id){
                    $heading = "数据获取失败-1";
                    $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                    showError($heading, $message);
                }else {
                    $this->load->model(array('system_role_model'));
                    $roleInfo = $this->system_role_model->getRoleInfoById($id);
                    if(!$roleInfo){
                        $heading = "数据获取失败-2";
                        $message = "尊敬的用户，数据获取失败，请联系运营管理员";
                        showError($heading, $message);
                    }else {
                        $this->data['data'] = $roleInfo;
                        $this->data['role_id'] = $id;
                        display($this->tpnamePath.'role_user_list', $this->data);
                    }
                }

                break;
        }
    }


}
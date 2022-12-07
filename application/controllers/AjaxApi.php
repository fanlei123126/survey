<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name 系统配置
 */
class AjaxApi extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->data['page_url'] = BASE_SITE_URL."/user_center/system/";
        $this->load->model(
            array(
                'account_list_model',
                'system_role_model',
            )
        );
    }

    // ********************** 个人账号-数据-管理 ***********************//
    /**
     * @name login 登录页面
     */
    function loginOn()
    {
        $data = array('sta' => 0, 'msg' => '登录失败');
        $this->load->library('session');

        $username = paramsIsNull($_POST, 'account')?"":$_POST['account'];
        $password = paramsIsNull($_POST, 'passwd')?"":$_POST['passwd'];


        if(!$username){
            $data['msg'] = '请输入系统账号';
            output_str(json_encode($data));
        }
        if(!$password){
            $data['msg'] = '请输入系统密码';
            output_str(json_encode($data));
        }
        //echo SSO_BASE_SITE_URL."createToken";exit;
        $response_result = $this->account_list_model->getAccountByName($username);
        // var_dump($response_result);exit;
        if($response_result)
        {
            //生成token
            $token = '123';//md5(TOKEN_SIGNED.$response_result['account_id'].time());
          //  echo $token;exit;
            if($response_result['account_pwd'] !=  $password){
                $data['msg'] = '密码错误';
                output_str(json_encode($data));
            }
            if ($response_result['status'] != 1) {
                $data['msg'] = '尊敬的用户，您的账号已经被进入黑名单，请联系系统管理员';
                output_str(json_encode($data));
            }

            setMyCookie("token", $token, 3600);

            $this->cache->save($token,json_encode($response_result),$this->cache_timeout);//保存userId
           // $this->cache->save(TOKEN_SIGNED.$response_result['account_id'],tim,$this->cache_timeout);//保存userId
            $response_result =  $this->cache->get($token);
           // var_dump($response_result);exit;
            $data = array('sta' => 1, 'msg' => '登录成功');
            output_str(json_encode($data));

        }else{
            $data['msg'] = '用户名错误';
            output_str(json_encode($data));
        }


    }

    /**
     * @name changePwd 修改密码
     */
    public function changePwd()
    {
        $data = array('code' => 0,'msg' => '修改密码失败');

        $old_pwd = paramsIsNull($_POST, 'old_pwd')?"":$_POST['old_pwd'];
        $new_pwd = paramsIsNull($_POST, 'new_pwd')?"":$_POST['new_pwd'];
        $confirm_new_pwd = paramsIsNull($_POST, 'confirm_new_pwd')?"":$_POST['confirm_new_pwd'];

        if(!$old_pwd || !$new_pwd || !$confirm_new_pwd){//查看参数是否完成
            $data['msg'] = '信息不完整';
            output_str(json_encode($data));
        }
        if($new_pwd != $confirm_new_pwd){//查看参数是否完成
            $data['msg'] = '新密码与确认密码不一致';
            output_str(json_encode($data));
        }
        $user_info = $this->account_list_model->getAccountById($this->user_id);
        if(!$user_info || $user_info['password'] != md5($_POST['old_pwd'])){
            $data['msg'] = '密码错误';
            output_str(json_encode($data));
        }

        $token = get_random_code(6, 6);

        $arr = array('account_passwd' => md5(md5($new_pwd).$token), 'token'=>$token);
        if($this->account_list_model->update($this->user_id, $arr)){
            $data['code'] = 1;
            $data['msg'] = '修改密码成功';
            $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '修改密码', '修改密码', 1, 8, 1);
            $this->load->library('session');
            $this->session->unset_userdata($this->login_ses_key);//用户信息添加进session
            $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '修改密码', '用户管理系统-密码修改', 1, 8, 1);
        }else{
            $data['msg'] = '修改密码失败！';
        }

        output_str(json_encode($data));
    }

    /**
     * @name 登出操作
     */
    function loginOut(){
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '注销', '注销退出用户管理系统', 1, 8, 1);
        $response_result = do_request(["method"=>"post","url"=>SSO_BASE_SITE_URL."loginOut"
            ,"data"=>["token"=>getMyCookie("token")],"header"=>["Content-Type: application/json"]]);
        delMyCookie("token");
        header("location:".BASE_URL);
        exit;

    }

    //*********************** 用户中心-数据-获取 ***********************//
    /**
     * @name 分页获取系统类别集合
     */
    function get_system_type(){
        $this->load->model(array('system_list_model'));
        $limit = paramsIsNull($_POST, 'limit')?10:$_POST['limit'];
        $offset = paramsIsNull($_POST, 'offset')?0:$_POST['offset'];

        $total = $this->system_list_model->getSystemTotal();
        $rows = $this->system_list_model->getSystemList($offset, $limit);

        $res['total'] = $total;
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }

    /**
     * @name 分页获取系统类别集合
     */
    function get_system_log(){
        $this->load->model(array('system_log_model'));
        $limit = paramsIsNull($_POST, 'limit')?10:$_POST['limit'];
        $offset = paramsIsNull($_POST, 'offset')?0:$_POST['offset'];
        $username = paramsIsNull($_POST, 'username')?'':$_POST['username'];
        $mobile = paramsIsNull($_POST, 'mobile')?'':$_POST['mobile'];

        $condition = array(
            'username' => $username,
            'mobile' => $mobile
        );

        $total = $this->system_log_model->getLogTotal($condition);
        $rows = $this->system_log_model->getLogListForPage($offset, $limit, $condition);

        $res['total'] = $total;
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }

    /**
     * @name 分页获取设备类别集合
     */
    function get_device_type($upid=0){
        $this->load->model(array('device_type_list_model'));
        $limit = paramsIsNull($_POST, 'limit')?10:$_POST['limit'];
        $offset = paramsIsNull($_POST, 'offset')?0:$_POST['offset'];

        $total = $this->device_type_list_model->getDeviceTypeTotal($upid);
        $rows = $this->device_type_list_model->getDeviceTypeList($offset, $limit, $upid);

        $res['total'] = $total;
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }

    /**
     * @name 分页获取版本类别信息集合
     */
    function get_version_type_list(){
        $this->load->model(array('version_type_list_model'));
        $limit = paramsIsNull($_POST, 'limit')?10:$_POST['limit'];
        $offset = paramsIsNull($_POST, 'offset')?0:$_POST['offset'];

        $total = $this->version_type_list_model->getDeviceTypeTotal();
        $rows = $this->version_type_list_model->getDeviceTypeList($offset, $limit);

        $res['total'] = $total;
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }

    /**
     * @name 分页获取版本信息集合
     */
    function get_version_list(){
        $this->load->model(array('version_list_model'));
        $limit = paramsIsNull($_POST, 'limit')?10:$_POST['limit'];
        $offset = paramsIsNull($_POST, 'offset')?0:$_POST['offset'];

        $condition = array();

        $total = $this->version_list_model->getVersionTotal($condition);
        $rows = $this->version_list_model->getVersionList($condition, $offset, $limit);

        $res['total'] = $total;
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }

    /**
     * @name 分页获取账号信息集合
     */
    function get_account_list(){
        $this->load->model(array('account_list_model'));
        $limit = paramsIsNull($_POST, 'limit')?10:$_POST['limit'];
        $offset = paramsIsNull($_POST, 'offset')?0:$_POST['offset'];
        $name = paramsIsNull($_POST, 'name')?0:$_POST['name'];
        $realname = paramsIsNull($_POST, 'realname')?0:$_POST['realname'];
        $systemId = paramsIsNull($_POST, 'systemId')?0:$_POST['systemId'];

        $condition =array();
        if($name){
            $condition['name'] = $name;
        }
        if($realname){
            $condition['r_name'] = $realname;
        }
//        if($systemId){
//            $condition['system_list'] = $systemId;
//        }

        $total = $this->account_list_model->getAccountTotal($condition);
        $rows = $this->account_list_model->getAccountListForPage($condition, $offset, $limit);
        foreach ($rows as &$item){
          $row =  $this->system_role_model->getRoleInfoById($item['role_id'],1);
          $item['role_name'] = $row['system_role_name']??'';
        }
        $res['total'] = $total;
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }


    /**
     * @name 分页获取账号信息集合
     */
    function get_supplier_list(){
        //$this->load->model(array('supplier_list_model'));
        $limit = paramsIsNull($_POST, 'limit')?10:$_POST['limit'];
        $offset = paramsIsNull($_POST, 'offset')?0:$_POST['offset'];
        $name = paramsIsNull($_POST, 'name')?0:$_POST['name'];
       // $realname = paramsIsNull($_POST, 'realname')?0:$_POST['realname'];
       // $systemId = paramsIsNull($_POST, 'systemId')?0:$_POST['systemId'];

//        $condition =array();
//        if($name){
//            $condition['name'] = $name;
//        }
//        if($realname){
//            $condition['r_name'] = $realname;
//        }
//        if($systemId){
//            $condition['system_list'] = $systemId;
//        }

//        $total = $this->account_list_model->getAccountTotal($condition);
//        $rows = $this->account_list_model->getAccountListForPage($condition, $offset, $limit);
//        foreach ($rows as &$item){
//            $row =  $this->system_role_model->getRoleInfoById($item['role_id'],1);
//            $item['role_name'] = $row['system_role_name']??'';
//        }
        $res['total'] = 1;
        $rows =[["Id"=>1,"supplier_name"=>"张三","mobile"=>"11111111",
            "payment_order"=>"2398aaa","boss_name"=>"里斯","sales_name"=>"王武","remark"=>"老客户","createTime"=>"2022-03-23 23:23:20"]];
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }

    //月度利润
    function get_monthly_profit(){
        $res['total'] = 1;
        $rows =[["Id"=>1,"supplier_name"=>"张三","product_code"=>"11111111","total_price"=>'123122',
            "issued_quantity"=>"2398aaa","signed_by"=>"里斯","coast_price"=>"100000","remark"=>"老客户","createTime"=>"2022-03-23 23:23:20"]];
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }

    //销售物流
    function get_sale_logistics() {
        $res['total'] = 1;
        $rows =[["Id"=>1,"supplier_name"=>"张三","product_code"=>"11111111","total_price"=>'123122',
            "issued_quantity"=>"2398aaa","signed_by"=>"里斯","logistics_name"=>"圆通快递","logistics_price"=>'12323',
            "monthly_logistics_total_price"=>"1232","sale_price"=>'1232',"sale_total_price"=>"2343","single_profit"=>"23423",
            "coast_price"=>"100000","remark"=>"老客户","createTime"=>"2022-03-23 23:23:20"]];
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }
    //费用
    function get_cost() {

        $res['total'] = 1;
        $rows =[["Id"=>1,"payment_expenses"=>"快递费","payment_expenses_code"=>"11111111","payee"=>'收款人张三',"remark"=>"老客户",
            "reimbursement_person"=>"100000","monthly_cost_total_price"=>"2394","createTime"=>"2022-03-23 23:23:20"]];
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }


    //获取付款单物料
    function expense_material_list(){
        $res['total'] = 1;
        $res['rows'] =[["Id"=>1,"material_name"=>"物料名1","supplier_name"=>"供应商1","material_number"=>'1',
            "material_unit_price"=>"1",
            "total_price"=>"1","included_tax"=>"含税","included_freight"=>"运费",
            "circle"=>"周期09天","remark"=>"备注"]];

        output_str(json_encode($res));
    }
    //付款项目单
    function get_project_payment_order_list(){
        $res['total'] = 1;
        $res['rows'] =[["Id"=>1,"project_code"=>"产品编号1","project_name"=>"产品名称1","project_status"=>"付款单中"]];

        output_str(json_encode($res));
    }

    //获取付款单物料
    function project_material_list(){
        $res['total'] = 1;
        $res['rows'] =[["Id"=>1,"material_name"=>"物料名1","supplier_name"=>"供应商1",
            "material_number"=>'1',
            "material_unit_price"=>"1",
            "send_material_number" =>"1",
            "total_price"=>"1","included_tax"=>"含税","included_freight"=>"运费","invoice"=>"开票",
            "circle"=>"周期09天","remark"=>"备注"]];

        output_str(json_encode($res));
    }

    /**
     * @name 分页获取账号信息集合
     * @param int $upid
     */
    function get_organ_list($upid=0){
        $this->load->model(array('organization_model'));
        $limit = paramsIsNull($_POST, 'limit')?10:$_POST['limit'];
        $offset = paramsIsNull($_POST, 'offset')?0:$_POST['offset'];

        $condition =array('up_id'=>$upid);

        $total = $this->organization_model->getOrganTotal($condition);
        $rows = $this->organization_model->getOrganListForPage($condition, $offset, $limit);

        $res['total'] = $total;
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }

    /**
     * @name 分页获取系统角色信息集合
    */
    function get_system_role(){
        $this->load->model(array('system_role_model'));
        $limit = paramsIsNull($_POST, 'limit')?10:$_POST['limit'];
        $offset = paramsIsNull($_POST, 'offset')?0:$_POST['offset'];

        $condition = array();
        $total = $this->system_role_model->getRoleTotal($condition);
        $rows = $this->system_role_model->getRoleListForPage($condition, $offset, $limit);

        $res['total'] = $total;
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }

    /**
     * @name 分页获取终端信息集合
    */
    function get_terminal_list(){
        $this->load->model(array('terminal_list_model'));
        $limit = paramsIsNull($_POST, 'limit')?10:$_POST['limit'];
        $offset = paramsIsNull($_POST, 'offset')?0:$_POST['offset'];

        $condition = array();
        $total = $this->terminal_list_model->getTerminalTotal($condition);
        $rows = $this->terminal_list_model->getTerminalListForPage($condition, $offset, $limit);

        $res['total'] = $total;
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }



    /**
     * @name 对账号进行密码重置
     * @param int $id
     * @return json_string
    */
    function reset_password(){
        $data = array('sta'=>0, 'msg'=>'');
        $id = verifyData($_POST, 'id', '0');

        if(!$id){
            $data['sta'] = -1;
            $data['msg'] = '查询的账号信息不在数据中心';
            output_str(json_encode($data));
        }

        $this->load->model(array('account_list_model'));

        $accountInfo = $this->account_list_model->getAccountById($id);
        if(!$accountInfo){
            $data['sta'] = -1;
            $data['msg'] = '编辑的账号信息不在数据中心';
            output_str(json_encode($data));
        }

        $resetPassword = 'SSY123456';
        $newPassword = md5(md5($resetPassword).$accountInfo['token']);

        if($this->account_list_model->update($id, array('account_passwd'=>$newPassword))){
            $data['sta'] = 1;
            $data['msg'] = '账号密码重置操作成功';
        }else{
            $data['msg'] = '账号密码重置操作失败';
        }
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '账号密码重置', '用户管理系统-'.$data['msg'], 1, 2, 3);
        output_str(json_encode($data));
    }

    /**
     * @name 删除账号信息
    */
    function remove_account(){
        $data = array('sta' => 0,'msg' => '账号信息删除操作失败');
        $id = verifyData($_POST, 'id', '0');

        if(!$id){
            $data['sta'] = -1;
            $data['msg'] = '查询的账号信息不在数据中心';
            output_str(json_encode($data));
        }

        $this->load->model(array('account_list_model'));

        $accountInfo = $this->account_list_model->getAccountById($id);
        if(!$accountInfo){
            $data['sta'] = -1;
            $data['msg'] = '编辑的账号信息不在数据中心';
            output_str(json_encode($data));
        }

        if($this->account_list_model->update($id, array('status'=>0))){
            $data['sta'] = 1;
            $data['msg'] = '账号信息删除操作成功';
        }
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '删除账号信息', '用户管理系统-'.$data['msg'], 1, 2, 4);
        output_str(json_encode($data));
    }

    /**
     * @name 创建单位信息
    */
    function create_organ(){
        $data = array('sta' => 0,'msg' => '单位信息新增操作失败');

        $code = verifyData($_POST, 'code', '');
        $name = verifyData($_POST, 'name', '');
        $organ_type = verifyData($_POST, 'organ_type', '0');
        $city_list = verifyData($_POST, 'city_list', '0');
        $address = verifyData($_POST, 'address', '');
        $contacts = verifyData($_POST, 'contacts', '');
        $email = verifyData($_POST, 'email', '');
        $phone = verifyData($_POST, 'phone', '');
        $service_start_time = verifyData($_POST, 'service_start_time', '');
        $service_end_time = verifyData($_POST, 'service_end_time', '');
        $internal = verifyData($_POST, 'internal', '');
        $site_name = verifyData($_POST, 'site_name', '');
        $remark = verifyData($_POST, 'remark', '');
        $upid = verifyData($_POST, 'upid', '0');
        $system_admin_id = verifyData($_POST, 'system_admin_id', 0);

        $this->load->library('cls_pinyin');
        $this->load->model(array('organization_model'));

        if(!$name){
            $data['sta'] = -1;
            $data['msg'] = '请输入单位名称';
            output_str(json_encode($data));
        }
        if(!$organ_type){
            $data['sta'] = -1;
            $data['msg'] = '请选择机构类别';
            output_str(json_encode($data));
        }
        if(!$city_list){
            $data['sta'] = -1;
            $data['msg'] = '请选择所属城市';
            output_str(json_encode($data));
        }
        if(!$address){
            $data['sta'] = -1;
            $data['msg'] = '请输入联系地址';
            output_str(json_encode($data));
        }
        if(!$email){
            $data['sta'] = -1;
            $data['msg'] = '请输入邮箱地址';
            output_str(json_encode($data));
        }
        if(!$phone){
            $data['sta'] = -1;
            $data['msg'] = '请输入联系电话';
            output_str(json_encode($data));
        }
        if(!$service_start_time){
            $data['sta'] = -1;
            $data['msg'] = '请选择服务起始时间';
            output_str(json_encode($data));
        }
        if(!$service_end_time){
            $data['sta'] = -1;
            $data['msg'] = '请选择服务截止时间';
            output_str(json_encode($data));
        }

        $checkOrganInfo = $this->organization_model->getOrganInfoByName($name, 1);
        if($checkOrganInfo){
            $data['sta'] = -1;
            $data['msg'] = '输入的医院名称系统中已存在，请核实后再增加';
            output_str(json_encode($data));
        }

        if(!$code){
            $code = $this->cls_pinyin->str2pys($name);
        }

        $data = array(
            'organ_code' => $code,
            'organ_name' => $name,
            'organ_type_id' => $organ_type,
            'city_id' => $city_list,
            'address' => $address,
            'contacts' => $contacts,
            'email' => $email,
            'mobile' => $phone,
            'service_start_date' => strtotime($service_start_time),
            'service_end_date' => strtotime($service_end_time),
            'internal_contacts' => $internal,
            'site_name' => $site_name,
            'remark' => $remark,
            'create_time'=>time(),
            'theme_id'=>1,
            'up_id'=>$upid,
            'is_admin'=>0,
            'status'=>1,
            'admin_account_id'=>$system_admin_id
        );

        if($this->organization_model->add($data)){
            $this->account_list_model->update($system_admin_id, array('system_role_id'=>3));
            $data['sta'] = 1;
            $data['msg'] = '单位信息新增操作成功';
        }else{
            $data['msg'] = '单位信息新增操作失败';
        }
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '新增机构信息', '用户管理系统-'.$data['msg'], 1, 3, 2);
        output_str(json_encode($data));
    }

    /**
     * @name 更新单位信息
    */
    function update_organ(){
        $data = array('sta' => 0,'msg' => '单位信息修改操作失败');

        $code = verifyData($_POST, 'code', '');
        $name = verifyData($_POST, 'name', '');
        $organ_type = verifyData($_POST, 'organ_type', '0');
        $city_list = verifyData($_POST, 'city_list', '0');
        $address = verifyData($_POST, 'address', '');
        $contacts = verifyData($_POST, 'contacts', '');
        $email = verifyData($_POST, 'email', '');
        $phone = verifyData($_POST, 'phone', '');
        $service_start_time = verifyData($_POST, 'service_start_time', '');
        $service_end_time = verifyData($_POST, 'service_end_time', '');
        $internal = verifyData($_POST, 'internal', '');
        $site_name = verifyData($_POST, 'site_name', '');
        $remark = verifyData($_POST, 'remark', '');
        $system_admin_id = verifyData($_POST, 'system_admin_id', '0');

        $id = verifyData($_POST, 'id', '0');


        $this->load->library('cls_pinyin');
        $this->load->model(array('organization_model'));

        if(!$id){
            $data['sta'] = -1;
            $data['msg'] = '接口数据传输出现错误';
            output_str(json_encode($data));
        }

        $organInfo = $this->organization_model->getOrganInfoById($id);
        if(!$organInfo){
            $data['sta'] = -1;
            $data['msg'] = '单位信息不在数据中心';
            output_str(json_encode($data));
        }
        $lastSystemAccountId = $organInfo['admin_account_id'];

        if(!$name){
            $data['sta'] = -1;
            $data['msg'] = '请输入单位名称';
            output_str(json_encode($data));
        }
        if(!$organ_type){
            $data['sta'] = -1;
            $data['msg'] = '请选择机构类别';
            output_str(json_encode($data));
        }
        if(!$city_list){
            $data['sta'] = -1;
            $data['msg'] = '请选择所属城市';
            output_str(json_encode($data));
        }
        if(!$address){
            $data['sta'] = -1;
            $data['msg'] = '请输入联系地址';
            output_str(json_encode($data));
        }
        if(!$email){
            $data['sta'] = -1;
            $data['msg'] = '请输入邮箱地址';
            output_str(json_encode($data));
        }
        if(!$phone){
            $data['sta'] = -1;
            $data['msg'] = '请输入联系电话';
            output_str(json_encode($data));
        }
        if(!$service_start_time){
            $data['sta'] = -1;
            $data['msg'] = '请选择服务起始时间';
            output_str(json_encode($data));
        }
        if(!$service_end_time){
            $data['sta'] = -1;
            $data['msg'] = '请选择服务截止时间';
            output_str(json_encode($data));
        }

        $checkOrganInfo = $this->organization_model->getOrganInfoByName($name, 1);
        if($checkOrganInfo){
            if($checkOrganInfo['organ_id']!=$id) {
                $data['sta'] = -1;
                $data['msg'] = '输入的医院名称系统中已存在，请核实后再编辑';
                output_str(json_encode($data));
            }
        }

        if(!$code){
            $code = $this->cls_pinyin->str2pys($name);
        }

        $data = array(
            'organ_code' => $code,
            'organ_name' => $name,
            'organ_type_id' => $organ_type,
            'city_id' => $city_list,
            'address' => $address,
            'contacts' => $contacts,
            'email' => $email,
            'mobile' => $phone,
            'service_start_date' => strtotime($service_start_time),
            'service_end_date' => strtotime($service_end_time),
            'internal_contacts' => $internal,
            'site_name' => $site_name,
            'remark' => $remark,
            'admin_account_id'=>$system_admin_id
        );

        if($this->organization_model->update($id, $data)){
            if($lastSystemAccountId!=$system_admin_id) {
                $this->account_list_model->update($lastSystemAccountId, array('system_role_id' => 5));
                $this->account_list_model->update($system_admin_id, array('system_role_id' => 3));
            }
            $data['sta'] = 1;
            $data['msg'] = '单位信息修改操作成功';
        }else{
            $data['msg'] = '单位信息修改操作失败';
        }
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '编辑机构信息', '用户管理系统-'.$data['msg'], 1, 3, 3);
        output_str(json_encode($data));
    }

    /**
     * @name 删除单位信息
    */
    function remove_organ(){
        $data = array('sta' => 0,'msg' => '单位信息删除操作失败');
        $id = verifyData($_POST, 'id', '0');

        if(!$id){
            $data['sta'] = -1;
            $data['msg'] = '查询的单位信息不在数据中心';
            output_str(json_encode($data));
        }

        $this->load->model(array('organization_model'));

        $accountInfo = $this->organization_model->getOrganInfoById($id);
        if(!$accountInfo){
            $data['sta'] = -1;
            $data['msg'] = '编辑的账号信息不在数据中心';
            output_str(json_encode($data));
        }

        if($this->organization_model->update($id, array('status'=>0))){
            $data['sta'] = 1;
            $data['msg'] = '账号信息删除操作成功';
        }
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '删除机构信息', '用户管理系统-'.$data['msg'], 1, 3, 4);
        output_str(json_encode($data));
    }

    /**
     * @name 创建角色信息
    */
    function create_role(){
        $data = array('sta' => 0,'msg' => '版本信息新增操作失败');
        $name = verifyData($_POST, 'name', '');
        $code = verifyData($_POST, 'code', '');

        $this->load->model(array('system_role_model'));
        $this->load->library(array('cls_pinyin'));

        if(!$name){
            $data['sta'] = -1;
            $data['msg'] = '请输入角色名称';
            output_str(json_encode($data));
        }

        if(!$code){
            $code = $this->cls_pinyin->str2pys($name);
        }

        $data = array(
            'system_role_code' => $code,
            'system_role_name' => $name,
            'system_id'=>1,
            'status' => 1,
            'is_admin' => 0,
            'organ_id' => 0,
            'can_edit' => 1
        );

        if($this->system_role_model->add($data)){
            $data['sta'] = 1;
            $data['msg'] = '设备信息新增操作成功';
        }else{
            $data['msg'] = '设备信息新增操作失败';
        }
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '创建系统角色', '用户管理系统-'.$data['msg'], 1, 4, 2);
        output_str(json_encode($data));
    }

    /**
     * @name 更新角色信息
    */
    function update_role(){
        $data = array('sta' => 0,'msg' => '角色信息编辑操作失败');
        $name = verifyData($_POST, 'name', '');
        $code = verifyData($_POST, 'code', '');
        $id = verifyData($_POST, 'id', '0');

        $this->load->model(array('system_role_model'));
        $this->load->library(array('cls_pinyin'));

        if(!$id){
            $data['sta'] = -1;
            $data['msg'] = '接口数据传输出现错误';
            output_str(json_encode($data));
        }

        $roleInfo = $this->system_role_model->getRoleInfoById($id);
        if(!$roleInfo){
            $data['sta'] = -1;
            $data['msg'] = '编辑的角色信息不在数据中心';
            output_str(json_encode($data));
        }

        if(!$name){
            $data['sta'] = -1;
            $data['msg'] = '请输入角色名称';
            output_str(json_encode($data));
        }
        if(!$code){
            $code = $this->cls_pinyin->str2pys($name);
        }

        $data = array(
            'system_role_code' => $code,
            'system_role_name' => $name
        );

        if($this->system_role_model->update($id, $data)){
            $data['sta'] = 1;
            $data['msg'] = '角色信息编辑操作成功';
        }
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '编辑系统角色', '用户管理系统-'.$data['msg'], 1, 4, 3);
        output_str(json_encode($data));
    }

    /**
     * @name 删除角色信息
    */
    function remove_role(){
        $data = array('sta' => 0,'msg' => '角色信息删除操作失败');
        $id = verifyData($_POST, 'id', '0');

        if(!$id){
            $data['sta'] = -1;
            $data['msg'] = '查询的角色信息不在数据中心';
            output_str(json_encode($data));
        }

        $this->load->model(array('system_role_model'));

        $roleInfo = $this->system_role_model->getRoleInfoById($id);
        if(!$roleInfo){
            $data['sta'] = -1;
            $data['msg'] = '编辑的角色信息不在数据中心';
            output_str(json_encode($data));
        }

        if($this->system_role_model->update($id, array('status'=>0))){
            $data['sta'] = 1;
            $data['msg'] = '角色信息删除操作成功';
        }else{
            $data['msg'] = '角色信息删除操作失败';
        }
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '删除系统角色', '用户管理系统-'.$data['msg'], 1, 4, 4);
        output_str(json_encode($data));
    }

    /**
     * @name 更新角色权限
    */
    function update_auth(){
        $data = array('sta'=>0, 'msg'=>'角色权限配置失败');

        $this->load->model('system_role_auth_model');
        $nodeArr = isset($_POST['node_arr'])?$_POST['node_arr']:array();
        $roleId = isset($_POST['role_id'])?$_POST['role_id']:0;
        if(!$roleId){
            $data['sta'] = -1;
            $data['msg'] = "请先选择一个角色再进行权限分配";
        }else {
            $roleInfo = $this->system_role_model->getRoleInfoById($roleId);
            if(!$roleInfo){
                $data['sta'] = -1;
                $data['msg'] = "选择的角色信息获取失败";
            }else {
                if (count($nodeArr)>0) {
                    $this->system_role_auth_model->removeRoleAuthByRoleId(MEMBER_SYSTEM_ID, $roleId);
                    foreach ($nodeArr as $node) {
                        $authData = array(
                            'system_id' => 1,
                            'system_role_id' => $roleId,
                            'system_menu_id' => $node
                        );
                        if ($this->system_role_auth_model->add($authData)) {
                            $data['sta'] = 1;
                            $data['msg'] = "角色权限配置成功";
                        } else {
                            $data['msg'] = "角色权限配置失败";
                        }
                    }
                } else {
                    $data['sta'] = -1;
                    $data['msg'] = "请选择角色的权限配置信息";
                }
            }
        }
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '配置角色权限', '用户管理系统-'.$data['msg'], 1, 4, 3);
        output_str(json_encode($data));
    }

    /**
     * @name 搜索查询机构信息集合
     * @param string $organ_name
     * @retur json
    */
    function suggest_organ($organ_name=""){
        $data = array('code'=>200, 'message'=>'', 'value'=>array());

        $this->load->model('organization_model');
        $data['value'] = $this->organization_model->searchOrganName($organ_name);

        output_str(json_encode($data));
    }

    /**
     * @name 搜索查询设备信息集合
     * @param string $device_name
     * @return json
    */
    function suggest_device($device_name=""){
        $data = array('code'=>200, 'message'=>'', 'value'=>array());

        $this->load->model('device_list_model');
        $data['value'] = $this->device_list_model->searchDeviceList($device_name);

        output_str(json_encode($data));
    }

    /**
     * @name 搜索查询版本信息集合
     * @param string $verion_name
     * @return json
    */
    function suggest_version($verion_name=""){
        $data = array('code'=>200, 'message'=>'', 'value'=>array());

        $this->load->model('version_list_model');
        $data['value'] = $this->version_list_model->searchVersionList($verion_name);

        output_str(json_encode($data));
    }

    /**
     * @name 搜索查询用户信息集合
     * @param string $user_name
     * @return json
    */
    function suggest_user($user_name=""){
        $data = array('code'=>200, 'message'=>'', 'value'=>array());

        $this->load->model('account_list_model');
        $data['value'] = $this->account_list_model->searchUserList($user_name);

        output_str(json_encode($data));
    }


    /**
     * @name 分页获取账号信息集合
     */
    function get_account_list_by_role_id(){
        $this->load->model(array('account_list_model'));
        $limit = paramsIsNull($_POST, 'limit')?10:$_POST['limit'];
        $offset = paramsIsNull($_POST, 'offset')?0:$_POST['offset'];
        $roleId = paramsIsNull($_POST, 'role_id')?0:$_POST['role_id'];

        $condition =array();
        if($roleId){
            $condition['role_id'] = $roleId;
        }

        $total = $this->account_list_model->getAccountTotal($condition);
        $rows = $this->account_list_model->getAccountListForPage($condition, $offset, $limit);

        $res['total'] = $total;
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }

    /**
     * @name 搜索查询机构信息集合
     * @param string $organ_name
     * @retur json
     */
    function suggest_hospital($organ_name=""){
        $name = urldecode($organ_name);
        $data = array();
        if($name) {
            $this->load->model('organization_model');
            $hostpitalList = $this->organization_model->searchOrganName($name);
            if($hostpitalList) {
                foreach ($hostpitalList as $h) {
                    $data[] = array('id' => $h['Id'], 'data' => $h['name']);
                }
            }
        }

        output_str(json_encode($data));
    }

    /**
     * @name 处理上传的excel信息
     * @param string $fileName
     * @return mixed
    */
    private function upload_excel($fileName=""){
        $result = array('code'=>0, 'message'=>'', 'file_id'=>0, 'file_path'=>'');
        $fileId = 0;

        if(isset($_FILES[$fileName])){
            $res = get_upload_fileinfo($fileName, array('.xlsx', '.xls'));
            if($res['state']=='SUCCESS'){
                $path = $res['strs'];
                $file_name = $res['original'];

                $data = array(
                    'file_name' => $file_name,
                    'file_path' => $path,
                    'upload_time' => time(),
                    'file_type' => $res['type'],
                    'system_id' => 1,
                    'account_id' => $this->user_id
                );

                $fileId = $this->file_list_model->add($data);
                if($fileId>0){
                    $result['file_id'] = $fileId;
                    $result['file_path'] = $res['path'];
                    $result['code'] = 1;
                    $result['message'] = "已经成功上传excel文件";
                }
            }
        }
        return $result;
    }

    // ********************** excel批量上传 ***********************//
    /**
     * @name 账号批量上传
    */
    function account_input(){

    }

    /**
     * @name 批量上传
    */
    function product_type_input(){

    }


    // ********************** excel批量下载 ***********************//
    /**
     * @name 账号批量上传
    */
    function account_output(){
        $this->load->library('excel/excel_helper');
        $data = array('sta'=>0, 'msg'=>'');
        $result = $this->upload_excel('account_file');

        if($result['code']>0){
            $accountResult = $this->saveAccountList($result['file_path'], $result['file_id']);
            $data['sta'] = $accountResult['sta'];
            $data['msg'] = $accountResult['msg'];
        }else{
            $data['sta'] = $result['sta'];
            $data['msg'] = $result['msg'];
        }
        output_str(json_encode($data));
    }

    /**
     * @name 批量读取保存账号信息
     * @param string $file_path
     * @param int $file_id
     * @return
    */
    private function saveAccountList($file_path="", $file_id=0){
        $result = array('sta'=>0, 'msg'=>'');

        $this->load->model(array('organization_model', 'account_list_model'));
        $file_data = $this->excel_helper->read_file($file_path, 'account_list');

        $organId = 0;
        $hasError = false;

        foreach($file_data as $index => $row){
            if($index == 0){
                $organName = $row[0];
                $organInfo = $this->organization_model->getOrganInfoByName($organName);
                if($organInfo){
                    $organId = $organInfo['organ_id'];
                }else{
                    $result['sta'] = -1;
                    $result['message'] = "机构名称不能为空";
                    break;
                }
            }
            if($index == 1)continue;//头部跳过

            $realName = $row[0];
            $accountName = $row[1];
            $mobile = $row[2];
            $jobName = $row[3];
            $email = $row[4];
            $sexStr = $row[5];
            $sex = 0;
            switch($sexStr){
                case '男': $sex=1; break;
                case '女': $sex=2; break;
                default: $sex=0; break;
            }

            if($realName && $accountName && $mobile && $jobName && $email) {
                $token = get_random_code(6, 8);
                $password = md5(md5("123456") . $token);
                $dtarr = array(
                    'account_code' => '',
                    'account_name' => $accountName,
                    'account_passwd' => $password,
                    'real_name' => $realName,
                    'mobile' => $mobile,
                    'job_name' => $jobName,
                    'email' => $email,
                    'sex' => $sex,
                    'create_time' => time(),
                    'organ_id' => $organId,
                    'token' => $token,
                    'status' => 1,
                    'system_role_id' => 4,
                    'account_status' => 1,
                    'member_system' => 0,
                    'device_system' => 1,
                    'shop_system' => 1
                );
                $userId = $this->account_list_model->add($dtarr);
                if($userId>0){
                    $detail = array(
                        'account_id' => $userId,
                        'open_id' => '',
                        'nickname' => '',
                        'avatar' => '',
                        'qq' => '',
                        'cost' => 0
                    );
                    $this->account_detail_model->add($detail);
                }
            }else{
                $hasError = true;
                break;
            }
        }

        if($hasError){
            $result['sta'] = 0;
            $result['msg'] = "账号信息批量操作失败";
        }else{
            $result['sta'] = 1;
            $result['msg'] = "账号信息批量操作成功";
        }
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '账户批量导入账号', '用户管理系统-'.$result['msg'], 1, 2, 2);
        return $result;
    }

    /**
     * @name 机构信息批量上传
    */
    function organ_output(){
        $this->load->library('excel/excel_helper');
        $data = array('sta'=>0, 'msg'=>'');
        $result = $this->upload_excel('organ_file');

        if($result['code']>0){
            $accountResult = $this->saveOrganList($result['file_path'], $result['file_id']);
            $data['sta'] = $accountResult['sta'];
            $data['msg'] = $accountResult['msg'];
        }else{
            $data['sta'] = $result['sta'];
            $data['msg'] = $result['msg'];
        }
        output_str(json_encode($data));
    }

    /**
     * @name 批量读取保存账号机构信息
     * @param string $file_path
     * @param int $file_id
    */
    private function saveOrganList($file_path="", $file_id=0){
        $result = array('sta'=>0, 'msg'=>'');

        $this->load->model(array('organization_model', 'account_list_model','city_list_model'));
        $file_data = $this->excel_helper->read_file($file_path, 'account_list');

        $organId = 0;
        $hasError = false;

        foreach($file_data as $index => $row){
            if($index == 0)continue;//头部跳过

            $organName = $row[0];
            $organType = $row[1];
            $cityName = $row[2];
            $address = $row[3];
            $contact = $row[4];
            $email = $row[5];
            $mobile = $row[6];
            $internalContacts = $row[7];
            $adminAccount = $row[8];
            $remark = $row[9];

            if($organName && $organType && $address && $contact && $email && $mobile && $internalContacts && $adminAccount ) {
                $adminAccountId = 0;
                $accountInfo = $this->account_list_model->getAccountByName($adminAccount);
                if($accountInfo){
                    $adminAccountId = $accountInfo['account_id'];
                }
                $cityId = 0;
                $cityInfo = $this->city_list_model->getCityByName($cityName);
                if($cityInfo){
                    $cityId = $cityInfo['city_id'];
                }

                $dtarr = array(
                    'organ_code' => '',
                    'organ_name' => $organName,
                    'organ_type_id' => 1,
                    'city_id' => $cityId,
                    'address' => $address,
                    'contacts' => $contact,
                    'email' => $email,
                    'mobile' => $mobile,
                    'service_start_date' => 0,
                    'service_end_date' => 0,
                    'internal_contacts' => $internalContacts,
                    'site_name' => "",
                    'remark' => $remark,
                    'create_time' => time(),
                    'theme_id' => 0,
                    'up_id' => 0,
                    'is_admin' => 0,
                    'status' => 1,
                    'admin_account_id' => $adminAccountId
                );
                $this->organization_model->add($dtarr);
            }else{
                $hasError = true;
                break;
            }
        }

        if($hasError){
            $result['sta'] = 0;
            $result['msg'] = "机构信息批量操作失败";
        }else{
            $result['sta'] = 1;
            $result['msg'] = "机构信息批量操作成功";
        }
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '机构批量导入账号', '用户管理系统-'.$result['msg'], 1, 3, 2);
        return $result;
    }

    /**
     * @name 终端信息批量上传
     */
    function terminal_output(){
        $this->load->library('excel/excel_helper');
        $data = array('sta'=>0, 'msg'=>'');
        $result = $this->upload_excel('terminal_file');

        if($result['code']>0){
            $accountResult = $this->saveTerminalList($result['file_path'], $result['file_id']);
            $data['sta'] = $accountResult['sta'];
            $data['msg'] = $accountResult['msg'];
        }else{
            $data['sta'] = $result['sta'];
            $data['msg'] = $result['msg'];
        }
        output_str(json_encode($data));
    }

    /**
     * @name 批量读取保存终端信息
     * @param string $file_path
     * @param int $file_id
     */
    private function saveTerminalList($file_path="", $file_id=0){
        $result = array('sta'=>0, 'msg'=>'');

        $this->load->model(array('termianl_list_model', 'terminal_type_list_model', 'account_list_model', 'version_list_model'));
        $file_data = $this->excel_helper->read_file($file_path, 'account_list');

        $organId = 0;
        $hasError = false;

        foreach($file_data as $index => $row){
            if($index == 0)continue;//头部跳过

            $terminalName = $row[0];
            $stockTime = $row[1];
            $serialNumber = $row[2];
            $terminalType = $row[3];
            $license = $row[4];
            $ip = $row[5];
            $remark = $row[6];

            if($terminalName && $stockTime && $serialNumber && $terminalType && $license && $ip) {
                $token = get_random_code(6, 8);
                $termianlTypeId = 0;
                $terminalTypeInfo = $this->terminal_type_list_model->getTerminalTypeByName($terminalType);
                if($terminalTypeInfo){
                    $termianlTypeId = $terminalTypeInfo['terminal_type_id'];
                }


                $dtarr = array(
                    'terminal_code' => '',
                    'terminal_name' => $terminalName,
                    'serial_number' => $serialNumber,
                    'stock_time' => $stockTime,
                    'terminal_type_id' => $termianlTypeId,
                    'version_id' => '',
                    'license' => $license,
                    'token' => $token,
                    'status' => 1,
                    'remark' => $remark,
                    'longitude' => 0,
                    'latitude' => 0,
                    'service_start_time' => 0,
                    'service_end_time' => 0,
                    'service_status' => 1,
                    'ip' => $ip,
                    'run_status' => 1
                );
                $this->termianl_list_model->add($dtarr);
            }else{
                $hasError = true;
                break;
            }
        }

        if($hasError){
            $result['sta'] = 0;
            $result['msg'] = "终端信息批量操作失败";
        }else{
            $result['sta'] = 1;
            $result['msg'] = "终端信息批量操作成功";
        }
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '终端批量导入账号', '用户管理系统-'.$result['msg'], 1, 6, 2);
        return $result;
    }

    /**
     * @name 设备类别信息批量上传
     */
    function device_type_output(){
        $this->load->library('excel/excel_helper');
        $data = array('sta'=>0, 'msg'=>'');
        $result = $this->upload_excel('device_type_file');

        if($result['code']>0){
            $accountResult = $this->saveDeviceTypeList($result['file_path'], $result['file_id']);
            $data['sta'] = $accountResult['sta'];
            $data['msg'] = $accountResult['msg'];
        }else{
            $data['sta'] = $result['sta'];
            $data['msg'] = $result['msg'];
        }
        output_str(json_encode($data));
    }

    /**
     * @name 批量读取保存终端信息
     * @param string $file_path
     * @param int $file_id
     */
    private function saveDeviceTypeList($file_path="", $file_id=0){
        $result = array('sta'=>0, 'msg'=>'');

        $this->load->model(array('device_type_list_model', 'device_list_model'));
        $file_data = $this->excel_helper->read_file($file_path, 'account_list');

        $organId = 0;
        $hasError = false;

        foreach($file_data as $index => $row){
            if($index == 0)continue;//头部跳过

            $deviceTypeName = $row[0];
            $remark = $row[1];
            $upDeviceTypeName = $row[2];

            if($deviceTypeName) {
                $upId = 0;
                $level = 0;
                if($upDeviceTypeName){
                    $upDeviceTypeInfo = $this->device_type_list_model->getDeviceTypeByName($upDeviceTypeName);
                    if($upDeviceTypeInfo){
                        $upId = $upDeviceTypeInfo['device_type_id'];
                        $level = $upDeviceTypeInfo['level'] + 1;
                    }
                }
                $dtarr = array(
                    'device_type_name' => $deviceTypeName,
                    'organ_id' => 0,
                    'status' => 1,
                    'device_type_code' => '',
                    'remark' => $remark,
                    'upid' => $upId,
                    'level' => $level
                );
                $this->device_type_list_model->add($dtarr);
            }else{
                $hasError = true;
                break;
            }
        }

        if($hasError){
            $result['sta'] = 0;
            $result['msg'] = "设备类别信息批量操作失败";
        }else{
            $result['sta'] = 1;
            $result['msg'] = "设备类别信息批量操作成功";
        }
        $this->system_log_model->addLog(1, $this->user_id, $this->role_id, '设备类别批量导入账号', '用户管理系统-'.$result['msg'], 1, 5, 2);
        return $result;
    }



    /**
     * @name 搜索查询设备信息集合
     * @param string $device_name
     * @return json
     */
    function suggest_devicelist($device_name=""){
        $data = array('code'=>200, 'message'=>'', 'value'=>array());

        $this->load->model(array('device_list_model', 'account_list_model'));
        $data['value'] = $this->device_list_model->searchDeviceListByDeviceName($device_name);

        output_str(json_encode($data));
    }

    /**
     * @name 根据机构ID获取设备信息集合
     * @param int $organId
     * @return json
    */
    function suggest_organ_devicelist($organId=0){
        $data = array('code'=>200, 'message'=>'', 'value'=>array());

        $this->load->model(array('device_list_model', 'account_list_model'));
        $data['value'] = $this->device_list_model->searchDeviceListByDeviceName("", $organId);

        output_str(json_encode($data));
    }

    /**
     * @name 根据结构ID获取所属未分配角色的用户集合
     * @return json_string
    */
    function getAccountByOrganId(){
        $this->load->model(array('account_list_model'));
        $limit = paramsIsNull($_POST, 'limit')?10:$_POST['limit'];
        $offset = paramsIsNull($_POST, 'offset')?0:$_POST['offset'];
        $name = paramsIsNull($_POST, 'name')?0:$_POST['name'];
        $realname = paramsIsNull($_POST, 'real_name')?0:$_POST['real_name'];
        $organId = paramsIsNull($_POST, 'organ_id')?0:$_POST['organ_id'];

        $condition =array();
        if($name){
            $condition['name'] = $name;
        }
        if($realname){
            $condition['r_name'] = $realname;
        }
        if($organId){
            $condition['organ_id'] = $organId;
        }
        $condition['role_id'] = 0;

        $total = $this->account_list_model->getAccountTotal($condition);
        $rows = $this->account_list_model->getAccountListForPage($condition, $offset, $limit);

        $res['total'] = $total;
        $res['rows'] = $rows;

        output_str(json_encode($res));
    }

    /**
     * @name 更新机构信息管理员账号
     * @param array $_POST
     * @return json_string
    */
    function update_organ_admin(){
        $result = array('sta'=>0, 'msg'=>'');

        $this->load->model(array('account_list_model', 'organization_model'));
        $userId = paramsIsNull($_POST, 'user_id')?0:$_POST['user_id'];
        $organId = paramsIsNull($_POST, 'organ_id')?0:$_POST['organ_id'];

        $condition =array();
        if(!$userId){
            $result['msg'] = '请选择合适的运营管理员';
        }
        if(!$organId){
            $result['msg'] = '请选择需要配置的单位';
        }

        $accountInfo = $this->account_list_model->getAccountById($userId, 1);
        if($accountInfo) {

            $status = $this->organization_model->update($organId, array('admin_account_id' => $userId));
            if ($status) {
                $this->account_list_model->update($userId, array('system_role_id'=>3));
                $result['sta'] = 1;
                $result['msg'] = "单位配置运营管理员操作成功";
            } else {
                $result['msg'] = "单位配置运营管理员操作失败";
            }
        }else{
            $result['sta'] = 1;
            $result['msg'] = "该用户已被删除，无法指定为管理员";
        }

        output_str(json_encode($result));
    }

    /**
     * @name 根据机构ID获取机构实验大楼集合
     * @param int $organId
     * @return json_string
    */
    function getOrganBuildByOrganId($organId=0){
        $result = array('sta'=>0, 'msg'=>'', 'list'=>array());
        if($organId>0){
            $this->load->model('organ_build_list_model');
            $list = $this->organ_build_list_model->getOrganBuildListByOrganId($organId);
            if($list){
                foreach ($list as $i){
                    if($i['status']==1) {
                        $result['list'][] = array(
                            'Id' => $i['organ_build_id'],
                            'name' => $i['organ_build_name']
                        );
                    }
                }
                $result['sta'] = 1;
                $result['msg'] = "实验大楼获取成功";
            }else{
                $result['msg'] = "单位下属没有实验楼信息";
            }
        }else{
            $result['msg'] = "请先选择单位";
        }
        output_str(json_encode($result));
    }

    /**
     * @name 根据实验大楼ID获取实验室集合
     * @param int $organBuildId
     * @return json_string
    */
    function getOrganLaboratoryByOrganId($organBuildId=0){
        $result = array('sta'=>0, 'msg'=>'', 'list'=>array());
        if($organBuildId){
            $this->load->model('organ_laboratory_model');
            $list = $this->organ_laboratory_model->getOrganLabList($organBuildId);
            if($list){
                foreach ($list as $i){
                    $result['list'][] = array(
                        'Id' => $i['organ_laboratory_id'],
                        'name' => $i['organ_laboratory_name']
                    );
                }
                $result['sta'] = 1;
                $result['msg'] = "实验室获取成功";
            }else{
                $result['msg'] = "该实验大楼下属没有实验室";
            }
        }else{
            $result['msg'] = "请先选择实验大楼";
        }
        output_str(json_encode($result));
    }

}
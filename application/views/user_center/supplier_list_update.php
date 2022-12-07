</head>

<body class="fixed-sidebar full-height-layout gray-bg">
<div class="ibox-content">
    <form class="form-horizontal m-t" id="ff">
        <div class="form-group">
            <label class="col-sm-3 control-label">所属机构名称：</label>
            <div class="col-sm-8">
                <?php $organInfo = $this->organization_model->getOrganInfoById($data['organ_id']); ?>

                <div class="input-group">
                    <input type="text" class="form-control" id="organ_name" name="organ_name" placeholder="请输入公司名称" value="<?php echo $organInfo['organ_name'];?>" />
                    <input type="hidden" name="organ_id" id="organ_id" value="<?php echo $data['organ_id'];?>" />
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">用户姓名：</label>
            <div class="col-sm-8">
                <input id="realname" name="realname" class="form-control" type="text" value="<?php echo $data['real_name'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">单位工号：</label>
            <div class="col-sm-8">
                <input id="name" name="name" class="form-control" type="text" value="<?php echo $data['account_name'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">账户密码：</label>
            <div class="col-sm-8">
                <input id="passwd" name="passwd" class="form-control" type="password" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">手机号码：</label>
            <div class="col-sm-8">
                <input id="mobile" name="mobile" class="form-control" type="text" value="<?php echo $data['mobile'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">部门名称：</label>
            <div class="col-sm-8">
                <input id="section" name="section" class="form-control" type="text" value="<?php echo $data['job_name'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">电子邮箱：</label>
            <div class="col-sm-8">
                <input id="email" name="email" class="form-control" type="text" value="<?php echo $data['email'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">性别：</label>
            <div class="col-sm-8">
                <div class="radio">
                    <label><input type="radio" value="" id="sex" name="sex" <?php if(!$data['sex']){echo "checked='checked'";}?>>未知</label>
                </div>
                <div class="radio">
                    <label><input type="radio" value="1" id="sex" name="sex" <?php if($data['sex']==1){echo "checked='checked'";}?>>男</label>
                </div>
                <div class="radio">
                    <label><input type="radio" value="2" id="sex" name="sex" <?php if($data['sex']==2){echo "checked='checked'";}?>>女</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">账号状态：</label>
            <div class="col-sm-8">
                <div class="radio">
                    <label><input type="radio" value="" id="account_status" name="account_status" <?php if(!$data['account_status']){echo "checked='checked'";}?>>关闭</label>
                </div>
                <div class="radio">
                    <label><input type="radio" value="1" id="account_status" name="account_status" <?php if($data['account_status']==1){echo "checked='checked'";}?>>启用</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">用户系统访问权限：</label>
            <div class="col-sm-8">
                <label>
                    <input type="checkbox" id="system_list" name="member_system" value="1" <?php if($data['member_system']==1){echo "checked='checked'";}?>> <i></i> 用户管理系统
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">设备系统访问权限：</label>
            <div class="col-sm-8">
                <label>
                    <input type="checkbox" id="system_list" name="device_system" value="1" <?php if($data['device_system']==1){echo "checked='checked'";}?>> <i></i> 设备信息管理系统
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">集采系统访问权限：</label>
            <div class="col-sm-8">
                <label>
                    <input type="checkbox" id="system_list" name="agentia_system" value="1" <?php if($data['shop_system']==1){echo "checked='checked'";}?>> <i></i> 试剂采购管理系统
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12" style="text-align: center;">
                <input type="hidden" id="id" name="id" value="<?php echo $data['account_id'];?>" />
                <button class="btn btn-primary" type="submit">提交</button>
            </div>
        </div>
    </form>
</div>

<?php $this->load->view('common/js')?>
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/suggest/bootstrap-suggest.min.js"></script>

<script>
    const e = "<i class='fa fa-times-circle'></i> ";
    $("#ff").validate({
        rules: {
            organ_name: {
                required: !0,
                maxlength: 20
            },
            realname: {
                required: !0,
                maxlength: 5
            },
            name: {
                required: !0,
                maxlength: 10
            },
            mobile: {
                required: !0,
                maxlength: 11
            },
            system_list: {
                required: !0,
            }
        },
        messages: {
            organ_name: {
                required: e + "请选择输入机构名称",
                maxlength: e + "设备分类名称不能超过20个字"
            },
            realname: {
                required: e + "请输入真实姓名",
                maxlength: e + "设备分类名称不能超过5个字"
            },
            name: {
                required: e + "请输入账号名称",
                maxlength: e + "设备分类名称不能超过10个字"
            },
            mobile: {
                required: e + "请输入手机号码",
                maxlength: e + "设备分类名称不能超过11个字"
            },
            system_list: {
                required: e + "请选择可访问的系统"
            }
        },
        submitHandler: function(form){
            parent_frame.layer.load();
            $(form).ajaxSubmit({
                type: 'post',
                url: '<?php echo $ajax_url?>/update_account',
                dataType: 'json',
                success: function(res){
                    ajaxSuccess(res, 1);
                },
                error: function(){
                    ajaxError();
                }
            })
        }
    })

    var testBsSuggest = $("#organ_name").bsSuggest({
        url: "<?php echo $ajax_url?>/suggest_organ",
        effectiveFields: ["name"],
        effectiveFieldsAlias:{name: "机构名称"},
        idField: "id",
        keyField: "name",
    }).on('onSetSelectValue', function (e, keyword) {
        $('#organ_id').val(keyword.id);
    });

</script>
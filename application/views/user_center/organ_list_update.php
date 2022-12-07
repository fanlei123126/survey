</head>

<body class="fixed-sidebar full-height-layout gray-bg">
<div class="ibox-content">
    <form class="form-horizontal m-t" id="ff">
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>机构名称：</label>
            <div class="col-sm-8">
                <input id="name" name="name" class="form-control" type="text" value="<?php echo $data['organ_name'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>机构类别：</label>
            <div class="col-sm-8">
                <select name="organ_type" id="organ_type" class="form-control">
                    <option value="">请选择...</option>
                    <?php foreach($organTypeList as $organType){?>
                        <option value="<?php echo $organType['organ_type_id']?>" <?php if($organType['organ_type_id']==$data['organ_type_id']){echo "selected='selected'";}?>><?php echo $organType['organ_type_name']?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>所在城市：</label>
            <div class="col-sm-8">
                <select name="city_list" id="city_list" class="form-control">
                    <option value="">请选择...</option>
                    <?php foreach($cityList as $city){?>
                        <option value="<?php echo $city['city_id']?>" <?php if($city['city_id']==$data['city_id']){echo "selected='selected'";}?>><?php echo $city['city_name']?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>联系地址：</label>
            <div class="col-sm-8">
                <input id="address" name="address" class="form-control" type="text" value="<?php echo $data['address'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>联系人：</label>
            <div class="col-sm-8">
                <input id="contacts" name="contacts" class="form-control" type="text" value="<?php echo $data['contacts'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>邮箱地址：</label>
            <div class="col-sm-8">
                <input id="email" name="email" class="form-control" type="text" value="<?php echo $data['email'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>联系电话：</label>
            <div class="col-sm-8">
                <input id="phone" name="phone" class="form-control" type="text" value="<?php echo $data['mobile'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>服务期限：</label>
            <div class="col-sm-8">
                <input class="form-control layer-date" id="service_start_time" name="service_start_time" value="<?php echo date("Y-m-d", $data['service_start_date']);?>" />
                <input class="form-control layer-date" id="service_end_time" name="service_end_time" value="<?php echo date("Y-m-d", $data['service_end_date']);?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">内部负责人：</label>
            <div class="col-sm-8">
                <input id="internal" name="internal" class="form-control" type="text" value="<?php echo $data['internal_contacts'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">站点管理员：</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" class="form-control" id="system_admin" name="system_admin" placeholder="请输入用户姓名" value="<?php echo isset($adminUser['real_name'])?$adminUser['real_name']:"";?>" />
                    <input type="hidden" id="system_admin_id" name="system_admin_id" value="<?php echo $data['admin_account_id'];?>" />
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
            <label class="col-sm-3 control-label">描述：</label>
            <div class="col-sm-8">
                <textarea cols="10" rows="10" class="form-control" name="remark"><?php echo $data['remark'];?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12" style="text-align: center;">
                <input type="hidden" name="id" id="id" value="<?php echo $data['organ_id'];?>" />
                <button class="btn btn-primary" type="submit">提交</button>
            </div>
        </div>
    </form>
</div>

<?php $this->load->view('common/js')?>
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/layer/laydate/laydate.js"></script>
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/suggest/bootstrap-suggest.min.js"></script>

<script>
    const e = "<i class='fa fa-times-circle'></i> ";
    $("#ff").validate({
        rules: {
            name: {
                required: !0,
                maxlength: 30
            },
            organ_type: {
                required: !0
            },
            city_list: {
                required: !0
            },
            address: {
                required: !0,
                maxlength: 30
            },
            contacts: {
                maxlength: 5
            },
            email: {
                required: !0
            },
            phone: {
                required: !0
            },
            service_start_time: {
                required: !0,
                maxlength: 10
            },
            service_end_time: {
                required: !0,
                maxlength: 10
            }
        },
        messages: {
            name: {
                required: e + "请输入单位名称",
                maxlength: e + "终端名称不能超过30个字"
            },
            organ_type: {
                required: e + "请选择单位类别"
            },
            city_list: {
                required: e + "请选择所属城市"
            },
            address: {
                required: e + "请输入联系地址",
                maxlength: e + "联系地址不能超过30个字"
            },
            contacts: {
                maxlength: e + "联系人姓名不能超过5个字"
            },
            email: {
                required: e + "请输入邮件地址"
            },
            phone: {
                required: e + "请输入联系电话"
            },
            service_start_time: {
                required: e + "请选择服务开始时间",
                maxlength: e + "服务开始时间不能超过10个字"
            },
            service_end_time: {
                required: e + "请选择服务截止时间",
                maxlength: e + "服务结束时间不能超过10个字"
            }
        },
        submitHandler: function(form){
            parent_frame.layer.load();
            $(form).ajaxSubmit({
                type: 'post',
                url: '<?php echo $ajax_url?>/update_organ',
                dataType: 'json',
                success: function(res){
                    ajaxSuccess(res, 1);
                },
                error: function(){
                    ajaxError();
                }
            })
        }
    });

    var start = {
        elem: '#service_start_time',
        format: 'YYYY/MM/DD',
        min: laydate.now(), //设定最小日期为当前日期
        max: '<?php echo MAXSERVICEDATE;?>', //最大日期
        istime: true,
        istoday: false,
        choose: function (datas) {
            end.min = datas; //开始日选好后，重置结束日的最小日期
            end.start = datas //将结束日的初始值设定为开始日
        }
    };
    var end = {
        elem: '#service_end_time',
        format: 'YYYY/MM/DD',
        min: laydate.now(),
        max: '<?php echo MAXSERVICEDATE;?>',
        istime: true,
        istoday: false,
        choose: function (datas) {
            start.max = datas; //结束日选好后，重置开始日的最大日期
        }
    };
    laydate(start);
    laydate(end);

    var testBsSuggest = $("#system_admin").bsSuggest({
        url: "<?php echo $ajax_url?>/suggest_user",
        effectiveFields: ["name"],
        effectiveFieldsAlias:{name: "用户姓名"},
        idField: "id",
        keyField: "name",
    }).on('onSetSelectValue', function (e, keyword) {
        $('#system_admin_id').val(keyword.id);
    });

</script>
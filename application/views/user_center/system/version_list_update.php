</head>

<body class="fixed-sidebar full-height-layout gray-bg">
<div class="ibox-content">
    <form class="form-horizontal m-t" id="ff">
        <div class="form-group">
            <label class="col-sm-3 control-label">版本名称：</label>
            <div class="col-sm-8">
                <input id="name" name="name" class="form-control" type="text" value="<?php echo $data['version_name'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">版本号：</label>
            <div class="col-sm-8">
                <input id="vcode" name="vcode" class="form-control" type="text" value="<?php echo $data['version_code'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">版本类别：</label>
            <div class="col-sm-8">
                <select id="version_type" name="version_type" class="form-control">
                    <option value="">请选择...</option>
                    <?php foreach($versionTypeList as $versionType){?>
                        <option value="<?php echo $versionType['Id']?>" <?php if($data['version_type_id']==$versionType['Id']){echo "selected='selected'";}?>><?php echo $versionType['name'];?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">更新包：</label>
            <div class="col-sm-8">
                <input type="file" class="form-control" id="package" name="package" value="<?php echo $data['version_package'];?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">发布时间：</label>
            <div class="col-sm-8">
                <input id="online_time" readonly name="online_time" class="form-control layer-date" type="text" placeholder="点击选择上线日期" value="<?php echo date("Y-m-d",$data['online_time']);?>" />
                <label class="laydate-icon inline demoicon" onclick="laydate({elem: '#online_time',istime: true, format: 'YYYY-MM-DD'});"></label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">描述：</label>
            <div class="col-sm-8">
                <textarea cols="10" rows="10" name="remark" id="remark" class="form-control"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12" style="text-align: center;">
                <input type="hidden" name="id" value="<?php echo $data['version_id'];?>" />
                <button class="btn btn-primary" type="submit">提交</button>
            </div>
        </div>
    </form>
</div>

<?php $this->load->view('common/js')?>
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/layer/laydate/laydate.js"></script>

<script>
    var e = "<i class='fa fa-times-circle'></i> ";
    $("#ff").validate({
        rules: {
            name: {
                required: !0,
                maxlength: 10
            },
            vcode: {
                required: !0,
                maxlength: 10
            },
            version_type: {
                required: !0
            },
            online_time: {
                required: !0,
                maxlength: 10
            }
        },
        messages: {
            name: {
                required: e + "请输入版本名称",
                maxlength: e + "设备分类名称不能超过10个字"
            },
            vcode: {
                required: e + "请输入版本号",
                maxlength: e + "设备分类名称不能超过10个字"
            },
            version_type: {
                required: e + "请选择版本类别",
            },
            online_time: {
                required: e + "请输入发布时间",
                maxlength: '发布日期格式为：2020-10-18'
            }
        },
        submitHandler: function(form){
            parent_frame.layer.load();
            $(form).ajaxSubmit({
                type: 'post',
                url: '<?php echo $ajax_url?>/update_version',
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

</script>
</head>

<body class="fixed-sidebar full-height-layout gray-bg">
<div class="ibox-content">
    <form class="form-horizontal m-t" id="ff">
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>发票金额：</label>
            <div class="col-sm-8">
                <input id="realname" name="realname" class="form-control" type="text" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>发票号：</label>
            <div class="col-sm-8">
                <input id="realname" name="realname" class="form-control" type="text" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">备注：</label>
            <div class="col-sm-8">
                <input id="realname" name="realname" class="form-control" type="text" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12" style="text-align: center;">
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
            passwd: {
                required: !0,
                maxlength: 20
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
                maxlength: e + "机构名称不能超过20个字"
            },
            realname: {
                required: e + "请输入真实姓名",
                maxlength: e + "真实姓名不能超过5个字"
            },
            name: {
                required: e + "请输入单位工号",
                maxlength: e + "单位工号不能超过10个字"
            },
            passwd: {
                required: e + "请输入账号密码",
                maxlength: e + "账号密码不能超过20个字"
            },
            mobile: {
                required: e + "请输入手机号码",
                maxlength: e + "手机号码不能超过11个字"
            },
            system_list: {
                required: e + "请选择可访问的系统"
            }
        },
        submitHandler: function(form){
            parent_frame.layer.load();
            $(form).ajaxSubmit({
                type: 'post',
                url: '<?php echo $ajax_url?>/create_account',
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
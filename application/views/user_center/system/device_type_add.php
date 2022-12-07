</head>

<body class="fixed-sidebar full-height-layout gray-bg">
    <div class="ibox-content">
        <form class="form-horizontal m-t" id="ff">
            <div class="form-group">
                <label class="col-sm-3 control-label">设备类别名称：</label>
                <div class="col-sm-8">
                    <input id="name" name="name" class="form-control" type="text" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">描述：</label>
                <div class="col-sm-8">
                    <textarea cols="10" rows="10" class="form-control" name="remark"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12" style="text-align: center;">
                    <input type="hidden" id="upid" name="upid" value="<?php echo $upid;?>" />
                    <button class="btn btn-primary" type="submit">提交</button>
                </div>
            </div>
        </form>
    </div>

<?php $this->load->view('common/js')?>

<script>
    var e = "<i class='fa fa-times-circle'></i> ";
    $("#ff").validate({
        rules: {
            name: {
                required: !0,
                maxlength: 20
            },
            remark: {
                maxlength: 100
            }
        },
        messages: {
			name: {
              required: e + "请输入设备分类名称",
              maxlength: e + "设备分类名称不能超过20个字"
			},
            remark: {
                maxlength: e + "描述内容不能超过100个字"
            }
        },
        submitHandler: function(form){
            parent_frame.layer.load();
            $(form).ajaxSubmit({
                type: 'post',
                url: '<?php echo $ajax_url?>/device_type_add',
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
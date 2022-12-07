</head>

<body class="fixed-sidebar full-height-layout gray-bg">
<div class="ibox-content">
    <form class="form-horizontal m-t" id="ff">
        <div class="form-group">
            <label class="col-sm-3 control-label">角色名称：</label>
            <div class="col-sm-8">
                <input id="name" name="name" class="form-control" type="text" />
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

<script>
    const e = "<i class='fa fa-times-circle'></i> ";
    $("#ff").validate({
        rules: {
            name: {
                required: !0,
                maxlength: 10
            }
        },
        messages: {
            name: {
                required: e + "请输入角色名称",
                maxlength: e + "角色名称不能超过10个字"
            }
        },
        submitHandler: function(form){
            parent_frame.layer.load();
            $(form).ajaxSubmit({
                type: 'post',
                url: '<?php echo $ajax_url?>/create_role',
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
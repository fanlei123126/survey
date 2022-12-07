</head>

<body class="fixed-sidebar full-height-layout gray-bg">
<div class="ibox-content">
    <form class="form-horizontal m-t" id="ff">
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-18" >
              <H2>上海库巴客物流派车单</H2>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>项目号：</label>
            <div class="col-sm-8">
            <select id="device_type" name="device_type"  class="form-control">
                <option value="">请选择项目号</option>
<!--                --><?php //foreach($device_type_list as $type){?>
                <option value="">11111</option>
                <option value="">22222</option>
                <option value="">3333333</option>
                <option value="">4444444</option>
<!--                --><?php //}?>
            </select>
                </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>派车内容：</label>
            <div class="col-sm-8">
                <input id="product_unit" name="product_unit" class="form-control" type="text" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>包装尺寸(MM)：</label>
            <div class="col-sm-8">
                <input id="product_unit" name="product_unit" class="form-control" type="text" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>单套体积(m2)：</label>
            <div class="col-sm-8">
                <input id="product_unit" name="product_unit" class="form-control" type="text" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>单套价格(元)：</label>
            <div class="col-sm-8">
                <input id="product_unit" name="product_unit" class="form-control" type="text" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>包装方式：</label>
            <div class="col-sm-8">
                <input id="product_unit" name="product_unit" class="form-control" type="text" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>数量(套)：</label>
            <div class="col-sm-8">
                <input id="product_unit" name="product_unit" class="form-control" type="text" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>重量(KG)：</label>
            <div class="col-sm-8">
                <input id="product_unit" name="product_unit" class="form-control" type="text" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>目的地：</label>
            <div class="col-sm-8">
                <input id="product_unit" name="product_unit" class="form-control" type="text" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">是否含票：</label>
            <div class="col-sm-8">
                <div class="radio">
                    <label><input type="radio" value="0" id="account_status" name="account_status" />含票</label>
                </div>
                <div class="radio">
                    <label><input type="radio" value="1" id="account_status" name="account_status" />不含票</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>日期：</label>
            <div class="col-sm-8">
                <input id="passwd" name="passwd" class="form-control" type="password" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12" style="text-align: center;">
                <button class="btn btn-primary" type="submit">提交</button>
             </div>
        </div>

    </form>
</div>
<!--弹窗-->
<div class="modal inmodal fade" id="modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>
<?php $this->load->view('common/js')?>
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/suggest/bootstrap-suggest.min.js"></script>

<script>
    $(function() {
        // var oTable;
        // //1.初始化Table
        // oTable = new TableInit();
        // oTable.Init();
        //2.初始化Button的点击事件
        var oButtonInit = new ButtonInit();
        oButtonInit.Init();
    });

    var ButtonInit = function() {
        var oInit = new Object();
        var postdata = {};
        oInit.Init = function() {
            //初始化页面上面的按钮事件
            //新增
            $('#btn_add').on('click', function() {
                //iframe窗
                parent_frame.showLayer({
                    title: '新增物料',
                    content: '<?php echo $page_url;?>/material_add'
                }, '800px', '460px');
            });
            //$('#btn_template').on('click', function(){
            //    location.href = '<?php //echo STATIC_URL."/directory/account.xlsx";?>//';
            //});
            // $('#btn_input').on('click', function(){
            //     console.log('upload_file');
            //     //parent_frame.layer.alert('正在建设中');
            //     $('#account_file').on('change', function(){
            //         parent_frame.layer.load();
            //         $('#uploadForm').ajaxSubmit(function(res){
            //             res = eval('('+res+')');
            //             $('#account_file').val('');
            //             ajaxSuccess(res, 0);
            //         });
            //     });
            // });
            // $('#btn_output').on('click', function(){
            //     parent_frame.layer.alert('正在建设中');
            // });
            // //查询
            // $('#btn_query').on('click', function() {
            //     $('#table').bootstrapTable('selectPage', 1);
            // })
        };
        return oInit;
    };
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
                required: e + "请选择输入产品名称",
                maxlength: e + "产品名称不能超过50个字"
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
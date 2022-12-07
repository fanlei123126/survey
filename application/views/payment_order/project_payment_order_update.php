</head>

<body class="fixed-sidebar full-height-layout gray-bg">
<div class="ibox-content">
             <div class="form-group">
                <label class="col-sm-3 control-label">项目状态：</label>
                <span class="span-red">付款单中</span>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">项目号：</label>
                <span class="span-red">显示项目号</span>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">产品名称：</label>
                <span class="span-red">显示产品名称</span>
            </div>
          <table id="table"></table>
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
        var oTable;
        //1.初始化Table
        oTable = new TableInit();
        oTable.Init();
        //2.初始化Button的点击事件
        var oButtonInit = new ButtonInit();
        oButtonInit.Init();
    });

    var TableInit = function() {
        var oTableInit = new Object();
        //初始化Table
        oTableInit.Init = function() {
            $('#table').bootstrapTable({
                url: '<?php echo $ajax_url; ?>/project_material_list',// 请求后台的URL（ * ）
                method: 'post', //请求方式（*）
                contentType: 'application/x-www-form-urlencoded',
                toolbar: '#toolbar', //工具按钮用哪个容器
                striped: true, //是否显示行间隔色
                cache: false, //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
                pagination: true, //是否显示分页（*）
                sortable: true, //是否启用排序
                sortOrder: "asc", //排序方式
                queryParams: oTableInit.queryParams, //传递参数（*）
                sidePagination: "server", //分页方式：client客户端分页，server服务端分页（*）
                pageNumber: 1, //初始化加载第一页，默认第一页
                pageSize: 10, //每页的记录行数（*）
                pageList: [10, 25, 50, 100], //可供选择的每页的行数（*）
                search: false, //是否显示表格搜索，此搜索是客户端搜索，不会进服务端，所以，个人感觉意义不大
                strictSearch: true,
                showColumns: true, //是否显示所有的列
                showRefresh: true, //是否显示刷新按钮
                minimumCountColumns: 2, //最少允许的列数
                clickToSelect: true, //是否启用点击选中行
                singleSelect: true, //是否单选
                height: '100%', //行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
                uniqueId: "ID", //每一行的唯一标识，一般为主键列
                showToggle: true, //是否显示详细视图和列表视图的切换按钮
                cardView: false, //是否显示详细视图
                detailView: false, //是否显示父子表
                columns: [{
                    field: 'material_name',
                    title: '物料名称'
                }, {
                    field: 'supplier_name',
                    title: '供应商'
                },{
                    field: 'material_number',
                    title: '现有数量'
                }, {
                    field: 'send_material_number',
                    title: '发货数量'
                }, {
                    field: 'material_unit_price',
                    title: '套价(元)'
                }, {
                    field: 'total_price',
                    title: '总价(元)'
                },  {
                    field: 'included_freight',
                    title: '是否含运费'
                },{
                    field: 'included_tax',
                    title: '是否含税'
                },{
                    field: 'invoice',
                    title: '是否开票'
                }, {
                    field: 'Id',
                    title: '操作',
                    formatter: function(value,row,index){
                        var a = '';
                        a += '<a href="javascript:void(0)" onclick="update(' + value + ')" class="btn btn-info"><i class="fa fa-paste"></i>编辑</a>';
                         return a;
                    }
                }]
            });
        };

        //得到查询的参数
        oTableInit.queryParams = function(params) {
            var temp = { //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
                limit: params.limit, //页面大小
                offset: params.offset, //页码
                material_name: $("#material_name").val(),
                supplier_name: $("#supplier_name").val(),
                total_price: $("#total_price").val(),
                material_unit_price: $("#material_unit_price").val(),
                material_number: $("#material_number").val(),
                send_material_number: $("#send_material_number").val(),
                included_tax: $("#included_tax").val(),
                included_freight: $("#included_freight").val(),
                invoice: $("#invoice").val(),
                circle: $("#circle").val(),
                remark: $("#remark").val(),
            };
            return temp;
        };
        return oTableInit;
    };

    var ButtonInit = function() {
        var oInit = new Object();
        var postdata = {};
        oInit.Init = function() {
            //初始化页面上面的按钮事件
            //新增
            $('#update').on('click', function() {
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
    function    update(){
        parent_frame.showLayer({
            title: '编辑付款物料',
            content: '<?php echo $page_url;?>/material_update'
        }, '800px', '460px');
    }

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
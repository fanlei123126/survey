<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <form id="formSearch" class="form-inline form-box">
                <div class="form-group">
                    <label for="search_code">用户姓名：</label>
                    <input type="text" class="form-control" id="username" placeholder="请输入用户姓名" autocomplete="off" />
                </div>
                <div class="form-group">
                    <label for="search_code">手机号码：</label>
                    <input type="text" class="form-control" id="mobile" placeholder="请输入手机号码" autocomplete="off" />
                </div>
                <button type="button" class="btn btn-primary search-btn" id="btn_query">查询</button>
            </form>
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
                        url: '<?php echo $ajax_url; ?>/get_system_log',// 请求后台的URL（ * ）
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
                            field: 'userName',
                            title: '用户姓名'
                        }, {
                            field: 'sectionType',
                            title: '版本类别'
                        }, {
                            field: 'dataType',
                            title: '操作类别'
                        }, {
                            field: 'title',
                            title: '标题'
                        }, {
                            field: 'remark',
                            title: '描述'
                        }, {
                            field: 'createTime',
                            title: '操作时间'
                        }/*, {
                            field: 'logId',
                            title: '操作',
                            formatter: function(value,row,index){
                                var a = '';
                                a += '<a href="javascript:void(0)" onclick="update('+value+')" class="btn btn-info"><i class="fa fa-paste"></i>详情</a>';
                                return a;
                            }
                        }*/]
                    });
                };
                //得到查询的参数
                oTableInit.queryParams = function(params) {
                    var temp = { //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
                        limit: params.limit, //页面大小
                        offset: params.offset, //页码
                        username: $("#username").val(),
                        mobile: $("#mobile").val()
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
                    $('#btn_add').on('click', function() {
                        //iframe窗
                        current_layer = layer.open({
                            type: 2,
                            title: '创建新的banner',
                            shade: [0.8, '#393D49'],
                            maxmin: true, //开启最大化最小化按钮
                            area: ['800px', '460px'],
                            content: '<?php echo $page_url;?>/index/add'
                        });
                    })
                    //查询
                    $('#btn_query').on('click', function() {
                        $('#table').bootstrapTable('selectPage', 1);
                    })
                };
                return oInit;
            };

            /*
             * 刷新页面
             */
            function refresh(){
                $('#table').bootstrapTable('refresh');
            }
        </script>
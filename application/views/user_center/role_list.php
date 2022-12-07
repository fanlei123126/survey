<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <form id="formSearch" class="form-inline form-box">
                <div class="form-group">
                    <label for="search_code">角色名称：</label>
                    <input type="text" class="form-control" id="search_code" placeholder="请输入角色名称" autocomplete="off" />
                </div>
                <button type="button" class="btn btn-primary search-btn" id="btn_query">查询</button>
            </form>
            <div id="toolbar" class="btn-group ">
                <button type="button" id="btn_add" class="btn btn-primary">新增角色</button>
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
                url: '<?php echo $ajax_url; ?>/get_system_role',// 请求后台的URL（ * ）
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
                    field: 'name',
                    title: '角色名称'
                }, {
                    field: 'Id',
                    title: '操作',
                    formatter: function(value,row,index){
                        console.log(row);
                        var a = '';
                            a += '<a href="javascript:void(0)" onclick="update(' + value + ')" class="btn btn-info"><i class="fa fa-paste"></i>编辑</a>';
                            a += '<a href="javascript:void(0)" onclick="remove(' + value + ')" class="btn btn-danger"><i class="fa fa-times"></i>删除</a>';
                            a += '<a href="javascript:void(0)" onclick="auth_edit(' + value + ')" class="btn btn-primary">权限分配</a>';

                        a += '<a href="javascript:void(0)" onclick="user_list(' + value + ')" class="btn btn-primary">用户列表</a>';
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
                name: $("#txt_search_name").val()
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
                parent_frame.showLayer({
                    title: '新增系统角色',
                    content: '<?php echo $page_url;?>/index/add'
                }, '800px', '190px');
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

    /*
     * 修改
     */
    function update(id){
        //iframe窗
        parent_frame.showLayer({
            title: '修改角色信息',
            content: '<?php echo $page_url;?>/index/update/'+id
        }, '800px', '190px');
    }

    /*
     * 删除
     */
    function remove(id){
        if(!id){
            layer.msg("数据传输错误，请联系管理员", {icon:2});
            return;
        }
        parent_frame.layer.confirm('您确定要删除这个角色信息？',
        {
            btn: ['确认','取消'], //按钮
            shade: [0.8, '#393D49'], //不显示遮罩
        },
        function()
        {
            parent_frame.layer.load();
            $.ajax({
                type: 'post',
                data: {'id': id},
                url: '<?php echo $ajax_url;?>/remove_role/',
                dataType: 'json',
                success: function(res) {
                    ajaxSuccess(res);
                    layer.closeAll();
                },
                error: function(){
                    ajaxError();
                }
            });
        });
    }

    /**
     * 角色权限分配
     */
    function auth_edit(_id){
        parent_frame.showLayer({
            title: '角色权限配置',
            content: '<?php echo $page_url;?>/index/auth_edit/'+_id
        }, '800px', '450px');
    }

    /**
     * 显示该角色的用户
     */
    function user_list(_id){
        parent_frame.showLayer({
            title: '角色用户列表',
            content: '<?php echo $page_url;?>/index/user_list/'+_id
        }, '800px', '450px');
    }


</script>
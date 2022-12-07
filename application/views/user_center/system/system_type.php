<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
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
            });
            var TableInit = function() {
                var oTableInit = new Object();
                //初始化Table
                oTableInit.Init = function() {
                    $('#table').bootstrapTable({
                        url: '<?php echo $ajax_url; ?>/get_system_type',// 请求后台的URL（ * ）
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
                            field: 'code',
                            title: '系统编号'
                        }, {
                            field: 'name',
                            title: '系统名称'
                        }, {
                            field: 'status',
                            title: '状态'
                        }]
                    });
                };
                //得到查询的参数
                oTableInit.queryParams = function(params) {
                    var temp = { //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
                        limit: params.limit, //页面大小
                        offset: params.offset, //页码
                    };
                    return temp;
                };
                return oTableInit;
            };

            /*
             * 刷新页面
             */
            function refresh(){
                $('#table').bootstrapTable('refresh');
            }
        </script>
//var parent_frame = getParentFrame(parent);
//function getParentFrame(par){
//	if(par.isFrame == 1){
//		return par;
//	}else {
//		getParentFrame(par.parent);
//	}
//}

var parent_frame = parent.parent.parent;


/*
 * 刷新table
 */
function refreshTable(tableid){
    var tableId = tableId ? tableId : '#table';
    if($(tableId).length == 0){
        window.location.reload();
        return;
//  	alert('tableId不正确');
//  	return;
    }
    $(tableId).bootstrapTable('refresh');
}

/*
 * ajax 返回的常用成功方法
 * @param {Object} res ajax返回的数据
 */
function ajaxSuccess(res,closeLayer){
    parent_frame.layer.closeAll('loading');//关闭load
    if(res){
        if(res.sta==1){
            parent_frame.layer.msg(res.msg, {icon:1});
            if(closeLayer) parent_frame.closeCurrentLayer();
            parent_frame.refreshCurrentIframeTable();
        }else if(res.sta==-99){
            window.top.location = window.location;
        }else{
            parent_frame.layer.alert(res.msg, {icon:2});
        }
    }else{
        parent_frame.layer.alert('服务器连接异常2', {icon:5});
    }
}

/*
 * ajax 返回的常用成功方法
 * @param {Object} res ajax返回的数据
 * @param int closeLayer 是否关闭弹窗
 * @param int refresh 刷新的类型 1页面刷新 2刷新表格 0不刷新
 */
function ajaxSuccess2(res, closeLayer, refresh){
    parent_frame.layer.closeAll('loading');//关闭load
    if(res){
        if(res.sta==1){
            parent_frame.layer.msg(res.msg, {icon:1});
            if(closeLayer) parent_frame.closeCurrentLayer();
            if(refresh == 1) parent_frame.refreshCurrentIframe();
            else if(refresh == 2) parent_frame.refreshCurrentIframeTable();
        }else if(res.sta==-99){
            window.top.location = window.location;
        }else{
            parent_frame.layer.alert(res.msg, {icon:2});
        }
    }else{
        parent_frame.layer.alert('服务器连接异常2', {icon:5});
    }
}

/*
 * ajax 返回的常用失败方法
 */
function ajaxError(){
    parent_frame.layer.closeAll('loading');//关闭load
    parent_frame.layer.alert('服务器连接异常', {icon:5});
}

/*
 * initTable 通用的table方法
 * @param {Object} option table的参数
 */
function initTable(option){
    var option = $.extend({
        tableId : '',
        url: '',// 请求后台的URL（ * ）
        method: 'post', //请求方式（*）
        contentType: 'application/x-www-form-urlencoded',
        toolbar: '#toolbar', //工具按钮用哪个容器
        striped: true, //是否显示行间隔色
        cache: false, //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
        pagination: true, //是否显示分页（*）
        sortable: false, //是否启用排序
        silentSort: false, //设置为 false 将在点击分页按钮时，自动记住排序项。仅在 sidePagination设置为 server时生效.
        sidePagination: "server", //分页方式：client客户端分页，server服务端分页（*）
        sortName: '', //定义排序列,通过url方式获取数据填写字段名，否则填写下标
        sortOrder: '', //排序方式
        queryParams: '', //传递参数（*）
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
        uniqueId: 'ID', //每一行的唯一标识，一般为主键列
        showToggle: true, //是否显示详细视图和列表视图的切换按钮
        cardView: false, //是否显示详细视图
        detailView: false, //是否显示父子表
        columns : []
    },option);

    if(option.tableId == ''){
        alert('table缺少id参数');
        return;
    }

    if($(option.tableId).length == 0){
        alert('tableId不正确');
        return;
    }

    if(option.url == ''){
        alert('table缺少url参数');
        return;
    }

    if(option.columns.length == 0){
        alert('table缺少columns参数');
        return;
    }

    $(option.tableId).bootstrapTable(option);
}

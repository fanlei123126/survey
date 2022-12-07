//window.isFrame = 1;
var current_layer_array = [];

/*
 * showLayer 通用的dialog方法
 * @param {Object} option dialog的参数
 */
function showLayer(option, _width, _height){
	var option = $.extend({
		type: 2,
        title: '',
        shade: [0.8, '#393D49'],
        maxmin: true, //开启最大化最小化按钮
        resize: true,
        area: [_width, _height],
        content: '',
        success: function(layero, index){
        	var iframeWin = window[layero.find('iframe')[0]['name']];
        	current_layer_array.push({'index':index,'win':iframeWin});
		},
        cancel: function(index, layero){
        	closeCurrentLayer();
        	return false;
		}
    },option);
    
    if(option.title == ''){
    	alert('layer缺少标题参数');
    	return;
    }
    
    if(option.content == ''){
    	alert('layer缺少url参数');
    	return;
    }
    layer.open(option);
}

/*
 * closeCurrentLayer 关闭index当前layer
 */
function closeCurrentLayer(){
	var current_layer = current_layer_array.pop();
	layer.close(current_layer['index']);
}


/*
 * refreshCurrentIframeTable 刷新当前iframe下table
 */
function refreshCurrentIframeTable(){
	var current_iframe = getCurrentIframe();
	current_iframe.refreshTable();
}

/*
 * refreshCurrentIframe 刷新当前iframe下
 */
function refreshCurrentIframe(){
	var current_iframe = getCurrentIframe();
	current_iframe.location.reload();
}

/*
 * refreshChildIframeTable 刷新当前iframe下的 子iframe的table
 */
function refreshChildIframeTable(iframe_id){
	var current_iframe = getCurrentIframe();
	current_iframe.document.getElementById(iframe_id).contentWindow.refreshTable();
}

/*
 * refreshChildIframe 刷新当前iframe下的 子iframe
 */
function refreshChildIframe(iframe_id, url){
	var current_iframe = getCurrentIframe();
	current_iframe.document.getElementById(iframe_id).src=url; 
}

/*
 * refreshChildChildIframeTable 刷新当前iframe下的 子iframe的table 下的 子iframe的table
 */
function refreshChildChildIframeTable(iframe_id1, iframe_id2){
	var current_iframe = getCurrentIframe();
	current_iframe.document.getElementById(iframe_id1).contentWindow.document.getElementById(iframe_id2).contentWindow.refreshTable();
}

/*
 * refreshChildChildIframe 刷新当前iframe下的 子iframe 下的 子iframe
 */
function refreshChildIChildframe(iframe_id1, iframe_id2, url){
	var current_iframe = getCurrentIframe();
	current_iframe.document.getElementById(iframe_id1).contentWindow.document.getElementById(iframe_id2).src=url; 
}

/*
 * 获得当前的iframe 
 */
function getCurrentIframe(){
	return window[$('iframe.J_iframe:visible')[0]['name']]
}

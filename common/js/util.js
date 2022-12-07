var grandpa = parent.parent.parent.parent.parent;
var $ = jQuery = layui.jquery, //jquery
	laydate = layui.laydate, //日期
	laypage = layui.laypage, //分页
	layer = layui.layer, //弹层
	table = layui.table, //表格
	tree = layui.tree, //树状
	carousel = layui.carousel, //轮播
	upload = layui.upload, //上传
	laytpl = layui.laytpl, //模板
	element = layui.element, //元素操作
	form = layui.form,
	autocomplete = layui.autocomplete;

$.regexMobilePhone = function(num){
	return /^1\d{10}$/i.test(num);
}

$.getUrlParam = function() {
	var url = location.search,theRequest = {};
	if (url.indexOf('?') != -1) {
		var str = url.substr(1);
		strs = str.split('&');
		for(var i = 0; i < strs.length; i ++) {
			theRequest[strs[i].split('=')[0]]=(strs[i].split('=')[1]);
		}
	}
	return theRequest;
}()

$.cookie = function(name, value, options){
    if (typeof value != 'undefined'){
        options = options || {};
        if(value === null){
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString();
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else {
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = $.trim(cookies[i]);
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
}

$.toast = function(msg){
	if(msg){
		grandpa.layer.msg(msg,{
			time: 1000
		})
	}
}

$.alert = function(msg){
	if(msg){
		grandpa.layer.alert(msg, {
			title: '提示'
		})
	}
}

$.loading = {
	show : function(time){
		if(!time) time = 20000;
		grandpa.loadingIndex = grandpa.layer.msg('加载中', {
		    icon: 16,
		    shade: 0.2,
		    time: time
		})
	},
	hide : function(){
		grandpa.layer.close(grandpa.loadingIndex);
	}
}

$.confirm = function(title,sureFunc){
	grandpa.layer.confirm(title, {icon: 3, title:'提示'}, function(index){
		sureFunc();
		grandpa.layer.close(index);
	})
}

$.prompt = function(title,sureFunc){
	grandpa.layer.prompt({
			title: title
	}, function(value, index, elem){
			sureFunc(value);
			grandpa.layer.close(index);
	});
}

$.initAjaxData = function(parameters){
	parameters = $.extend({ajaxArguments:null,callBackAll:null,showLoading:1,loadingTime:0},parameters);
	var ajaxArguments = parameters.ajaxArguments;
	var callBackAll = parameters.callBackAll;
	var showLoading = parameters.showLoading;
	var loadingTime = parameters.loadingTime;
	var dataLength = ajaxArguments.length;
	var dataNow = 0;
	var dataAll = {};
	if(showLoading) $.loading.show(loadingTime);
	$.each(ajaxArguments,function(k,v){
		v = $.extend({
			dataType:'json',
			type:'post',
			success:function(res){
				//console.log(JSON.stringify(res));
				if(-99 == res.code){
				}else if(1 == res.code){
					if(v.name) dataAll[v.name] = res.data;
					if(v.callBack) v.callBack(res.data,res.msg);
				}else{
					if(v.callBackError){
						v.callBackError(res.msg);
					}else{
						$.toast(res.msg);
					}
				}
				if(dataLength == ++dataNow){
					if(showLoading) $.loading.hide();
					if(callBackAll) callBackAll(dataAll);
				}
			},
			error:function(){
				if(showLoading) $.loading.hide();
				$.toast('服务器连接异常');
			}
		},v)
		$.ajax(v);
	})
}

$.popIframe = function(parameters,btns,pageParameters){
	parameters = $.extend({
        type: 2,
        title: '', //不显示标题栏
        skin: 'layui-layer-rim', //加上边框
        area: '400px',
        maxmin: true,
        shade: 0.2,
        btn: [],
        btnAlign: 'r',
        btnSwitch: 1,
        content: '',
        maxmin: true, //开启最大化最小化按钮
		cancel: function(){
			//TODO 取消注释
			//grandpa.layerCloseLast();
		},
		success: function(layero, index){
			grandpa.popArr.push(index);
			if(pageParameters){
				layero.find('iframe')[0].contentWindow['pageParameters'] = pageParameters;
			}
		}
    },parameters);
    if('' == parameters.title){
    	$.toast('缺少标题');
    	return;
    }
    if('' == parameters.content){
    	$.toast('缺少url');
    	return;
    }
    if(0 == parameters.btnSwitch){
    	parameters.btn = '';
    }else{
    	btns = btns || [];
    	btns.push(['取消',function(iframeWin,index){
			grandpa.layerCloseLast();
	    }]);
	    $.each(btns, function(k,v) {
			parameters.btn.push(v[0]);
			parameters[(0 == k ? 'yes' : 'btn'+(k+1))] = function(index, layero){
				var iframeWin = null;
				if(2 == parameters.type){//iframe
					iframeWin = layero.find('iframe')[0].contentWindow;
				}
				v[1](iframeWin,index);
				if('取消' != v[0]) return false;
			}
	    })
    }
	grandpa.layer.open(parameters);
}

$.table = function(parameters,callBack){
	$.loading.show();
	parameters = $.extend({
		elem: '#table',
		toolbar: '#toolbar',
		defaultToolbar: ['filter'],
		size: 'sm',
	    url: '',
	    method: 'post',
	    where: $.form2Json('search_from'),
	    done: function(res, curr, count){
			$.loading.hide();
			if(callBack) callBack();
	    },
	    page: {
	    	limit:20
	    }, //开启分页
	    cols: [],
	    request: {
  			pageName: 'page', //页码的参数名称，默认：page
  			limitName: 'rows' //每页数据量的参数名，默认：limit
		},
		response: {
			statusName: 'code', //数据状态的字段名称，默认：code
			statusCode: 1, //成功的状态码，默认：0
			msgName: 'msg', //状态信息的字段名称，默认：msg
			countName: 'count', //数据总数的字段名称，默认：count
			dataName: 'data' //数据列表的字段名称，默认：data
		}
	},parameters);
	if('' == parameters.elem){
    	$.toast('缺少容器ID');
    	return;
    }
	if('' == parameters.url){
    	$.toast('缺少url');
    	return;
    }
	if(0 == parameters.cols.length){
    	$.toast('缺少cols');
    	return;
    }
	parameters.height = $(parameters.elem).parent().outerHeight();
	table.render(parameters);
}

$.tree = function(parameters){
	parameters = $.extend({
		elem: '', //指定元素
	    target: '', //_blank是否新选项卡打开（比如节点返回href才有效）
	    click: function(item){ //点击节点回调
	    },
	    nodes: '' //节点
	},parameters);
	if('' == parameters.elem){
    	$.toast('缺少容器ID');
    	return;
    }
	if('' == parameters.nodes){
    	$.toast('缺少节点数据');
    	return;
    }
	tree(parameters);
}

$.form2Json = function(formId) {
	var arr = $('#' + formId).serializeArray();
	var _json = {};
	for(var i=0;i<arr.length;i++){
		var k = arr[i]['name'];
		var v = arr[i]['value'];
		if(_json[k]){
			if(Array.isArray(_json[k])){
				_json[k].push(v);
			}else{
				_json[k] += ","+v;
			}
		}else{
			_json[k] = v;
		}
	}
    return _json;
}

$.iframe = {
	arr:[],
	init:function(name){
		var isrepeat = false;
		$.each($.iframe.arr,function(k,v){
	    	if(v == name) isrepeat = true;
	    })
	    if(isrepeat) return;
		$.iframe.arr.push(name);
		$.iframe.initCrumbs();
	},
	add:function(name,url){
		$.iframe.init(name);
		$('.base-layout-content-index').append('<iframe scrolling="auto" frameborder="0" src="'+url+'" width="100%" height="100%" class="iframe-tab" />');
	},
	step:function(index){
		var newarr = [];
		var $iframe = $('.iframe-tab');
		$.each($.iframe.arr,function(k,v){
			if(k <= index){//保留
				newarr.push($.iframe.arr[k]);
			}else{//删除
				$iframe.eq(k).remove();
			}
		})
		$.iframe.arr = newarr;
		$.iframe.initCrumbs();
	},
	initCrumbs:function(){
		var $breadcrumb = $('#breadcrumb');
		if($breadcrumb.length){
			$breadcrumb.html('');
			$.each($.iframe.arr,function(k,v){
		    	var html = '';
		    	if($.iframe.arr.length - 1 == k){
					html += '<a><cite>'+v+'</cite></a>';
		    	}else{
		    		html += '<a href="javascript:$.iframe.step('+k+')">'+v+'</a>';
		    	}
		    	$breadcrumb.append(html);
		    })
			element.render('breadcrumb','breadcrumb');
		}
	}
}

$.limitWordsLength = function(element_id,length){
	$element = $(element_id);
	value = $.trim($element.val())
	$element.on('input',function(){
		if(value.length > length){
			$element.val(value.substr(0,length));
			$.toast('已超过字数限制');
		}
	})
}

$.showTime = function(seconds){
	second = parseInt(seconds);// 秒
    var minute = 0;// 分
    var hour = 0;// 小时
    if(second > 60) {
		minute = parseInt(second/60);
		second = parseInt(second%60);
		if(minute > 60) {
			hour = parseInt(minute/60);
        	minute = parseInt(minute%60);
        }
    }
	var result = parseInt(second)+'秒';
	if(minute > 0) {
		result = parseInt(minute)+'分'+result;
	}
	if(hour > 0) {
		result = parseInt(hour)+'小时'+result;
	}
	return result;
}

$.tableTab = function(data,viewUrl){
	$.each(data.tab,function(k,v){
		element.tabAdd('tableTab', {
			title: v.name,
			content: '<iframe class="iframe-layui-tab" scrolling="auto" frameborder="0" src="" width="100%" height="100%" style="position:relative"></iframe>',
			id: 'tab_'+k
		})
	})
	element.on('tab(tableTab)', function(obj){
		var $iframe = $(obj.elem).find('iframe').eq(obj.index);
		if($iframe.attr('src') == ''){
			$iframe.attr('src',viewUrl+data.tab[obj.index].url);
		}else{
			grandpa.refreshCurrentIframeTable('table');
		}
	})
	element.tabChange('tableTab', 'tab_0');
}
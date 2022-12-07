</head>

<body class="fixed-sidebar full-height-layout gray-bg">
<div class="ibox-content">
    <form class="form-horizontal m-t" id="ff">
        <div class="form-group">
            <label class="col-sm-3 control-label">终端名称：</label>
            <div class="col-sm-8">
                <input id="name" name="name" class="form-control" type="text" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><span class="span-red">*</span>所属机构名称：</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" class="form-control" id="organ_name" name="organ_name" placeholder="请输入公司名称" />
                    <input type="hidden" name="organ_id" id="organ_id" />
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">单位大楼：</label>
            <div class="col-sm-8">
                <select name="organ_build" id="organ_build" class="form-control">
                    <option value="">请选择...</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">放置实验室：</label>
            <div class="col-sm-8">
                <select name="organ_laboratory" id="organ_laboratory" class="form-control">
                    <option value="">请选择...</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">入库时间：</label>
            <div class="col-sm-8">
                <input id="in_time" readonly name="in_time" class="form-control layer-date" type="text" placeholder="点击选择上线日期"  />
                <label class="laydate-icon inline demoicon" onclick="laydate({elem: '#in_time',istime: true, format: 'YYYY-MM-DD'});"></label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">产品序列号：</label>
            <div class="col-sm-8">
                <input id="serial_num" name="serial_num" class="form-control" type="text" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">终端类别：</label>
            <div class="col-sm-8">
                <select name="trminal_type" class="form-control">
                    <option value="">请选择...</option>
                    <?php foreach($terminalTypeList as $trminalType){?>
                    <option value="<?php echo $trminalType['Id'];?>"><?php echo $trminalType['name'];?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">绑定设备：</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" class="form-control" id="deviceName" name="deviceName" placeholder="请输入设备名称" />
                    <input type="hidden" name="deviceId" id="deviceId" />
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">软件版本：</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" class="form-control" id="versionName" name="versionName" placeholder="请输入版本名称" />
                    <input type="hidden" name="versionId" id="versionId" />
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">License：</label>
            <div class="col-sm-8">
                <input id="license" name="license" class="form-control required" type="text" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">终端IP：</label>
            <div class="col-sm-8">
                <input id="ip" name="ip" class="form-control" type="text" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">服务时间：</label>
            <div class="col-sm-8">
                <input placeholder="开始日期" class="form-control layer-date" id="service_start_time" name="service_start_time" />
                <input placeholder="结束日期" class="form-control layer-date" id="service_end_time" name="service_end_time" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">服务状态：</label>
            <div class="col-sm-8">
                <select name="service_status" class="fa-cart-plus form-control">
                    <option value="">请选择...</option>
                    <option value="0">关闭</option>
                    <option value="1">启用</option>
                    <option value="2">维护中</option>
                    <option value="3">报废</option>
                </select>
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
                <button class="btn btn-primary" type="submit">提交</button>
            </div>
        </div>
    </form>
</div>

<?php $this->load->view('common/js')?>
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/layer/laydate/laydate.js"></script>
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/suggest/bootstrap-suggest.min.js"></script>

<script>
    const e = "<i class='fa fa-times-circle'></i> ";
    $("#ff").validate({
        rules: {
            name: {
                required: !0,
                maxlength: 30
            },
            in_time: {
                required: !0
            },
            serial_num: {
                required: !0
            },
            trminal_type: {
                required: !0,
                maxlength: 10
            },
            deviceName: {
                maxlength: 20
            },
            versionName: {
                required: !0
            },
            license: {
                required: !0
            },
            service_start_time: {
                required: !0,
                maxlength: 10
            },
            service_en_time: {
                required: !0,
                maxlength: 10
            },
            organ_id: {
                required: !0
            },
            organ_build: {
                required: !0
            },
            organ_laboratory: {
                required: !0
            }
        },
        messages: {
            name: {
                required: e + "请输入终端名称",
                maxlength: e + "终端名称不能超过10个字"
            },
            in_time: {
                required: e + "请输入入库时间"
            },
            serial_num: {
                required: e + "请输入设备序列号"
            },
            trminal_type: {
                required: e + "请选择终端类别"
            },
            deviceName: {
                maxlength: e + "请选择需要绑定的设备"
            },
            versionName: {
                required: e + "请选择软件版本"
            },
            license: {
                required: e + "请输入终端license"
            },
            service_start_time: {
                required: e + "请选择服务开始时间"
            },
            service_end_time: {
                required: e + "请选择服务截止时间"
            },
            organ_id: {
                required: e + "请选择单位机构"
            },
            organ_build: {
                required: e + "请选择服务截止时间"
            },
            organ_laboratory: {
                required: e + "请选择服务截止时间"
            }
        },
        submitHandler: function(form){
            parent_frame.layer.load();
            $(form).ajaxSubmit({
                type: 'post',
                url: '<?php echo $ajax_url?>/create_terminal',
                dataType: 'json',
                success: function(res){
                    ajaxSuccess(res, 1);
                },
                error: function(){
                    ajaxError();
                }
            })
        }
    });

    var start = {
        elem: '#service_start_time',
        format: 'YYYY/MM/DD',
        min: laydate.now(), //设定最小日期为当前日期
        max: '<?php echo MAXSERVICEDATE;?>', //最大日期
        istime: true,
        istoday: false,
        choose: function (datas) {
            end.min = datas; //开始日选好后，重置结束日的最小日期
            end.start = datas //将结束日的初始值设定为开始日
        }
    };
    var end = {
        elem: '#service_end_time',
        format: 'YYYY/MM/DD',
        min: laydate.now(),
        max: '<?php echo MAXSERVICEDATE;?>',
        istime: true,
        istoday: false,
        choose: function (datas) {
            start.max = datas; //结束日选好后，重置开始日的最大日期
        }
    };
    laydate(start);
    laydate(end);

    var versionBsSuggest = $("#versionName").bsSuggest({
        url: "<?php echo $ajax_url?>/suggest_version",
        effectiveFields: ["name",'code'],
        effectiveFieldsAlias:{name: "版本名称", code:'版本号'},
        idField: "id",
        keyField: "name",
    }).on('onSetSelectValue', function (e, keyword) {
        $('#organId').val(keyword.id);
    });

    var testBsSuggest = $("#organ_name").bsSuggest({
        url: "<?php echo $ajax_url?>/suggest_organ",
        effectiveFields: ["name"],
        effectiveFieldsAlias:{name: "机构名称"},
        idField: "id",
        keyField: "name",
    }).on('onSetSelectValue', function (e, keyword) {
        $('#organ_id').val(keyword.id);
        initOrganBuildList(keyword.id);
        initDeviceList(keyword.id);
    });

    function initOrganBuildList(_organId){
        var _optionBuildHtml = '<option value="">请选择...</option>';
        if(_organId){
            $.ajax({
                type: 'post',
                url: '<?php echo $ajax_url;?>/getOrganBuildByOrganId/'+_organId,
                dataType: 'json',
                async: false,
                success: function(res) {
                    console.log(res);
                    for(var i=0;i<res.list.length;i++){
                        _optionBuildHtml += '<option value="'+res.list[i].Id+'">'+res.list[i].name+'</option>';
                    }
                },
                error: function(){
                    console.log("get_system_ad_img_list error")
                }
            });
        }
        $('#organ_build').empty().html(_optionBuildHtml);
    }

    function initDeviceList(_organId){

        var deviceBsSuggest = $("#deviceName").bsSuggest({
            url: "<?php echo $ajax_url?>/suggest_organ_devicelist/"+_organId,
            effectiveFields: ["name"],
            effectiveFieldsAlias:{name: "设备名称"},
            idField: "id",
            keyField: "name",
        }).on('onSetSelectValue', function (e, keyword) {
            $('#deviceId').val(keyword.id);
        });
    }

    $('#organ_build').on('change', function(){
        var _optionValue = $(this).val();
        if(_optionValue){
            initOrganLaboratoryList(_optionValue);
        }
    });

    function initOrganLaboratoryList(_organBuildId){
        var _optionLabortoryHtml = '<option value="">请选择...</option>';
        if(_organBuildId){
            $.ajax({
                type: 'post',
                async: false,
                url: '<?php echo $ajax_url;?>/getOrganLaboratoryByOrganId/'+_organBuildId,
                dataType: 'json',
                success: function(res) {
                    console.log(res);
                    for(var i=0;i<res.list.length;i++){
                        _optionLabortoryHtml += '<option value="'+res.list[i].Id+'">'+res.list[i].name+'</option>';
                    }
                },
                error: function(){
                    console.log("get_system_ad_img_list error")
                }
            });
        }
        $('#organ_laboratory').empty().html(_optionLabortoryHtml);
    }

</script>
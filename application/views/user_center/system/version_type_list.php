</head>

<body class="fixed-sidebar full-height-layout gray-bg">
<div class="ibox-content">
    <table class="table">
        <thead>
        <tr>
            <th>类别名称</th>
            <th>操作</th>
        </tr>
        <tr>
            <th>
                <input id="versionTypeName" type="text" class="form-control" />
            </th>
            <th>
                <button class="btn btn-primary" type="button" id="btn_add" onclick="create()">新增</button>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($versionTypeList as $versionType){?>
        <tr>
            <td><input id="versionTypeName_<?php echo $versionType['Id'];?>" type="text" class="form-control" value="<?php echo $versionType['name'];?>" /></td>
            <td>
                <button class="btn btn-primary" type="button" id="btn_update" onclick="update('<?php echo $versionType['Id']?>')">修改</button>
                <button class="btn btn-primary" type="button" id="btn_remove" onclick="remove('<?php echo $versionType['Id']?>')">删除</button>
            </td>
        </tr>
        <?php }?>
        </tbody>
    </table>
</div>

<?php $this->load->view('common/js')?>

<script>
    function create()
    {
        var _name = $('#versionTypeName').val();
        if(!_name){
            parent_frame.layer.msg('请填写版本类别名称', {icon:2});
        }else {
            $.ajax({
                type: 'post',
                url: '<?php echo $ajax_url . "/create_version_type/"; ?>',
                data:{'name':_name},
                dataType: 'json',
                success: function (res) {
                    if (res.sta == '1') {
                        parent_frame.layer.msg(res.msg, {icon:1});
                        location.reload();
                    }else{
                        parent_frame.layer.msg(res.msg, {icon:2});
                    }

                },
                error: function () {
                    parent_frame.layer.msg("数据接口访问出错", {icon:2});
                }
            });
        }
    }

    function update(_id){
        if(!_id){
            parent_frame.layer.msg('数据编号获取失败', {icon: 2});
        }else {
            var _name = $('#versionTypeName_' + _id).val();
            if (!_name) {
                parent_frame.layer.msg('请填写版本类别名称', {icon: 2});
            } else {
                $.ajax({
                    type: 'post',
                    url: '<?php echo $ajax_url . "/update_version_type/"; ?>',
                    data: {'id': _id, 'name': _name},
                    dataType: 'json',
                    success: function (res) {
                        if (res.sta == '1') {
                            parent_frame.layer.msg(res.msg, {icon: 1});
                            location.reload();
                        } else {
                            parent_frame.layer.msg(res.msg, {icon: 2});
                        }

                    },
                    error: function () {
                        parent_frame.layer.msg("数据接口访问出错", {icon: 2});
                    }
                });
            }
        }
    }

    function remove(_id){
        if(!_id){
            parent_frame.layer.msg('数据编号获取失败', {icon: 2});
        }else {
            $.ajax({
                type: 'post',
                url: '<?php echo $ajax_url . "/remove_version_type/"; ?>',
                data: {'id': _id},
                dataType: 'json',
                success: function (res) {
                    if (res.sta == '1') {
                        parent_frame.layer.msg(res.msg, {icon: 1});
                        location.reload();
                    } else {
                        parent_frame.layer.msg(res.msg, {icon: 2});
                    }

                },
                error: function () {
                    parent_frame.layer.msg("数据接口访问出错", {icon: 2});
                }
            });
        }
    }

</script>
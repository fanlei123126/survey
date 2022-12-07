<link rel="stylesheet" href="<?php echo FRONT_CSS_URL;?>/hplus/plugins/treeview/bootstrap-treeview.css">
</head>

<body class="fixed-sidebar full-height-layout gray-bg">
<div class="ibox float-e-margins">
    <div class="ibox-content">
        <form class="form-horizontal m-t" id="ff">
            <div class="form-group">
                <div id="treeview" class="treeview"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-12" style="text-align: center;">
                    <input type="hidden" name="role_id" id="role_id" value="<?php echo $role_id;?>" />
                    <button class="btn btn-primary" type="button" id="btn_submit">权限编辑</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('common/js')?>
<script src="<?php echo FRONT_JS_URL;?>/bootstrap-treeview.js"></script>
<script>
    //tree start
    var defaultData = <?php echo json_encode($node_data);?>;

    var node_arr = new Array();
    var $checkableTree = $('#treeview');
    $checkableTree.treeview({
        data: defaultData,
        showIcon: true,
        showCheckbox: true,
        onNodeChecked: nodeChecked,
        onNodeUnchecked: nodeUnchecked,
        onNodeSelected :  function(event, node){
            if(node.state.checked==true){
                nodeUnchecked(event, node);
            }else{
                nodeChecked(event, node);
            }
            $checkableTree.treeview('unselectNode', [ node.nodeId, { silent: true}]);
        }
    });
    //node_arr数组增加默认选中的id
    var all_nodes = $checkableTree.treeview('getEnabled');
    for (var i = 0; i < all_nodes.length; i++) {
        if(all_nodes[i].state.checked==true){
            node_arr.push(all_nodes[i].id);
        }
    }
    //元素选中方法
    function nodeChecked(event, node){
        node_arr.push(node.id);
        $checkableTree.treeview('checkNode', [ node.nodeId, { silent: true}]);
        if(node.nodes){
            for (var i = 0; i < node.nodes.length; i++) {
                var v = node.nodes[i];
                if(v.state.checked==false){
                    node_arr.push(v.id);
                    $checkableTree.treeview('checkNode', [ v.nodeId, { silent: true}]);
                }
            }
        }else if(node.parentId !== undefined){
            var parent_node = $checkableTree.treeview('getParent', node);
            if(parent_node && parent_node.state.checked==false){
                node_arr.push(parent_node.id);
                $checkableTree.treeview('checkNode', [ parent_node, { silent: true}]);
            }
        }
    }
    //取消选中方法
    function nodeUnchecked(event, node){
        node_arr.splice(node_arr.indexOf(node.id), 1);
        $checkableTree.treeview('uncheckNode', [node.nodeId, { silent: true}]);
        if(node.nodes){
            for (var i = 0; i < node.nodes.length; i++) {
                var v = node.nodes[i];
                node_arr.splice(node_arr.indexOf(v.id), 1);
                $checkableTree.treeview('uncheckNode', [v.nodeId, { silent: true}]);
            }
        }else if(node.parentId !== undefined){
            var parent_node = $checkableTree.treeview('getParent', node);
            var allUnchecked = true;
            for (var i = 0; i < parent_node.nodes.length; i++) {
                if(parent_node.nodes[i].state.checked==true){
                    allUnchecked = false;
                    break;
                }
            }
            if(allUnchecked == true){
                node_arr.splice(node_arr.indexOf(parent_node.id), 1);
                $checkableTree.treeview('uncheckNode', [parent_node.nodeId, { silent: true}]);
            }
        }
    }
    //拓展 数组方法  根据数组的值返回index下表
    Array.prototype.indexof = function(value) {
        var _this = this;//为了增加方法扩展适应性。我这稍微修改了下
        for (var i = 0; i < _this.length; i++) {
            if (_this[i] == value)
                return i;
        }
    }
    //tree end


    $('#btn_submit').on('click', function(){
        var _role_id = $('#role_id').val();
        parent_frame.layer.load();
        $.ajax({
            type: 'post',
            url: '<?php echo $ajax_url?>/update_auth/',
            dataType: 'json',
            data: {'node_arr':node_arr,'role_id':_role_id},
            success: function(res) {
                ajaxSuccess(res, true);
            },
            error: function(){
                ajaxError();
            }
        });
    });

</script>

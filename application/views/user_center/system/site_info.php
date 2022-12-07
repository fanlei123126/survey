<div class="ibox-content col-sm-8">
    <form class="form-horizontal m-t" id="ff">
        <div class="form-group">
            <label class="col-sm-3 control-label">网站名称：</label>
            <div class="col-sm-8">
                <input class="form-control" id="site_name" name="site_name" value="<?php echo isset($siteInfo['site_name'])?$siteInfo['site_name']:"";?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">联系地址：</label>
            <div class="col-sm-8">
                <input class="form-control" id="address" name="address" value="<?php echo isset($siteInfo['address'])?$siteInfo['address']:"";?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">ICP备案号：</label>
            <div class="col-sm-8">
                <input class="form-control" id="icp" name="icp" value="<?php echo isset($siteInfo['icp_num'])?$siteInfo['icp_num']:"";?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">版权信息：</label>
            <div class="col-sm-8">
                <input class="form-control" id="copyright" name="copyright" value="<?php echo isset($siteInfo['copyright'])?$siteInfo['copyright']:"";?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">公司名称：</label>
            <div class="col-sm-8">
                <input class="form-control" id="company_name" name="company_name" value="<?php echo isset($siteInfo['company_name'])?$siteInfo['company_name']:"";?>" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12" style="text-align: center;">
                <input type="hidden" id="id" name="id" value="<?php echo isset($siteInfo['site_id'])?$siteInfo['site_id']:"0";?>" />
                <button class="btn btn-primary" type="submit">提交</button>
            </div>
        </div>
    </form>
</div>
<?php $this->load->view('common/js')?>
<script>
    $(function(){
        var e = "<i class='fa fa-times-circle'></i> ";
        $("#ff").validate({
            rules: {
                siteName: {
                    required: !0,
                    maxlength: 15
                },
                address: {
                    required: !0,
                    maxlength: 40
                },
                icp: {
                    required: !0,
                    maxlength: 20
                },
                copyright: {
                    required: !0,
                    maxlength: 20
                },
                companyName: {
                    required: !0,
                    maxlength: 20
                }
            },
            messages: {
                siteName: {
                    required: e + "请输入站点名称",
                    maxlength: e + "站点名称不能超过10个字符"
                },
                address: {
                    required: e + "请输入联系地址",
                    maxlength: e + "联系地址不能超过40个字符"
                },
                icp: {
                    required: e + "请输入ICP备案号",
                    maxlength: e + "联系地址不能超过20个字符"
                },
                copyright: {
                    required: e + "请输入联系地址",
                    maxlength: e + "联系地址不能超过20个字符"
                },
                companyName: {
                  required: e + "请输入公司名称",
                  number: e + "公司名称不能超过20个字符"
                }
            },
            submitHandler: function(form){
                parent_frame.layer.load();
                $(form).ajaxSubmit({
                    type: 'post',
                    url: '<?php echo $ajax_url?>/update_site_info',
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
    });
</script>

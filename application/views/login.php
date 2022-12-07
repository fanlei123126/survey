<link href="<?php echo FRONT_CSS_URL;?>/login.css?v=<?php echo date('YmdHis');?>" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo FRONT_CSS_URL;?>/jquery.coolautosuggest.css?v=<?php echo date('YmdHis');?>" />
<!--[if lt IE 9]>
<meta http-equiv="refresh" content="0;ie.html" />
<![endif]-->
<script>if(window.top !== window.self){ window.top.location = window.location;}</script>
</head>

<body class="signin maxheight">
<div class="signinpanel maxheight">
    <div class="row maxheight">
        <div class="col-sm-8 sign-left-box maxheight">
            <div class="signin-info">
                <div class="index-txt-box ">
                    <img src="<?php echo FRONT_IMG_URL;?>/index-txt.png" />
                </div>
                <div class="index-image-box">
                    <img src="<?php echo FRONT_IMG_URL;?>/earth_bg.png" />
                </div>
            </div>
<!--            <div class="sign-copyright">-->
<!--                CopyRight© 2020-2021 上海塞司鹰科技有限公司 版权所有 沪备ICP-1234567890-->
<!--            </div>-->
        </div>
        <div class="col-sm-4 sign-right-box maxheight">
            <div class="sign-box">
                <form method="post" role="form" id="onLogin" novalidate="novalidate" onsubmit="return login();">
                     <div class="logo">
                        <img src="<?php echo FRONT_IMG_URL;?>/logo.png" />
                    </div>
                    <div class="system-txt"><img src="<?php echo FRONT_IMG_URL;?>/system_txt.png" /></div>
                    <div class="input-box">

                        <div class="input-txt-box flex">
                            <i class="fa fa-user"></i>
                            <input type="text" name="account" value="admin" id="account" class="form-control" placeholder="请输入用户名" autocomplete="off" />
                        </div>
                        <div class="input-txt-box flex">
                            <i class="fa fa-unlock-alt"></i>
                            <input type="password" name="passwd"  value="123456" id="passwd" class="form-control" placeholder="请输入密码" autocomplete="off" />
                        </div>
                    </div>
                    <div class="btn-box">
                        <a href="javascript:;">忘记密码</a>
                        <button class="btn btn-success" type="submit">登录</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

<?php $this->load->view('common/js');?>
<script type="text/javascript" src="<?php echo FRONT_JS_URL;?>/jquery.coolautosuggest.js?v=0.1"></script>
<script>
    function login() {
        const _account = $('#account').val(), _password = $('#passwd').val();
        if (!_account) {
            parent_frame.layer.alert('请输入用户名');
        } else if (!_password) {
            parent_frame.layer.alert('请输入登录密码');
        } else {
            $.ajax({
                type: 'post',
                url: '<?php echo $ajax_url?>/loginOn',
                data: {'account': _account, 'passwd': _password},
                async: true,
                dataType: 'json',
                success: function (res) {
                    if (res.sta == 1) {
                       location.reload();
                     } else {
                        parent_frame.layer.alert(res.msg);
                    }
                }
            });
        }
        return false;
    }


</script>

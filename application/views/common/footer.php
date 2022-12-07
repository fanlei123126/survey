<!-- 自定义js -->
<script src="<?php echo FRONT_JS_URL;?>/hplus/hplus.js?v=4.1.0"></script>
<script type="text/javascript" src="<?php echo FRONT_JS_URL;?>/hplus/contabs.js"></script>
<!-- 自定义js -->
<script src="<?php echo FRONT_JS_URL;?>/hplus/content.js?v=1.0.1"></script>

<!-- 第三方插件 -->
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/pace/pace.min.js"></script>

<?php if(isset($is_login) && !$is_login){ ?>
<!-- ajax登陆跳转js -->
<script src="<?php echo FRONT_JS_URL;?>/redirect.js"></script>
<?php } ?>
</body>

</html>
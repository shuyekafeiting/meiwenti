<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($this_auth["name"]); ?>_煤问题后台管理</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> 
    <link href="/Public/hplus/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/Public/hplus/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/hplus/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/Public/hplus/css/animate.css" rel="stylesheet">
    <link href="/Public/hplus/css/style.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
    主页
    <!-- 全局js -->
    <script src="/Public/hplus/js/jquery.min.js?v=2.1.4"></script>
    <script src="/Public/hplus/js/bootstrap.min.js?v=3.3.6"></script>

    <!-- 自定义js -->
    <script src="/Public/hplus/js/content.js?v=1.0.0"></script>

    <!-- iCheck -->
    <script src="/Public/hplus/js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
			//全选和全不选
			$('.level1_auth').click(function(){
				var checkbox_div=$(this).parent('.checkbox_div');
				var level1_auth=checkbox_div.find('.icheckbox_square-green');
				var level2_auth=checkbox_div.find('.level2_auth');
				 if(level1_auth.hasClass('checked')){
					 	 level2_auth.prop("checked", true); 
					 }else{
						 level2_auth.prop("checked", false);
						 }
			});
			//二级权限联动一级权限
			$('.level2_auth').click(function(){
				var checkbox_div=$(this).parents('.checkbox_div');
				
				var level1_auth=checkbox_div.find('.icheckbox_square-green');
				var level1_auth_value=checkbox_div.find('.level1_auth_value')
				if($(this).is(':checked')){
						if(!level1_auth.hasClass('checked')){
							level1_auth.addClass('checked');
							level1_auth_value.prop("checked", true); 
							}
					}
				})
        });
    </script>
</body>
</html>
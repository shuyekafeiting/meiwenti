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
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo ($this_auth["name"]); ?>
                        <small>&nbsp;&nbsp;添加管理员的角色然后给角色分派权限</small>
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" action="<?php echo U('do_add');?>">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">角色的名字</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control"> 
                                    <span class="help-block m-b-none">如：车主信息管理员，司机管理员</span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">分派权限</label>
                                <div class="col-sm-10">
                                <?php if(is_array($user_auth_level1)): foreach($user_auth_level1 as $key=>$vo1): ?><div class="checkbox_div">
                                    <label style="font-weight:bold" class="checkbox-inline i-checks level1_auth">
                                        <input type="checkbox"  class="level1_auth_value"  name='role_auth[]' value="<?php echo ($vo1["id"]); ?>">
                                        <?php echo ($vo1["name"]); ?></label>
                                        <div class="hr-line-dashed"></div>
                                        <?php if(is_array($user_auth_level2)): foreach($user_auth_level2 as $key=>$vo2): if($vo2['aid'] == $vo1['id']): ?><label style="font-weight:normal">
                                                <input type="checkbox" class="level2_auth" name='role_auth[]' value="<?php echo ($vo2["id"]); ?>">
                                                <?php echo ($vo2["name"]); ?></label>&nbsp;&nbsp;<?php endif; endforeach; endif; ?>
                                	<div class="hr-line-dashed"></div>
                                </div><?php endforeach; endif; ?>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit">保存内容</button>
                                    <button class="btn btn-white" type="submit">取消</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 全局js -->
    <script src="/Public/hplus/js/jquery.min.js?v=2.1.4"></script>
    <script src="/Public/hplus/js/bootstrap.min.js?v=3.3.6"></script>

    <!-- 自定义js -->
    <!--<script src="/Public/hplus/js/content.js?v=1.0.0"></script>-->

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
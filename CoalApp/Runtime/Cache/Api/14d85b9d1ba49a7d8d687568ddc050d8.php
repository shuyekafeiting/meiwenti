<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo ($new["title"]); ?></title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="/./CoalApp/Api/Public/link/css/sm.min.css">
    <link rel="stylesheet" href="/./CoalApp/Api/Public/link/css/sm-extend.min.css">
    <link rel="stylesheet" href="/./CoalApp/Api/Public/link/css/my.css">

  </head>
  <body>
  <!-- page集合的容器，里面放多个平行的.page，其他.page作为内联页面由路由控制展示 -->
    <div class="page-group">
        <!-- 单个page ,第一个.page默认被展示-->
        <div class="page">
            <!-- 这里是页面内容区 -->
            <div class="content" style="margin:0 0.5rem;">
            	<h1 style="font-family:'微软雅黑'; font-size:1rem;"><?php echo ($new["title"]); ?></h1>
                <div class="row" style="margin:0 0.2rem;style="font-size:90%;"">
                  <div class="col-60" style="margin:0;">来源：<?php echo ($new["source"]); ?></div>
                  <div class="col-40" style="text-align:right; margin:0;"><?php echo ($new["creat_time"]); ?></div>
                </div>
                <div class="row" style="margin-top:1rem;"><img src="<?php echo ($new["true_pic"]); ?>"></div>
            	<?php echo (htmlspecialchars_decode($new["body"])); ?>
            </div>
        </div>
    </div>
   
    <script type='text/javascript' src='/./CoalApp/Api/Public/link/js/zepto.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='/./CoalApp/Api/Public/link/js/sm.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='/./CoalApp/Api/Public/link/js/sm-extend.min.js' charset='utf-8'></script>
  </body>
</html>
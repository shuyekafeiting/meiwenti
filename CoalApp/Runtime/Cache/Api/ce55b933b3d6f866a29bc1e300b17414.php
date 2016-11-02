<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($this_auth["name"]); ?>_煤问题后台管理</title>
    <meta name="keywords" content="">
    <meta name="description" content="">


</head>

<body class="gray-bg">
<form action="<?php echo U('upImgs');?>" method="post" enctype="multipart/form-data">
  <table>
  	<tr><td>图片1</td><td><input name="img1" type="file"/></td></tr>
    <tr><td>图片1</td><td><input name="img2" type="file"/></td></tr>
    <tr><td><input type="submit"/></td><td><input type="reset"></td></tr>
  </table>
</form>
</body>
</html>
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
<form action="<?php echo U('test2');?>" method="post" enctype="multipart/form-data">
  <table>
  	<tr><td>图片1</td><td><input name="img1" type="text" /></td></tr>
    <tr><td>图片1</td><td><input name="img2" type="text"/></td></tr>
    <tr><td><input type="submit"/></td><td><input type="reset"></td></tr>
  </table>
</form>
<script src="/Public/hplus/js/jquery.min.js?v=2.1.4"></script>
<script>
	$.ajax({
				async:false,
				cache:false,
				type:"POST",
				dataType:"json",
				url:"<?php echo U('test2');?>",
				data:{'page':1},
				error: function(){
					alert('请求数据失败');
					},
				success:function(data){
					mydata=data;
				}
				})
</script>
</body>
</html>
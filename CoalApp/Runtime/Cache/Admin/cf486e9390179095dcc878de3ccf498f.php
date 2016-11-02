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
    <link href="/Public/hplus/css/style.css?v=4.1.0" rel="stylesheet">
    <link href="/./CoalApp/Admin/Public/css/font.css" rel="stylesheet">
    <link href="/./CoalApp/Admin/Public/css/admin.css" rel="stylesheet">
</head>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo ($this_auth["name"]); ?></h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" action="<?php echo U('editHanddle');?>" onsubmit="return checkform(this)">
                        	<input type="hidden" value="<?php echo ($account["id"]); ?>" name="id"/>
                        	<div class="form-group">
                                <label class="col-sm-2 control-label">注册账号</label>
                                <div class="col-sm-10">
                                    <input type="text" name="acc" value="<?php echo ($account["account"]); ?>" class="form-control"> 
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-10">
                                    <input type="text" name="pas" class="form-control"> 
                                    <span class="help-block m-b-none">输入新密码用来重置密码，不输入则不对密码进行更改</span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否通过审核</label>
                                <div class="col-sm-10">
                                	<label for="is_passed_no" class="control-label">是</label>
                                    <?php if($account['is_passed'] == 1): ?><input type="radio" name="is_passed" id="is_passed_no" class="form" checked value="1">
                                    <?php else: ?>
                                    	<input type="radio" name="is_passed" id="is_passed_no" class="form"  value="1"><?php endif; ?> 
                                    <label for="is_passed_yes" class="control-label">否</label>
                                    <?php if($account['is_passed'] == 1): ?><input type="radio" name="is_passed" id="is_passed_yes"  class="form" value="0">
                                    <?php else: ?>
                                    	<input type="radio" name="is_passed" id="is_passed_yes"  class="form" checked value="0"><?php endif; ?>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否锁定</label>
                                <div class="col-sm-10">
                                	<label for="is_lock_no" class="control-label">否</label>
                                    <?php if($account['is_lock'] == 0): ?><input type="radio" name="is_lock" id="is_lock_no" class="form" checked value="0">
                                    <?php else: ?>
                                    	<input type="radio" name="is_lock" id="is_lock_no" class="form"  value="0"><?php endif; ?> 
                                    <label for="is_lock_yes" class="control-label">是</label>
                                    <?php if($account['is_lock'] == 0): ?><input type="radio" name="is_lock" id="is_lock_yes"  class="form" value="1">
                                    <?php else: ?>
                                    	<input type="radio" name="is_lock" id="is_lock_yes"  class="form" checked value="1"><?php endif; ?>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">姓名</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" value="<?php echo ($driver["name"]); ?>" class="form-control"> 
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">地址</label>
                                <div class="col-sm-2" >
                                    <select class="form-control m-b" id="address_p" name="address_p">
                                    	<?php if(is_array($province)): foreach($province as $key=>$vo): if($vo == $account['add_p']): ?><option value="<?php echo ($vo); ?>" selected><?php echo ($vo); ?></option>
                                            <?php else: ?>
                                            	<option value="<?php echo ($vo); ?>" ><?php echo ($vo); ?></option><?php endif; endforeach; endif; ?>
                                    </select> 
                                </div>
                                <div class="col-sm-2" >
                                    <select class="form-control m-b" id="address_s" style="display:block;" name="address_s">
                                    	<?php if(is_array($shiqu)): foreach($shiqu as $key=>$vo): if($vo == $account['add_s']): ?><option value="<?php echo ($vo); ?>" selected><?php echo ($vo); ?></option>
                                            <?php else: ?>
                                            	<option value="<?php echo ($vo); ?>" ><?php echo ($vo); ?></option><?php endif; endforeach; endif; ?>
                                    </select> 
                                </div>
                                <div class="col-sm-2" >
                                    <select class="form-control m-b" id="address_q" style="display:block;" name="address_q">
                                    	<?php if(is_array($diqu)): foreach($diqu as $key=>$vo): if($vo == $account['add_q']): ?><option value="<?php echo ($vo); ?>" selected><?php echo ($vo); ?></option>
                                            <?php else: ?>
                                            	<option value="<?php echo ($vo); ?>" ><?php echo ($vo); ?></option><?php endif; endforeach; endif; ?>
                                    </select> 
                                </div>
                                <div class="col-sm-2" >
                                    <input type="text" value="<?php echo ($account["add_d"]); ?>" class="form-control m-b" style="display:block;" id="address_d" name="address_d" placeholder="详细地址"/>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">头像</label>
                                <input type="hidden" id="photo_pic" name="photo_pic" value="<?php echo ($account["photo"]); ?>"/>
                                <input type="hidden" id="photo_lit" name="photo_lit" value="<?php echo ($account["photo_lit"]); ?>"/>
                                <?php if($account['photo']): ?><div class="col-sm-10 photo_div" style="display:none">
                                        <i class="iconfont photo_ico" >&#xe608;</i>
                                        <input  name="litpic"  type="file" onChange="ajaxFileUpload(this)" id="photo_file" class="photo_file" />
                                    </div>
                                    <div class="col-sm-10 photo_img_div" style="display:block">
                                     <img class="photo_img" id="photo_img" src="/Public/u_img/<?php echo ($account["photo"]); ?>"> 
                                     <a href="javascript:genghuan()" class="photo_img_del">更换</a> 
                                    </div> 
                                <?php else: ?>
                                    <div class="col-sm-10 photo_div">
                                            <i class="iconfont photo_ico" >&#xe608;</i>
                                            <input  name="litpic"  type="file" onChange="ajaxFileUpload(this)" id="photo_file" class="photo_file" />
                                        </div>
                                        <div class="col-sm-10 photo_img_div" >
                                         <img class="photo_img" id="photo_img" src="/./CoalApp/Admin/Public/images/meikuang.jpg"> 
                                         <a href="javascript:genghuan()" class="photo_img_del">更换</a> 
                                        </div><?php endif; ?>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">身份证</label>
                                <input type="hidden" id="idcard_pic" name="idcard_pic" value="<?php echo ($driver["idcard_pic"]); ?>"/>
                                <input type="hidden" id="idcard_lit" name="idcard_lit"/>
                                <?php if($driver['idcard_pic']): ?><div class="col-sm-10 idcard_div" style="display:none">
                                        <i class="iconfont lic_ico"  >&#xe60a;</i>
                                        <input type="file" name="idcard" onchange="ajaxFileUpload2(this)" id="idcard_file" class="idcard_file" />
                                       
                                    </div>
                                    <div class="col-sm-10 idcard_img_div" style="display:block">
                                     <img class="idcard_img" id="idcard_img" src="/Public/u_img/<?php echo ($driver["idcard_pic"]); ?>"> 
                                     <a href="javascript:genghuan2()" class="lic_img_del">更换</a> 
                                    </div>  
                                 <?php else: ?>
                                 	    <div class="col-sm-10 idcard_div" >
                                        <i class="iconfont lic_ico">&#xe60a;</i>
                                        <input type="file" name="idcard" onchange="ajaxFileUpload2(this)" id="idcard_file" class="idcard_file" />
                                       
                                    </div>
                                    <div class="col-sm-10 idcard_img_div" >
                                     <img class="idcard_img" id="idcard_img" src="/Public/u_img/<?php echo ($driver["idcard_pic"]); ?>"> 
                                     <a href="javascript:genghuan2()" class="lic_img_del">更换</a> 
                                    </div><?php endif; ?> 

                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">身份证号码</label>
                                <div class="col-sm-10">
                                	<input type="text" name="idcard_num" value="<?php echo ($driver["idcard_num"]); ?>" class="form-control"> 
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">驾驶证</label>
                                <input type="hidden" id="lic_pic" name="lic_pic" value="<?php echo ($driver["lic_pic"]); ?>"/>
                                <input type="hidden" id="lic_lit" name="lic_lit"/>
                                <?php if($driver['lic_pic']): ?><div class="col-sm-10 lic_div" style="display:none;">
                                        <i class="iconfont lic_ico"  >&#xe609;</i>
                                        <input type="file" name="licpic" onchange="ajaxFileUpload1(this)" id="lic_file" class="lic_file" />
                                    </div>
                                    <div class="col-sm-10 lic_img_div" style="display:block;">
                                     <img class="lic_img" id="lic_img" src="/Public/u_img/<?php echo ($driver["lic_pic"]); ?>"> 
                                     <a href="javascript:genghuan1()" class="lic_img_del">更换</a> 
                                    </div> 
                                <?php else: ?>
                                	 <div class="col-sm-10 lic_div">
                                        <i class="iconfont lic_ico"  >&#xe609;</i>
                                        <input type="file" name="licpic" onchange="ajaxFileUpload1(this)" id="lic_file" class="lic_file" />
                                    </div>
                                    <div class="col-sm-10 lic_img_div">
                                     <img class="lic_img" id="lic_img" src="/./CoalApp/Admin/Public/images/meikuang.jpg"> 
                                     <a href="javascript:genghuan1()" class="lic_img_del">更换</a> 
                                    </div><?php endif; ?> 
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">驾驶证编号</label>
                                <div class="col-sm-10">
                                	<input type="text" name="lic_number" value="<?php echo ($driver["lic_number"]); ?>" class="form-control"> 
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">驾驶证类型</label>
                                <div class="col-sm-3">
                                	 <select class="form-control m-b" id="address_p" name="lic_level">
                                     		<?php if($driver['lic_level'] == 'A'): ?><option value="A" selected>A</option>
                                            <?php else: ?>
                                            	<option value="A" >A</option><?php endif; ?>
                                            <?php if($driver['lic_level'] == 'B'): ?><option value="B" selected>B</option>
                                            <?php else: ?>
                                            	<option value="B" >B</option><?php endif; ?>
                                            <?php if($driver['lic_level'] == 'C'): ?><option value="C" selected>C</option>
                                            <?php else: ?>
                                            	<option value="C" >C</option><?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group" style="display:none;">
                                <label class="col-sm-2 control-label">地图定位</label>
                                <div class="col-sm-10">
                                   	<input id="gps" type="hidden" name="gps"/>
                                    <input id="address" class="col-sm-5 form-control" value="" name="address"/>
                                    <div class="hr-line-dashed"></div> 
                                    <div id="message" style="font-size:90%; color:#900;"></div>
                                    <div id="container"  class="form-control" style="height:300px;" tabindex="0"></div>
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
    
    <!--自制upload--->
    <script src="/./CoalApp/Admin/Public/js/ajaxfileupload.js"></script>
    <script type="text/javascript">
		//上传头像函数
        function ajaxFileUpload(upfile) {
            $.ajaxFileUpload
            (
                {
                    url: "<?php echo U('Api/Upload/junweiUpImg');?>", //用于文件上传的服务器端请求地址
                    secureuri: false, //是否需要安全协议，一般设置为false
                    fileElementId: $(upfile).attr('id'), //文件上传域的ID
                    dataType: 'json', //返回值类型 一般设置为json
                    success: function (data,status)  //服务器成功响应处理函数
                    {
					   //解析返回的json
					   var data = jQuery.parseJSON(data); 
					   //判断是否上传成功
					   if(data.code==1){
						   $('.photo_img').attr('src',data.true_url);
						   $('#photo_pic').val(data.url);
						   $('#photo_lit').val(data.lit_url);
						   $('.photo_img_div').show();
						   $('.photo_div').hide();
						   }else{
							   alert(data.msg); 
							  }
                    },
                    error: function (data, status, e)//服务器响应失败处理函数
                    {
                        alert(data);
                    }
                }
            )
            return false;
        }
		//上传营业执照
        function ajaxFileUpload1(upfile) {
            $.ajaxFileUpload
            (
                {
                    url: "<?php echo U('Api/Upload/junweiUpImg');?>", //用于文件上传的服务器端请求地址
                    secureuri: false, //是否需要安全协议，一般设置为false
                    fileElementId: $(upfile).attr('id'), //文件上传域的ID
                    dataType: 'json', //返回值类型 一般设置为json
                    success: function (data,status)  //服务器成功响应处理函数
                    {
					   //解析返回的json
					   var data = jQuery.parseJSON(data); 
					   //判断是否上传成功
					   if(data.code==1){
						  
						   $('.lic_img').attr('src',data.true_url);
						   $('#lic_pic').val(data.url);
						   $('#lic_lit').val(data.lit_url);
						   $('.lic_img_div').show();
						   $('.lic_div').hide();
						   }else{
							alert(data.msg); 
							  }
                    },
                    error: function (data, status, e)//服务器响应失败处理函数
                    {
                        alert(data);
                    }
                }
            )
            return false;
        }
		//上传身份证
        function ajaxFileUpload2(upfile) {
            $.ajaxFileUpload
            (
                {
                    url: "<?php echo U('Api/Upload/junweiUpImg');?>", //用于文件上传的服务器端请求地址
                    secureuri: false, //是否需要安全协议，一般设置为false
                    fileElementId: $(upfile).attr('id'), //文件上传域的ID
                    dataType: 'json', //返回值类型 一般设置为json
                    success: function (data,status)  //服务器成功响应处理函数
                    {
					   //解析返回的json
					   var data = jQuery.parseJSON(data); 
					   //判断是否上传成功
					   if(data.code==1){
						  
						   $('.idcard_img').attr('src',data.true_url);
						   $('#idcard_pic').val(data.url);
						   $('#idcard_lit').val(data.lit_url);
						   $('.idcard_img_div').show();
						   $('.idcard_div').hide();
						   }else{
							alert(data.msg); 
							  }
                    },
                    error: function (data, status, e)//服务器响应失败处理函数
                    {
                        alert(data);
                    }
                }
            )
            return false;
        }
		//更换头像函数
		function genghuan(){
			$('.photo_file').remove();
			var htmlstr="<input  name='litpic'  type='file' onChange='ajaxFileUpload(this)' id='photo_file' class='photo_file'/>";
			$('.photo_div').append(htmlstr);
			$('#photo_pic').val('');
			$('#photo_lit').val('');
			$('.photo_img_div').hide();
			$('.photo_div').show();
			}
		//更换驾驶证函数
		function genghuan1(){
			$('.lic_file').remove();
			var htmlstr="<input  name='licpic'  type='file' onChange='ajaxFileUpload1(this)' id='lic_file' class='lic_file'/>";
			$('.lic_div').append(htmlstr);
			$('#lic_pic').val('');
			$('#lic_lit').val('');
			$('.lic_img_div').hide();
			$('.lic_div').show();
			}
		//更换身份证函数
		function genghuan2(){
			$('.idcard_file').remove();
			var htmlstr="<input  name='idcard'  type='file' onChange='ajaxFileUpload2(this)' id='idcard_file' class='idcard_file'/>";
			$('.idcard_div').append(htmlstr);
			$('#idcard_pic').val('');
			$('#idcard_lit').val('');
			$('.idcard_img_div').hide();
			$('.idcard_div').show();
			}
    </script>
    
    <!-- 自定义js -->
    <!--<script src="/Public/hplus/js/content.js?v=1.0.0"></script>-->
     <!---------------------------------高德地图----------------------------->
    <script type="text/javascript" src="https://webapi.amap.com/maps?v=1.3&key=edaad8cbb817ea4af9b8cad5d09ab591"></script> 
    <script>
    	 var map = new AMap.Map('container',{
            resizeEnable: true,
            zoom: 13,
            center: [116.39,39.9]
   		 });
		 
		var gps = document.getElementById('gps');
		var searchinput = document.getElementById('address');
			
   		AMap.plugin('AMap.Geocoder',function(){
			var geocoder = new AMap.Geocoder({
				city: "0"//城市，默认：“全国”
			});
			var marker = new AMap.Marker({
				map:map,
				bubble:true
        	})
			
		//点击地图事件
		map.on('click',function(e){
            marker.setPosition(e.lnglat);
			gps.value=e.lnglat;
			geocoder.getAddress(e.lnglat,function(status,result){
              if(status=='complete'){
                 searchinput.value = result.regeocode.formattedAddress
                 document.getElementById('message').innerHTML  = ''
              }else{
                 document.getElementById('message').innerHTML  = '无法获取地址'
              }
			})
        })
		
		
		//输入框定位事件
		
		
        searchinput.onchange = function(e){
            var address = searchinput.value;
            geocoder.getLocation(address,function(status,result){
              if(status=='complete'&&result.geocodes.length){
                marker.setPosition(result.geocodes[0].location);
                map.setCenter(marker.getPosition());
                document.getElementById('message').innerHTML = '';
				gps.value=marker.getPosition();
              }else{
                document.getElementById('message').innerHTML = '自动定位失败，请更换地址输入，或者手动选择'
              }
            })
        }
		
    });
    </script>
    <script>
		var address='';
    	$(document).ready(function(){
			//获取市区列表
			$('#address_p').change(function(){
					$.post(
							"<?php echo U('Api/Add/getShiqu');?>",
							{province:$('#address_p').val()},
							function(data){
								shiqu=jQuery.parseJSON(data); 
								var html='';
								var shiqu = shiqu.data;
								//循环处理
								$.each(shiqu,function(index,value){
										html=html+"<option value='"+value+"'>"+value+"</option>";
									})
									
								//地图定位
								address=$("#address_p").val();
								searchinput = document.getElementById('address');
								searchinput.value=address;
								searchinput.onchange();
								
								$("#address_q").val('');
								$("#address_d").val('');
								$("#address_s").empty().append(html).show();
								}
							);
				})
			//获取地区列表
			$("#address_s").change(function(){
					$.post(
							"<?php echo U('Api/Add/getDiqu');?>",
							{
								province:$('#address_p').val(),
								shiqu:$("#address_s").val()
							},
							function(data){
								shiqu=jQuery.parseJSON(data); 
								var html='';
								var shiqu = shiqu.data;
								//循环处理
								$.each(shiqu,function(index,value){
										html=html+"<option value='"+value+"'>"+value+"</option>";
									})
								//地图定位
								address=$("#address_p").val()+$("#address_s").val();
								searchinput = document.getElementById('address');
								searchinput.value=address;
								searchinput.onchange();
								
								$("#address_d").val('');
								$("#address_q").empty().append(html).show();
								}
							);
				})
			//显示详细地址输入框
			$("#address_q").change(function(){
					//地图定位
					address=$("#address_p").val()+$("#address_s").val()+$("#address_q").val();
					searchinput = document.getElementById('address');
					searchinput.value=address;
					searchinput.onchange();
				
					$("#address_d").show();
				})
		//详细地址修改相应处理
		 $("#address_d").change(function(){
					//地图定位
					address=$("#address_p").val()+$("#address_s").val()+$("#address_q").val()+$("#address_d").val();
					searchinput = document.getElementById('address');
					searchinput.value=address;
					searchinput.onchange();
				})
       	 });
    </script>
        
    <script type="text/javascript" src="https://webapi.amap.com/demos/js/liteToolbar.js"></script>
  </body>
</body>
</html>
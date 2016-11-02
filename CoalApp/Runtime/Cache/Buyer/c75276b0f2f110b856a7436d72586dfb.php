<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><HTML 
xmlns="http://www.w3.org/1999/xhtml"><HEAD><META content="IE=11.0000" 
http-equiv="X-UA-Compatible">
 
<META http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<TITLE>煤问题后台管理登陆</TITLE> 
<SCRIPT src="/./CoalApp/Admin/Public/js/jquery.min.js" type="text/javascript"></SCRIPT>
<STYLE>
body{
	background: #ebebeb;
	font-family: "Helvetica Neue","Hiragino Sans GB","Microsoft YaHei","\9ED1\4F53",Arial,sans-serif;
	color: #222;
	font-size: 12px;
}
*{padding: 0px;margin: 0px;}
.top_div{
	background: #008ead;
	width: 100%;
	height: 340px;
}
.ipt{
	border: 1px solid #d3d3d3;
	padding: 10px 10px;
	width: 290px;
	border-radius: 4px;
	padding-left: 35px;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
	-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s
}
.ipt:focus{
	border-color: #66afe9;
	outline: 0;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)
}
.u_logo{
	background: url("/./CoalApp/Admin/Public/images/username.png") no-repeat;
	padding: 10px 10px;
	position: absolute;
	top: 43px;
	left: 40px;

}
.p_logo{
	background: url("/./CoalApp/Admin/Public/images/password.png") no-repeat;
	padding: 10px 10px;
	position: absolute;
	top: 12px;
	left: 40px;
}
a{
	text-decoration: none;
}
.tou{
	background: url("/./CoalApp/Admin/Public/images/tou.png") no-repeat;
	width: 97px;
	height: 92px;
	position: absolute;
	top: -87px;
	left: 140px;
}
.left_hand{
	background: url("/./CoalApp/Admin/Public/images/left_hand.png") no-repeat;
	width: 32px;
	height: 37px;
	position: absolute;
	top: -38px;
	left: 150px;
}
.right_hand{
	background: url("/./CoalApp/Admin/Public/images/right_hand.png") no-repeat;
	width: 32px;
	height: 37px;
	position: absolute;
	top: -38px;
	right: -64px;
}
.initial_left_hand{
	background: url("/./CoalApp/Admin/Public/images/hand.png") no-repeat;
	width: 30px;
	height: 20px;
	position: absolute;
	top: -12px;
	left: 100px;
}
.initial_right_hand{
	background: url("/./CoalApp/Admin/Public/images/hand.png") no-repeat;
	width: 30px;
	height: 20px;
	position: absolute;
	top: -12px;
	right: -112px;
}
.left_handing{
	background: url("/./CoalApp/Admin/Public/images/left-handing.png") no-repeat;
	width: 30px;
	height: 20px;
	position: absolute;
	top: -24px;
	left: 139px;
}
.right_handinging{
	background: url("/./CoalApp/Admin/Public/images/right_handing.png") no-repeat;
	width: 30px;
	height: 20px;
	position: absolute;
	top: -21px;
	left: 210px;
}

</STYLE>    
<SCRIPT type="text/javascript">
$(function(){
	//得到焦点
	$("#password").focus(function(){
		$("#left_hand").animate({
			left: "150",
			top: " -38"
		},{step: function(){
			if(parseInt($("#left_hand").css("left"))>140){
				$("#left_hand").attr("class","left_hand");
			}
		}}, 2000);
		$("#right_hand").animate({
			right: "-64",
			top: "-38px"
		},{step: function(){
			if(parseInt($("#right_hand").css("right"))> -70){
				$("#right_hand").attr("class","right_hand");
			}
		}}, 2000);
	});
	//失去焦点
	$("#password").blur(function(){
		$("#left_hand").attr("class","initial_left_hand");
		$("#left_hand").attr("style","left:100px;top:-12px;");
		$("#right_hand").attr("class","initial_right_hand");
		$("#right_hand").attr("style","right:-112px;top:-12px");
	});
	
	
});
//刷新验证码
function refresh(){
	$('#verify_img').attr('src',"<?php echo U('show_verify','','');?>"+"/"+Math.random());
	}
</SCRIPT>
<META name="GENERATOR" content="MSHTML 11.00.9600.17496"></HEAD> 
<BODY>
	<DIV class="top_div">
    	<h1 style="width:100%; text-align:center; font-family:'仿宋'; padding-top:90px; color:#fff;">煤问题_管理登陆</h1>
    </DIV>
	<DIV style="background: rgb(255, 255, 255); margin: -100px auto auto; border: 1px solid rgb(231, 231, 231); border-image: none; width: 400px; height: 230px; text-align: center;">
        <DIV style="width: 165px; height: 96px; position: absolute;">
        <DIV class="tou"></DIV>
        <DIV class="initial_left_hand" id="left_hand"></DIV>
        <DIV class="initial_right_hand" id="right_hand"></DIV></DIV>
        <form method="post" action="<?php echo U('doLogin');?>">
        <P style="padding: 30px 0px 10px; position: relative;">
        <SPAN class="u_logo"></SPAN>        
         <INPUT class="ipt" type="text" name="accoun" placeholder="请输入用户名" value=""> 
         </P>
        <P style="position: relative;">
        <SPAN class="p_logo"></SPAN>         
        <INPUT class="ipt" id="password" type="password" name="pass" placeholder="请输入密码" value="">   
         </P>
         <div style="padding:5px 0 0 2px;">
         <INPUT class="ipt" name="verify" style="display:inline; width:31%; margin-top:0;border-radius: 0px; padding:11px 15px;" id="password" type="text"  placeholder="验证码" value="">
         <img id='verify_img' src="<?php echo U('show_verify');?>" style="width:150px; vertical-align:middle;" />
         
         <a href="javascript:refresh()">
        	 <img style="vertical-align:middle; height:20px;" src="/./CoalApp/Admin/Public/images/fresh.png" />
         </a>
         
          </div>
        <DIV style="height: 50px; line-height: 50px; margin-top: 20px; border-top-color: rgb(231, 231, 231); ">
         
        <P style="margin: 0px 35px 20px 45px;"> 
        
         
          <input type="submit" value="登陆" style="background: rgb(0, 142, 173); padding: 8px 35px; border-radius: 4px; border: 1px solid rgb(26, 117, 152); border-image: none; color: rgb(255, 255, 255); font-weight: bold;" >        
        
        </P>
        </DIV>
        </form>
    </DIV>
</BODY>
</HTML>
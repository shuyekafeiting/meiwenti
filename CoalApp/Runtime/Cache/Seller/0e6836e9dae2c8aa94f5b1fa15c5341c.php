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
<div class="col-sm-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>新增订单 </h5>

        </div>
        <div class="ibox-content">
            <form method="post" class="form-horizontal" action="<?php echo U('addHanddle');?>">

                <div class="form-group">
                    <label class="col-sm-2 control-label">订单号</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" name="oder_id" >
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">采购方</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" name="buyer_id">
                        <label>提示：请填写买方账号，一般为买方的手机号</label>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">提货地点</label>
                    <div class="col-sm-10">
                        <input id="gps" type="hidden" name="gps" value="<?php echo ($gps["x"]); ?> <?php echo ($gps["y"]); ?>"/>
                        <input id="address" class="col-sm-5 form-control" value="" name="address"/>
                        <div class="hr-line-dashed"></div>
                        <div id="message" style="font-size:90%; color:#900;"></div>
                        <div id="container"  class="form-control" style="height:300px;" tabindex="0"></div>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">送货地址</label>
                    <div class="col-sm-10">
                        <input id="gps1" type="hidden" name="gps"/>
                        <input id="address1" class="col-sm-5 form-control" value="" name="address"/>
                        <div class="hr-line-dashed"></div>
                        <div id="message1" style="font-size:90%; color:#900;"></div>
                        <div id="container1"  class="form-control" style="height:300px;" tabindex="0"></div>
                    </div>

                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">煤种</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text"name="coal_type">
                    </div>
                </div>

                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"> 订单量</label>

                    <div class="col-sm-10">
                        <input class="laydate-icn form-control layer-date"id="quantity"name="quantity">
                        <label>吨</label>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"> 含税单价</label>
                    <div class="col-sm-10">
                        <input class="laydate-icn form-control layer-date"id="price"name="price">
                        <label>元/吨</label>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">总金额</label>

                    <div class="col-sm-10">
                        <input class="laydate-icn form-control layer-date"id="total_money"
                               name="total_money" value="">
                        <label>元</label>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">订单日期：</label>
                    <div class="col-sm-10">
                        <input id="hello" class="laydate-icon form-control layer-date"name="create_time">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">保存内容</button>
                        <button class="btn btn-white" type="reset">取消</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


    <!-- 全局js -->
    <script src="/Public/hplus/js/jquery.min.js?v=2.1.4"></script>
    <script src="/Public/hplus/js/bootstrap.min.js?v=3.3.6"></script>



    <!-- iCheck -->
    <script src="/Public/hplus/js/plugins/iCheck/icheck.min.js"></script>
    <script src="/Public/hplus/js/plugins/layer/laydate/laydate.js"></script>
<!---------------------------------高德地图----------------------------->
<script type="text/javascript" src="https://webapi.amap.com/maps?v=1.3&key=edaad8cbb817ea4af9b8cad5d09ab591"></script>
<script>
    // 总金额
        $("#price").change(function(){
            $quantity=$('#quantity').val();
            $price=$('#price').val();
            $('#total_money').val($quantity*$price);

    });
    $("#quantity").change(function(){
        $quantity=$('#quantity').val();
        $price=$('#price').val();
        $('#total_money').val($quantity*$price);

    });

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


    //外部js调用日期

    laydate({
        elem: '#hello', //目标元素。由于laydate.js封装了一个轻量级的选择器引擎，因此elem还允许你传入class、tag但必须按照这种方式 '#id .class'
        event: 'focus' //响应事件。如果没有传入event，则按照默认的click
    });

    //日期范围限制
    var start = {
        elem: '#start',
        format: 'YYYY/MM/DD hh:mm:ss',
        min: laydate.now(), //设定最小日期为当前日期
        max: '2099-06-16 23:59:59', //最大日期
        istime: true,
        istoday: false,
        choose: function (datas) {
            end.min = datas; //开始日选好后，重置结束日的最小日期
            end.start = datas //将结束日的初始值设定为开始日
        }
    };
    var end = {
        elem: '#end',
        format: 'YYYY/MM/DD hh:mm:ss',
        min: laydate.now(),
        max: '2099-06-16 23:59:59',
        istime: true,
        istoday: false,
        choose: function (datas) {
            start.max = datas; //结束日选好后，重置开始日的最大日期
        }
    };
    laydate(start);
    laydate(end);
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
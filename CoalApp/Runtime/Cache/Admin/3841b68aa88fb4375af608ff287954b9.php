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
    <!-- jqgrid-->
    <link href="/Public/hplus/css/plugins/jqgrid/ui.jqgrid.css?0820" rel="stylesheet">
    <link href="/Public/hplus/css/animate.css" rel="stylesheet">
    <link href="/Public/hplus/css/style.css?v=4.1.0" rel="stylesheet">
    <style>
        /* Additional style to fix warning dialog position */
        #alertmod_table_list_2 {
            top: 900px !important;
        }
    </style>

</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox ">  
                    <div class="ibox-content">
                        <div class="jqGrid_wrapper">
                            <table id="table_list_1"></table>
                            <div id="pager_list_1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 全局js -->
    <script src="/Public/hplus/js/jquery.min.js?v=2.1.4"></script>
    <script src="/Public/hplus/js/bootstrap.min.js?v=3.3.6"></script>

    <!-- Peity -->
    <script src="/Public/hplus/js/plugins/peity/jquery.peity.min.js"></script>

    <!-- jqGrid -->
    <script src="/Public/hplus/js/plugins/jqgrid/i18n/grid.locale-cn.js?0820"></script>
    <script src="/Public/hplus/js/plugins/jqgrid/jquery.jqGrid.min.js?0820"></script>

    <!-- 自定义js -->
    <!--<script src="/Public/hplus/js/content.js?v=1.0.0"></script>-->

    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function () {
           $.jgrid.defaults.styleUI = 'Bootstrap';  
            // Configuration for jqGrid Example 1
            $("#table_list_1").jqGrid({
				url:"<?php echo U('ajaxListIspassed');?>",
                data: {},
                datatype: "json",
				mtype: 'POST',
				loadonce: false,
				ajaxGridOptions: { contentType: 'application/json; charset=utf-8'},
				serializeGridData: function (postData) {
                    return JSON.stringify(postData);
                },
                height: 350,
				autowidth: true,
                shrinkToFit: true,
                rowNum: 10,
                rowList: [10, 20, 30],
                colNames: ['ID', '姓名','手机号', '注册日期', '拥有车辆','省','市','区','详细地址', '操作'],
                colModel: [
                    {name: 'id',index: 'id',width: 20,sorttype: "int",align: "center"},
					
					
                    {name: 'name',index: 'name',width: 30,sorttype: "string",formatter: "string",stype:'string',align: "center"},
					{name: 'account',index: 'account',width: 50,sorttype: "int",align: "center"},
                    {name: 'addate',index: 'addate',width: 50,align: "center",sorttype: "date",stype:'date'},
					{name: 'truck_num',index: 'truck_num',width: 30,sorttype: "int",formatter: "int",stype:'int',align: "center"},
					{name: 'add_p',index: 'add_p',width: 30,sorttype: "string",formatter: "string",stype:'string',align: "center"},
					 {name: 'add_s',index: 'add_s',width: 30,sorttype: "string",formatter: "string",stype:'string',align: "center"},
					  {name: 'add_q',index: 'add_q',width: 30,sorttype: "string",formatter: "string",stype:'string',align: "center"},
					  
					 {name: 'add_d',index: 'add_d',width: 30,sorttype: "string",formatter: "string",stype:'string',align: "center"},
                    {name: 'dostr',index: 'dostr',width: 30,align: "center"}
                ],
                pager: "#pager_list_1",
                viewrecords: true,
                caption: "<?php echo ($this_auth["name"]); ?>&nbsp;&nbsp;<a href='<?php echo U('addTrucker');?>' style='align:right'>+新增车主</a>",
                hidegrid: false,
            });
			jQuery("#table_list_1").jqGrid('navGrid', '#pager_list_1', { edit: false, add: false, del: false });
        });
		
		function del(){ 
		if(!confirm("确认要删除？")){ 
		window.event.returnValue = false; 
		} 
		} 	
    </script>

</body>

</html>
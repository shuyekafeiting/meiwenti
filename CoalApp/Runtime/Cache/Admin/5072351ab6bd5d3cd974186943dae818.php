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
				url:"<?php echo U('News/listAjaxPort');?>",
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
                colNames: ['ID', '港口名称', '更新日期', '库存', '增减', '操作'],
                colModel: [
                    {name: 'id',index: 'id',width: 60,sorttype: "int",align: "center"},
					
                    {name: 'name',index: 'name',width: 90,sorttype: "string",formatter: "string",stype:'string',align: "center"},
					
                    {name: 'update_time',index: 'update_time',width: 50,align: "center",sorttype: "date",stype:'date'},
					
                    {name: 'inventory', index: 'inventory',width: 80,align: "center"},
					
                    {name: 'in_out',index: 'in_out',width: 80,align: "left",sorttype: "double",formatter: "double",stype:'double',align: "center" },
					
                    {name: 'dostr',index: 'dostr',width: 80,align: "center"}
                ],
                pager: "#pager_list_1",
                viewrecords: true,
                caption: "<?php echo ($this_auth["name"]); ?>&nbsp;&nbsp;<a href='<?php echo U('addPort');?>' style='align:right'>+新增港口</a>",
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
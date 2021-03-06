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
    <!--编辑器控件-->
    
    <link rel="stylesheet" type="text/css" href="/Public/hplus/css/plugins/webuploader/webuploader.css">
    <link rel="stylesheet" type="text/css" href="/Public/hplus/css/demo/webuploader-demo.css">
    
    
    <link href="/Public/hplus/css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="/Public/hplus/css/plugins/summernote/summernote-bs3.css" rel="stylesheet">
    <link href="/Public/hplus/css/style.css?v=4.1.0" rel="stylesheet">
</head>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo ($this_auth["name"]); ?>
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" action="<?php echo U('editShipHanddle');?>" onsubmit="return checkform(this)">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">起始地</label>
                                <div class="col-sm-10">
                                	<input name="id" type="hidden" value="<?php echo ($data["id"]); ?>"/>
                                    <input type="text" name="from" value="<?php echo ($data["from"]); ?>" class="form-control"> 
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">结束地</label>
                                <div class="col-sm-10">
                                    <input type="text" name="to" value="<?php echo ($data["to"]); ?>" class="form-control"> 
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">港口图片</label>
                                <input type="hidden" name="picture" value="<?php echo ($data["picture"]); ?>"/>
                                <input type="hidden" name="litpic" value="<?php echo ($data["litpic"]); ?>"/>
                                <div class="col-sm-10">
                                   <div id="uploader"  class="wu-example">
                                                        <div class="queueList">
                                                            <div id="dndArea" style="padding-top:10px; min-height:80px;" class="placeholder">
                                                            	<img src="<?php echo ($data["true_pic"]); ?>" id='true_pic'/>
                                                                <div id="filePicker"></div>
                                                            </div>
                                                        </div>
                                                        <div class="statusBar" style="display:none;">
                                                            <div class="progress">
                                                                <span class="text">0%</span>
                                                                <span class="percentage"></span>
                                                            </div>
                                                            <div class="info"></div>
                                                            <div class="btns">
                                                                <div id="filePicker2"></div>
                                                                <div class="uploadBtn">开始上传</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">船型</label>
                                <div class="col-sm-10">
                                	<input type="text" name="ship" value="<?php echo ($data["ship"]); ?>" class="form-control"/> 
                                    <span class="help-block m-b-none">例如:3-4万吨</span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">运费</label>
                                <div class="col-sm-10">
                                	<input type="text" name="price" value="<?php echo ($data["price"]); ?>" class="form-control"/> 
                                    <span class="help-block m-b-none">元/吨</span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">变化</label>
                                <div class="col-sm-10">
                                	<input type="text" name="in_out" value="<?php echo ($data["in_out"]); ?>" class="form-control"/> 
                                    <span class="help-block m-b-none">元/吨</span>
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

    
    <!-- SUMMERNOTE -->
    <script src="/Public/hplus/js/plugins/summernote/summernote.min.js"></script>
    <script src="/Public/hplus/js/plugins/summernote/summernote-zh-CN.js"></script>
    <script>
		//编辑器
        $(document).ready(function () {
            $('.summernote').summernote({
                lang: 'zh-CN',
				onImageUpload: function(files, editor, welEditable) {
                        sendFile(files[0],editor,welEditable);
                },
				edit:edit,
            });
			
        });
        var edit = function () {
            $("#eg").addClass("no-padding");
            $('.click2edit').summernote({
                lang: 'zh-CN',
                focus: true
            });
        };
        var save = function () {
            $("#eg").removeClass("no-padding");
            var aHTML = $('.click2edit').code(); //save HTML If you need(aHTML: array).
            $('.click2edit').destroy();
        };
		//表单提交
		function checkform(obj)
		{
		$('#bodytext').val($('.summernote').code());    
		}
		//编辑器图片上传
		 function sendFile(file,editor,welEditable) {
                data = new FormData();
                data.append("file", file);
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "<?php echo U('Api/Upload/editorUpImg');?>",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        editor.insertImage(welEditable, data);
                    }
                });
            }
    </script>
    <!--上传-->
  <!-- Web Uploader -->
    <script type="text/javascript">
		var UP_URL="<?php echo U('Api/Upload/baiduUpImg');?>";
        // 添加全局站点信息
        var BASE_URL = 'js/plugins/webuploader';
    </script>
    <script src="/Public/hplus/js/plugins/webuploader/webuploader.min.js"></script>

    <script src="/Public/hplus/js/demo/webuploader-demo.js"></script>
</body>
</html>
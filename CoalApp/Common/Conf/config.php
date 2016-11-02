<?php
return array(
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '192.168.0.201', // 服务器地址
    'DB_NAME'   => 'coal', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '123456', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'coal_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
    'CODE_KEY'  =>  'coal',//加密key
    'TMPL_FILE_DEPR'=>'_',//模板连接符
    'UP_LOAD_DIR' => "./".__ROOT__.'/Public/u_img/',//上传图片路径
    'OUT_LOAD_DIR' => __ROOT__.'/Public/u_img/',//图片输出路径
    'HTTP_TYPE' => 'https://',//http协议类型，用于API输出图像
    //'TMPL_EXCEPTION_FILE' => APP_PATH.'/Public/exception.tpl',//异常模板
);
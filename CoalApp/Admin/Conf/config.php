<?php
return array(
	//'配置项'=>'配置值'
    //模板相关配置项
    'TMPL_PARSE_STRING'=>array(
        '__IPUBLIC__' =>"/".APP_PATH."Admin/Public",
        '__HPLUS__' =>"/Public/hplus",
    ),
    //后台默认开启的操作
    'DEFAULT_URL'=>array(
        0=>'Index_index',
        1=>'Index_main',
    ),
);
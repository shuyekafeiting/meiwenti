<?php
namespace Api\Controller;
use Think\Controller;
class MesController extends ApiController {
    public function regSendCode(){
        if(!I('tel_num')){
            $ret['code']=0;
            $ret['message']='必要参数不能为空';
            die(json_encode($ret));
        }
        //生产随机码
        $ver_num=rand_num(6);
        session('ver_num',$ver_num);
        $phone_num=I('tel_num');
        //引入类库
        vendor('TopSdk.TopSdk');
        //公共请求参数参数
        $c = new \TopClient;
        $c ->appkey = '23469693';
        $c ->secretKey = '43a0d5c38aed37f034fe2566cbf6fa19';
        $c->format='json';
        //请求参数
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req ->setExtend( "1" );
        $req ->setSmsType( "normal" );
        $req ->setSmsFreeSignName( "煤问题" );
        $req ->setSmsParam( "{code:'".$ver_num."',product:'煤问题'}" );
        $req ->setRecNum( $phone_num );
        $req ->setSmsTemplateCode( "SMS_16750451" );
        $resp = $c ->execute( $req );
        //结果处理
        $res=object_array($resp);
        if($res['result']['success']){
            $ret['code']=1;
            $ret['message']='发送成功';
            $ret['ver_num']=session('ver_num');
            die(json_encode($ret));
        }else{
            $ret['code']=0;
            $ret['message']='发送失败';
            die(json_encode($ret));
        }
    }
}
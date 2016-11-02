<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/30
 * Time: 13:44
 * 车主控制器
 */
namespace Api\Controller;
use Think\Controller;
class DriverController extends ApiController {
  public function _initialize()
  {
      //判断必须参数是否为空
      if(!I('acc')){
          $ret['code']=0;
          $ret['message']='必要参数不能为空';
          die(json_encode($ret));
      }
      //判断账号是否统一并且为司机类型账号

      if(I('acc')!=session('account')||session('type')!=4){
          $ret['code']=2;
          $ret['message']='传递的账号错误';
          die(json_encode($ret));
      }
  }

    /*******************************************车主主页*********************************************/
    public function index(){

    }
    /*******************************************生成绑定二维码*********************************************/
    public function showCode(){
        //获取账号内容
        $acc=M('account')->where(array('account'=>I('acc')))->field('account,id')->find();
        $driver=M('driver')->where(array('aid'=>$acc['id']))->field('name,lic_number,lic_level')->find();
        $data=array_merge($acc,$driver);
        //dump($data);
        $data=json_encode($data);
        //加密
        $key=rand_num(4,"ALL");
        vendor('Phpqrcode.Phpqrcode');
        $value = $key.encrypt($data,$key); //二维码内容
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 5;//生成图片大小
        //生成二维码图片
        \QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize, 0);
    }

}
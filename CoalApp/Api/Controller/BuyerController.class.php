<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/30
 * Time: 13:44
 * 买家控制器
 */
namespace Api\Controller;
use Think\Controller;
class BuyerController extends Controller {
    public function _initialize()
    {
        //判断必须参数是否为空
        if(!I('acc')){
            $ret['code']=0;
            $ret['message']='必要参数不能为空';
            die(json_encode($ret));
        }
        //判断账号是否统一并且为买家类型账号
        if(I('acc')!=session('account')||session('type')!=0){
            $ret['code']=2;
            $ret['message']='账号类型错误';
            die(json_encode($ret));
        }
        //判断是否已经通过审核
        $account=M('account')->where(array('account'=>session('account')))->find();
        if($account['is_passed']==0){
            $ret['code']=3;
            $ret['message']='该账户还未通过审核';
            die(json_encode($ret));
        }
    }
    /********************************************注册*********************************************/
    public function reg()
    {
        //判断是否为空
        if(!I('acc')||!I('pas')||!I('name')||!I('add')||!I('ver_num')){
            $ret['code']=0;
            $ret['message']='必要字段不能为空';
            die(json_encode($ret));
        }
        //判断手机验证码是否正确
        if(I('ver_num')!==session('ver_num')){
            $ret['code']=4;
            $ret['message']='手机验证码不正确';
            die(json_encode($ret));
        }
        //判断账号是否已经注册
        $res=M('account')->where(array('account'=>I('acc')))->find();
        if($res){
            $ret['code']=2;
            $ret['message']='账号已经存在';
            die(json_encode($ret));
        }
        //接收数据准备写入数据库
        $data1['account']=I('acc');
        $data1['password']=authcode(I('pas'),C('CODE_KEY'));
        $data1['type']=0;
        $data1['addate']=date('Y-m-d');
        $data1['gps']=NULL;
        $data2['name']=I('name');
        $data2['address']=I('add');
        $data2['lic_number']=I('lic_n');

        M()->startTrans();//开启事务
        //添加主表
        $res1=M('account')->add($data1);
        //添加坐标
        if(I('gps_x')&&I('gps_y')){
            $gps_x=I('gps_x');
            $gps_y=I('gps_y');
            $gps_str=$gps_x." ".$gps_y;
            $query="update coal_account set gps=ST_GeomFromText('POINT($gps_str)') where (id=$res1)";
            $res3=M()->execute($query);
        }else{
            $res3=1;
        }
        $data2['aid']=$res1;
        //添加附表
        $res2=M('buyer')->add($data2);
        if(!empty($res1)&&!empty($res2)&&!empty($res3)){
            //成功执行事务
           M()->commit();
           $ret['code']=1;
           $ret['message']='success';
           die(json_encode($ret));
        }else{
           M()->rollback();
           $ret['code']=3;
           $ret['message']='fail';
           die(json_encode($ret));
        }
    }
    /*******************************************获取账号详情*********************************************/
    public function getDetail(){
        //判断必须参数是否为空
        if(!I('acc')){
            $ret['code']=0;
            $ret['message']='必要参数不能为空';
            die(json_encode($ret));
        }
        //判断账号是否统一
        if(I('acc')!=session('account')){
            $ret['code']=2;
            $ret['message']='传递的账号错误';
            die(json_encode($ret));
        }
        //获取内容
        $res=M('buyer')->where(array('aid'=>session('aid')))->find();
        $ret['name']=$res['name'];
        $ret['address']=$res['address'];
        $ret['lic_number']=$res['lic_number'];
        die(json_encode($ret));
    }
    /******************************************获取供货信息详情*********************************************/
    public function sellerNewDetail(){
        if(!I('id')){
            $ret['code']=4;
            $ret['message']='必要字段为空';
            die(json_encode($ret));
        }
        $new=M('seller_news')->where(array('id'=>I('id')))->find();
        if($new){
            $new['address']=$new['del_address_p'].'-'.$new['del_address_s'];
            //获取卖方信息
            $seller=M('seller')->where(array('aid'=>$new['seller_id']))->field('name')->find();
            $seller_tel=M('account')->where(array('id'=>$new['seller_id']))->field('account')->find();
            $new['seller']=$seller['name'];
            $new['seller_tel']=$seller_tel['account'];
            $ret['code']=1;
            $ret['message']='返回成功';
            $ret['detail']=$new;
            echo json_encode($ret);
        }else{
            $ret['code']=5;
            $ret['message']='数据为空';
            die(json_encode($ret));
        }

    }
    /******************************************发布供货信息购买意向*********************************************/
    public function intentionBuy(){
        if(!I('seller_new_id')||!I('quantity')||!I('del_time')){
            $ret['code']=4;
            $ret['message']='必要字段为空';
            die(json_encode($ret));
        }
        $data['seller_new_id']=I('seller_new_id');
        $data['buyer_id']=session('aid');
        $data['quantity']=I('quantity');
        $data['del_time']=I('del_time');
        if(I('buyer_tel')){
            $data['buyer_tel']=I('buyer_tel');
        }else{
            $data['buyer_tel']=session('account');
        }
        if(I('buyer_name')){
            $data['buyer_name']=I('buyer_name');
        }else{
            $buyer=M('buyer')->where(array('aid'=>session('aid')))->find();
            $data['buyer_name']=$buyer['name'];
        }
        if(M('buy_intention')->add($data)){
            $ret['code']=1;
            $ret['message']='提交成功';
            //发送短信
            $mes=send_message('18682371812');
            if(!$mes){
                $ret['code']=1;
                $ret['message']='信息已经提交，但是提醒短信没有发送成功';
            }
            echo json_encode($ret);
        }else{
            $ret['code']=5;
            $ret['message']='提交失败';
            echo json_encode($ret);
        }
    }
    /**********************************************需求信息发布***********************************************/
    public function releaseBuyNews(){
        if(!I('coal_type')||!I('dwrz')||!I('kgjhff')||!I('sdjlf')||!I('qsf')){
            $ret['code']=4;
            $ret['message']='必要参数不能为空';
            die(json_encode($ret));
        }
        $data=I('post.');
        $data['buyler_id']=session('aid');
        $data['creat_time']=date('Y-m_d');
        //解析地址
        $address=explode('-',I('del_address'));
        $data['del_address_p']=$address[0];
        $data['del_address_s']=$address[1];
        //写入数据库
        if(M('buy_news')->add($data)){
            $ret['code']=1;
            $ret['message']='发布成功';
            die(json_encode($ret));
        }else{
            $ret['code']=5;
            $ret['message']='发布失败';
            die(json_encode($ret));
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/30
 * Time: 13:44
 * 卖家控制器
 */
namespace Api\Controller;
use Think\Controller;
class SellerController extends Controller {
    public function _initialize()
    {
        //判断必须参数是否为空
        if(!I('acc')){
            $ret['code']=0;
            $ret['message']='必要参数不能为空';
            die(json_encode($ret));
        }
        //判断账号是否统一并且为卖方类型账号
        if(I('acc')!=session('account')||session('type')!=1){
            $ret['code']=2;
            $ret['message']='传递的账号错误';
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

    /*******************************************发布供应信息*********************************************/
    public function releaseSellerNews(){
        //判断必要字段
        if(!I('coal_type')||!I('dwrz')||!I('kgjhff')||!I('sdjlf')||!I('qsf')||!I('place')){
            $ret['code']=4;
            $ret['message']='必要参数不能为空';
            die(json_encode($ret));
        }
        $data=I('post.');
        $data['seller_id']=session('aid');
        $data['creat_time']=date('Y-m_d');
        //解析地址
        $address=explode('-',I('del_address'));
        $data['del_address_p']=$address[0];
        $data['del_address_s']=$address[1];
        //写入数据库
        if(M('seller_news')->add($data)){
            $ret['code']=1;
            $ret['message']='发布成功';
            die(json_encode($ret));
        }else{
            $ret['code']=5;
            $ret['message']='发布失败';
            die(json_encode($ret));
        }
    }
    /******************************************获取需求信息详情*********************************************/
    public function buyNewDetail(){
        if(!I('id')){
            $ret['code']=4;
            $ret['message']='必要字段为空';
            die(json_encode($ret));
        }
        $new=M('buy_news')->where(array('id'=>I('id')))->find();

        if($new){
            $new['address']=$new['del_address_p'].'-'.$new['del_address_s'];
            //获取购买方信息
            $buyer=M('buyer')->where(array('aid'=>$new['buyer_id']))->field('name')->find();
            $buyer_tel=M('account')->where(array('id'=>$new['buyer_id']))->field('account')->find();
            $new['buyer']=$buyer['name'];
            $new['buyer_tel']=$buyer_tel['account'];
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
    /******************************************针对需求信息发布报价*********************************************/
    public function intentionSeller(){
        if(!I('coal_type')||!I('dwrz')||!I('kgjhff')||!I('qsf')||!I('place')||!I('sdjlf')||!I('offer_id')){
            $ret['code']=4;
            $ret['message']='必要字段为空';
            die(json_encode($ret));
        }
        $data['offer_id']=I('offer_id');
        $data['coal_type']=I('coal_type');
        $data['dwrz']=I('dwrz');
        $data['kgjhff']=I('kgjhff');
        $data['qsf']=I('qsf');
        $data['place']=I('place');
        $data['sdjlf']=I('sdjlf');
        $data['seller_id']=session('aid');
        $data['quantity']=I('quantity');
        $data['sdjhff']=I('sdjhff');
        $data['kgjlf']=I('kgjlf');
        $data['nsf']=I('nsf');
        $data['hf']=I('hf');
        $data['gdt']=I('gdt');
        $data['the_g']=I('the_g');
        $data['the_y']=I('the_y');
        $data['hrd']=I('hrd');
        $data['hskm']=I('hskm');
        $data['kld']=I('kld');
        $data['from']=I('from');
        $data['to']=I('to');
        $data['del_price']=I('del_price');
        $data['del_time_from']=I('del_time_from');
        $data['del_time_to']=I('del_time_to');
        $data['nodes']=I('nodes');
        if(M('offer_new')->add($data)){
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
}
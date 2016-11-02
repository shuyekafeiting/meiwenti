<?php
namespace Buyer\Controller;
use Think\Controller;
class OrderController extends IndexController{

    /**********************************************添加订单************************************************************/
    public function order(){
        //获取gps_x
        $sql="select ST_X(gps) as x,ST_Y(gps) as y from coal_account WHERE (account=".session('user.name').")";
        $gps=M("account")->query($sql);
        $gps=$gps[0];
        $this->assign('gps',$gps);
        $this->display();
    }
    /**********************************************添加处理************************************************************/
    public function addHanddle(){
        if(!I('buyer_id')||!I('coal_type')||!I('quantity')||!I('price')||!I('create_time')||!I('total_money')){
           $this->error('输入信息不完整，请检查');
        }
        $data=I('post.');
        $data['seller_id']=session('user.name');
        $data['creat_time']=I('creat_time')?I("creat_time"):date('Y-m-d');
        $data['order_id']=I('order_id')?I('order_id'):'MWT'.session('user.name').'T'.time();

        //验证订单号是否已经存在
        $rs=M("orders")->where(array("order_id"=>I('order_id')))->count();
        if($rs){
           $this->error('订单号重复');

        }else{
            //验证输入的买方账号是否存在
            $seller_id=M("orders")->where(array("buyer_id"=>I('buyer_id')))->count();
           if($seller_id){
               $res=M('orders')->add($data);
               if($res){
                   $this->success('添加成功，等待对方进行确认');
               }else{
                   $this->error('添加失败，请稍后再试一下');
               }

           } else{
               $this->error('买方的账号不存在，请确认后再试');
           }

        }


    }

    //待确认的订单
     function confirm(){
         $this->display();
    }

}
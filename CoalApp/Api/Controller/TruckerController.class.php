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
class TruckerController extends ApiController {
  public function _initialize()
  {
      //判断必须参数是否为空
      if(!I('acc')){
          $ret['code']=0;
          $ret['message']='必要参数不能为空';
          die(json_encode($ret));
      }
      //判断账号是否统一并且为车主类型账号

      if(I('acc')!=session('account')||session('type')!=3){
          $ret['code']=2;
          $ret['message']='传递的账号错误';
          die(json_encode($ret));
      }
  }

    /*******************************************车主主页*********************************************/
    public function index(){
        //获取内容
        $res=M('trucker')->where(array('aid'=>session('aid')))->find();
        $aid=session('aid');
        //基本信息
        $ret['name']=$res['name'];
        //已拉货信息
        $ret['freight ']=M('bill')->where(array('trucker_id'=>$aid,'state'=>3))->count();
        //正在进行的提煤单
        $ret['bill ']=M('bill')->where("trucker_id=$aid and state !=3 and type = 0")->count();
        //正在进行的社会化招标提煤单
        $ret['social_bill ']=M('bill')->where("trucker_id=$aid and state !=3 and type=1")->count();
        //获取拥有车辆信息
        $truck=M('truck')->field('lic_number,is_passed')->where(array('owner_id'=>$aid))->select();
        $ret['truck']=$truck;
        $ret['truck_num']=count($truck);
        //合作的物流公司
        $company=M('company_trucker')->where(array('trucker_id'=>$aid,'status'=>1))->count();
        //是否有待确认的合作
        $new_company=M('company_trucker')->where(array('trucker_id'=>$aid,'status'=>2))->find();
        if($new_company){
            $ret['new_company']=1;
        }else{
            $ret['new_company']=0;
        }
        $ret['company']=$company;
        //拥有的提煤单信息
        dump($ret);
    }
    /*******************************************增加车辆*********************************************/
    public function addTruck(){
        //判断提交数据是否为空
        if(!I('lic_number')||!I('lic_pic')||!I('owner_name')||!I('maximum')||!I('lic_date')){
            $ret['code']=3;
            $ret['message']='必要字段为空';
            die(json_encode($ret));
        }
        $user=M('account')->where(array('account'=>I('acc')))->find();
        $data['owner_id']=$user['id'];
        //判断操作者是不是车辆拥有者
        $user=M('trucker')->where(array('aid'=>$user['id']))->find();
        if($user['name']==I('owner_name')){
            $data['owner_name']=I('owner_name');
        }else{
            $ret['code']=4;
            $ret['message']='车主信息核对失败';
            die(json_encode($ret));
        }
        $data['maximum']=I('maximum');
        $data['lic_pic']=I('lic_pic');
        $data['lic_number']=I('lic_number');
        //判断车辆是否已经添加过
        if(M('truck')->where(array('lic_number'=>I('lic_number')))->find()){
            $ret['code']=5;
            $ret['message']='车辆已经添加';
            die(json_encode($ret));
        }
        $data['lic_date']=I('lic_date');

        $data['ins_date']=I('ins_date')?I('ins_date'):null;
        $data['picture']=I('picture')?I('picture'):null;
        $data['comment']=I('comment')?I('comment'):null;
        $data['model']=I('model')?I('model'):null;
        $res=M('truck')->add($data);
        if($res){
            $ret['code']=1;
            $ret['message']='添加成功';
            die(json_encode($ret));
        }else{
            $ret['code']=5;
            $ret['message']='添加失败';
            die(json_encode($ret));
        }
    }
    /*******************************************绑定司机扫描二维码*********************************************/
    public function readCode(){
        if(!I('code')){
            $ret['code']='3';
            $ret['message']='二维码信息有误';
            die(json_encode($ret));
        }
        //截取密钥
        $key=substr(I('code'),0,4);
        $data=substr(I('code'),4);
        $ret['detail']=json_decode(decrypt($data,$key));
        $ret['code']=1;
        $ret['messag']='正确获取信息';
        die(json_encode($ret));
    }
    /*******************************************绑定司机操作********************************************/
    public function bandDriver()
    {
        if(!I('truck_id')||!I('driver_id')){
            $ret['code']=3;
            $ret['message']="必要字段为空";
            die(json_encode($ret));
        }
        //判断给的司机信息是否正确
        $driver=M('driver')->where(array('aid'=>I('driver_id')))->find();
        if(!$driver){
            $ret['code']=4;
            $ret['message']="司机信息不对";
            die(json_encode($ret));
        }elseif($driver['tru_id']||$driver['trucker_id']){
            $ret['code']=5;
            $ret['message']="该司机已经绑定有车辆";
            die(json_encode($ret));
        }
        //判断给的车辆信息是否正确
        $truck=M('truck')->where(array('id'=>I('truck_id')))->find();
        $acc=M('account')->where(array('account'=>session('account')))->find();
        if(!$truck||!$acc||$truck['owner_id']!=$acc['id']){
            $ret['code']=6;
            $ret['message']="车辆信息不对";
            die(json_encode($ret));
        }
        $data['aid']=I('driver_id');
        $data['tru_id']=I('truck_id');
        $data['trucker_id']=$acc['id'];
        if(M('driver')->where(array('aid'=>$data['aid']))->save($data)){
            $ret['code']=1;
            $ret['message']="绑定成功";
            die(json_encode($ret));
        }else{
            $ret['code']=7;
            $ret['message']="绑定失败";
            die(json_encode($ret));
        }
    }
}
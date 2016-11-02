<?php
namespace Admin\Controller;
use Think\Controller;
class BuyerController extends IndexController {
    /***********************************************买家列表***********************************************************/
    public function listSeller()
    {
        $this->display();
    }
    /********************************************买家列表Ajax数据调用**************************************************/
    public function ajaxList()
    {
        //定义模型
        $account=D('Account');

        $pagesize=10;
        //获取前端数据：因为jqgride用的是request payload提交故用一下方式接收
        $data=file_get_contents( "php://input");
        $data = json_decode($data);
        $data = object_to_array($data);
        //当前页面
        if($data['_search']==false){
            //不是搜索的处理
            //获得总数据
            $ret['records']=$account->relation('Buyer')->where(array('type'=>0,'is_passed'=>1))->count();//总条数
            $ret['total']=ceil($ret['records']/$pagesize);
            //获取当前页
            $page=$data['page'];
            $limit=$pagesize*($page-1).",".$pagesize;
            $ret['rows']=$account->relation('Buyer')->where(array('type'=>0,'is_passed'=>1))->order(array('id','addate'=>'desc'))->field('id,account,password,type,is_passed,addate,add_p,add_s,add_q,add_d')->limit($limit)->select();
            foreach ($ret['rows'] as $key=>$value){
                $ret['rows'][$key]['dostr']="<a href='".U('del',array('id'=>$ret['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editBuyer',array('id'=>$ret['rows'][$key]['id']))."'>编辑</a>";
            }
            $ret['page']=$page;
            echo json_encode($ret);
        }else{
            //接收搜索条件
            $searc_field=$data['searchField'];//搜索字段
            $searc_oper=$data['searchOper'];//搜索条件
            $searc_string=$data['searchString'];//搜索内容
            //组织条件str
            switch ($searc_oper){
                case eq:$map="type =0 and is_passed=1 and $searc_field = '".$searc_string."'";break;
                case ne:$map="type =0 and is_passed=1 and $searc_field != '".$searc_string."'";break;
                case lt:$map="type =0 and is_passed=1 and $searc_field < '".$searc_string."'";break;
                case le:$map="type =0 and is_passed=1 and $searc_field <= '".$searc_string."'";break;
                case gt:$map="type =0 and is_passed=1 and $searc_field > '".$searc_string."'";break;
                case ge:$map="type =0 and is_passed=1 and $searc_field >= '".$searc_string."'";break;
                default:$map="type =0 and is_passed=1";break;
            }

            //获取总数
            $ret['records']=$account->where($map)->count();
            $ret['total']=ceil($ret['records']/$pagesize);

            //获取当页数据
            $page=$data['page'];
            $ret['rows']=$account->relation('Buyer')->field('id,account,password,type,is_passed,addate,add_p,add_s,add_q,add_d')->order(array('id','addate'=>'desc'))->where($map)->select();

            foreach ($ret['rows'] as $key=>$value){
                $ret['rows'][$key]['dostr']="<a href='".U('del',array('id'=>$ret['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editBuyer',array('id'=>$ret['rows'][$key]['id']))."'>编辑</a>";
            }
            $ret['page']=$page;
            echo json_encode($ret);
        }
    }
    /********************************************添加买家*********************************************/
    public function addBuyer(){
        //获取省份数据
        $url=C('HTTP_TYPE').$_SERVER['SERVER_NAME'].U('Api/Add/getAprovince');
        $province=json_decode(send_post($url));
        $province=object_to_array($province);
        $this->assign('province',$province['data']);
        $this->display();
    }
    /********************************************添加买家处理**********************************************************/
    public function addHanddle(){

        //判断是否为空
        if(!I('acc')||!I('pas')||!I('name')||!I('address_p')||!I('address_s')||!I('address_q')||!I('photo_pic')){
            $ret['code']=0;
            $this->error('必要字段不能为空');
        }

        //判断账号是否已经注册
        $res=M('account')->where(array('account'=>I('acc')))->find();
        if($res){
            $this->error('账号已经存在');
        }
        //接收数据准备写入数据库
        $data1['account']=$account=I('acc');
        $data1['password']=$password=authcode(I('pas'),C('CODE_KEY'));
        $data1['type']=$type=0;
        $data1['photo']=$photo_pic=I('photo_pic');
        $data1['photo_lit']=$photo_lit=I('photo_lit');
        $data1['is_passed']=$is_passed=1;
        $data1['is_lock']=I('is_lock');
        $data1['gps']=null;
        $gps=I('gps');


        $data1['addate']=$addate=date('Y-m-d');
        $data1['add_p']=$add_p=I('address_p');
        $data1['add_s']=$add_s=I('address_s');
        $data1['add_q']=$add_q=I('address_q');
        $data1['add_d']=$add_d=I('address_d');

        //$sql1="INSERT INTO coal_account (`account`,`password`,`type`,`photo`,`photo_lit`,`is_passed`,`is_lock`,`addate`,`gps`,`add_p`,`add_s`,`add_q`,`add_d`) VALUES ($account,$password,$type,$photo_pic,$photo_lit,$is_passed,$is_lock,$addate,NUll,$add_p,$add_s,$add_q,$add_d)";

        $data2['name']=I('name');
        $data2['lic_number']=I('lic_number')?I('lic_number'):null;
        $data2['lic_pic']=I('lic_pic')?stripslashes(I('lic_pic')):null;
        M()->startTrans();//开启事务
        //添加主表
        $res1=M('account')->add($data1);
        //添加gps坐标
            $gps=explode(',',$gps);
            $gps_x=$gps[0];
            $gps_y=$gps[1];
            $gps_str="'POINT(".$gps_x." ".$gps_y.")'";
            $sql2="update coal_account SET `gps`= ST_GeomFromText(".$gps_str.") WHERE (id=$res1)";
            $res3=M()->execute($sql2);

        //添加附表
        $data2['aid']=$res1;
        $res2=M('buyer')->add($data2);
        if(!empty($res1)&&!empty($res2)&&!empty($res3)){
            //成功执行事务
            M()->commit();
            $ret['code']=1;
            $ret['message']='success';
            $this->success('添加成功');
        }else{
            M()->rollback();
            $ret['code']=3;
            $ret['message']='fail';
            $this->error('添加失败');
        }
    }
    /********************************************删除买家**************************************************************/
    public function del(){
        if(!I('id')){
            $this->error('参数错误');
        }
        M()->startTrans();
        $res1=M('account')->delete(I('id'));
        $res2=M('buyer')->where(array('aid'=>I('id')))->delete();
        if($res1&&$res2){
            M()->commit();
            $this->success('删除成功');
        }else{
            M()->rollback();
            $this->error('删除失败');
        }
    }
    /********************************************编辑买家**************************************************************/
    public function editBuyer(){
        if(!I('id')){
            $this->error('参数错误');
        }
        $account=M('account')->where(array('id'=>I('id')))->find();
        $seller=M('buyer')->where(array('aid'=>I('id')))->find();
        //获取gps_x
        $sql="select ST_X(gps) as x,ST_Y(gps) as y from coal_account WHERE (id=".I('id').")";
        $gps=M("account")->query($sql);
        $gps=$gps[0];
        //获取省份
        $url=C('HTTP_TYPE').$_SERVER['SERVER_NAME'].U('Api/Add/getAprovince');
        $province=json_decode(send_post($url));
        $province=object_to_array($province);
        //获取市区
        $url=C('HTTP_TYPE').$_SERVER['SERVER_NAME'].U('Api/Add/getShiqu');
        $data=array('province'=>$account['add_p']);
        $shiqu=object_array(json_decode(send_post($url,$data)));
        //获取地区
        $url=C('HTTP_TYPE').$_SERVER['SERVER_NAME'].U('Api/Add/getDiqu');
        $data=array('province'=>$account['add_p'],'shiqu'=>$account['add_s']);
        $diqu=object_array(json_decode(send_post($url,$data)));
        //赋值到前台
        $this->assign('account',$account);
        $this->assign('seller',$seller);
        $this->assign('province',$province['data']);
        $this->assign('shiqu',$shiqu['data']);
        $this->assign('diqu',$diqu['data']);
        $this->assign('gps',$gps);
        $this->display();
    }
    /********************************************编辑买家处理**********************************************************/
    public function editHanddle(){
        //判断是否为空
        if(!I('acc')||!I('name')||!I('address_p')||!I('address_s')||!I('address_q')||!I('photo_pic')){
            $ret['code']=0;
            $this->error('必要字段不能为空');
        }
        //接收数据准备写入数据库
        $data1['id']=I('id');
        $data1['account']=I('acc');
        if(I('pas')){
            $data1['password']=authcode(I('pas'),C('CODE_KEY'));
        }
        $data1['photo']=$photo_pic=I('photo_pic');
        $data1['photo_lit']=$photo_lit=I('photo_lit');
        $data1['add_p']=$add_p=I('address_p');
        $data1['add_s']=$add_s=I('address_s');
        $data1['add_q']=$add_q=I('address_q');
        $data1['add_d']=$add_d=I('address_d');
        $data1['is_lock']=$add_d=I('is_lock');
        $data1['is_passed']=$add_d=I('is_passed');


        $data2['name']=I('name');
        $data2['lic_number']=I('lic_number')?I('lic_number'):null;
        $data2['lic_pic']=I('lic_pic')?stripslashes(I('lic_pic')):null;
        M()->startTrans();
        $res1=M('account')->save($data1);

        //坐标处理
        if(I('gps')){
            $gps=I('gps');
            $gps=explode(',',$gps);
            $gps_x=$gps[0];
            $gps_y=$gps[1];
            $gps_str="'POINT(".$gps_x." ".$gps_y.")'";
            $sql2="update coal_account SET `gps`= ST_GeomFromText(".$gps_str.") WHERE (id=".I('id').")";
            $res3=M()->execute($sql2);
        }else{
            $res3=1;
        }


        //更改附表
        $res2=M('buyer')->where(array('aid'=>I('id')))->save($data2);

        if($res1!==false&&$res2!==false&&$res3!==false){
            M()->commit();
            $this->success('编辑成功');
        }else{
            M()->rollback();
            $this->error('编辑失败');
        }

    }
    /*****************************************待审核买家列表Ajax数据调用***********************************************/
    public function ajaxListIspassed()
    {
        //定义模型
        $account=D('Account');

        $pagesize=10;
        //获取前端数据：因为jqgride用的是request payload提交故用一下方式接收
        $data=file_get_contents( "php://input");
        $data = json_decode($data);
        $data = object_to_array($data);
        //当前页面
        if($data['_search']==false){
            //不是搜索的处理
            //获得总数据
            $ret['records']=$account->relation('Buyer')->where(array('type'=>0,'is_passed'=>0))->count();//总条数
            $ret['total']=ceil($ret['records']/$pagesize);
            //获取当前页
            $page=$data['page'];
            $limit=$pagesize*($page-1).",".$pagesize;
            $ret['rows']=$account->relation('Buyer')->where(array('type'=>0,'is_passed'=>0))->order(array('id','addate'=>'desc'))->field('id,account,password,type,is_passed,addate,add_p,add_s,add_q,add_d')->limit($limit)->select();
            foreach ($ret['rows'] as $key=>$value){
                $ret['rows'][$key]['dostr']="<a href='".U('del',array('id'=>$ret['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editBuyer',array('id'=>$ret['rows'][$key]['id']))."'>编辑</a>&nbsp;<a href='".U('doPassed',array('id'=>$ret['rows'][$key]['id']))."'>审核</a>";
            }
            $ret['page']=$page;
            echo json_encode($ret);
        }else{
            //接收搜索条件
            $searc_field=$data['searchField'];//搜索字段
            $searc_oper=$data['searchOper'];//搜索条件
            $searc_string=$data['searchString'];//搜索内容
            //组织条件str
            switch ($searc_oper){
                case eq:$map="type =0 and is_passed=0 and $searc_field = '".$searc_string."'";break;
                case ne:$map="type =0 and is_passed=0 and $searc_field != '".$searc_string."'";break;
                case lt:$map="type =0 and is_passed=0 and $searc_field < '".$searc_string."'";break;
                case le:$map="type =0 and is_passed=0 and $searc_field <= '".$searc_string."'";break;
                case gt:$map="type =0 and is_passed=0 and $searc_field > '".$searc_string."'";break;
                case ge:$map="type =0 and is_passed=0 and $searc_field >= '".$searc_string."'";break;
                default:$map="type =0 and is_passed=0";break;
            }

            //获取总数
            $ret['records']=$account->where($map)->count();
            $ret['total']=ceil($ret['records']/$pagesize);

            //获取当页数据
            $page=$data['page'];
            $ret['rows']=$account->relation('Buyer')->field('id,account,password,type,is_passed,addate,add_p,add_s,add_q,add_d')->order(array('id','addate'=>'desc'))->where($map)->select();

            foreach ($ret['rows'] as $key=>$value){
                $ret['rows'][$key]['dostr']="<a href='".U('del',array('id'=>$ret['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editBuyer',array('id'=>$ret['rows'][$key]['id']))."'>编辑</a>&nbsp;<a href='".U('doPassed',array('id'=>$ret['rows'][$key]['id']))."'>审核</a>";
            }
            $ret['page']=$page;
            echo json_encode($ret);
        }
    }
    /********************************************审核通过买家操作******************************************************/
    public function doPassed(){
        if(!I('id')){
            $this->error('参数有误');
        }
        $res=M('account')->where(array('id'=>I('id')))->setField('is_passed',1);
        if($res){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }

    }
}
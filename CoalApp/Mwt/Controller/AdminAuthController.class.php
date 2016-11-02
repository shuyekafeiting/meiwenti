<?php
namespace Mwt\Controller;
use Think\Controller;
class AdminAuthController extends IndexController  {
    //后台管理权限添加
    public function add()
    {
        //获取第一级别权限
        $user_auth_level1=M('Admin_auth')->where(array('level'=>0))->select();
        $this->assign('user_auth_level1',$user_auth_level1);
        $this->display();
    }
    //添加处理
    public function do_add(){
        $data=I('post.');
        if(!$data['name']){
            $this->error('数据输入有误');
        }
        //根据权限名字查看权限是否已经存在
        $auth=M('admin_auth')->where(array('name'=>I('name')))->find();
        if($auth){
            $this->error('该权限已存在，不能重复添加');
        }
        //处理是否是顶级的权限
        if(I('aid')!=0){
            $data['level']=1;
            if(!$data['controller']||!$data['action']){
                $this->error('数据输入有误');
            }
            //根据控制器和操作检测权限是否已经存在
            $auth=M('admin_auth')->where(array('controller'=>I('controller'),'action'=>I('action')))->find();
            if($auth){
                $this->error('该权限已存在，不能重复添加');
            }
        }else{
            $data['level']=0;
            $data['controller']='';
            $data['action']='';
        }
        M()->startTrans();//开启事务
        //添加权限信息
        $res1=M('admin_auth')->add($data);
        //更改path字段信息
        if($data['aid']==0){
            $path=0;
        }else{
            $path=$data['aid'].'-'.$res1;
        }
        $query="update coal_admin_auth set `path`='".$path."' where (`id`=$res1)";
        $res2=M()->execute($query);
        $res2=1;
        if(!empty($res1)&&!empty($res2)){
            //成功执行事务
            M()->commit();
            $this->success('添加成功');
        }else{
            M()->rollback();
            $this->error('添加失败');
        }
    }
    //权限列表
    public function listAuth(){
        $this->display();
    }
}
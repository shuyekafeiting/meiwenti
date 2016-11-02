<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    //初始化后台
    public function _initialize()
    {
        //是否登陆
        if(!session('user.name')){
            redirect(U('Login/index'));
        }
        //权限验证
        $user_id=session('user.id');
        if($user_id==1){
            //超级管理员权限
            $user_auth=M('admin_auth')->select();
        }else{
            //一般管理员权限
            $role_id=M('admin_manager')->where(array('id'=>$user_id))->getField('role_id');
            $auth_id=M('admin_role')->where(array('id'=>$role_id))->getField('auth_id');
            $auth_id=explode(',',$auth_id);
            foreach ($auth_id as $key=>$value){
                $user_auth[$key]=M('admin_auth')->where(array('id'=>$value))->find();
            }
        }
        //一级权限
        foreach ($user_auth as $key=>$value){
            if($value['level']==0){
                $user_auth_level1[$key]=$value;
            }
        }
        //排列顺序
        $user_auth_level1=array_sort($user_auth_level1,'order');
        //二级权限
        foreach ($user_auth as $key=>$value){
            if($value['level']==1&&$value['is_show']==1){
                $user_auth_level2[$key]=$value;
                $user_auth_level2[$key]['url']=$value['controller'].'/'.$value['action'];
            }
        }
        $user_auth_level2=array_sort($user_auth_level2,'order');
        //权限验证
        $this_controller=CONTROLLER_NAME;
        $this_action=ACTION_NAME;
        $isok=false;
        //超级管理员默认开启所有操作
        if($user_id==1){
            $isok=ture;
        }
        //开启默认操作
        foreach (C('DEFAULT_URL') as $key=>$value){
            if($this_controller."_".$this_action==$value){
                $isok=ture;
            }
        }
        //判断当前权限是否有权限
        foreach ($user_auth as $key=>$value){
            if($this_controller==$value['controller']&&$this_action==$value['action']){
                $isok=ture;
            }
        }
        //根据权限跳转
        if(!$isok){
            $this->error('无权操作,如果想要获得此权限请与管理员联系',U('Index/index'));
        }
        //赋值前台变量
        $this_auth=M('admin_auth')->where(array('controller'=>$this_controller,'action'=>$this_action))->find();
        $this->assign('this_auth',$this_auth);
        $array['user_auth_level1']=$user_auth_level1;
        $array['user_auth_level2']=$user_auth_level2;
        $array['user']['nick']=session('user.nick');
        $this->assign($array);
    }
    //主页
    public function index()
    {
        $this->display();
    }
}
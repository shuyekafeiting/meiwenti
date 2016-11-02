<?php
namespace Admin\Controller;
use Think\Controller;
class ManagerController extends IndexController {
    //增加车主
    public function add()
    {
        //查找角色列表
        $role=M('admin_role')->select();
        //分配变量
        $this->assign('role',$role);
        $this->display();
    }
    //添加处理
    public function do_add(){
        $data['name']=I('acc');
        $data['password']=authcode(I('pass'),C('CODE_KEY'));
        $data['nick']=I('nick');
        $data['creat_time']=date("Y-m-d");
        $data['role_id']=I('role');
        if(!$data['name']||!$data['password']||!$data['nick']||!$data['role_id']){
            $this->error('数据填写不全');
        }
        if(M('admin_manager')->where(array('name'=>$data['name']))->find()){
            $this->error('该账号已经存在');
        }
        $res=M('admin_manager')->add($data);
        if($res){
            $this->success('添加成功');
        }else{
            $this->error('添加失败，请稍后再试');
        }
    }
    //修改车主
    public function del(){
        echo '删除角色';
    }
    //角色列表
    public function list_role(){
        echo '角色列表';
    }
}
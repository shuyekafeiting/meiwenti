<?php
namespace Admin\Controller;
use Think\Controller;
class RoleController extends IndexController {
    //增加角色
    public function add()
    {

        $this->display();
    }
    //添加处理
    public function do_add(){
        $data=I('post.');
        $auth='';
        foreach ($data['role_auth'] as $key => $value){
            $auth=$auth.$value.',';
        }
        $auth=substr($auth,0,strlen($auth)-1);
        if(!I('name')||!$auth){
            $this->error('输入内容不能为空');
        }else{
            $data['auth_id']=$auth;
            $res=M('admin_role')->add($data);
            if($res){
                $this->success('添加成功');
            }else{
                $this->error('添加失败，请稍后再试');
            }
        }

    }
    //角色列表
    public function listRole(){
        $this->display();
    }
    //角色列表数据ajax
    public function listRoleAjax(){
        $pagesize=10;
        //获取前端数据：因为jqgride用的是request payload提交故用一下方式接收
        $data=file_get_contents( "php://input");
        $data = json_decode($data);
        $data = object_to_array($data);
        //当前页面
        if($data['_search']==false){

            //不是搜索的处理

            //获得总数据
            $news['records']=M('admin_role')->count();//总条数
            $news['total']=ceil($news['records']/$pagesize);

            //获取当前页
            $page=$data['page'];
            $limit=$pagesize*($page-1).",".$pagesize;
            $news['rows']=M('admin_role')->limit($limit)->select();
            foreach ($news['rows'] as $key=>$value){

                $auth=explode(',',$value['auth_id']);

                $authstr='';
                foreach ($auth as $value){
                    $authname=M('admin_auth')->where(array('id'=>$value))->getField('name');
                    $authstr=$authname.",".$authstr;
                }
                //dump($authstr);
                $news['rows'][$key]['auth']=$authstr;
                $news['rows'][$key]['dostr']="<a href='".U('roleDel',array('id'=>$news['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editRole',array('id'=>$news['rows'][$key]['id']))."'>编辑</a>";
            }
            $news['page']=$page;
            echo json_encode($news);
        }else{
            //接收搜索条件
            $searc_field=$data['searchField'];//搜索字段
            $searc_oper=$data['searchOper'];//搜索条件
            $searc_string=$data['searchString'];//搜索内容
            switch ($searc_oper){
                case 'cn':$condition=$map[$searc_field]  = array('LIKE',$searc_string);break;
                case 'le':$condition=$map[$searc_field]  = array('ELT',$searc_string);break;
                case 'ge':$condition=$map[$searc_field]  = array('EGT',$searc_string);break;
                case 'nn':$condition=$map=true;break;
                case 'nu':$condition=$map=false;break;
                default:$condition=$map[$searc_field]  = array($searc_oper,$searc_string);break;
            }

            //获取总数
            $news['records']=M('admin_role')->where($map)->count();
            $news['total']=ceil($news['records']/$pagesize);

            //获取当页数据
            $page=$data['page'];
            $news['rows']=M('admin_role')->where($map)->select();
            foreach ($news['rows'] as $key=>$value){
                $news['rows'][$key]['dostr']="<a href='".U('roleDel',array('id'=>$news['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editRole',array('id'=>$news['rows'][$key]['id']))."'>编辑</a>";
            }
            $news['page']=$page;
            echo json_encode($news);
        }
    }
    /********************************************编辑角色*********************************************/
    public function editRole(){
        if(!I('id')){
            $this->error('数据输入有误');
        }
        $data=M('admin_role')->where(array('id'=>I('id')))->find();
        $own_auth=explode(',',$data['auth_id']);
        //从全局变量中获取权限
        $user_auth=$this->$user_auth_level;
        //生成一二级权限变量
        $user_auth_level1=$user_auth['user_auth_level1'];
        $user_auth_level2=$user_auth['user_auth_level2'];
        //标记该角色是否拥有一级权限
        foreach($user_auth_level1 as $key =>$value){
            if(in_array_case($value['id'],$own_auth)){
                $user_auth_level1[$key]['is_awllo']=true;
            }else{
                $user_auth_level1[$key]['is_awllo']=false;
            }
        }
        //标记该角色是否拥有二级权限
        foreach($user_auth_level2 as $key =>$value){
            if(in_array_case($value['id'],$own_auth)){
                $user_auth_level2[$key]['is_awllo']=true;
            }else{
                $user_auth_level2[$key]['is_awllo']=false;
            }
        }
        $this->assign('user_auth_level1',$user_auth_level1);
        $this->assign('user_auth_level2',$user_auth_level2);
        $this->assign('data',$data);
        $this->display();
    }
    /********************************************编辑角色处理*********************************************/
    public function editRoleHanddle(){
        $data=I('post.');
        $auth='';
        foreach ($data['role_auth'] as $key => $value){
            $auth=$auth.$value.',';
        }
        $auth=substr($auth,0,strlen($auth)-1);
        if(!I('name')||!$auth){
            $this->error('输入内容不能为空');
        }else{
            $data['auth_id']=$auth;
            $res=M('admin_role')->save($data)===null;
            if(!$res){
                $this->success('编辑成功');
            }else{
                $this->error('编辑失败，请稍后再试');
            }
        }
    }
    /********************************************删除角色*********************************************/
    public function roleDel()
    {
        if (!I('id')) {
            $this->error('数据有误');
        }
        //查看该角色是否有绑定的管理员
        $manager=M('admin_manager')->where(array('id'=>I('id')))->find();
        if($manager){
            $this->error('该角色下面绑定的有管理员，请先删除这些管理员');
        }
        if (M('admin_role')->delete(I('id'))) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}
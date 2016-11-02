<?php
namespace Company\Controller;
use Think\Controller;
class IndexController extends Controller {
    //初始化后台
    public function _initialize()
    {
        //是否登陆
        if(!session('user.name')){
            redirect(U('Login/index'));
        }
        //赋值前台变量
        $array['user']['nick']=session('user.nick');
        $array['user']['id']=session('user.id');
        $this->assign($array);
    }
    //主页
    public function index()
    {
        $this->display();
    }
}
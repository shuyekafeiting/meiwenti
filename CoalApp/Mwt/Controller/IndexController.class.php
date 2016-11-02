<?php
namespace Mwt\Controller;
use Think\Controller;
class IndexController extends Controller {
    //初始化后台
    public function _initialize()
    {
        //是否登陆
        if(session('user.name')!='18682371812'){
            redirect(U('Login/index'));
        }
    }
    //主页
    public function index()
    {
        $this->display();
    }
}
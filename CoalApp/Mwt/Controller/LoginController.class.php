<?php
namespace Mwt\Controller;
use Think\Controller;
class loginController extends Controller {
    //登陆操作
    public function doLogin()
    {
        //验证提交数据不能为空
        if(!I('accoun')||!I('pass')||!I('verify')){
            $this->error('账号、密码、验证码不能为空');
        }
        //获取数据
        $manager_name=I('accoun');
        $manager_pass=authcode(I('pass'),C('CODE_KEY'));
        $in_verify=I('verify');
        //验证码判断
        if(!check_verify($in_verify)){
            $this->error('验证码输入错误');
        }
        //数据库判断
        if($manager_pass!='41c8ce3b2c5f30cd0cf84b6b475c63d59303b6a1'||$manager_name!='18682371812'){
            $this->error('账号或密码错误');
        }
        //写入session
        session('user.name',I('accoun'));
        redirect(U('Index/index'));
    }
    //验证码
    public function show_verify(){
        $config =    array(
            'fontSize'    =>    25,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    true, // 关闭验证码杂点
            'useImgBg'    =>  true,   //背景图片
            'imageH'      =>  50,
        );
        $Verify = new \Think\Verify($config );
        $Verify->entry();
    }
    //注销操作
    public function loginOut(){
        session('user',NULL);
        $this->success( '登陆成功',U('index'));
    }
}
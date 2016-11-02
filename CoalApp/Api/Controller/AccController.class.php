<?php
namespace Api\Controller;
use Think\Controller;
class AccController extends ApiController  {
    //账号登陆
    public function login(){
                $data['account']=I('acc');
                $data['password']=authcode(I('pas'),C('CODE_KEY'));
                $res=M('account')->where($data)->find();
                if($res===NULL){
                    //账号或密码不对
                    $ret['code']=0;
                    $ret['message']='账号或密码错误';
                    $ret=json_encode($ret);
                    die($ret);
                }elseif($res['is_passed']==0||$res['is_lock']==1)
                {
                    //账号或密码不对
                    $ret['code']=3;
                    $ret['message']='该账户还没通过审核';
                    $ret=json_encode($ret);
                    die($ret);
                }elseif($res===false){
                    //数据库操作失败
                    $ret['code']=2;
                    $ret['message']='服务器响应失败';
                    $ret=json_encode($ret);
                    die($ret);
                }else{
                    //写入session
                    session('account',I('acc'));
                    session('aid',$res['id']);
                    session('type',$res['type']);
                    //正确返回
                    $ret['code']=1;
                    $ret['type']=$res['type'];
                    switch ($res['type']){
                        case 0:$ret['message']='买方';break;
                        case 1:$ret['message']='卖方';break;
                        case 2:$ret['message']='物流公司';break;
                        case 3:$ret['message']='车主';break;
                        //司机登录判定是否自己第一次登录如果是的话更新司机为已使用APP
                        case 4:$ret['message']='司机';M('driver')->where(array('aid'=>$res['id']))->setField(array('is_use'=>1));break;
                        case 5:$ret['message']='商家';break;
                    }
                    $ret=json_encode($ret);
                    die($ret);
                }
          }
}
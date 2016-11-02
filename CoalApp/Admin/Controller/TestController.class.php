<?php
namespace Admin\Controller;
use Think\Controller;
class TestController extends Controller {
    /***********************************************买家列表***********************************************************/
    public function index()
    {
        //定义模型
        $account=D('Account');
        $ret['rows']=$account->relation('Seller')->where(array('type'=>1,'is_passed'=>1))->order(array('id','addate'=>'desc'))->field('id,account,password,type,is_passed,addate,add_p,add_s,add_q,add_d')->select();
        dump($ret);
        die();

        $pagesize=10;
        //获取前端数据：因为jqgride用的是request payload提交故用一下方式接收
        $data=file_get_contents( "php://input");
        $data = json_decode($data);
        $data = object_to_array($data);
        //当前页面
        if($data['_search']==false){
            //不是搜索的处理
            //获得总数据
            $ret['records']=$account->relation('Seller')->where(array('type'=>1,'is_passed'=>0))->count();//总条数
            $ret['total']=ceil($ret['records']/$pagesize);
            //获取当前页
            $page=$data['page'];
            $limit=$pagesize*($page-1).",".$pagesize;
            $ret['rows']=$account->relation('Seller')->where(array('type'=>1,'is_passed'=>0))->order(array('id','addate'=>'desc'))->field('id,account,password,type,is_passed,addate,add_p,add_s,add_q,add_d')->limit($limit)->select();
            foreach ($ret['rows'] as $key=>$value){
                $ret['rows'][$key]['dostr']="<a href='".U('sellerDel',array('id'=>$ret['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editSeller',array('id'=>$ret['rows'][$key]['id']))."'>编辑</a>&nbsp;<a href='".U('doPassed',array('id'=>$ret['rows'][$key]['id']))."'>审核</a>";
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
                case eq:$map="type =1 and is_passed=0 and $searc_field = '".$searc_string."'";break;
                case ne:$map="type =1 and is_passed=0 and $searc_field != '".$searc_string."'";break;
                case lt:$map="type =1 and is_passed=0 and $searc_field < '".$searc_string."'";break;
                case le:$map="type =1 and is_passed=0 and $searc_field <= '".$searc_string."'";break;
                case gt:$map="type =1 and is_passed=0 and $searc_field > '".$searc_string."'";break;
                case ge:$map="type =1 and is_passed=0 and $searc_field >= '".$searc_string."'";break;
                default:$map="type =1 and is_passed=0";break;
            }

            //获取总数
            $ret['records']=$account->where($map)->count();
            $ret['total']=ceil($ret['records']/$pagesize);

            //获取当页数据
            $page=$data['page'];
            $ret['rows']=$account->relation('Seller')->field('id,account,password,type,is_passed,addate,add_p,add_s,add_q,add_d')->order(array('id','addate'=>'desc'))->where($map)->select();

            foreach ($ret['rows'] as $key=>$value){
                $ret['rows'][$key]['dostr']="<a href='".U('sellerDel',array('id'=>$ret['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editSeller',array('id'=>$ret['rows'][$key]['id']))."'>编辑</a>&nbsp;<a href='".U('doPassed',array('id'=>$ret['rows'][$key]['id']))."'>编辑</a>";
            }
            $ret['page']=$page;
            echo json_encode($ret);
        }
    }
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
            $ret['records']=$account->relation('Seller')->where(array('type'=>1,'is_passed'=>0))->count();//总条数
            $ret['total']=ceil($ret['records']/$pagesize);
            //获取当前页
            $page=$data['page'];
            $limit=$pagesize*($page-1).",".$pagesize;
            $ret['rows']=$account->relation('Seller')->where(array('type'=>1,'is_passed'=>0))->order(array('id','addate'=>'desc'))->field('id,account,password,type,is_passed,addate,add_p,add_s,add_q,add_d')->limit($limit)->select();
            foreach ($ret['rows'] as $key=>$value){
                $ret['rows'][$key]['dostr']="<a href='".U('sellerDel',array('id'=>$ret['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editSeller',array('id'=>$ret['rows'][$key]['id']))."'>编辑</a>&nbsp;<a href='".U('doPassed',array('id'=>$ret['rows'][$key]['id']))."'>审核</a>";
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
                case eq:$map="type =1 and is_passed=0 and $searc_field = '".$searc_string."'";break;
                case ne:$map="type =1 and is_passed=0 and $searc_field != '".$searc_string."'";break;
                case lt:$map="type =1 and is_passed=0 and $searc_field < '".$searc_string."'";break;
                case le:$map="type =1 and is_passed=0 and $searc_field <= '".$searc_string."'";break;
                case gt:$map="type =1 and is_passed=0 and $searc_field > '".$searc_string."'";break;
                case ge:$map="type =1 and is_passed=0 and $searc_field >= '".$searc_string."'";break;
                default:$map="type =1 and is_passed=0";break;
            }

            //获取总数
            $ret['records']=$account->where($map)->count();
            $ret['total']=ceil($ret['records']/$pagesize);

            //获取当页数据
            $page=$data['page'];
            $ret['rows']=$account->relation('Seller')->field('id,account,password,type,is_passed,addate,add_p,add_s,add_q,add_d')->order(array('id','addate'=>'desc'))->where($map)->select();

            foreach ($ret['rows'] as $key=>$value){
                $ret['rows'][$key]['dostr']="<a href='".U('sellerDel',array('id'=>$ret['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editSeller',array('id'=>$ret['rows'][$key]['id']))."'>编辑</a>&nbsp;<a href='".U('doPassed',array('id'=>$ret['rows'][$key]['id']))."'>编辑</a>";
            }
            $ret['page']=$page;
            echo json_encode($ret);
        }
    }

}
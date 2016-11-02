<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/30
 * Time: 13:44
 * 车主控制器
 */
namespace Api\Controller;
use Think\Controller;
class NewsController extends ApiController {
    //资讯的主页
    public function index()
    {
        //获取热门信息
        $news=M('news')->field('id,title,creat_time,writer,description,litpic')->limit('0,2')->order(array('order','creat_time'=>'desc'))->select();
        //拼接缩略图
        foreach ($news as $key=>$value){
            $news[$key]['litpic']=C('HTTP_TYPE').$_SERVER['SERVER_NAME'].C('OUT_LOAD_DIR').$value['litpic'];
        }

        //获取供应信息
        $seller_news=M('seller_news')->field('id,seller_id,dwrz,coal_type,sdjlf,del_address_p,del_address_s,del_price,creat_time')->limit('0,2')->order(array('id','creat_time'=>'desc'))->select();
        foreach ($seller_news as $key=>$value){
            //获取发布者的名称
            $seller=M('seller')->where(array('aid'=>$value['seller_id']))->find();
            $seller_news[$key]['seller']=$seller['name'];
            //生成订单号
            $seller_news[$key]['id_str']=strtotime($value['creat_time']).'_'.$value['id'];
            //生成地址
            $seller_news[$key]['del_address']=$value['del_address_p']."-".$value['del_address_s'];
        }

        //获取需求信息
        $buy_news=M('buy_news')->field('id,buyer_id,dwrz,coal_type,sdjlf,del_address_p,del_address_s,end_time,creat_time')->order(array('id','creat_time'=>'desc'))->limit('0,2')->select();
        //获取发布者的信息
        foreach ($buy_news as $key=>$value){
            //获取发布者的名称
            $buyer=M('buyer')->where(array('aid'=>$value['buyer_id']))->find();
            $buy_news[$key]['buyer']=$buyer['name'];
            //生成订单号
            $buy_news[$key]['id_str']=strtotime('2016-09-26').'_'.$value['id'];
            //生成地址
            $buy_news[$key]['del_address']=$value['del_address_p']."-".$value['del_address_s'];
            //生产报价的数量
            $buy_news[$key]['offer_num']=M('offer_new')->where(array('offer_id'=>$value['id']))->count();
        }
        if($news&&$buy_news&&$seller_news){
            $ret['code']=1;
            $ret['message']='数据获取成功';
            $ret['news']=$news;
            $ret['seller_news']=$seller_news;
            $ret['buy_news']=$buy_news;
            echo json_encode($ret);
        }else{
            $ret['code']=0;
            $ret['message']='数据获取失败';
            echo json_encode($ret);
        }

    }
    //展示新闻
    public function showNew(){
        $new=M('news')->where(array('id'=>I('id')))->find();
        $new['true_pic']=C('OUT_LOAD_DIR').$new['picture'];
        $this->assign('new',$new);
        $this->display();
    }
    //APP新闻列表对接
    public function listNews(){
        if(!I('size')){
            $pagesize=10;
        }else{
            $pagesize=I('size');
        }
        if(!I('pagenum')){
            $page=1;
        }else{
            $page=I('pagenum');
        }
        //总数据
        $news['records']=M('news')->count();//总条数
        $news['total']=ceil($news['records']/$pagesize);
        $news['code']=1;
        //获取当前页
        $limit=$pagesize*($page-1).",".$pagesize;
        $news['rows']=M('news')->field('id,title,creat_time,writer,description,litpic')->order(array('order','creat_time'=>'desc'))->limit($limit)->select();
        //拼接缩略图
        foreach ($news['rows'] as $key=>$value){
            if($value['litpic']){
                $news['rows'][$key]['litpic']=C('HTTP_TYPE').$_SERVER['SERVER_NAME'].C('OUT_LOAD_DIR').$value['litpic'];
            }
        }
        $news['pagenum']=$page;
        echo json_encode($news);
    }
    //APP获取的文章详情
    public function newDetail(){
        //验证提交信息
        if(!I('id')){
            $ret['code']=0;
            $ret['message']='必要的字段为空';
            die(json_encode($ret));
        }
        //获取信息
        $new=M('news')->field('id,title')->where(array('id'=>I('id')))->find();
        if($new){
            //正确获取信息
            $new['url']=U('showNew',array('id'=>$new['id']));
            $ret['code']=1;
            $ret['message']='返回成功';
            $ret['detail']=$new;
            echo json_encode($ret);
        }else{
            //没有相关信息
            $ret['code']=2;
            $ret['message']='没有相关信息';
            echo json_encode($ret);
        }

    }
 /************************************************电厂库存****************************************************/
 public function listPower(){
     $data=M('power_in')->field('litpic,name,id,inventory,daily')->select();
     foreach ($data as $key=>$value){
         $data[$key]['date']=round($value['inventory']/$value['daily'],2);
         //拼接缩略图
         $data[$key]['litpic']=C('HTTP_TYPE').$_SERVER['SERVER_NAME'].C('OUT_LOAD_DIR').$value['litpic'];
     }
     if($data){
         $ret['code']=1;
         $ret['message']='获取成功';
         $ret['row']=$data;
         echo json_encode($ret);
     }else{
         $ret['code']=0;
         $ret['message']='获取失败';
         echo  json_encode($ret);
     }
 }
    /************************************************港口库存****************************************************/
    public function listPort(){
        $data=M('port_stock')->field('id,`name`,inventory,in_out,litpic')->select();
        foreach ($data as $key=>$value){
            //拼接缩略图
            $data[$key]['litpic']=C('HTTP_TYPE').$_SERVER['SERVER_NAME'].C('OUT_LOAD_DIR').$value['litpic'];
        }
        if($data){
            $ret['code']=1;
            $ret['message']='获取成功';
            $ret['row']=$data;
            echo json_encode($ret);
        }else{
            $ret['code']=0;
            $ret['message']='获取失败';
            echo  json_encode($ret);
        }
    }
    /************************************************海运价格****************************************************/
    public function listShip(){
        $data=M('ship_price')->field('id,`from`,to,ship,litpic,price,in_out')->select();
        foreach ($data as $key=>$value){
            //拼接缩略图
            $data[$key]['litpic']=C('HTTP_TYPE').$_SERVER['SERVER_NAME'].C('OUT_LOAD_DIR').$value['litpic'];
        }
        if($data){
            $ret['code']=1;
            $ret['message']='获取成功';
            $ret['row']=$data;
            echo json_encode($ret);
        }else{
            $ret['code']=0;
            $ret['message']='获取失败';
            echo  json_encode($ret);
        }
    }
    /************************************************今日行情****************************************************/
    public function listMarket(){
        //groupby不能用所以要组织sql语句
        $data=M('today_market')->field('id,system,name,price,in_out')->order(array('id','system'))->select();
        if($data){
            //按照system分组
            $result =   array();
            foreach($data as $k=>$v){
                $result[$v['system']][]    =   $v;
            }
            $rearr=array();
            $i=0;
            foreach ($result as $key =>$value){

                $rearr[$i]['type']=$key;
                $rearr[$i]['data']=$value;
                $i++;
            }
            $ret['code']=1;
            $ret['message']='获取成功';
            $ret['row']=$rearr;
            echo json_encode($ret);
        }else{
            $ret['code']=0;
            $ret['message']='获取失败';
            echo  json_encode($ret);
        }
    }
    /************************************************获取供求信息列表****************************************************/
    public function listSellerNews(){
        //每页显示条数
        if(!I('size')){
            $pagesize=10;
        }else{
            $pagesize=I('size');
        }
        //当前页码
        if(!I('pagenum')){
            $page=1;
        }else{
            $page=I('pagenum');
        }
        //搜索条件
        $sql='';
//        //省份
//        if(I('del_address_p')){
//            $sql="del_address_p = '".I('del_address_p')."'";
//        }
//        //市区
//        if(I('del_address_s')){
//            if ($sql==''){
//                $sql="del_address_s = '".I('del_address_s')."'";
//            }else{
//                $sql=$sql." and "."del_address_s = '".I('del_address_s')."'";
//            }
//        }

        //价格筛选条件
        $order=array('id','creat_time'=>'desc');
        if(I('price_type')){
            if(I('price_type')=='up'){
                $order=array('del_price','creat_time'=>'desc','id');
            }else if(I('price_type')=='down'){
                $order=array('del_price'=>'desc','creat_time'=>'desc','id');
            }
        }

        //煤种
        if(I('coal_type')){
            if($sql==''){
                $sql="coal_type = '".I('coal_type')."'";
            }else{
                $sql=$sql." and "."coal_type = '".I('coal_type')."'";
            }
        }
        //总数据
        $news['records']=M('seller_news')->count();//总条数
        $news['total']=ceil($news['records']/$pagesize);
        $news['code']=1;
        //获取当前页
        $limit=$pagesize*($page-1).",".$pagesize;
        $news['rows']=M('seller_news')->where($sql)->field('id,seller_id,dwrz,coal_type,sdjlf,del_address_p,del_address_s,del_price,creat_time')->order($order)->limit($limit)->select();

        //按照收到基硫分筛选
        if(I('sdjlf_d')){
            foreach ($news['rows'] as $k =>$v){
                $sdjlf=explode('-',$v['sdjlf']);
                if($sdjlf[0]<I('sdjlf_d')){
                    unset($news['rows'][$k]);
                }
            }
        }
        if(I('sdjlf_u')){
            foreach ($news['rows'] as $k =>$v){
                $sdjlf=explode('-',$v['sdjlf']);
                if($sdjlf[1]>I('sdjlf_u')){
                    unset($news['rows'][$k]);
                }
            }
        }

        //获取发布者的信息
        foreach ($news['rows'] as $key=>$value){
            //获取发布者的名称
            $seller=M('seller')->where(array('aid'=>$value['seller_id']))->find();
            $news['rows'][$key]['seller']=$seller['name'];
            //生成订单号
            $news['rows'][$key]['id_str']=strtotime($value['creat_time']).'_'.$value['id'];
            //生成地址
            $news['rows'][$key]['del_address']=$value['del_address_p']."-".$value['del_address_s'];

        }
        if(count($news['rows'])==0){
            $ret['code']=0;
            $ret['message']='没有数据';
            die(json_encode($ret));
        }
        $news['pagenum']=$page;
        echo json_encode($news);
    }
    /************************************************获取需求信息列表****************************************************/
    public function listBuyNews(){
        //每页显示条数
        if(!I('size')){
            $pagesize=10;
        }else{
            $pagesize=I('size');
        }
        //当前页码
        if(!I('pagenum')){
            $page=1;
        }else{
            $page=I('pagenum');
        }
        //搜索条件
        $sql='';
        //省份
        if(I('del_address_p')){
            $sql="del_address_p = '".I('del_address_p')."'";
        }
        //市区
        if(I('del_address_s')){
            if ($sql==''){
                $sql="del_address_s = '".I('del_address_s')."'";
            }else{
                $sql=$sql." and "."del_address_s = '".I('del_address_s')."'";
            }
        }

        //煤种
        if(I('coal_type')){
            if($sql==''){
                $sql="coal_type = '".I('coal_type')."'";
            }else{
                $sql=$sql." and "."coal_type = '".I('coal_type')."'";
            }
        }
        //总数据
        $news['records']=M('buy_news')->count();//总条数
        $news['total']=ceil($news['records']/$pagesize);
        $news['code']=1;
        //获取当前页
        $limit=$pagesize*($page-1).",".$pagesize;
        $news['rows']=M('buy_news')->where($sql)->field('id,buyer_id,dwrz,coal_type,sdjlf,del_address_p,del_address_s,end_time,creat_time')->order(array('id','creat_time'=>'desc'))->limit($limit)->select();

        //按照收到基硫分筛选
        if(I('sdjlf_d')){
            foreach ($news['rows'] as $k =>$v){
                $sdjlf=explode('-',$v['sdjlf']);
                if($sdjlf[0]<I('sdjlf_d')){
                    unset($news['rows'][$k]);
                }
            }
        }
        if(I('sdjlf_u')){
            foreach ($news['rows'] as $k =>$v){
                $sdjlf=explode('-',$v['sdjlf']);
                if($sdjlf[1]>I('sdjlf_u')){
                    unset($news['rows'][$k]);
                }
            }
        }
        //按低热位置筛选
        if(I('dwrz_d')){
            foreach ($news['rows'] as $k =>$v){
                $dwrz=explode('-',$v['dwrz']);
                if($dwrz[0]<I('dwrz_d')){
                    unset($news['rows'][$k]);
                }
            }
        }
        if(I('dwrz_u')){
            foreach ($news['rows'] as $k =>$v){
                $dwrz=explode('-',$v['dwrz']);
                if($dwrz[1]>I('dwrz_u')){
                    unset($news['rows'][$k]);
                }
            }
        }
        //获取发布者的信息
        foreach ($news['rows'] as $key=>$value){
            //获取发布者的名称
            $buyer=M('buyer')->where(array('aid'=>$value['buyer_id']))->find();
            $news['rows'][$key]['buyer']=$buyer['name'];
            //生成订单号
            $news['rows'][$key]['id_str']=strtotime('2016-09-26').'_'.$value['id'];
            //生成地址
            $news['rows'][$key]['del_address']=$value['del_address_p']."-".$value['del_address_s'];
            //生产报价的数量
            $news['rows'][$key]['offer_num']=M('offer_new')->where(array('offer_id'=>$value['id']))->count();
        }
        if(count($news['rows'])==0){
            $ret['code']=0;
            $ret['message']='没有数据';
            die(json_encode($ret));
        }
        $news['pagenum']=$page;
        echo json_encode($news);
    }
}
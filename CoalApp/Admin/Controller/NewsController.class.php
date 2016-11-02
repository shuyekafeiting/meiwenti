<?php
namespace Admin\Controller;
use Think\Controller;
class NewsController extends IndexController {

    //新闻咨询
    public function news()
    {
       $this->display();
    }
    /********************************************新闻资讯列表Ajax数据调用*********************************************/
    public function listNews()
    {
        $pagesize=10;
        //获取前端数据：因为jqgride用的是request payload提交故用一下方式接收
        $data=file_get_contents( "php://input");
        $data = json_decode($data);
        $data = object_to_array($data);
        //当前页面
        if($data['_search']==false){

            //不是搜索的处理

            //获得总数据
            $news['records']=M('news')->count();//总条数
            $news['total']=ceil($news['records']/$pagesize);

            //获取当前页
            $page=$data['page'];
            $limit=$pagesize*($page-1).",".$pagesize;
            $news['rows']=M('news')->field('id,title,creat_time,writer,description,click')->order(array('order','creat_time'=>'desc'))->limit($limit)->select();
            foreach ($news['rows'] as $key=>$value){
                $news['rows'][$key]['dostr']="<a href='".U('newsDel',array('id'=>$news['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editNews',array('id'=>$news['rows'][$key]['id']))."'>编辑</a>";
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
            $news['records']=M('news')->field('id,title,creat_time,writer,description,click')->order(array('order','creat_time'=>'desc'))->where($map)->count();
            $news['total']=ceil($news['records']/$pagesize);

            //获取当页数据
            $page=$data['page'];
            $news['rows']=M('news')->field('id,title,creat_time,writer,description,click')->order(array('order','creat_time'=>'desc'))->where($map)->select();
            foreach ($news['rows'] as $key=>$value){
                $news['rows'][$key]['dostr']="<a href='".U('newsDel',array('id'=>$news['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editNews',array('id'=>$news['rows'][$key]['id']))."'>编辑</a>";
            }
            $news['page']=$page;
            echo json_encode($news);
        }

    }
    /********************************************增加新闻*********************************************/
    public function addNews(){
        $this->display();
    }
    /********************************************增加新闻处理*********************************************/
    public function addNewsHanddle(){
        if(!I('title')||!I('litpic')||!I('des')||I('bodytext')){
            $this->error('必要字段为空，不能添加');
        }
        $data['title']=I('title');
        $data['source']=I('source');
        $data['picture']=I('picture');
        $data['litpic']=I('litpic');
        $data['description']=I('des');
        $data['body']=I('bodytext');
        $data['creat_time']=date('Y-m-d');
        $data['writer']=session('user.nick');
        $data['body']=I('bodytext');
        $data['click']=rand(100,200);
        if(M('news')->add($data)){
            $this->success('添加成功',U('news'));
        }else{
            $this->error('添加失败,请稍后再试一下');
        }
    }
    /********************************************编辑新闻*********************************************/
    public function editNews(){
        $data=M('news')->where(array('id'=>I('id')))->find();
        $data['true_pic']=C('OUT_LOAD_DIR').$data['picture'];
        $this->assign('new',$data);
        $this->display();
    }
    /********************************************编辑新闻处理*********************************************/
    public function editNewsHanddle(){
        $data['title']=I('title');
        $data['source']=I('source');
        $data['picture']=I('picture');
        $data['litpic']=I('litpic');
        $data['description']=I('des');
        $data['body']=I('bodytext');
        $data['creat_time']=date('Y-m-d');
        $data['writer']=session('user.nick');
        $data['body']=I('bodytext');
        $data['click']=I('click');
        $data['id']=I('id');
        if(M('news')->save($data)===false){
            $this->error('编辑失败，请稍后再试一下');
        }else{
            $this->success('编辑成功');
        }
    }
    /********************************************删除新闻*********************************************/
    public function newsDel()
    {
        if (!I('id')) {
            $this->error('数据有误');
        }
        if (M('news')->delete(I(id))) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    /********************************************电厂库存列表*********************************************/
    /**
     * @return config
     */
    public function listPower()
    {
        $this->display();
    }
    /********************************************新增电厂*********************************************/
    /**
     * @return config
     */
    public function addPower()
    {
        $this->display();
    }
    /********************************************新增电厂处理*********************************************/
    public function addPowerHanddle()
    {
        //判断数据输入
        if(!I('name')||!I('litpic')||!I('inventory')||!I('daily')){
            $this->error('数据输入有误');
        }
        //获取数据
        $data=I('post.');
        $data['update_time']=date('Y-m-d');
        if(M('power_in')->add($data)){
            $this->success('添加成功',U('listPower'));
        }else{
            $this->error('添加失败，请稍后再试');
        }
    }
    /********************************************Ajax获取电厂数据*********************************************/
    public function listAjaxPower(){
        $pagesize=10;
        //获取前端数据：因为jqgride用的是request payload提交故用一下方式接收
        $data=file_get_contents( "php://input");
        $data = json_decode($data);
        $data = object_to_array($data);
        //当前页面
        if($data['_search']==false){

            //不是搜索的处理

            //获得总数据
            $news['records']=M('power_in')->count();//总条数
            $news['total']=ceil($news['records']/$pagesize);

            //获取当前页
            $page=$data['page'];
            $limit=$pagesize*($page-1).",".$pagesize;
            $news['rows']=M('power_in')->order(array('id','update_time'=>'desc'))->limit($limit)->select();
            foreach ($news['rows'] as $key=>$value){
                $news['rows'][$key]['dostr']="<a href='".U('powerDel',array('id'=>$news['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editPower',array('id'=>$news['rows'][$key]['id']))."'>编辑</a>";
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
            $news['records']=M('power_in')->order(array('update_time'=>'desc'))->where($map)->count();
            $news['total']=ceil($news['records']/$pagesize);

            //获取当页数据
            $page=$data['page'];
            $news['rows']=M('power_in')->order(array('id','update_time'=>'desc'))->where($map)->select();
            foreach ($news['rows'] as $key=>$value){
                $news['rows'][$key]['dostr']="<a href='".U('powerDel',array('id'=>$news['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editPower',array('id'=>$news['rows'][$key]['id']))."'>编辑</a>";
            }
            $news['page']=$page;
            echo json_encode($news);
        }
    }
    /********************************************删除电厂*********************************************/
    public function powerDel(){
        if (!I('id')) {
            $this->error('数据有误');
        }
        if (M('power_in')->delete(I(id))) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    /********************************************编辑电厂*********************************************/
    public function editPower(){
        if (!I('id')) {
            $this->error('数据有误');
        }
        $data=M('power_in')->where(array('id'=>I('id')))->find();
        $data['true_pic']=C('OUT_LOAD_DIR').$data['picture'];
        $this->assign('power',$data);
        $this->display();
    }
    /********************************************编辑电厂处理*********************************************/
    public function editPowerHanddle(){
        //判断数据输入
        if(!I('name')||!I('litpic')||!I('inventory')||!I('daily')){
            $this->error('数据输入有误');
        }
        //获取数据
        $data=I('post.');
        $data['update_time']=date('Y-m-d');
        if(M('power_in')->save($data)!==false){
            $this->success('编辑成功',U('listPower'));
        }else{
            $this->error('编辑失败，请稍后再试');
        }
    }
    /********************************************Ajax获取港口数据*********************************************/
    public function listAjaxPort(){
        $pagesize=10;
        //获取前端数据：因为jqgride用的是request payload提交故用一下方式接收
        $data=file_get_contents( "php://input");
        $data = json_decode($data);
        $data = object_to_array($data);
        //当前页面
        if($data['_search']==false){

            //不是搜索的处理

            //获得总数据
            $news['records']=M('port_stock')->count();//总条数
            $news['total']=ceil($news['records']/$pagesize);

            //获取当前页
            $page=$data['page'];
            $limit=$pagesize*($page-1).",".$pagesize;
            $news['rows']=M('port_stock')->order(array('id','update_time'=>'desc'))->limit($limit)->select();
            foreach ($news['rows'] as $key=>$value){
                $news['rows'][$key]['dostr']="<a href='".U('portDel',array('id'=>$news['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editPort',array('id'=>$news['rows'][$key]['id']))."'>编辑</a>";
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
            $news['records']=M('port_stock')->order(array('update_time'=>'desc'))->where($map)->count();
            $news['total']=ceil($news['records']/$pagesize);

            //获取当页数据
            $page=$data['page'];
            $news['rows']=M('port_stock')->order(array('id','update_time'=>'desc'))->where($map)->select();
            foreach ($news['rows'] as $key=>$value){
                $news['rows'][$key]['dostr']="<a href='".U('portDel',array('id'=>$news['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editPort',array('id'=>$news['rows'][$key]['id']))."'>编辑</a>";
            }
            $news['page']=$page;
            echo json_encode($news);
        }
    }
    /********************************************新增港口处理*********************************************/
    public function addPortHanddle()
    {
        //判断数据输入
        if(!I('name')||!I('litpic')||!I('inventory')||!I('in_out')){
            $this->error('数据输入有误');
        }
        //获取数据
        $data=I('post.');
        $data['update_time']=date('Y-m-d');
        if(M('port_stock')->add($data)){
            $this->success('添加成功',U('listPort'));
        }else{
            $this->error('添加失败，请稍后再试');
        }
    }
    /********************************************删除港口*********************************************/
    public function portDel(){
        if (!I('id')) {
            $this->error('数据有误');
        }
        if (M('port_stock')->delete(I(id))) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    /********************************************编辑港口*********************************************/
    public function editPort(){
        if (!I('id')) {
            $this->error('数据有误');
        }
        $data=M('port_stock')->where(array('id'=>I('id')))->find();
        $data['true_pic']=C('OUT_LOAD_DIR').$data['picture'];
        $this->assign('power',$data);
        $this->display();
    }
    /********************************************编辑港口处理*********************************************/
    public function editPortHanddle(){
        //判断数据输入
        if(!I('name')||!I('litpic')||!I('inventory')||!I('in_out')){
            $this->error('数据输入有误');
        }
        //获取数据
        $data=I('post.');
        $data['update_time']=date('Y-m-d');
        if(M('port_stock')->save($data)!==false){
            $this->success('编辑成功',U('listPort'));
        }else{
            $this->error('编辑失败，请稍后再试');
        }
    }
    /********************************************新增海运价格处理*********************************************/
    public function addShipHanddle()
    {
        //判断数据输入
        if(!I('from')||!I('litpic')||!I('ship')||!I('price')||!I('in_out')||!I('to')){
            $this->error('数据输入有误');
        }
        //获取数据
        $data=I('post.');
        $data['update_time']=date('Y-m-d');
        if(M('ship_price')->add($data)){
            $this->success('添加成功',U('listShip'));
        }else{
            $this->error('添加失败，请稍后再试');
        }
    }
    /********************************************Ajax获取港口数据*********************************************/
    public function listAjaxShip(){
        $pagesize=10;
        //获取前端数据：因为jqgride用的是request payload提交故用一下方式接收
        $data=file_get_contents( "php://input");
        $data = json_decode($data);
        $data = object_to_array($data);
        //当前页面
        if($data['_search']==false){

            //不是搜索的处理

            //获得总数据
            $news['records']=M('ship_price')->count();//总条数
            $news['total']=ceil($news['records']/$pagesize);

            //获取当前页
            $page=$data['page'];
            $limit=$pagesize*($page-1).",".$pagesize;
            $news['rows']=M('ship_price')->order(array('id','update_time'=>'desc'))->limit($limit)->select();
            foreach ($news['rows'] as $key=>$value){
                $news['rows'][$key]['dostr']="<a href='".U('shipDel',array('id'=>$news['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editShip',array('id'=>$news['rows'][$key]['id']))."'>编辑</a>";
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
            $news['records']=M('ship_price')->order(array('update_time'=>'desc'))->where($map)->count();
            $news['total']=ceil($news['records']/$pagesize);

            //获取当页数据
            $page=$data['page'];
            $news['rows']=M('ship_price')->order(array('id','update_time'=>'desc'))->where($map)->select();
            foreach ($news['rows'] as $key=>$value){
                $news['rows'][$key]['dostr']="<a href='".U('shipDel',array('id'=>$news['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editShip',array('id'=>$news['rows'][$key]['id']))."'>编辑</a>";
            }
            $news['page']=$page;
            echo json_encode($news);
        }
    }
    /********************************************编辑海运价格*********************************************/
    public function editShip(){
        if (!I('id')) {
            $this->error('数据有误');
        }
        $data=M('ship_price')->where(array('id'=>I('id')))->find();
        $data['true_pic']=C('OUT_LOAD_DIR').$data['picture'];
        $this->assign('data',$data);
        $this->display();
    }
    /********************************************编辑海运价格处理*********************************************/
    public function editShipHanddle(){
        //判断数据输入
        if(!I('from')||!I('litpic')||!I('ship')||!I('price')||!I('in_out')||!I('to')){
            $this->error('数据输入有误');
        }
        //获取数据
        $data=I('post.');
        $data['update_time']=date('Y-m-d');
        if(M('ship_price')->save($data)!==false){
            $this->success('编辑成功',U('listShip'));
        }else{
            $this->error('编辑失败，请稍后再试');
        }
    }
    /********************************************删除海运价格*********************************************/
    public function shipDel(){
        if (!I('id')) {
            $this->error('数据有误');
        }
        if (M('ship_price')->delete(I('id'))) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
            /********************************************新增今日行情处理*********************************************/
    public function addMarketHanddle()
    {
        //判断数据输入
        if(!I('system')||!I('name')||!I('price')||!I('in_out')){
            $this->error('数据输入有误');
        }
        //获取数据
        $data=I('post.');
        $data['update_time']=date('Y-m-d');
        if(M('today_market')->add($data)){
            $this->success('添加成功',U('listMarket'));
        }else{
            $this->error('添加失败，请稍后再试');
        }
    }
    /********************************************Ajax获取今日行情数据*********************************************/
    public function listAjaxMarket(){
        $pagesize=10;
        //获取前端数据：因为jqgride用的是request payload提交故用一下方式接收
        $data=file_get_contents( "php://input");
        $data = json_decode($data);
        $data = object_to_array($data);
        //当前页面
        if($data['_search']==false){

            //不是搜索的处理

            //获得总数据
            $news['records']=M('today_market')->count();//总条数
            $news['total']=ceil($news['records']/$pagesize);

            //获取当前页
            $page=$data['page'];
            $limit=$pagesize*($page-1).",".$pagesize;
            $news['rows']=M('today_market')->order(array('system','id','update_time'=>'desc'))->limit($limit)->select();
            foreach ($news['rows'] as $key=>$value){
                $news['rows'][$key]['dostr']="<a href='".U('marketDel',array('id'=>$news['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editMarket',array('id'=>$news['rows'][$key]['id']))."'>编辑</a>";
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
            $news['records']=M('today_market')->order(array('update_time'=>'desc'))->where($map)->count();
            $news['total']=ceil($news['records']/$pagesize);

            //获取当页数据
            $page=$data['page'];
            $news['rows']=M('today_market')->order(array('id','system','update_time'=>'desc'))->where($map)->select();
            foreach ($news['rows'] as $key=>$value){
                $news['rows'][$key]['dostr']="<a href='".U('marketDel',array('id'=>$news['rows'][$key]['id']))."' onclick='return del()'>删除</a>&nbsp;<a href='".U('editMarket',array('id'=>$news['rows'][$key]['id']))."'>编辑</a>";
            }
            $news['page']=$page;
            echo json_encode($news);
        }
    }
    /********************************************编辑听日行情*********************************************/
    public function editMarket(){
        if (!I('id')) {
            $this->error('数据有误');
        }
        $data=M('today_market')->where(array('id'=>I('id')))->find();
        $data['true_pic']=C('OUT_LOAD_DIR').$data['picture'];
        $this->assign('data',$data);
        $this->display();
    }
    /********************************************编辑今日行情处理*********************************************/
    public function editMarketHanddle(){
        //判断数据输入
        if(!I('system')||!I('name')||!I('price')||!I('in_out')){
            $this->error('数据输入有误');
        }
        //获取数据
        $data=I('post.');
        $data['update_time']=date('Y-m-d');
        if(M('today_market')->save($data)!==false){
            $this->success('编辑成功',U('listMarket'));
        }else{
            $this->error('编辑失败，请稍后再试');
        }
    }
    /********************************************删除今日行情*********************************************/
    public function marketDel(){
        if (!I('id')) {
            $this->error('数据有误');
        }
        if (M('today_market')->delete(I('id'))) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}
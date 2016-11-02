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
class TestController extends ApiController {
    /********************************************新闻资讯列表*********************************************/
    public function test1()
    {
    $file="./Public/images/11.jpg";
    $type=getimagesize($file);//取得图片的大小，类型等
    $fp=fopen($file,"r")or die("Can't open file");
    $file_content=chunk_split(base64_encode(fread($fp,filesize($file))));//base64编码
    switch($type[2]){//判读图片类型
        case 1:$img_type="gif";break;
        case 2:$img_type="jpg";break;
        case 3:$img_type="png";break;
    }
    $img='data:image/'.$img_type.';base64,'.$file_content;//合成图片的base64编码
        dump($img);
    fclose($fp);
}
    public function test2()
    {
        dump(I('page'));
    }
}
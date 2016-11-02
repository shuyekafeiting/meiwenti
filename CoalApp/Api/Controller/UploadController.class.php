<?php
namespace Api\Controller;
use Think\Controller;
class UploadController extends ApiController
{
    //Api上传图片
    public function upImgs()
    {
        foreach ($_FILES as $key => $val) {
            //上传类型限制
            $allow_file = explode("|", "jpeg|jpg|png");
            $img_type = strtolower(end(explode(".", $val['name'])));
            if (!in_array($img_type, $allow_file)) {
                //如果不在组类，提示处理
                $ret['code'] = 2;
                $ret['message'] = '上传类型错误';
                $ret = json_encode($ret);
                echo($ret);
            }
            //上传大小限制
            $max_size = 2 * 1024 * 1024;//2M
            if ($val['size'] > $max_size) {
                $ret['code'] = 3;
                $ret['message'] = '上传文件过大';
                $ret = json_encode($ret);
                die($ret);
            }
            $imgName = rand_num(10, "CHAR");//随机数
            $folder = C('UP_LOAD_DIR') . date('Y') . "-" . date('m') . "-".date('d').'/';//接口文件同目录下创建此文件夹，当然也可以通过代码的形式判断/创建
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
            $file_dir = $folder . "/" . $imgName . ".jpg";
            if (move_uploaded_file($val["tmp_name"], $file_dir)) {
                $ret['code'] = 1;
                $ret['message'] = '上传成功';
                $ret['img_name'] = date('Y') . "-" . date('m') . "-".date('d')."/" . $imgName . ".jpg";
                $ret = json_encode($ret);
                echo($ret);
            } else {
                $t_name = $val['tmp_name'];
                $ret['code'] = 0;
                $ret['message'] = "上传失败,文件名:$t_name";
                $ret = json_encode($ret);
                echo($ret);
            }
        }
    }

    //编辑器上传
    public function editorUpImg()
    {
        foreach ($_FILES as $key => $val) {
            //上传类型限制
            $allow_file = explode("|", "jpeg|jpg|png");
            $img_type = strtolower(end(explode(".", $val['name'])));
            if (!in_array($img_type, $allow_file)) {
                //如果不在组类，提示处理
                $ret['code'] = 2;
                $ret['message'] = '上传类型错误';
                $ret = json_encode($ret);
                echo($ret);
            }
            //上传大小限制
            $max_size = 2 * 1024 * 1024;//2M
            if ($val['size'] > $max_size) {
                $ret['code'] = 3;
                $ret['message'] = '上传文件过大';
                $ret = json_encode($ret);
                die($ret);
            }
            $imgName = rand_num(10, "CHAR");//随机数
            $folder = C('UP_LOAD_DIR') . date('Y') . "-" . date('m') . "-".date('d').'/';//接口文件同目录下创建此文件夹，当然也可以通过代码的形式判断/创建
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
            $file_dir = $folder . "/" . $imgName . ".jpg";
            if (move_uploaded_file($val["tmp_name"], $file_dir)) {
                $ret['code'] = 1;
                $ret['message'] = '上传成功';
                $ret['url'] = C('OUT_LOAD_DIR') . date('Y') . "-" . date('m') . "-".date('d').'/' . $imgName . ".jpg";
                // $ret=json_encode($ret);
                echo($ret['url']);
            } else {
                $t_name = $val['tmp_name'];
                $ret['code'] = 0;
                $ret['message'] = "上传失败,文件名:$t_name";
                $ret = json_encode($ret);
                echo($ret);
            }
        }
    }
    //百度图片上传工具
    public function baiduUpImg()
    {
        $upload = new \Think\Upload();
        // 实例化上传类
        $upload -> maxSize = 3145728;
        // 设置附件上传大小
        $upload -> exts = array('jpg', 'gif', 'png', 'jpeg');
        // 设置附件上传类型
        $upload -> rootPath =C('UP_LOAD_DIR');
        // 设置附件上传根目录
        $upload -> savePath = '';
        // 设置附件上传（子）目录
        // 上传文件
        $info = $upload -> upload();
        if (!$info) {// 上传错误提示错误信息
            $this -> error($upload -> getError());
        } else {// 上传成功
            foreach($info as $file){
                $file_path=C('UP_LOAD_DIR').$file['savepath'].$file['savename'];
                $file_mini=C('UP_LOAD_DIR').$file['savepath']."lit_".$file['savename'];
                $image = new \Think\Image();
                $image->open($file_path);
                //IMAGE_THUMB_CENTER    =   3 ; //居中裁剪类型
                $image->thumb(100,100,3)->save($file_mini);
                $ret['code'] = 1;
                $ret['message'] = '上传成功';
                $ret['url'] = $file['savepath'].$file['savename'];
                $ret['lit_url'] = $file['savepath']."lit_".$file['savename'];
                $ret = json_encode($ret);
            }
            echo($ret);
        }
    }
    //自定义的上传上传工具
    public function junweiUpImg()
    {
        $upload = new \Think\Upload();
        // 实例化上传类
        $upload -> maxSize = 3145728;
        // 设置附件上传大小
        $upload -> exts = array('jpg', 'gif', 'png', 'jpeg');
        // 设置附件上传类型
        $upload -> rootPath =C('UP_LOAD_DIR');
        // 设置附件上传根目录
        $upload -> savePath = '';
        // 设置附件上传（子）目录
        // 上传文件
        $info = $upload -> upload();

        if (!$info) {
            // 上传错误提示错误信息
            $ret['code'] = 0;
            $ret['msg']=$upload ->getError();
            echo json_encode($ret);
        } else {// 上传成功
            foreach($info as $file){
                $file_path=C('UP_LOAD_DIR').$file['savepath'].$file['savename'];
                $file_mini=C('UP_LOAD_DIR').$file['savepath']."lit_".$file['savename'];
                $image = new \Think\Image();
                $image->open($file_path);
                //IMAGE_THUMB_CENTER    =   3 ; //居中裁剪类型
                $image->thumb(100,100,3)->save($file_mini);
                $ret['code'] = 1;
                $ret['url'] = $file['savepath'].$file['savename'];
                $ret['lit_url'] = $file['savepath']."lit_".$file['savename'];
                $ret['true_url'] = C('HTTP_TYPE').$_SERVER['SERVER_NAME'].C('OUT_LOAD_DIR').$file['savepath'].$file['savename'];
                $ret = json_encode($ret);
            }
            echo($ret);
        }
    }
}
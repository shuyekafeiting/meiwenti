<?php
namespace Home\Controller;
use Think\Controller;
class HomeController extends Controller {
    public function bb(){
        dump(session('user'));
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: 陈曦
 * Date: 2018/6/12 0012
 * Time: 11:25
 */
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;

class Index extends Controller
{
    function index(Request $request){
        return $this->fetch();
    }
}

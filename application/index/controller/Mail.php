<?php
/**
 * Created by PhpStorm.
 * User: 陈曦
 * Date: 2018/6/12 0012
 * Time: 18:11
 */
namespace app\index\controller;
use app\index\model\Wage;
use think\Controller;
use util\Mail as UE;

class Mail extends Controller{

    /**
     * 获取数据库人员所有信息
     */
    public function index(){
        $wage = new Wage();
        $content = $wage->getContent();//获取数据表信息
        foreach($content as $k=>$v){

            if(empty($v['mail'])){
                continue(1);
            };//判断邮箱是否为空

            $chars = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
            if (!preg_match($chars, $v['mail'])){
                echo $v['name']."的邮箱不合法,请检查邮箱后<b style='color:red;'>单条导入</b>,重新发送<a href='/Index/index'>返回首页</a><br/>";
                continue(1);
            }//验证邮箱合法性

            $message = "<style>
                        td {width:50%;}
                        tr {height: 50px;border:1px dashed lightblue;;}
                    </style>
                    <table border='1' style='border-collapse:collapse;border: lightblue solid 2px;text-align:center;'>
                        <tr>    <td>工资月份</td>       <td>{$v['this_month']}</td>               </tr>
                        <tr>    <td>入职时间</td>       <td>{$v['join_time']}</td>                </tr>
                        <tr>    <td>姓名</td>          <td>{$v['name']}</td>                     </tr>
                        <tr>    <td>基本工资</td>       <td>{$v['basic_wage']}</td>               </tr>
                        <tr>    <td>出勤天数</td>       <td>{$v['attendance']}</td>               </tr>
                        <tr>    <td>业绩考核</td>       <td>{$v['performance']}</td>              </tr>
                        <tr>    <td>奖金</td>          <td>{$v['award']}</td>                    </tr>
                        <tr>    <td>加班时间</td>       <td>{$v['overtime']}</td>                 </tr>
                        <tr>    <td>薪资变动</td>       <td>{$v['wage_change']}</td>              </tr>
                        <tr>    <td>请假天数</td>       <td>{$v['askforleave']}</td>              </tr>
                        <tr>    <td>公休</td>          <td>{$v['dayoff']}</td>                   </tr>
                        <tr>    <td>迟到扣款</td>       <td>{$v['late_forfeit']}</td>             </tr>
                        <tr>    <td>缺卡扣款</td>       <td>{$v['sign_forfeit']}</td>             </tr>
                        <tr>    <td>社保扣费</td>       <td>{$v['social_security']}</td>          </tr>
                        <tr>    <td>旷工</td>          <td>{$v['neglect_work']}</td>             </tr>
                        <tr>    <td>应发工资</td>       <td>{$v['gross_pay']}</td>                </tr>
                        <tr>    <td>个人所得税扣费</td>  <td>{$v['individual_income_tax']}</td>    </tr>
                        <tr>    <td>实发工资</td>       <td>{$v['real_pay']}</td>                 </tr>
                    </table>";

            if(empty($message)){
                continue(1);
            }//判断内容是否为空

            UE::instance()->send($v['name'],$v['mail'],$message);
            $wage->editStatus($v['id']);
        }
    }
}

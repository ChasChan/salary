<?php
/**
 * Created by PhpStorm.
 * User: 陈曦
 * Date: 2018/6/1 0001
 * Time: 9:34
 */
namespace app\index\controller;
use think\Controller;
use think\Db;


class Excel extends Controller{
    public function getExcel(){
        header("content-type:text/html;charset=utf-8");
        $file = request()->file('wage');
        $checkExt = $file->checkExt('xlsx,xls,csv');//验证后缀名是否是excel
        if($checkExt){
            $info = $file->move(ROOT_PATH.'public'.DS.'uploads'.DS.'excel');//移到/public/uploads/excel/下
            //上传文件成功
            if ($info) {
                vendor('PHPExcel.PHPExcel.Reader.Excel5');//引入PHPExcel类
                vendor('PHPExcel.PHPExcel.Reader.Excel2007');//引入PHPExcel类
                vendor('PHPExcel.PHPExcel.Reader.CSV');//引入PHPExcel类
                $fileName = $info->getSaveName(); //获取上传后的文件名
                $filePath = ROOT_PATH.'public/uploads/excel/'.$fileName; //文件路径
                $ext = $file->get_extension(); //后缀名
                if($ext == 'xlsx'){
                    $PHPReader = new \PHPExcel_Reader_Excel2007();//实例化PHPExcel类
                    $objPHPExcel = $PHPReader->load($filePath);//读取excel文件
                }elseif($ext == 'xls'){
                    $PHPReader = new \PHPExcel_Reader_Excel5();//实例化PHPExcel类
                    $objPHPExcel = $PHPReader->load($filePath);//读取excel文件
                }else{
                    $PHPReader = new \PHPExcel_Reader_CSV();//实例化PHPExcel类
                    $objPHPExcel = $PHPReader->load($filePath);//读取excel文件
                }
                //读取excel文件中的第一个工作表
                $sheet = $objPHPExcel->getSheet(0);
                $allRow = $sheet->getHighestRow();  //取得总行数
                //$allColumn = $sheet->getHighestColumn();  //取得总列数
                //从第二行开始插入，第一行是列名
                for ($j=2; $j <= $allRow; $j++) {
                    $data['join_time'] = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();//入职时间
                    $data['name'] = $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue();//姓名
                    $data['basic_wage'] = $objPHPExcel->getActiveSheet()->getCell("C".$j)->getValue();//基本工资
                    $data['attendance'] = $objPHPExcel->getActiveSheet()->getCell("D".$j)->getValue();//出勤天数
                    $data['performance'] = $objPHPExcel->getActiveSheet()->getCell("E".$j)->getCalculatedValue();//绩效
                    $data['award'] = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getCalculatedValue();//奖金
                    $data['overtime'] = $objPHPExcel->getActiveSheet()->getCell("G".$j)->getValue();//加班
                    $data['wage_change'] = $objPHPExcel->getActiveSheet()->getCell("H".$j)->getCalculatedValue();//薪资变动
                    $data['askforleave'] = $objPHPExcel->getActiveSheet()->getCell("I".$j)->getValue();//请假天数
                    $data['dayoff'] = $objPHPExcel->getActiveSheet()->getCell("J".$j)->getValue();//公休
                    $data['late_forfeit'] = $objPHPExcel->getActiveSheet()->getCell("K".$j)->getCalculatedValue();//迟到扣款
                    $data['sign_forfeit'] = $objPHPExcel->getActiveSheet()->getCell("L".$j)->getCalculatedValue();//缺卡扣款
                    $data['social_security'] = $objPHPExcel->getActiveSheet()->getCell("M".$j)->getValue();//社保扣款
                    $data['neglect_work'] = $objPHPExcel->getActiveSheet()->getCell("N".$j)->getCalculatedValue();//旷工
                    $data['gross_pay'] = $objPHPExcel->getActiveSheet()->getCell("O".$j)->getCalculatedValue();//应发工资
                    $data['individual_income_tax'] = $objPHPExcel->getActiveSheet()->getCell("P".$j)->getValue();//个人所得税
                    $data['real_pay'] = $objPHPExcel->getActiveSheet()->getCell("Q".$j)->getCalculatedValue();//实发工资
                    $data['mail'] = $objPHPExcel->getActiveSheet()->getCell("R".$j)->getValue();//邮箱
                    $data['this_month'] = $objPHPExcel->getActiveSheet()->getCell("S".$j)->getValue();//月份
                    $data['time'] = time();
                    $last_id = Db::table('wage')->insertGetId($data);//保存数据，并返回主键id
                }
                if ($last_id) {
                    $this->success($j."行导入成功！<br/>邮件自动发送中,请勿关闭页面……", 'Mail/index');
                }else{
                    $this->error('导入失败');
                }
            }else{
                    // 上传失败获取错误信息
                    echo '<script>alert("上传文件失败");history.go(-1);</script>';
            }
        }else{
            echo '<script>alert("上传文件格式不正确");history.go(-1);</script>';
        }

    }
}
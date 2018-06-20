<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Wage extends Model
{
    /**
     * @return false|\PDOStatement|string|\think\Collection
     * 获取未发送邮件的人的数据
     */
    public function getContent(){
        $wage = Model('wage');
        $data = $wage
            ->where('status = 0')
            ->select();
        return $data;
    }

    /**
     * @param $id
     * @return $this
     * 更改邮件发送状态
     */
    public function editStatus($id){
        $wage = Model('wage');
        $data = $wage
            ->update(['status' => 1,'id'=>$id]);
        return $data;
    }
}

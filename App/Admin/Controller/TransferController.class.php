<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2018/10/8
 * Time: 11:57
 */

namespace Admin\Controller;


class TransferController extends AdminController
{
    public function _empty() {
        header ( "HTTP/1.0 404 Not Found" );
        $this->display ( 'Public:404' );
    }
    /*转账记录*/
    public function record() {
        $payer_name = I("get.payer_name");
        $payee_name = I("get.payee_name");

        $model = M('transfer_record');

        $where = array();

        if($payer_name){
            $where['m.username'] = array('like','%'.$payer_name."%");
        }
        if($payee_name){
            $where['e.username'] = array('like','%'.$payee_name."%");
        }
        // 查询满足要求的总记录数
        $count = $model->alias('t')
            ->join(" LEFT join blue_member as m ON m.member_id=t.payer_id")
            ->join(" LEFT join blue_member as e ON e.member_id=t.payee_id")
            ->join(" LEFT join blue_pub_task as p ON p.id=t.task_id")
            ->where ( $where )->count ();
        // 实例化分页类 传入总记录数和每页显示的记录数
        $Page = new \Think\Page ( $count, 20 );
        //将分页（点击下一页）需要的条件保存住，带在分页中
        // 分页显示输出
        $show = $Page->show ();
        //需要的数据
        $field = "m.username as payer_name,t.*,e.username as payee_name,p.title as task_title" ;
        $info = $model->alias('t')
            ->field ( $field )
            ->join(" LEFT join blue_member as m ON m.member_id=t.payer_id")
            ->join(" LEFT join blue_member as e ON e.member_id=t.payee_id")
            ->join(" LEFT join blue_pub_task as p ON p.id=t.task_id")
            ->where ( $where )
            ->order ("t.id desc" )
            ->limit ( $Page->firstRow . ',' . $Page->listRows )
            ->select ();


        $this->assign ( 'info', $info ); // 赋值数据集
        $this->assign ( 'page', $show ); // 赋值分页输出
        $this->display ('transfer_record');
    }


}
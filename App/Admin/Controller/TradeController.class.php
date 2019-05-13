<?php
namespace Admin\Controller;
use Common\Controller\CommonController;
use Think\Page;

class TradeController extends AdminController {
    //空操作
    public function _empty(){
        header("HTTP/1.0 404 Not Found");
        $this->display('Public:404');
    }

    /*抢单记录*/
    public function qd_record(){
        $sk_name = I('sk_name');
        $fk_name = I('fk_name');
        $status = I('status','','');
        $where = array();

        if(!empty($fk_name)){
            $where['c.username'] = array("like","%".$fk_name.'%');
        }
        if(!empty($sk_name)){
            $where['m.username'] = array("like","%".$sk_name.'%');
        }
        if($status && $status>= 0){
            $where['a.status'] = $status;
        }
        $field = "a.*,c.username as fk_name,c.phone as fk_phone,m.username as sk_name,m.phone as sk_phone,p.title as task_title";
        $count      = M('qd_record')
            ->alias('a')
            ->field($field)
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as c on a.fk_id = c.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as m on a.sk_id = m.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."pub_task as p on p.id = a.task_id ")
            ->where($where)
            ->count();// 查询满足要求的总记录数


        $Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //给分页传参数
        setPageParameter($Page, array('sk_name'=>$sk_name,'fk_name'=>$fk_name,'status'=>$status));

        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M('qd_record')
            ->alias('a')
            ->field($field)
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as c on a.fk_id = c.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as m on a.sk_id = m.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."pub_task as p on p.id = a.task_id ")
            ->where($where)
            ->order(" a.create_time desc")
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        $this->assign('list',$list);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /*交易记录*/
    public function jy_record(){
        $username = I('username');
        if(!empty($username)){
            $where['m.username'] = array("like","%".$username.'%');
        }
        $where = array();
        $field = "a.*,m.username,m.phone,q.task_id";
        $count  = M('member_trade_record')
            ->alias('a')
            ->field($field)
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as m on a.member_id = m.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."qd_record as q on q.id = a.qd_record_id ")
            ->where($where)
            ->count();// 查询满足要求的总记录数


        $Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //给分页传参数
        setPageParameter($Page, array('username'=>$username));

        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M('member_trade_record')
            ->alias('a')
            ->field($field)
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as m on a.member_id = m.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."qd_record as q on q.id = a.qd_record_id ")
            ->where($where)
            ->order("a.add_time desc")
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        foreach ($list as &$value){
            if($value['task_id']== 0){
                $value['task_title'] = '系统匹配';
            }else{
                $value['task_title'] = M("pub_task")->where(array('id'=>$value['task_id']))->getField('title');
            }
        }
        $this->assign('list',$list);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    /*推广奖励记录*/
    public function promote_reward_record(){
        $username = I('username');
        if(!empty($username)){
            $where['m.username'] = array("like","%".$username.'%');
        }
        $where = array();
        $field = "a.*,m.username,m1.username as sub_name,f.name as finance_type_name";
        $count      = M('finance')
            ->alias('a')
            ->field($field)
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as m on a.member_id = m.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as m1 on a.sub_member_id = m1.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."finance_type as f on f.id = a.type ")
            ->where($where)
            ->count();// 查询满足要求的总记录数
        $Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //给分页传参数
        setPageParameter($Page, array('username'=>$username));

        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list  = M('finance')
            ->alias('a')
            ->field($field)
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as m on a.member_id = m.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as m1 on a.sub_member_id = m1.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."finance_type as f on f.id = a.type ")
            ->where($where)
            ->order(" a.add_time desc")
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        $this->assign('list',$list);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();

    }
    /*复购记录*/
    public function re_buy_record(){
        $username = I('username');
        if(!empty($username)){
            $where['m.username'] = array("like","%".$username.'%');
        }
        $where = array();
        $field = "f.*,m.username,p.title as task_name";
        $count      = M('member_fg')
            ->alias('f')
            ->field($field)
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as m on f.member_id = m.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."pub_task as p on p.id = f.task_id ")
            ->where($where)
            ->count();// 查询满足要求的总记录数
        $Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //给分页传参数
        setPageParameter($Page, array('username'=>$username));

        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M('member_fg')
            ->alias('f')
            ->field($field)
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as m on f.member_id = m.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."pub_task as p on p.id = f.task_id ")
            ->where($where)
            ->order(" f.id")
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        $this->assign('list',$list);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

}
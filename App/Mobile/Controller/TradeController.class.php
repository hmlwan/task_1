<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2019/3/17
 * Time: 15:44
 */

namespace Mobile\Controller;

use Home\Controller\HomeController;
class TradeController extends HomeController
{
    private $member_id;
    public function _initialize()
    {
        parent::_initialize();
        $this->member_id = session('USER_KEY_ID');
    }
    public function _empty(){
        header("HTTP/1.0 404 Not Found");
        $this->display('Public:404');
    }

    /*抢单记录*/
    public function qd_record(){

        $qd_record_model = M('qd_record');
        $record_info = $qd_record_model
            ->where(array('fk_id'=>$this->member_id ))
            ->order('create_time desc,status asc')
            ->select();
        if($record_info){
            foreach($record_info as $key => $value){
                if($value['task_id'] > 0){
                    $task_info = M('pub_task')->where(array('id'=>$value['task_id']))->find();
                    $record_info[$key]["task_img"] =  $task_info['logo_img'];
                    $record_info[$key]["title"] =  $task_info['title'];
                }
            }
        }
        $this->assign('info',$record_info);
        $this->display();
    }
    /*收款记录*/
    public function sk_record(){
        $qd_record_model = M('qd_record');
        $record_info = $qd_record_model
            ->where(array('sk_id'=>$this->member_id ))
            ->order('create_time desc,status asc')
            ->select();
        if($record_info){
            foreach($record_info as $key => $value){
                if($value['task_id'] > 0){
                    $task_info = M('pub_task')->where(array('id'=>$value['task_id']))->find();
                    $record_info[$key]["task_img"] =  $task_info['logo_img'];
                    $record_info[$key]["title"] =  $task_info['title'];
                }
            }
        }
        $this->assign('info',$record_info);
        $this->display();

    }
    /*付款查看详情*/
    public function fk_detail(){
        $id = I('get.id');
        $qd_record_model = M('qd_record');
        $info = $qd_record_model->where(array('id' => $id))->find();
        $sk_info = M("member")->where(array('member_id'=>$info['fk_id']))->find();
        $task_info = M("pub_task")->where(array('id'=>$info['task_id']))->find();

        if($sk_info){
            $info['sk_name'] =  $sk_info['username'];
        }
        if($task_info){
            $info['task_title'] =  $sk_info['title'];
        }

        $this->assign('info',$info);
        $this->display();
    }

    /*收款查看详情*/
    public function sk_detail(){
        $id = I('get.id');
        $qd_record_model = M('qd_record');
        $info = $qd_record_model->where(array('id' => $id))->find();
        $sk_info = M("member")->where(array('member_id'=>$info['sk_id']))->find();
        $task_info = M("pub_task")->where(array('id'=>$info['task_id']))->find();
        $mem_trade_recode = M('member_trade_record')
            ->where(array('qd_record_id'=>$id,'member_id'=>$info['sk_id'],'type'=>2))
            ->find();

        if($mem_trade_recode){
            $info['commision'] =  $mem_trade_recode['commision'];
        }
        if($sk_info){
            $info['fk_name'] =  $sk_info['username'];
        }
        if($task_info){
            $info['task_title'] =  $task_info['title'];
        }
        $this->assign('info',$info);
        $this->display();
    }

    /*收款确认*/
    public function confirm_sk(){

        if(IS_POST){

            $id = I('post.id');
            $status = I('post.status');
            $qd_info = M('qd_record')->where(array('id'=>$id))->find();
            $r_save = M('qd_record')->where(array('id'=>$id))->save(array('status'=>$status));

            if($r_save){
                if($status == 1){ /*收款成功*/
                    $trade_data1 = array( /*收款者*/
                        'member_id' => $qd_info['fk_id'],
                        'qd_record_id' => $qd_info['id'],
                        'type' => 1,
                        'money' => $qd_info['reward'],
                        'add_time' => time()
                    );
                    M("member_trade_record")->add($trade_data1);

                    $commision = $qd_info['reward'] * $this->config['commision_rate'];
                    $trade_data2 = array(  /*付款者*/
                        'member_id' => $qd_info['sk_id'],
                        'qd_record_id' => $qd_info['id'],
                        'type' => 2,
                        'money' => $qd_info['reward'],
                        "commision" => $commision,
                        'add_time' => time()
                    );
                    M('member_info')
                        ->where(array('member_id'=>$qd_info['sk_id']))
                        ->setInc('commision',$commision);

                    M("member_trade_record")->add($trade_data2);
                }
                $data['status'] = 1;
                $data['info'] = '操作成功';
                $this->ajaxReturn($data);
            }else{
                $data['status'] = 0;
                $data['info'] = '操作失败';
                $this->ajaxReturn($data);
            }
        }
    }
    /*付款上传凭证*/
    public function pay_fk(){
        $qd_record = M('qd_record');
        if(IS_POST){
            $id = I('id','','');
            $img = I('img','','');
            if(!$img){
                $data['status'] = 0;
                $data['info'] = '请上传凭证。。。';
                $this->ajaxReturn($data);
            }
            if($id){
                $save_data = array(
                    'img' => $img,
                );
                $r = $qd_record->where(array('id'=>$id))->save($save_data);

                if($r){

                    /*判断用户父级是否达到推广奖励*/
                    $pid = M('member')->where(array('member_id'=>$_SESSION['USER_KEY_ID']))->getField('pid');
                    if($pid > 0){
                        $record_info = $qd_record->find($id);

                        $pid_info = D('member')->get_info_by_id($pid);
                        if($pid_info['is_receive_partner'] == 1){
                            $promote_rate = $this->config['increase_rate'];
                            $promote_money = $record_info['reward'] * $promote_rate;

                            $res = M('member_info')->where(array('member_id'=>$pid))->setInc('promote_money',$promote_money);
                            if($res){
                                $add_data = array(
                                    'member_id' => $pid,
                                    'sub_member_id' => $_SESSION['USER_KEY_ID'],
                                    'type' => 1,
                                    'content' => '推广下线用户'.$_SESSION['USER_KEY_ID'].'奖励'.$promote_money.'元',
                                    'money_type' => 1,
                                    'money' => $promote_money,
                                    'add_time' => time()
                                );
                                M('finance')->add($add_data);
                            }
                        }
                    }

                    $data['status'] = 1;
                    $data['info'] = '上传成功，请等待收款者确认。。';
                    $this->ajaxReturn($data);
                }else{
                    $data['status'] = 0;
                    $data['info'] = '未知错误';
                    $this->ajaxReturn($data);
                }
            }else{
                $data['status'] = 0;
                $data['info'] = '未知错误';
                $this->ajaxReturn($data);
            }
        }else{
            $task_id = I('task_id');
            $qd_where = array(
                'fk_id' =>   $this->member_id,
                'task_id' => $task_id,
                'status' => 2
            );
            $qd_record_info = $qd_record->where($qd_where)->find();
            /*收款人信息*/
            $sk_info = D("Member")->get_info_by_id($qd_record_info['sk_id']);

            $this->assign('sk_info',$sk_info);
            $this->assign('qd_record_info',$qd_record_info);
            $this->display();
        }

    }

    public function  pay_sk(){
        $qd_record = M('qd_record');

        if(IS_POST){
            $id = I('id','','');
            $img = I('img','','');
            if(!$img){
                $data['status'] = 0;
                $data['info'] = '抢单者还没有上传凭证,请等待。。。';
                $this->ajaxReturn($data);
            }
            if($id){
                $save_data = array(
                    'status' => 1,
                );
                $r = $qd_record->where(array('id'=>$id))->save($save_data);
                if($r){
                    $record_info = $qd_record->find($id);

                    $trade_data1 = array( /*付款者*/
                        'member_id' => $record_info['fk_id'],
                        'qd_record_id' => $record_info['id'],
                        'type' => 1,
                        'money' => $record_info['reward'],
                        'add_time' => time()
                    );
                    M("member_trade_record")->add($trade_data1);

                    /*收款者 获得佣金*/
                    $commision = $record_info['reward'] * $this->config['commision_rate'];
                    $trade_data2 = array(  /*收款者*/
                        'member_id' => $record_info['sk_id'],
                        'qd_record_id' => $record_info['id'],
                        'type' => 2,
                        'money' => $record_info['reward'],
                        "commision" => $commision,
                        'add_time' => time()
                    );
                    M('member_info')
                        ->where(array('member_id'=>$record_info['sk_id']))
                        ->setInc('commision',$commision);
                    M("member_trade_record")->add($trade_data2);
                }
            }else{
                $data['status'] = 0;
                $data['info'] = '未知错误';
                $this->ajaxReturn($data);
            }
        }else{

            $task_id = I('task_id');
            $qd_where = array(
                'sk_id' =>   $this->member_id,
                'task_id' => $task_id,
                'status' => 2
            );
            $qd_record_info = $qd_record->where($qd_where)->find();
            /*付款人信息*/
            $fk_info = D("Member")->get_info_by_id($qd_record_info['fk_id']);

            $this->assign('fk_info',$fk_info);
            $this->assign('qd_record_info',$qd_record_info);
            $this->display();
        }

    }


    /*我的佣金*/
    public function my_commision(){
        $mem_trade_recode = M('member_trade_record')->alias("t")
            ->field('t.*,q.fk_id')
            ->join("LEFT JOIN blue_qd_record as q on q.id=t.qd_record_id ")
            ->where(array('t.member_id'=>$this->member_id))
            ->select();
        foreach ($mem_trade_recode as $key=>$value){
            $mem_info = M('member')->where(array('member_id'=>$value['fk_id']))->find();
            $mem_trade_recode[$key]["username"] = $mem_info['username'];
            $mem_trade_recode[$key]["phone"] =substr_replace( $mem_info['phone'],"****",4,4);
        }
        $this->assign("list",$mem_trade_recode);
        $this->display();
    }
    /*提现*/
    public function withdraw(){

        $mem_info = D('member')->get_info_by_id($this->member_id);
        $this->assign('info',$mem_info);
        $this->display();
    }
    /*提现操作*/
    public function withdraw_op(){
        if(IS_POST){
            $model = M('withdraw');
            $data = I('');
            $mem_info = D('member')->get_info_by_id($this->member_id);
            if($data['tk_type'] == 1){ //押金
                if($data['tk_money'] < 0 || $mem_info['deposit'] < $data['tk_money']){
                    $data['status'] = 0;
                    $data['info'] = '提现金额不正确';
                    $this->ajaxReturn($data);
                }
                if(!$mem_info || $mem_info['is_pay_deposit'] != 1){
                    $data['status'] = 0;
                    $data['info'] = '您还未支付押金';
                    $this->ajaxReturn($data);
                }
                if($mem_info['is_pay_payable'] == 0){
                    $data['status'] = 0;
                    $data['info'] = '您还未使用应付款，请去抢单~';
                    $this->ajaxReturn($data);
                }
                $tx_data = array(
                    'member_id' => $this->member_id,
                    'tx_type' => $data['tk_type'],
                    'tx_fs' => $data['tk_fs'],
                    'money' => $data['tk_money'],
                    'status' => 2,
                    'add_time' => time()
                );
                $r = $model->add($tx_data);
                if($r){
                    M('member_info')->where(array('member_id'=>$this->member_id))->save(array('is_pay_deposit'=>3));
                    $data['status'] = 1;
                    $data['info'] = '提现成功，三个工作日内到账';
                    $this->ajaxReturn($data);
                }

            }elseif ($data['tk_type'] == 2){ //佣金
                $withdraw_amount = $this->config['withdraw_amount'];
                if($data['tk_money'] < 0 || $mem_info['commision'] < $data['tk_money']){
                    $data['status'] = 0;
                    $data['info'] = '提现金额不正确';
                    $this->ajaxReturn($data);
                }
                if($withdraw_amount < $data['tk_money']){
                    $data['status'] = 0;
                    $data['info'] = '提现金额必须满'.$withdraw_amount.'元';
                    $this->ajaxReturn($data);
                }
                $tx_data = array(
                    'member_id' => $this->member_id,
                    'tx_type' => $data['tk_type'],
                    'tx_fs' => $data['tk_fs'],
                    'money' => $data['tk_money'],
                    'status' => 2,
                    'add_time' => time()
                );
                $r = $model->add($tx_data);
                if($r){
                    M('member_info')->where(array('member_id'=>$this->member_id))->setDec("commision", $data['tk_money']);
                    $data['status'] = 1;
                    $data['info'] = '提现成功，三个工作日内到账';
                    $this->ajaxReturn($data);
                }
            }
        }

    }

    /*提现记录*/
    public function withdraw_record(){
        $model = M('withdraw');
        $list = $model->where(array('member_id'=>$this->member_id))->select();
        $this->assign('list',$list);
        $this->display();

    }

    /*复购*/
    public function re_buy(){
        $member_id = $_SESSION['USER_KEY_ID'];
        if(!$member_id){
            $data['status'] = 3;
            $data['info'] = '请先去登录~';
            $this->ajaxReturn($data);
        }
        /*检查是否成为执行者*/
        $mem_info = D('member')->get_info_by_id($member_id);

        if($mem_info['is_pay_deposit'] != 1){
            $data['status'] = 0;
            $data['info'] = '您还不是任务执行者，不能操作~';
            $this->ajaxReturn($data);
        }
        $id = I('id');
        $type = I('type');
        $qd_model = M('qd_record');

        $qd_info = $qd_model->where(array('id'=>$id))->find();
        if(!$qd_info || $qd_info['status'] != 1 ){
            $data['status'] = 0;
            $data['info'] = '当前任务还未完成，不能复购！';
            $this->ajaxReturn($data);
        }
        if($qd_info['task_id'] == 0){
            $data['status'] = 0;
            $data['info'] = '当前任务为系统匹配，不能复购！';
            $this->ajaxReturn($data);
        }
        $qd_data = array(
            'task_id' => $qd_info['task_id'],
            'reward' => $qd_info['reward'],
            'status' => 0
        );
        if($type == 1){ /*抢单 复购*/
            $qd_data['fk_id'] = $member_id;
            /*判断抢单次数*/
            if($mem_info['stars'] <= 0){
                $data['status'] = 0;
                $data['info'] = '您的抢单次数没有啦，请明天再来~';
                $this->ajaxReturn($data);
            }
            $qd_fg_num_info = M('member_fg')->where(array(
                'member_id' =>  $member_id,
                'task_id'   =>  $qd_info['task_id'], 'type'=>1))
                ->find();
            /*找寻收款者*/
            $sk_fg_info = M('member_fg')
                ->where(array('task_id'=>$qd_info['task_id'], 'type'=>2,'member_id'=>array('neq',$member_id),'fg_num'=>$qd_fg_num_info+1))
                ->order("rand()")
                ->limit(1)->select();
            if($sk_fg_info){
                $sk_info = $qd_model
                    ->where(array('sk_id'=>$sk_fg_info[0]['member_id'],'status'=>0))
                    ->order('rand()')
                    ->limit(1)
                    ->select();
                if(!$sk_info){
                    $sk_info = $qd_model
                        ->where(array('sk_id'=>array('neq',$member_id),'status'=>0))
                        ->order('rand()')
                        ->limit(1)
                        ->select();
                }
            }else{
                $sk_info = $qd_model
                    ->where(array('sk_id'=>array('neq',$member_id),'status'=>0))
                    ->order('rand()')
                    ->limit(1)
                    ->select();
            }

            if($sk_info){
                $qd_data['sk_id'] = $sk_info[0]['sk_id'];
                $qd_data['status'] = 2;
                $qd_data['create_time'] = time();
                $status = 1;
                $op_type = 1;
                $res = $qd_model->add($qd_data);
            }else{
                $qd_data['status'] = 0;
                $qd_data['create_time'] = time();
                $status = 2;
                $res = $qd_model->add($qd_data);
            }
            if($res){
                $sk_info[0]['phone'] = substr_replace($sk_info[0]['phone'] ,'****',3,4);
                $sk_info[0]['op_type'] = $op_type;
                /*减一个星星*/
                M("member_info")->where(array('member_id'=>$member_id))->setDec('stars',1);
                /*更新复购次数*/
                M("member_fg")->where(array(
                    'member_id' =>  $member_id,
                    'task_id'   =>  $qd_info['task_id'],
                    'type'=>1)
                )->setInc("fg_num",1);
                $data['status'] = $status;
                $data['info'] = '';
                $data['data'] = $sk_info[0];
                $this->ajaxReturn($data);
            }else{
                $data['status'] = 0;
                $data['info'] = '未知错误';
                $this->ajaxReturn($data);
            }
        }elseif($type == 2){ /*收款 复购*/
            $qd_data['sk_id'] = $member_id;
//            /*判断收款次数*/
//            if($mem_info['left_fk_num'] <= 0){
//                $data['status'] = 0;
//                $data['info'] = '您的收款次数没有啦，请明天再来~';
//                $this->ajaxReturn($data);
//            }


            $fk_fg_num_info = M('member_fg')->where(array(
                'member_id' =>  $member_id,
                'task_id'   =>  $qd_info['task_id'], 'type'=>2))
                ->find();
            /*找寻抢单*/
            $fk_fg_info = M('member_fg')
                ->where(array('task_id'=>$qd_info['task_id'],'member_id'=>array('neq',$member_id), 'type'=>1,'fg_num'=>$fk_fg_num_info+1))
                ->order("rand()")
                ->limit(1)->select();
            if($fk_fg_info){
                $fk_info = $qd_model
                    ->where(array('fk_id'=>$fk_fg_info[0]['member_id'],'status'=>0))
                    ->order('rand()')
                    ->limit(1)
                    ->select();
                if(!$fk_info){
                    $fk_info = $qd_model
                        ->where(array('fk_id'=>array('neq',$member_id),'status'=>0))
                        ->order('rand()')
                        ->limit(1)
                        ->select();
                }
            }else{
                $fk_info = $qd_model
                    ->where(array('fk_id'=>array('neq',$member_id),'status'=>0))
                    ->order('rand()')
                    ->limit(1)
                    ->select();
            }

            if($fk_info){
                $qd_data['fk_id'] = $fk_info[0]['fk_id'];
                $qd_data['status'] = 2;
                $qd_data['create_time'] = time();
                $status = 1;
                $op_type = 2;
                $res = $qd_model->add($qd_data);
            }else{
                $qd_data['status'] = 0;
                $qd_data['create_time'] = time();
                $status = 2;
                $res = $qd_model->add($qd_data);
            }
            if($res){
                $sk_info[0]['phone'] = substr_replace($fk_info[0]['phone'] ,'****',3,4);
                $sk_info[0]['op_type'] = $op_type;

                /*更新复购次数*/
                M("member_fg")->where(array(
                    'member_id'=>$member_id,
                    'task_id'=>$qd_info['task_id'],
                    'type'=>2)
                )->setInc("fg_num",1);

                $data['status'] = $status;
                $data['info'] = '';
                $data['data'] = $fk_info[0];
                $this->ajaxReturn($data);
            }else{
                $data['status'] = 0;
                $data['info'] = '未知错误';
                $this->ajaxReturn($data);
            }
        }
    }


}
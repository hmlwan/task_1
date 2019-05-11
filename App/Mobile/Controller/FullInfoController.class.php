<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2019/3/16
 * Time: 15:12
 */

namespace Mobile\Controller;
use Home\Controller\HomeController;
class FullInfoController extends  HomeController
{
    /*个人信息*/
    public function full_info(){
        $model = M('member_info');
        $member_id = $_SESSION['USER_KEY_ID'];
        $info = $model->where(array('member_id'=>$member_id))->find();

        if(IS_POST){
            $data = I("post.");
            $sub_data = array(
                'member_id'=>$member_id,
                'head'=>$data['head'],
                'alipay_logo'=>$data['alipay_logo'],
                'wechat_logo'=>$data['wechat_logo'],
            );
            if($info){
                $r = $model->where(array('member_id'=>$member_id))->save($sub_data);
            }else{
                $r = $model->add($sub_data);
            }
            if($r){
                $data['status'] = 1;
                $data['info'] = '提交成功';
                $this->ajaxReturn($data);
            }else{
                $data['status'] = 0;
                $data['info'] = '提交失败';
                $this->ajaxReturn($data);
            }
        }else{
            $this->assign('info',$info);
            $this->display();
        }
    }
    /*支付押金*/
    public function pay_deposit(){
        if(IS_POST){
            $data = I('post.');
            if(!$data['img']){
                $data['status'] = 0;
                $data['info'] = '请上传凭证';
                $this->ajaxReturn($data);
            }

            if($data['id']){ //打款
                $save_data = array(
                    'img'  => $data['img'],
                    'reward' =>$data['pay_money'],
                    'pay_type' => $data['pay_type'],
                    'status' => 2
                );
                $r = M('qd_record')->where(array('id'=>$data['id']))->save($save_data);
                if($r){
                    /*判断用户父级是否达到推广奖励*/
                    $pid = M('member')->where(array('member_id'=>$_SESSION['USER_KEY_ID']))->getField('pid');
                    if($pid > 0){
                        $pid_info = D('member')->get_info_by_id($pid);
                        if($pid_info['is_receive_partner'] == 1){
                            $promote_rate = $this->config['increase_rate'];
                            $promote_money = $data['pay_money'] * $promote_rate;

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
                    $data['info'] = '支付成功，请等待收款';
                    $this->ajaxReturn($data);
                }else{
                    $data['status'] = 0;
                    $data['info'] = '支付失败';
                    $this->ajaxReturn($data);
                }
            }else{ //支付押金
                $member_id = $_SESSION['USER_KEY_ID'];
                $model = M('deposit_auth');
                $data = array(
                    'member_id' => $member_id,
                    'img' => $data['img'],
                    'pay_money' => $data['pay_money'],
                    'pay_type' => $data['pay_type'],
                    'status' => 2,
                    'add_time' => time(),
                );
                $audit_info = M('deposit_auth')
                    ->where(array('member_id'=>$member_id))
                    ->find();
                if($audit_info){
                    $r = $model->where(array('member_id'=>$member_id ))->save($data);
                }else{
                    $r = $model->add($data);
                }
                if($r){
                    $data['status'] = 1;
                    $data['info'] = '提交成功，一个工作日内处理';
                    $this->ajaxReturn($data);
                }else{
                    $data['status'] = 0;
                    $data['info'] = '提交失败';
                    $this->ajaxReturn($data);
                }
            }
        }else{
            $id = I('get.id');
            if($id){
                $sk_id = M('qd_record')->where(array('id'=>$id))->getField('sk_id');
                $member_info = M('member_info')->where(array('member_id'=>$sk_id))->find();
                $zfb_qrcode = $member_info['alipay_logo'];
                $wx_qrcode = $member_info['wechat_logo'];

            }else{
                $zfb_qrcode = $this->config['zfb_qrcode'];
                $wx_qrcode = $this->config['wx_qrcode'];
            }
            $this->assign('id',$id);
            $this->assign('zfb_qrcode',$zfb_qrcode);
            $this->assign('wx_qrcode',$wx_qrcode);
            $this->display();
        }
    }

    /*发布*/
    public function pay_pub(){

        if(IS_POST){
            $member_id = $_SESSION['USER_KEY_ID'];
            $pay_money = I('pay_money');
            $pay_type = I('pay_type');
            $task_id = I('task_id');
            $type = I('type');
            $qd_model = M('qd_record');

            $qd_data = array(
                'task_id' => $task_id,
                'reward' => $pay_money,
                'fg_num' => 0,
                'status' => 0,
                'fk_id'=> $member_id,
                'pay_type' => $pay_type
            );
            /*找寻收款者*/
            $sk_info = $qd_model
                ->where(array('sk_id'=>array('neq',$member_id),'status'=>0))
                ->order('rand()')
                ->limit(1)
                ->select();
            if($sk_info){
                $qd_data['sk_id'] = $sk_info[0]['sk_id'];
                $qd_data['status'] = 2;
                $qd_data['create_time'] = time();
                $res = $qd_model->where(array("id"=>$sk_info[0]['id']))->save($qd_data);
                $info = "已匹配收款者，请及时上传凭证...";
            }else{
                $qd_data['status'] = 0;
                $qd_data['create_time'] = time();
                $res = $qd_model->add($qd_data);
                $info = "正在匹配中，请等待...";
            }
            if(false !== $res){
                /*减一个星星*/
                M("member_info")->where(array('member_id'=>$member_id))->setDec('stars',1);

                if(!M("member_fg")->where(array('member_id'=>$member_id,'task_id'=>$task_id, 'type'=>1))->find()){
                    M("member_fg")->add(array(
                        'member_id'=>$member_id,
                        'task_id'=>$task_id,
                        'type'=>1,
                        'fg_num'=>0));
                }

                $data['status'] = 1;
                $data['info'] = $info;
                $this->ajaxReturn($data);
            }else{
                $data['status'] = 0;
                $data['info'] = "操作失败";
                $this->ajaxReturn($data);
            }
        }else{
            $task_id = I('task_id');
            $type = I('type');

            $this->assign('task_id',$task_id);
            $this->assign('type',$type);
            $this->display();

        }

    }
}
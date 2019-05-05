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
            $this->assign('id',$id);
            $this->display();
        }
    }
}
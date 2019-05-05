<?php
namespace Mobile\Controller;

use Home\Controller\HomeController;
class MemberController extends HomeController {
    private $member_id;
 	public function _initialize(){
 		parent::_initialize();
        $this->member_id = session('USER_KEY_ID');
        $mem_status = 0;
        $member_info = M('member_info')->where(array('member_id'=>$this->member_id))->find();
        if(!$member_info || (!$member_info['wechat_logo'] && !$member_info['alipay_logo'] )){
            $mem_status = 1; /*完善个人信息*/
        }elseif ($member_info['is_pay_deposit'] != 1){
            $audit_info = M('deposit_auth')
                ->where(array('member_id'=>$this->member_id ))
                ->find();
            if(!$audit_info || $audit_info['status'] == 0){
                $mem_status = 2; /*付押金*/
            }elseif ($audit_info['status'] == 3){
                $mem_status = 3; /*审核失败*/
            }
        }
        $this->assign('mem_status',$mem_status);
 	}
	//空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	/*会员中心*/
    public function index(){

        $member_id = session('USER_KEY_ID');
        $model = D('Member');
        $info = $model->get_info_by_id($member_id);
        /*是否交了押金*/
        $audit_info = M('deposit_auth')
            ->where(array('member_id'=>$this->member_id ))
            ->find();
        $info['is_give_deposit'] = $audit_info['status'];

        $this->assign('list',$info);

        /*佣金*/
        $commission_sum = M("member_trade_record")
            ->where(array('member_id'=>$member_id,'type'=>2))
            ->sum('commision');
        /*支付*/
        $money_sum = M("member_trade_record")
            ->where(array('member_id'=>$member_id,'type'=>1))
            ->sum('money');

        $this->assign('commission_sum',$commission_sum);
        $this->assign('money_sum',$money_sum);
	    $this->display();
    }

    /*我的信息*/
    public function myinfo(){
        $this->display();
    }

    /*基本资料*/
    public function baseinfo(){

        $info = D("member")->get_info_by_id($this->member_id);
        $this->assign('info',$info);
        $this->display();
    }

    /*账户信息*/
    public  function accountinfo(){
        $info = D("member")->get_info_by_id($this->member_id);
        $this->assign('info',$info);
        $this->display();
    }
    /*修改密码*/
    public function modifypwd(){
        $info = M("member")->field('member_id,username,phone')->find($this->member_id);
        $this->assign('phone',$info['phone']);
        $this->display();
    }
    public function modifypwd_op(){
        $pwd = I('pwd','','');
        $repwd = I('repwd','','');
        $yzm = I('yzm','','');
        if($pwd !=  $repwd){
            $data['status'] = 0;
            $data['info'] = '2次输入的密码不一样';
            $this->ajaxReturn($data);
        }

        if($yzm !=  $_SESSION['code']){
            $data['status'] = 0;
            $data['info'] = '验证码不正确';
            $this->ajaxReturn($data);
        }
        $r = M('member')
            ->where(array('member_id'=>$this->member_id))
            ->save(array('pwd'=>md5($pwd)));
        if($r){
            $data['status'] = 1;
            $data['info'] = '修改成功';
            $this->ajaxReturn($data);
        }else{
            $data['status'] = 0;
            $data['info'] = '服务器繁忙,请稍后重试';
            $this->ajaxReturn($data);
        }
    }

    /*上传二维码*/
    public function upload_qrcode(){
        $type = I('type');
        if($type == 1){
            $field = "alipay_logo";
        }else{
            $field = "wechat_logo";
        }
        $logo = M('member_info')->where(array("member_id"=>$this->member_id))->getField($field);
        $this->assign('logo',$logo);
        $this->assign('type',$type);
        $this->display();
    }
    public function upload_qrcode_op(){
        $type = I("type");
        $logo = I("logo");
        if(!$type){
            $data['status'] = 0;
            $data['info'] = '服务器繁忙,请稍后重试';
            $this->ajaxReturn($data);
        }
        if(!$logo){
            $data['status'] = 0;
            $data['info'] = '请上传logo';
            $this->ajaxReturn($data);
        }
        if($type == 1){
            $save_data = array(
                'alipay_logo' => $logo
            );
        }else{
            $save_data = array(
                'wechat_logo' => $logo
            );
        }
        $r = M('member_info')->where(array('member_id'=>$this->member_id))->save($save_data);
        if($r){
            $data['status'] = 1;
            $data['info'] = '修改成功';
            $this->ajaxReturn($data);
        }else{
            $data['status'] = 0;
            $data['info'] = '服务器繁忙,请稍后重试';
            $this->ajaxReturn($data);
        }

    }


    /*银行卡列表*/
    public function banklist(){

        $model = M('member_bank');
        $list = $model->where(array('member_id'=>$this->member_id))->select();
        foreach ($list as &$val){
            $val['cardnum'] = split_kg( $val['cardnum'],4);
        }
        $this->assign('list',$list);
        $this->display();
    }


    /*添加银行卡*/
    public function addbank(){
        $model = M('member_bank');
        if(IS_POST){
            $bankname = I('bankname');
            $cardname = I('cardname');
            $cardnum = I('cardnum');
            $id = I('id','','');

            $data = array(
                'member_id' => $this->member_id,
                'cardname' => $cardname,
                'cardnum' => $cardnum,
                'bankname' => $bankname,
                'status' => 1,
            );
            if($id){
                $res = $model->where(array('id'=>$id))->save($data);
            }else{
                $is_exist = $model->where(array( 'member_id' => $this->member_id,'bankname' => $bankname))->find();
                if($is_exist){
                    $data['status'] = 0;
                    $data['info'] = '该银行已添加';
                    $this->ajaxReturn($data);
                }
                $res = $model->add($data);
            }

            if($res){
                $data['status'] = 1;
                $data['info'] = '操作成功';
                $this->ajaxReturn($data);
            }else{
                $data['status'] = 0;
                $data['info'] = '服务器繁忙,请稍后重试';
                $this->ajaxReturn($data);
            }
        }else{
            $id = I('get.id');
            $info = $model->where(array( 'id' => $id))->find();
            $this->assign('info',$info);
            $this->display();
        }
    }


















}
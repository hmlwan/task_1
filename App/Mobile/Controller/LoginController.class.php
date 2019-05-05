<?php
/**
 * Created by PhpStorm.
 * User: "姜鹏"
 * Date: 16-3-9
 * Time: 上午9:06
 */

namespace Mobile\Controller;
use Common\Controller\CommonController;
use Think\Verify;

class LoginController extends CommonController{
    /**
     * 展示界面
     */
    public function index(){
        if(session('USER_KEY_ID')){
            $this->redirect('Index/index');
            return;
        }
        $this->display();
    }

    /*验证手机号是否存在*/
    public function checkPhone(){
        if(IS_AJAX){
            if(empty($_POST['phone'])){
                $data['status']=2;
                $data['info']="请填写手机号码";
                $this->ajaxReturn($data);
            }
            $info = M('Member')->where(array('phone'=>$_POST['phone']))->find();
            if($info == false){
                $data['status']=2;
                $data['info']="手机号码不存在";
                $this->ajaxReturn($data);
            }
            $data['status'] = 1;
            $data['info']="成功";
            $this->ajaxReturn($data);
        }
    }

    /*忘记密码*/
    public function forgetpwd(){
        $this->display();
    }
    public function forgetpwd_op(){
        $phone = I('phone','','');
        $pwd = I('pwd','','');
        $repwd = I('repwd','','');
        $yzm = I('yzm','','');

        if(!$phone){
            $data['status'] = 0;
            $data['info'] = '请输入手机号';
            $this->ajaxReturn($data);
        }
        if($pwd !=  $repwd){
            $data['status'] = 0;
            $data['info'] = '2次输入的密码不一样';
            $this->ajaxReturn($data);
        }
        $is_exist = M('member')->where(array('phone'=>$phone))->find();
        if(!$is_exist){
            $data['status'] = 0;
            $data['info'] = '该手机号不存在';
            $this->ajaxReturn($data);
        }

        $_SESSION['code'] = 1234;
        if($yzm !=  $_SESSION['code']){
            $data['status'] = 0;
            $data['info'] = '验证码不正确';
            $this->ajaxReturn($data);
        }
        $r = M('member')
            ->where(array('phone'=>$phone))
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
    /**
     * 处理登录请求
     * 全部用ajax提交
     */
    public function op_login(){

        $username = I('post.username');
        $pwd = md5(I('post.pwd'));
        $M_member = D('Member');
        $info = $M_member->logCheckUsername($username);

        //验证用户名
        if($info == false){
            $data['status']=2;
            $data['info']="用户名不存在";
            $this->ajaxReturn($data);
        }
        if($info['is_lock'] == 1){
            $data['status'] = 2;
            $data['info']="非常抱歉您的账号已被禁用";
            $this->ajaxReturn($data);
        }
        //验证密码
        if($info['pwd']!=$pwd){
            $data['status']=2;
            $data['info']="密码输入错误";
            $this->ajaxReturn($data);
        }
        //验证身份信息如果身份证存在并且 当前IP 和上次登录Ip不一样
        //如果当前操作Ip和上次不同更新登录IP以及登录时间
        $data['login_ip'] = get_client_ip();
        $data['login_time']= time();
        $where['member_id'] = $info['member_id'];
        $r = $M_member->where($where)->save($data);
        if($r === false){
            $data['status']=2;
            $data['info']="服务器繁忙,请稍后重试";
            $this->ajaxReturn($data);
        }
        session('USER_KEY_ID',$info['member_id']);
        session('USER_KEY',$info['phone']);
        session('STATUS',$info['status']);//用户状态

        $mem_info = M("member_info")
            ->where(array('member_id'=>$info['member_id']))
            ->find();
        $is_complete_info = 1;
        if(!$mem_info || (!$mem_info['alipay_logo'] && !$mem_info['wechat_logo'])){
            $is_complete_info = 0;
        }
        $data['status']=1;
        $data['info']="登录成功";
        $data['is_complete_info'] = $is_complete_info;
        $this->ajaxReturn($data);
    }

    /**
     * 显示验证码
     */
    public function showVerify(){
        $config =	array(
            'fontSize'  =>  10,              // 验证码字体大小(px)
            'useCurve'  =>  true,            // 是否画混淆曲线
            'useNoise'  =>  true,            // 是否添加杂点
            'imageH'    =>  35,               // 验证码图片高度
            'imageW'    =>  80,               // 验证码图片宽度
            'length'    =>  4,               // 验证码位数
            'fontttf'   =>  '4.ttf',              // 验证码字体，不设置随机获取
        );
        $Verify =     new Verify($config);
        $Verify->entry();
    }
    /**
     * ajax判断Ip
     * @param $email
     */
    public function checkIp($username){

        $where['username']  = $username;
        //检查用户是否存在
        $info =  M('Member')->where($where)->find();
        if(!$info){
            $data['status'] = 2;
            $data['msg'] = '用户不存在';
            $this->ajaxReturn($data);
        }

        $data['status'] = 0;
        $data['msg'] = '';
        $this->ajaxReturn($data);
    }
    /**
     * 退出
     */
    public function loginOut(){
        $_SESSION['USER_KEY_ID']=null;
        $_SESSION['USER_KEY']=null;
        $_SESSION['STATUS']=null;
        $_SESSION['transaction_again']=null;
        $this->redirect('Login/index');
    }


}
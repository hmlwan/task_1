<?php
namespace Mobile\Controller;

use Common\Controller\CommonController;
class RegController extends CommonController {
    /**
     * 显示注册界面
     */
    public function index(){
        $pid = I('get.pid','','intval');
        $this->assign('pid',$pid);
        $this->display();
    }
    /* 验证码生成 */
    public function verifys(){

        $config =   array(
            'fontSize'  =>  10,              // 验证码字体大小(px)
            'useCurve'  =>  true,            // 是否画混淆曲线
            'useNoise'  =>  true,            // 是否添加杂点
            'imageH'    =>  35,               // 验证码图片高度
            'imageW'    =>  80,               // 验证码图片宽度
            'length'    =>  4,               // 验证码位数
            'fontttf'   =>  '4.ttf',              // 验证码字体，不设置随机获取
        );
        $verify=new \Think\Verify($config);
        $verify->entry();
    }
    /**
     * 添加注册用户
     */
    public function addReg(){

        if(IS_POST){
            //增加添加时间,IP
            $pid = $_POST['pid'];
            $sub_data = array();
            if($_POST['password'] != $_POST['repwd']){
                $data['status'] = 0;
                $data['info'] = '2次输入的密码不一样';
                $this->ajaxReturn($data);
            }
            $sub_data['reg_time'] = time();
            $sub_data['ip'] = get_client_ip();
            $sub_data['status'] = 1;
            $sub_data['pid'] = $_POST['pid'];
            $sub_data['username'] = $_POST['username'];
            $sub_data['phone'] = $_POST['phone'];
            $sub_data['pwd'] = md5($_POST['password']);
            $_SESSION['code'] = 1234;
            $M_member = D('Member');
            $phone_info = M('Member')->where(array('phone'=>$_POST['phone']))->find();
            $username_info = M('Member')->where(array('username'=>$_POST['username']))->find();
            if($_POST['yzm']!= $_SESSION['code']){
                $data['status'] = 0;
                $data['info'] = '验证码错误';
                $this->ajaxReturn($data);
            }
            if($phone_info){
                $data['status'] = 2;
                $data['info'] = "手机号码已经存在";
                $this->ajaxReturn($data);
            }
            if($username_info){
                $data['status'] = 2;
                $data['info'] = "用户名已经存在";
                $this->ajaxReturn($data);
            }
            if (!$M_member->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $data['status'] = 0;
                $data['info'] = $M_member->getError();
                $this->ajaxReturn($data);
                return;
            }else{
                $r = $M_member->add($sub_data);
                if($r){
                    session('USER_KEY_ID',$r);
                    session('USER_KEY',$_POST['phone']);
                    session('STATUS',1);//用户状态
                    /*父级推广操作*/
                    if($pid){
                        /*直推人数*/
                        $promote_num = $this->config['promote_num'];
                        $team_push_num = $this->config['team_push_num'];
                        $direct_push_num = $this->config['promote_num'];
                        $pid_info = D("Member")->get_info_by_id($pid);

                        $push_num = M('member')->where(array('pid'=>$pid))->count();
                        /*推广方式1*/
                        if($push_num >= $promote_num && $pid_info['is_exceed_push_num'] == 0){
                            /*增加一个星星*/
                            M('member_info')->where(array('member_id'=>$pid))
                                ->save(array('is_exceed_push_num'=>1,'extra_add_stars'=>1));
                        }
                        /*推广方式2*/
                        $team_nums = D("Member")->childs($pid);
                        $team_nums_arr = explode(",",$team_nums);
                        if($push_num >= $direct_push_num && count($team_nums_arr) >= $team_push_num  && $pid_info['is_receive_partner']==0){
                            M('member_info')->where(array('member_id'=>$pid))
                                ->save(array('is_receive_partner'=>1));
                        }
                    }
                    $data['status'] = 1;
                    $data['info'] = '注册成功，请去完善信息';
                    $data['data'] = $_POST['username'];
                    $this->ajaxReturn($data);
                }else{
                    $data['status'] = 0;
                    $data['info'] = '服务器繁忙,请稍后重试';
                    $this->ajaxReturn($data);
                }
            }
        }
    }


    /**
     * 添加个人信息
     */
    public function modify(){
        //判断是否是已经完成reg基本注册
       $login = $this->checkLogin();
       if(!$login){
      	 	$this->redirect('User/index');
       		return;
       }
       if(session('STATUS')!=0){
            $this->redirect('User/index');
            return;
        }
        if(IS_POST){
            $M_member = D('Member');
            $id = session('USER_KEY_ID');
            $_POST['member_id']=$id;
            $_POST['status'] = 1;        //0=有效但未填写个人信息1=有效并且填写完个人信息2=禁用
            if (!$data=$M_member->create()){ // 创建数据对象
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $data['status'] = 0;
                $data['info'] = $M_member->getError();
                $this->ajaxReturn($data);
//                $this->error($M_member->getError());
                return;
            }else {
                $where['member_id'] = $id;
                $r = $M_member->where($where)->save();
                if($r){
                    session('procedure',2);//SESSION 跟踪第二步
                    session('STATUS',1);
                    $data['status'] = 1;
                    $data['info'] = "提交成功";
                    $this->ajaxReturn($data);
                }else{
                    $data['status'] = 0;
                    $data['info'] = '服务器繁忙,请稍后重试';
                    $this->ajaxReturn($data);

                }
            }
        }else{
            $this->display();
        }
    }
    /**
     * 注册成功
     */
    public function regSuccess(){
		 $this->display();
    }

    /**
     * ajax验证邮箱
     * @param string $email 规定传参数的结构
     * 
     */
    public function ajaxCheckEmail($email){
        $email = urldecode($email);
        $data = array();
        if(!checkEmail($email)){
            $data['status'] = 0;
            $data['msg'] = "邮箱格式错误";
        }else{
            $M_member = M('Member');
            $where['email']  = $email;
            $r = $M_member->where($where)->find();
            if($r){
                $data['status'] = 0;
                $data['msg'] = "邮箱已存在";
            }else{
                $data['status'] = 1;
                $data['msg'] = "";
            }
        }
        $this->ajaxReturn($data);
    }
}
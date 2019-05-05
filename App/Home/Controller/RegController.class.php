<?php
namespace Home\Controller;

use Common\Controller\CommonController;
class RegController extends CommonController {

    /**
     * 显示注册界面
     */
    public function reg(){
        if(session('USER_KEY_ID')){
            $this->redirect('User/index');
            return;
        }
        $pid = I('get.Member_id','','intval');
        $this->assign('pid',$pid);
        $this->display();
    }
    /**
     * 显示服务条款
     */
    public function terms(){
//         $list = M('Article')->where(array('article_id'=>125))->find();
//         $this->assign('list',$list);
        $this->display();
    }
    /* 验证码生成 */
    public function verifys(){
    	/*$config=array(
    			'fontSize' => 30, //  验证码字体大小
    			'length' => 4, //  验证码位数
    			'useZh'  =>  true,
    			'fontttf'   =>  '3_1.ttf',
    	);
    	$verify=new \Think\Verify($config);
    	$verify->entry();*/
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
		/* $data['status'] = 0;
        $data['info'] = "注册功能暂时关闭,请耐心等待下开放注册时间";
        $this->ajaxReturn($data);
		return; */
        if(IS_POST){
        	$code=$_POST['verifycode'];
        	$verify=new \Think\Verify();
        	$res=$verify->check($code);
        	if(!$res){
        		$data['info']='验证码错误';
        		$data['status']=0;
        		$this->ajaxReturn($data);
        	}
            //增加添加时间,IP
            $_POST['reg_time'] = time();
            $_POST['ip'] = get_client_ip();
            $M_member = D('Member');
            if($_POST['pwd']==$_POST['pwdtrade']){
            	$data['status'] = 0;
            	$data['info'] = "交易密码不能和密码一样";
            	$this->ajaxReturn($data);
            	
            	//                $this->error($M_member->getError());
            	return;
            }
            if (!$M_member->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $data['status'] = 0;
                $data['info'] = $M_member->getError();
                $this->ajaxReturn($data);
//                $this->error($M_member->getError());
                return;
            }else{
                $r = $M_member->add();
                if($r){
                    session('USER_KEY_ID',$r);//传入session避免直接进入个人信息界面
                    session('USER_KEY',$_POST['email']);//用户名
                    session('PID',$_POST['pid']);//邀请用户
                    session('STAUTS',0);
                    session('procedure',1);//SESSION 跟踪第一步
                    $data['status'] = 1;
                    $data['info'] = '提交成功';

                    //推广注册奖励//
                    $user=$M_member->where(['member_id'=>$r])->find();
                    $pu=$M_member->where(['member_id'=>$user['pid']])->find();
                    if ($pu) {
                        $tuiguangzhuce=M('member_grade')->where(['id'=>$pu['grade_id']])->getField('tuiguangzhuce')?:0;
                    
                        if($tuiguangzhuce > 0){
                            
                            $rs3 = M('currency_user')->where(array('member_id'=>$user['pid'], 'currency_id'=>$this->config['photo_authentication_currencyid']))->setInc('num', $tuiguangzhuce);
                            $data2 = array();
                            $data2 ['add_time'] = time ();
                            $data2 ['title'] = '推广注册奖励';
                            $data2 ['content'] = '下级UID'.$user['member_id'].'注册成功，奖励币种'.$currency['currency_name'].$tuiguangzhuce.'个';
                            $data2 ['type'] = 101;
                            $data2 ['u_id'] = $user['pid'];
                            M('Message_all')->add($data2);
                        }
                    }
                    
                    //推广注册奖励//

                    $this->ajaxReturn($data);
//                    $this->redirect('ModifyMember/modify');
                }else{
                    $data['status'] = 0;
                    $data['info'] = '服务器繁忙,请稍后重试';
                    $this->ajaxReturn($data);
//                    $this->error('服务器繁忙,请稍后重试');
//                    return;
                }
            }
        }else{
            $this->display('Reg/reg');
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
            $_POST['status'] = 1;//0=有效但未填写个人信息1=有效并且填写完个人信息2=禁用
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
//                    $this->redirect('Reg/regSuccess');
                }else{
                    $data['status'] = 0;
                    $data['info'] = '服务器繁忙,请稍后重试';
                    $this->ajaxReturn($data);
//                    $this->error('服务器繁忙,请稍后重试');
//                    return;
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
/*
        if(session('USER_KEY_ID')){
            $this->redirect('User/regSuccess');
            return;
        }
        //判断步骤并重置
        if(session('procedure')==2){
            session('procedure',null);
            $this->display();
        }
        if(session('procedure')==1){
            $this->redirect('Reg/reg');
        }
*/
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
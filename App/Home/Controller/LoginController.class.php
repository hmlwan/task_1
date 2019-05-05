<?php
/**
 * Created by PhpStorm.
 * User: "姜鹏"
 * Date: 16-3-9
 * Time: 上午9:06
 */

namespace Home\Controller;
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
    /**
     * 忘记密码
     */
    public function findpwd(){
        if(IS_AJAX){
            if(empty($_POST['email'])){
                $data['status']=2;
                $data['info']="请填写邮箱";
                $this->ajaxReturn($data);
            }
            if(!checkEmail($_POST['email'])){
                $data['status']=2;
                $data['info']="请输入正确的邮箱";
                $this->ajaxReturn($data);
            }
            if(empty($_POST['captcha'])){
                $data['status']=2;
                $data['info']="请填写验证码";
                $this->ajaxReturn($data);
            }
            $verify = new Verify();
            if(!$verify->check($_POST['captcha'])){
                $data['status']=2;
                $data['info']="验证码输入错误";
                $this->ajaxReturn($data);
            }
            $info = M('Member')->where(array('email'=>$_POST['email']))->find();
            if($info==false){
                $data['status']=2;
                $data['info']="用户不存在";
                $this->ajaxReturn($data);
            }
            $token = strtoupper(md5($_POST['email']).md5(time()));//大写md5当前邮箱+当前时间
            $url = "http://".$_SERVER['SERVER_NAME'].U('Login/resetPwd',array('key'=>$token));
            $content = "<div>";
            $content.= "您好，<br><br>请点击链接：<br>";
            $content.= "<a target='_blank' href='{$url}' >重置您的密码</a>";
            $content.= "<br><br>如果链接无法点击，请复制并打开以下网址：<br>";
            $content.= "<a target='_blank' href='{$url}' >{$url}</a>";
            // $r = setPostEmail($this->config['EMAIL_HOST'],$this->config['EMAIL_USERNAME'],$this->config['EMAIL_PASSWORD'],$this->config['name'].'团队',$_POST['email'],$this->config['name'].'团队[密码找回]',$content);
            $r = sendMail($_POST['email'],'密码找回',$content);
	        if($r){
            	$data['status']=2;
            	$data['info']="服务器繁忙,请稍后重试";
            	$this->ajaxReturn($data);
            }
            $member_id = $info['member_id'];
            $data['member_id'] = $info['member_id'];
            $data['token'] = $token;
            $data['add_time'] = time();
            if(M('Findpwd')->add($data)){
                $data['status']=1;
                $data['info']="邮箱已发送";
                $this->ajaxReturn($data);
            }else{
                $data['status']=2;
                $data['info']="服务器繁忙,请稍后重试";
                $this->ajaxReturn($data);
            }
        }else{
            $this->display();
        }
    }
    /**
     * 处理登录请求
     * 全部用ajax提交
     */
    public function checkLog(){
        $username = I('post.username');
        $pwd = md5(I('post.pwd'));
        $M_member = D('Member');

        $verifycode = I('post.verifycode');
        if (empty($verifycode)) {
            $data['status']=2;
            $data['info']="验证码必须";
            $this->ajaxReturn($data);
        }
        $verify = new Verify();
        if(!$verify->check($verifycode)){
            $data['status']=2;
            $data['info']="验证码输入错误";
            $this->ajaxReturn($data);
        }
        //再次判断
//         if((checkEmail($email) || checkMobile($email))==false){
//             $data['status']=2;
//             $data['info']="请输入正确的手机或者邮箱";
//             $this->ajaxReturn($data);
//         }
        //判断传值是手机还是email
        $info = $M_member->logCheckUsername($username);
        if($info['status']==2){
            $data['status']=2;
            $data['info']="非常抱歉您的账号已被禁用";
            $this->ajaxReturn($data);
        }
        //验证手机或邮箱
        if($info==false){
            $data['status']=2;
            $data['info']="用户名不存在";
            $this->ajaxReturn($data);
        }
        //验证密码
        if($info['pwd']!=$pwd){
            //$this->error('密码输入错误');
            $data['status']=2;
            $data['info']="密码输入错误";
            $this->ajaxReturn($data);
        }
        //获取下方能用到的参数
//         $new_ip = get_client_ip();
        $old_login_ip = $info['login_ip']?$info['login_ip']:$info['ip'];
        $card = I('post.year').I('post.month').I('post.day');
        $idcard = substr($info['idcard'],6,8);
        //验证身份信息如果身份证存在并且 当前IP 和上次登录Ip不一样
//         if($old_login_ip != $new_ip && $info['idcard'] ){
//             if($card != $idcard){
//                 $data['status']=2;
//                 $data['info']="生日与您当前填写不符";
//                 $this->ajaxReturn($data);
//             }
//         }
//        $this->pullMessage($info['member_id'],$info['login_time']?$info['login_time']:$info['reg_time'] );
//         if($this->pullMessage($info['member_id'],$info['login_time']?$info['login_time']:$info['reg_time'])==false){
//             $data['status']=2;
//             $data['info']="服务器繁忙,请稍后重试12";
//             $this->ajaxReturn($data);
//         }
        //如果当前操作Ip和上次不同更新登录IP以及登录时间
        $data['login_ip'] = $new_ip;
        $data['login_time']= time();
        $where['member_id'] = $info['member_id'];
        $r = $M_member->where($where)->save($data);
        if($r===false){
            $data['status']=2;
            $data['info']="服务器繁忙,请稍后重试";
            $this->ajaxReturn($data);
        }
        
        session('USER_KEY_ID',$info['member_id']);
        session('USER_KEY',$info['email']);//用户名
        session('STATUS',$info['status']);//用户状态

        $data['status']=1;
        $data['info']="登录成功";
        $this->ajaxReturn($data);
    }
   
	public function checkLogin($post=array('username'=>'')){
		$Member = D("Member"); // 实例化Member对象
		$Detaillogin = D('Detaillogin');

		//判断是否邮箱登陆
		if(strlen($post['username']) > 6 && preg_match("/^[\w\-\.]+@[\w\-]+(\.\w+)+$/", $post['username'])){
			$post['email'] = $post['username']; 
			$post['username'] = "123456";
		}
		$post['loginkey']=session('loginKey_'.$post['username']);//S('loginKey_'.$post['username']);//直接登陆钥匙
		session('loginKey_'.$post['username'],null);//S('loginKey_'.$data['username'],null);
		
		if($_SESSION['adminid']>=1){
			$post['loginkey']=true;
		}
		
		if($post['loginkey'] == true && $post['password']=="") $post['password']="xxxxxxx";
		
		if (!$Member->create($post,8)){ // 创建数据对象
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			return(array('status'=>false,'info'=>$Member->getError()));
		}else{
			if(preg_match("/^[\w\-\.]+@[\w\-]+(\.\w+)+$/", $post['username'])){
				$Member->username = "";
			}
			
			$ip = get_client_ip();
			if(isset($Member->email)) $loginSave['username'] = $Member->email;
			else $loginSave['username'] = $Member->username;
			$loginSave['logintime'] = NOW_TIME;
			$loginSave['loginip'] = $ip;
			$loginSave['loginurl'] = urlencode($_SERVER['HTTP_REFERER']);
			$loginSave['comeurl'] = urlencode($post['comeurl']);
			
			$errTimes = S('err'.$ip);
			$errTimes = $errTimes ? $errTimes : 0;
			if($errTimes > 5){
				return(array('status'=>false,'info'=>L('tooMany')));//$this->error(L('tooMany'));
			}
			
			
			if(isset($Member->email)) $where['email']=$Member->email;
			else $where['username']=$Member->username;
			$memberArr=$Member->field('userid,username,rank,logintype,loginstr,refereeid,email,pwd,mobile')->relation(true)->where($where)->find();
			if($memberArr['Verification']!="" && $memberArr['Verification']<0){
				$this->error("账号冻结");
			}
			$passwordmd5=md5($post['password']."_".$memberArr['jointime']."_".$memberArr['joinip']);
			
			/*if(C('SZ_YZ_GOOGLE') > 0 && substr($memberArr['rank'],-3,1) >= 2){
				if($memberArr['googlestr'] == ""){
					$Memberinfo = D('Memberinfo');
					$googlestr = $Memberinfo->field('googlestr')->where(array('userid'=>$memberArr['userid']))->find();	
					$memberArr['googlestr'] = $googlestr['googlestr'];
				}
				
				$ga = new GoogleController();
				$checkResult = $ga->verifyCode($memberArr['googlestr'], I('post.googlecode'), 2);
				if (!$checkResult) {
					$loginSave['submitstr'] = $post['googlecode'];
					$Detaillogin->add($loginSave);
					return(array('status'=>false,'info'=>L('googleWorng')));//$this->error(L('googleWorng'));
					exit;
				}
			}*/
			if (is_array($memberArr) || $memberArr['userid']>0){ // 登录验证数据
				 // 验证通过 执行登录操作				 
				 if($memberArr['pwd']==$passwordmd5 || $post['loginkey']==true){
					 $memberArr['pwd']="";
					 session('username',$memberArr['username']);  //设置session
					 session('userid',$memberArr['userid']);  //设置session
					 session('rank',$memberArr['rank']);  //设置session
					 
					 $userArray['rank'] = $memberArr['rank'];
					 for($i=1 ; $i <= strlen($userArray['rank']) ; $i++){
						$rank[$i] = substr($userArray['rank'],-$i,1);
					 }
					 
					 $memberArr['loginip'] = $loginSave['loginip'];
					 $memberArr['logintime'] = $loginSave['logintime'];
					 $memberArr['rank'] = $rank;
					 $memberArr['idcard'] = substr($memberArr['idcard'],0,5).'******';
					 $memberArr['pwd'] = NULL;
					 R('Public/setCookie',array('userInfo',$memberArr));
					 Cookie('nickname',$memberArr['nickname']);
					 cookie('loginTime',NOW_TIME);
					 $loginEdit['logintime']=NOW_TIME;
					 $loginEdit['loginip']=$ip;
					 $Member->where($where)->save($loginEdit);
					 $loginSave['userid'] = $memberArr['userid'];
					 $loginSave['loginresult'] = 'ok';
					 S('err'.$ip,NULL);
					 //记录登陆
					 $Detaillogin->add($loginSave);
					 return(array('status'=>true,'info'=>L('Login Success')));//$this->success(L('Login Success'),U('Trade/index'));
				 }else{
					 
					 //exit($memberArr['pwd']."==".$passwordmd5);
					 S('err'.$ip,$errTimes+1,600);
					 $loginSave['loginresult'] = 'passwordWrong'.",".'chanceTimes:'.$errTimes;
					 $loginSave['submitstr'] = $post['password'];
					 //记录登陆
					 $Detaillogin->add($loginSave);
					 return(array('status'=>false,'info'=>L('passwordWrong').L('chanceTimes',array('chances'=>(5-$errTimes)))));//$this->error('passwordWrong').",".L('chanceTimes',array('chances'=>$errTimes));//.(5-$errTimes).
				 }
				 
			}else{
				 $loginSave['loginresult'] = "not User";
				 $Detaillogin->add($loginSave);
				 return(array('status'=>false,'info'=>L('notUser')));//$this->error(L('notUser'));
			}
			
		}
		
	}
    public function qqLogin(){		
		$app_id = C('SZ_QQ_APP_ID');
		$app_key = C('SZ_QQ_APP_KEY');
		$callback = C('SZ_QQ_CALLBACK');
		$qq = new \Common\Api\QQConnect;
		/* callback返回openid和access_token */
		$back = $qq->callback($app_id , $app_key, $callback);
		//防止刷新
		empty($back) && $this->error("请重新授权登录",U('Login/index'));
		$user_info = $qq->get_user_info($app_id,$back['token'],$back['openid']);
		$Member = M('Member');
		$where['threepwd']=$back['openid'];
		$MemberArray = $Member->where("threepwd='".$back['openid']."'")->field('member_id,username,status')->find();
		if($MemberArray['member_id']!=""){ 
			session('USER_KEY_ID',$MemberArray['member_id']);
	        session('USER_KEY',$MemberArray['username']);//用户名
	        session('STATUS',$MemberArray['status']);//用户状态
			$this->error("登陆成功",U('Index/index'));
			
		}else{
			
			$add['username'] = $back['openid'];
			$add['threepwd'] = $back['openid'];
			$add['pwdtrade'] = md5('111111');
			
			
			if($Member->create($add)){
				$userid = $Member->add();
				if($userid){
					session('USER_KEY_ID',$userid);
			        session('USER_KEY',$back['openid']);//用户名
			        session('STATUS',0);//用户状态
			        $this->error("登陆成功",U('Index/index'));
				}else{
					$this->error("登陆失败",U('Login/index'));
				}
			}
		}
    }
    /**
     * 根据发送邮箱地址显示修改密码界面
     */
    public function resetPwd(){
        $token = I('key');
        if (empty($token)) {
            $this->success('无效的链接1', U('Index/index'));
            return;
        }
        $findpwd_info = M('Findpwd')->where("token = '$token'")->find();
        if ($findpwd_info === false) {
            $this->success('无效的链接', U('Index/index'));
            return;
        }
        if (time() - $findpwd_info['add_time'] > 24 * 60 * 60) {
        	M('Findpwd')->delete($findpwd_info['id']);
            $this->success('邮件已过期', U('Index/index'));
            return;
        }
        if(IS_POST){
            $verify = new Verify();
            if(!$verify->check($_POST['captcha'])){
                $data['status']=2;
                $data['info']="验证码输入错误";
                $this->ajaxReturn($data);
            }
            if(empty($_POST['pwd'])){
                $data['status']=2;
                $data['info']="请输入密码";
                $this->ajaxReturn($data);
            }
            if(!checkPwd($_POST['pwd'])){
                $data['status']=2;
                $data['info']="密码长度在6-20个字符之间";
                $this->ajaxReturn($data);
            }
            if($_POST['repwd'] != $_POST['pwd']){
                $data['status']=2;
                $data['info']="确认密码和密码不一致";
                $this->ajaxReturn($data);
            }
            $member_info = M('member')->where(array('member_id'=>$findpwd_info['member_id']))->find();
            if(!empty($member_info['idcard'])){
                if($_POST['idcard']!=$member_info['idcard']){
                    $data['status']=2;
                    $data['info']="身份证输入错误";
                    $this->ajaxReturn($data);
                }
            }
            $member_newPwd = I('pwd','','md5');
            $r = M('member')
                ->where(array('member_id'=>$member_info['member_id']))
                ->setField('pwd',$member_newPwd);
            if($r===false){
                $data['status']=2;
                $data['info']="服务器繁忙,请稍后重试";
                $this->ajaxReturn($data);
            }else{
                M('findpwd')->delete($findpwd_info['id']);
                $data['status']=1;
                $data['info']="修改成功";
                $this->ajaxReturn($data);
            }
        }else{
            $this->assign("keys",$token);
            $this->display();
        }
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
        //判断传过来的是手机号还是email
//         $data = array();
//         if(!checkEmail($email) && !checkMobile($email)){
//             $data['status'] = 2;
//             $data['msg'] = '请输入正确的邮箱或手机号码';
//             $this->ajaxReturn($data);
//         }
//         if(checkEmail($email)){
//             $where['email']  = $email;
//         }else{
            $where['username']  = $username;
//         }
        //检查用户是否存在
        $info =  M('Member')->where($where)->find();
        if(!$info){
            $data['status'] = 2;
            $data['msg'] = '用户不存在';
            $this->ajaxReturn($data);
        }
        //检查是否做了身份认证
//         if($info['idcard']){
//             //如果login_ip不存在那么就是第一次登录取注册IP
//             $old_login_ip = $info['login_ip']?$info['login_ip']:$info['ip'];
//             $new_ip = get_client_ip();
//             if($old_login_ip!=$new_ip){
//                 $data['status'] = 1;
//                 $data['msg'] = '系统监测到您的账号本次登录IP和上次不同，为了保障您的账户资产安全，请输入您在'.$this->config['name'].'预留的身份证上的出生日期；如还未实名认证，请联系客服认证。';
//                 $this->ajaxReturn($data);
//             }
//         }
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
        $this->redirect('Index/index');
    }

    /**
     * 读取消息库中有自己消息的列表并且存储至个人消息库中
     * @param $id 用户ID
     * @param $login_time 用户最后一次登录时间
     * @return bool 返回 成功失败
     */
    public function pullMessage($id,$login_time){
        if(empty($id)){
            return false;
        }
        if(empty($login_time)){
            return false;
        }
        //消息库
        $M_message_all = M('message_all');
        //用户消息库
        $M_message = M('message');
        $messageAllWhere['add_time'] = array('EGT',$login_time);
        $messageAllWhere['_string'] = " u_id= -1 or  u_id = $id";
        $message_info = $M_message_all->where($messageAllWhere)->select();
        if($message_info){
            foreach ($message_info as $vo) {
                $data[] = array(
                    'member_id'=>$id,
                    'title'=>$vo['title'],
                    'type' => $vo['type'],
                    'content'=> $vo['content'],
                    'add_time'=> $vo['add_time'],
                    'status' => 0,//未读
                    'message_all_id'=> $vo['id'],
                );
            }
            if($M_message->addAll($data)===false){
                return false;
            }
        }
        return true;
    }
}
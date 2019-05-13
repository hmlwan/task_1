<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-3-8
 * Time: 下午12:28
 */

namespace Admin\Controller;
use Think\Page;
use Think\Upload;
class MemberController extends AdminController {
    public function _initialize(){
        parent::_initialize();
    }
    /**
     * 会员列表
     */
    public function index(){
        $username = I('username');
        $member_id=I('member_id');
        if(!empty($username)){
            $where['m.username'] = array('like','%'.$username.'%');
        }
        if (!empty($member_id)){
            $where['m.member_id']=$member_id;
        }

        
//        $count      =  M('Member')->where($where)->count();// 查询满足要求的总记录数
        $count      =  M('Member')->alias('m')
            ->join('LEFT JOIN blue_member_info as i on i.member_id=m.member_id')
            ->where($where)
            ->count();

        $Page       = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        //给分页传参数
        setPageParameter($Page, array('username'=>$username,'member_id'=>$member_id));

        $show       = $Page->show();// 分页显示输出
        $list =  M('Member')->alias('m')
            ->field('m.*,i.stars,i.commision')
            ->join('LEFT JOIN blue_member_info as i on i.member_id=m.member_id')
            ->where($where)
            ->order("m.member_id desc ")
            ->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($list as $key =>$value){
            $qd_fg_num = M('member_fg')->where(array('member_id','type'=>1))->sum('fg_num');
            $fk_fg_num = M('member_fg')->where(array('member_id','type'=>2))->sum('fg_num');
            $list[$key]['qd_fg_num'] = $qd_fg_num? $qd_fg_num :0;
            $list[$key]['fk_fg_num'] = $fk_fg_num ? $fk_fg_num :0;
        }

        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
    }
    /**
     * 审核列表
     */
    public function auth_list(){

        $member_id = I('member_id');

        if(!empty($member_id)){
            $where['username'] = array('like','%'.$member_id.'%');
        }
        if (!empty($username)){
            $where['username']=$member_id;
        }
        $model = M('deposit_auth');
        $count      =  $model->where($where)->count();// 查询满足要求的总记录数

    	$Page       = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    
    	//给分页传参数
    	//setPageParameter($Page, array('email'=>$email,'member_id'=>$member_id));
    
    	$show       = $Page->show();// 分页显示输出
    
    	$list =  $model->alias('a')
            -> field('a.*, b.username,b.phone')
            ->join('__MEMBER__ b on a.member_id=b.member_id')
            ->where($where)
            ->order(" a.status asc, a.id desc ")
            ->limit($Page->firstRow.','.$Page->listRows)->select();

    	$this->assign('list',$list);// 赋值数据集
    	$this->assign('page',$show);// 赋值分页输出
    	$this->display(); // 输出模板
    }
    public function auth_do(){

    	if(IS_GET){
    		$id = I('get.id');
    		$status = I('get.status');
    		$model = M('deposit_auth');
    		$res = $model->where(array('id'=>$id))->find();
    		if(empty($res)){
    			$this->error('参数错误！');
    		}
    		$save_data = array(
                'status' =>$status,
                'op_time' => time(),
                'op_man' => $_SESSION['admin_userid']
            );
            $rs = $model
                ->where(array('id'=>$id))
                ->save($save_data);

            if($rs !== false){
                $this->success('操作成功！', U('auth_list'));exit;
            }else{
                $this->error('操作失败');
            }
    	}
    }
    /*匹配优先匹配者*/
    public function add_member_priority(){
        $model = M('deposit_auth');
        if(IS_POST){
            $sk_id = I('sk_id');
            $fk_id = I('fk_id');
            $pay_money = I('pay_money');
            if($_FILES["img"]["tmp_name"]){
                $_POST['img']=$this->upload($_FILES["img"]);
                if (!$_POST['img']){
                    $this->error('请上传凭证');
                }
            }
            if(!$sk_id || !$fk_id){
                $this->error('未知错误');
            }
            $d_res = $model->where(array('member_id'=>$fk_id))->save(array('status'=>1));

            if($d_res){
                /*更新用户信息表*/
                M('member_info')->where(array('member_id'=>$fk_id))
                    ->save(array(
                        'is_pay_deposit' =>1,
                        'deposit' => $this->config['dk_money'],
                        'is_other_receive_payable' => 0,
                        'op_time' => time(),
                        'op_man' => $_SESSION['admin_userid'],
                    ));
                $pro_data = array(
                    'fk_id' => $fk_id,
                    'sk_id' => $sk_id,
                    'task_id' => 0,
                    'reward' => $pay_money,
                    'fg_num' => 0,
                    'status' => 0,
                    'create_time' => time(),
                );
                 M("qd_record")->add($pro_data);
                $this->success('提交成功', U('auth_list'));
            }else{
                $this->error('提交失败');
            }
        }else{
            $id = I('id');
            $res = $model->where(array('id'=>$id))->find();
            /*系统自动匹配收款方*/
            $sk_info = M('member')->alias('m')
                ->join('LEFT JOIN blue_member_info as i on i.member_id=m.member_id')
                ->where(array("m.member_id"=>array('neq',$res['member_id']),'is_priority'=>1))
                ->order('rand()')
                ->limit(1)
                ->select();
            if(!$sk_info){
                $model
                    ->where(array('id'=>$id))
                    ->save(array('status'=>2));
                M('member_info')
                    ->where(array('member_id'=>$res['member_id']))
                    ->save(array(
                        'deposit' => $res['pay_money'],
                        'is_pay_deposit' => 0,
                    ));
                $this->error("未找到优先匹配者，请检查");
            }
            $info = $sk_info[0];
            $info['fk_id'] = $res['member_id'];
            $info['img'] = $res['img'];

            $this->assign('info',$info);
            $this->display();

        }
    }

    /**
     * 添加会员
     */
    public function addMember(){
        if(IS_POST){
            $M_member = D('Member');
            $_POST['ip'] = get_client_ip();
            $_POST['reg_time'] = time();

            if($r = $M_member->create()){
                //头像上传
                $upload = new Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
                $upload->savePath  =     'Member/Head/'; // 设置附件上传（子）目录
                // 上传文件
                $file_path = '';
                if(!$_FILES['head']['error']){
                    $info   =   $upload->upload();
                    $file_path = ltrim($upload->rootPath.$info['head']["savepath"].$info['head']["savename"],'.');
                }
                $r['head'] = $file_path;
                if($M_member->add($r)){
                    $this->success('添加成功',U('Member/index'));
                    return;
                }else{
                    $this->error('服务器繁忙,请稍后重试');
                    return;
                }
            }else{
                $this->error($M_member->getError());
                return;
            }
        }else{
            $this->display();
        }
    }
    /**
     * 添加个人信息
     */
    public function saveModify(){

        $member_id = I('get.member_id','','intval');
        $M_member = D('Member');
        if(IS_POST){

            if (!$data=$M_member->create()){ // 创建数据对象
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($M_member->getError());
                return;
            }else {
                $where['member_id'] = $_POST['member_id'];
                $r = $M_member->where($where)->save();
                if($r){
                    $this->success('添加成功',U('Member/index'));
                    return;
                }else{
                    $this->error('服务器繁忙,请稍后重试');
                    return;
                }
            }
        }else{
            $where['member_id'] = $member_id;
            $list = $M_member->where($where)->find();
            $this->assign('list',$list);
            $this->display();
        }
    }
    /**
     * 显示自己推荐列表
     */
    public function show_my_invit(){
        $member_id = $_GET['member_id'];
        if(empty($member_id)){
            $this->error('参数错误');
            return;
        }
        $M_member = M('Member');
        $count   = $M_member->alias('m')
            ->join("LEFT JOIN blue_member_info i on i.member_id=m.member_id")
            ->where(array('m.pid'=>$member_id))
            ->count();// 查询满足要求的总记录数
        $Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $my_invit = $M_member->alias('m')
            ->join("LEFT JOIN blue_member_info i on i.member_id=m.member_id")
            ->where(array('m.pid'=>$member_id))
            ->order("m.reg_time desc ")
            ->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('my_invit',$my_invit);
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板

    }
    /**
     * 修改会员
     */
    public function saveMember(){
        $member_id = I('get.member_id','','intval');
        $M_member = D('Member');
        if(IS_POST){
            $member_id = I('post.member_id','','intval');
            $where['member_id'] = $member_id;
            $list = $M_member->where($where)->find();
            //头像上传
            $upload = new Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
            $upload->savePath  =     'Member/Head/'; // 设置附件上传（子）目录
            // 上传文件
            if(!$_FILES['head']['error']){
                $info   =   $upload->upload();
                $file_path = ltrim($upload->rootPath.$info['head']["savepath"].$info['head']["savename"],'.');
            }
            $_POST['head'] = empty($file_path) ? I('post.headold'):$file_path;
            //头像上传end

            if($_POST['username']!=$list['username']){
                $where = null;
                $where['member_id']  = array('NEQ',$member_id);
                $where['username'] = $_POST['username'];
                if($M_member->field('nick')->where($where)->select()){
                    $this->error('用户名重复');
                    return;
                }
            }
            if($_POST['phone']!=$list['phone']){
                $where = null;
                $where['member_id']  = array('NEQ',$member_id);
                $where['phone'] = $_POST['phone'];
                if($M_member->field('phone')->where($where)->select()){
                    $this->error('手机号重复');
                    return;
                }
            }
            $_POST['pwd'] =  $_POST['pwd']?I('post.pwd','','md5'):$list['pwd'];
            $r = $M_member->save($_POST);
            $stars = I('stars');
            M('Member_info')->where(array('member_id'=>$where['member_id']))->save(array('stars'=>$stars));

            if($r!==false){
                $this->success('修改成功',U('Member/index'));
                return;
            }else{
                $this->error('修改失败');
                return;
            }
        }else{
            if($member_id){
                $list = $M_member->get_info_by_id($member_id);

                $this->assign('list',$list);
                $this->display();
            }else{
                $this->error('参数错误');
                return;
            }
        }
    }
    /**
     * 删除会员
     */
    public function delMember(){
        $member_id = I('get.member_id','','intval');
        $M_member = M('Member');
        //判断还有没有余额
        $where['member_id']= $member_id;
        $member = $M_member->where($where)->find();
        $member_currency = M('Currency_user')->where($where)->find();
        if($member['rmb']>0||$member['forzen_rmb']>0||$member_currency['num']>0||$member_currency['forzen_num']>0){
            $this->error('因账户有剩余余额,禁止删除');
            return;
        }
        $r[] = $M_member->delete($member_id);
        $r[] = M('Currency_user')->where($where)->delete();
        $r[] = M('Finance')->where($where)->delete();
        $r[] = M('Orders')->where($where)->delete();
        $r[] = M('Trade')->where($where)->delete();
        $r[] = M('Withdraw')->where('uid='.$member_id)->delete();
        $r[] = M('Pay')->where($where)->delete();
        if($r){
            $this->success('删除成功',U('Member/index'));
            return;
        }else{
            $this->error('删除失败');
            return;
        }
    }
    /*
     * 解封用户
     * */
    public function lockMember(){
        $member_id = I('get.member_id','','intval');
        $is_lock = I('get.is_lock','','intval');
        $M_member = M('Member');

        $where['member_id']= $member_id;
        $r = $M_member->where($where)->save(array('is_lock'=>$is_lock));

        if($r){
            $this->success('操作成功',U('Member/index'));
            return;
        }else{
            $this->error('操作失败');
            return;
        }
    }

    /*查看账户信息*/
    public function show(){
        if(IS_POST){
            $M_member = D('member_info');
            $id= I('member_id');
            $stars = I("stars");
            //头像上传
            $upload = new Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
            $upload->savePath  =     'Member/Head/'; // 设置附件上传（子）目录
            // 上传文件
            if($_FILES['head']['tmp_name']){
                $info   =   $upload->upload();
                $head = ltrim($upload->rootPath.$info['head']["savepath"].$info['head']["savename"],'.');
            }else{
                $head = I('headold');
            }
            if($_FILES['alipay_logo']['tmp_name']){
                $info   =   $upload->upload();
                $alipay_logo = ltrim($upload->rootPath.$info['alipay_logo']["savepath"].$info['alipay_logo']["savename"],'.');
            }else{
                $alipay_logo = I('alipay_logoold');
            }
            if($_FILES['wechat_logo']['tmp_name']){
                $info   =   $upload->upload();
                $wechat_logo= ltrim($upload->rootPath.$info['wechat_logo']["savepath"].$info['wechat_logo']["savename"],'.');
            }else{
                $wechat_logo = I('wechat_logoold');
            }
            $r['head'] = $head;
            $r['alipay_logo'] = $alipay_logo;
            $r['wechat_logo'] = $wechat_logo;
            $r['stars'] = $stars;
            $is_exist = M('member_info')->where("member_id=$id")->find();
            if($is_exist){
                $res =  $M_member->where("member_id=$id")->save($r);
            }else{
                $r['member_id'] = $id;
                $res =  $M_member->add($r);

            }
            if($res){
                $this->success('添加成功',U('Member/index'));
                return;
            }else{
                $this->error('服务器繁忙,请稍后重试');
                return;
            }
        }else{
            $member_id = I('member_id');
            $info = D("member")->get_info_by_id($member_id);
            $this->assign('list',$info);
            $this->assign('member_id',$member_id);
            $this->display();
        }
    }
    /**
     * ajax验证昵称是否存在
     */
    public function ajaxCheckNick($nick){
        $nick = urldecode($nick);
        $data =array();
        $M_member = M('Member');
        $where['nick']  = $nick;
        $r = $M_member->where($where)->find();
        if($r){
            $data['msg'] = "昵称已被占用";
            $data['status'] = 0;
        }else{
            $data['msg'] = "";
            $data['status'] = 1;
        }
        $this->ajaxReturn($data);
    }
    /**
     * ajax手机验证
     */
    function ajaxCheckPhone($phone) {
        $phone = urldecode($phone);
        $data = array();
        if(!checkMobile($phone)){
            $data['msg'] = "手机号不正确！";
            $data['status'] = 0;
        }else{
            $M_member = M('Member');
            $where['phone']  = $phone;
            $r = $M_member->where($where)->find();
            if($r){
                $data['msg'] = "此手机已经绑定过！请更换手机号";
                $data['status'] = 0;
            }else{
                $data['msg'] = "";
                $data['status'] = 1;
            }
        }
        $this->ajaxReturn($data);
    }

   /*成为优先匹配者*/
   public function become_priority(){
       $member_id = I('get.member_id','','intval');

       $where['member_id']= $member_id;
       $member_info = M('member_info')->where($where)->find();
       if(!$member_info){
           $this->error('该用户还未上传收款二维码');
           return;
       }
       $r = M('member_info')->where($where)->save(array('is_priority'=>1));
       if($r){
           $this->success('操作成功',U('Member/index'));
           return;
       }else{
           $this->error('操作失败');
           return;
       }
   }
    
    
}
<?php
namespace Home\Controller;
use Common\Controller\CommonController;
class MemberCommentController extends CommonController {
	public function add(){
		// if(!$this->checkLogin()){
			// $data['status']=-1;
			// $data['info']='请先登录再进行此操作';
			// $this->ajaxReturn($data);
		// }
		$add['comment']=I('post.comment');
		if (empty($add['comment'])){
			$msg['status']=-1;
			$msg['info']='请填写留言内容';
			$this->ajaxReturn($msg);
		}
		$add['currency_id']=I('post.currency_id');
		$add['member_id']=$_SESSION['USER_KEY_ID'];
		if($this->getUserSpeakById($add['member_id'])){
		    $msg['status']=-3;
		    $msg['info']='由于发布信息过于频繁被禁言';
		    $this->ajaxReturn($msg);
		}
		$add['add_time']=time();
		$res=M('Member_comment')->add($add);
		if ($res){
		    $msg['status']=1;
		    $msg['info']='操作成功';
		    $this->ajaxReturn($msg);
		}else {
		    $msg['status']=-2;
		    $msg['info']='操作未成功';
		    $this->ajaxReturn($msg);
		}
	}
	/*
	 * 获取用户留言权限
	 */
	private function getUserSpeakById($id){
		$user=M("Member")->field('speak')->where("member_id=".$id)->find();
		return $user['speak'];
	}
    //获取挂单记录
    public function getComment(){
    	$this->ajaxReturn($membercomment=$this->getMemberCommentByCurrencyid());
     
    }
}
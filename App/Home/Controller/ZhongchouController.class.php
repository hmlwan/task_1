<?php
namespace Home\Controller;
use Common\Controller\CommonController;
class ZhongchouController extends CommonController {
	protected  $currency;
	protected  $entrust;
	protected  $trade;
	public function _initialize(){
		parent::_initialize();
		$this->currency=M('Currency');
		$this->entrust=M('Entrust');
		$this->trade=M('Trade');
	}
	//空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	//众筹页面显示
	public function index(){
		$this->checkZhongchou();
		$count = M('Issue')->join("left join ".C("DB_PREFIX")."currency on ".C("DB_PREFIX")."currency.currency_id=".C("DB_PREFIX")."issue.currency_id")->count();// 查询满足要求的总记录数
		$Page  = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
	
		$list=$list=M('Issue')
			->field(C("DB_PREFIX")."issue.*,".C("DB_PREFIX")."currency.currency_name as name,".C("DB_PREFIX")."currency.currency_logo as logo")
			->join("left join ".C("DB_PREFIX")."currency on ".C("DB_PREFIX")."currency.currency_id=".C("DB_PREFIX")."issue.currency_id")
			->limit($Page->firstRow.','.$Page->listRows)
			->order('end_time desc')->select();
			

		//算进度
		foreach ($list as $key=>$vo) {
			$alllogmoney = M('Issue_log')->field("sum(num) as alllogmoney")->where(array('iid'=>$vo['id']))->find();
			$bili = round(($alllogmoney['alllogmoney']/$vo['num'])*100,4);
			if ($bili<0.001){
				$bili=0.001;
			}
// 			if ($bili>100){
// 				$bili=100;
// 			}
			$list[$key]['bl'] = $bili;
		}
		$this->assign('page',$show);
		$this->assign('list',$list);
		$this->display();
	}
	//众筹详细页面显示
	public function details(){
		if(empty($_GET['id'])){
			$this->redirect('Public:404');
		}
		$this->checkZhongchou();
		$list=M('Issue')
			->field(C("DB_PREFIX")."issue.*,".C("DB_PREFIX")."currency.currency_name as name")
			->join("left join ".C("DB_PREFIX")."currency on ".C("DB_PREFIX")."currency.currency_id=".C("DB_PREFIX")."issue.currency_id")
			->order('ctime desc')
			->where('id='.$_GET['id'])
			->find();
		$sum=M('Issue_log')->field('sum(price) as sumprice,sum(1) as sumpeople,sum(num) as sumnum')->group('iid')->where('iid='.$_GET['id'])->find();
		$this->assign('sum',$sum);
		$list['buy_name'] = $list['buy_currency_id']==0?"人民币":M('Currency')->field('currency_name')->where("currency_id='{$list['buy_currency_id']}'")->find()['currency_name'];
		$list['buy_mark'] = $list['buy_currency_id']==0?"RMB":M('Currency')->field('currency_mark')->where("currency_id='{$list['buy_currency_id']}'")->find()['currency_mark'];
		$list['zhongchou_success_bili']=$list['zhongchou_success_bili']*100;
		$list['zhongchou_success_num']=$list['num']*$list['zhongchou_success_bili']/100-$list['num_nosell'];
		$uid=$_SESSION['USER_KEY_ID'];
		if (!empty($uid)){
			$list['buy_count']  =$this->getIssuecountById($uid,$_GET['id']);
		}
		if(!$list){
			$this->redirect('Public:404');
		}
		$list['info']=html_entity_decode($list['info']);
		//查询个人记录
		$where['uid']=$_SESSION['USER_KEY_ID'];
		$where['iid']=$list['id'];
		$log=M('Issue_log')->field('*,num*price as count')->where($where)->select();
		$num_buy=M('Issue_log')->where($where)->sum('num');
		//查询账户余额
		if (!empty($uid)){
			if($list['buy_currency_id']!=0){
				$buy_num = M('Currency_user')->field('num')->where("member_id=$uid and currency_id={$list['buy_currency_id']}")->find();
				$buy_num = $buy_num['num'];
			}else{
				$buy_num = M('Member')->field('rmb')->where("member_id=$uid")->find();
				$buy_num = $buy_num['rmb'];
			}
		}
		$this->assign('id',$list['id']);
		$this->assign('num_buy',$num_buy);
		$this->assign('log',$log);
		$this->assign('list',$list);
		$this->assign('buy_num',$buy_num);

		$grade_id=M('member')->where(['id'=>$uid])->getField('grade_id');
		$this->grade=M('member_grade')->where(['id'=>$grade_id])->find();
		
		$this->display();
	}
	//处理众筹
	public function run(){
		$num=intval(I('post.num'));
		$id=I('post.id');
		$uid=$_SESSION['USER_KEY_ID'];

		$buy_currency_id = I('post.buy_currency_id');
//    	$member=$this->member;
		if($buy_currency_id!=0){
			$buy_num = M('Currency_user')->field('num')->where("member_id=$uid and currency_id=$buy_currency_id")->find();
			$buy_num = $buy_num['num'];
		}else{
			$buy_num = M('Member')->field('rmb')->where("member_id=$uid")->find();
			$buy_num = $buy_num['rmb'];
		}
		$config=$this->config;
		$issue=M('Issue')->where("id=$id")->find();

		$where['uid']=$_SESSION['USER_KEY_ID'];
		$where['iid']=$id;
		$num_buy=M('Issue_log')->where($where)->sum('num');

		//获取会员一次众筹有几次记录
		$count=$this->getIssuecountById($uid,$id);
		if($issue['limit_count']<=$count){
			$data['status']=0;
			$data['info']='认筹次数不能超过限购次数';
			$this->ajaxReturn($data);
		}
		if($issue['limit']<$num){
			$data['status']=0;
			$data['info']='认筹数量不能超过限购数量';
			$this->ajaxReturn($data);

		}
		if($num>$issue['one_limit']){
			$data['status']=0;
			$data['info']='认筹数量不能超过每次限购数量'.$issue['one_limit']."个";
			$this->ajaxReturn($data);

		}
		if($issue['min_limit']>$num){
			$data['status']=0;
			$data['info']='认筹数量不能小于最小认筹数量';
			$this->ajaxReturn($data);
		}
// 		if($issue['deal']<$num){
// 			$data['status']=0;
// 			$data['info']='认筹数量不能超过剩余数量';
// 			$this->ajaxReturn($data);
// 		}
		if($issue['limit']<$num_buy+$num){
			$data['status']=0;
			$data['info']='您已超过总认筹限购';
			$this->ajaxReturn($data);
		}

		//需要支付money  认购优惠后
		$grade_id=M('member')->where(['member_id'=>$uid])->getField('grade_id');
		$rengouyouhui=M('member_grade')->where(['id'=>$grade_id])->getField('rengouyouhui')?:0;
		$need_money=round($num*$issue['price']*(1-$rengouyouhui/100),2);

		$youhui_money=round($num*$issue['price']*($rengouyouhui/100),2);

		if($buy_num<$need_money){
			$data['status']=0;
			$data['info']='您的账户余额不足';
			$this->ajaxReturn($data);
		}
		//修改会员表人民币字段
//    	$rs=M('Member')->where("member_id=$uid")->setDec('rmb',$num*$issue['price']*$config['bili']);
		if($buy_currency_id!=0){
			$rs = M('Currency_user')->where("member_id=$uid and currency_id={$issue['buy_currency_id']}")->setDec('num',$need_money);
		}else{
			$rs = M('Member')->where("member_id=$uid")->setDec('rmb',$need_money);
		}
		//添加财务日志表
		if($rs){
			//添加认购记录
			$arr['iid']=$id;
			$arr['uid']=$uid;
			$arr['cid']=$issue['currency_id'];
			$arr['num']=$num;
			$arr['deal']=$num;
			$arr['price']=$issue['price'];
			$arr['add_time']=time();
			$arr['buy_currency_id']=$buy_currency_id;
			$arr['status']=0;
			$arr['youhui_money']=$youhui_money;
			M('Issue_log')->add($arr);
			//修改会员币种数量
			// if($issue['is_forzen']==0){
				// M('Currency_user')->where("member_id=$uid and currency_id={$issue['currency_id']}")->setInc('forzen_num',$num);
			// }else{
				// M('Currency_user')->where("member_id=$uid and currency_id={$issue['currency_id']}")->setInc('num',$num);
			// }
				$this->addFinance($uid, 8, '众筹扣款'.$need_money, $need_money, 2, $buy_currency_id);
				// $this->addFinance($uid, 9, '众筹获取'.$num, $num, 1, $issue['currency_id']);
			//添加信息表
			$this->addMessage_all($uid, -2, '认购成功', '您参与的众筹项目'.$issue['title'].'已成功,扣除交易币'.$need_money.',获取众筹币'.$num);
			//添加众筹推荐人员奖励
			
			if($issue['zc_awards_status']==1){
				$this->setAwards($issue,$uid,$num);
			}
			$data['status']=0;
			$data['info']='认筹成功';
			$this->ajaxReturn($data);
		}else{
			//添加信息表
			$this->addMessage_all($uid, -2, '众筹失败', '您参与的众筹项目'.$issue['title'].'未成功');
			$data['status']=0;
			$data['info']='认筹失败';
			$this->ajaxReturn($data);
		}
	}

	public function setAwards($arr,$uid,$num){
		//查找此用户下推荐人 一代 二代
		$u_info = M('Member')->field('member_id,pid')->where("member_id = '{$uid}'")->find();
		if(empty($u_info['pid'])){
			return true;
		}

		$one_info = M('Member')->field('status,member_id,pid,grade_id')->where("member_id = '{$u_info['pid']}'")->find();
		$one_where['member_id'] = $one_info['member_id'];
		$one_where['currency_id'] = $arr['zc_awards_currency_id'];
		//$num = $arr['zc_awards_one_ratio']/100*$num;

		//下线认购奖励
		$xiaxianrengou=M('member_grade')->where(['id'=>$one_info['grade_id']])->getField('xiaxianrengou')?:0;
		$num=$xiaxianrengou/100*$num;
		
		if ($one_info['status']==1&&$u_info['is_lock']==0){//只有用户正常情况才会有推荐奖励
			//dump($one_info);die();
    		  $r = M('Currency_user')->where($one_where)->setInc('num',$num);
    		  $this->addFinance($one_info['member_id'], 12, '众筹推荐奖励'.$num, $num, 1, $arr['zc_awards_currency_id']);
		}
		
		if(empty($one_info['pid'])){
			return true;
		}
		
		$two_info = M('Member')->field('status,member_id,pid')->where("member_id = '{$one_info['pid']}'")->find();
		$two_where['member_id'] = $two_info['member_id'];
		$two_where['currency_id'] = $arr['zc_awards_currency_id'];
		//$num = $arr['zc_awards_two_ratio']/100*$num;

		//下线认购奖励
		$xiaxianrengou=M('member_grade')->where(['id'=>$two_info['grade_id']])->getField('xiaxianrengou')?:0;
		$num=$xiaxianrengou/100*$num;

		if ($two_info['status']==1&&$two_info['is_lock']==0){//只有用户正常情况才会有推荐奖励
    		$r = M('Currency_user')->where($two_where)->setInc('num',$num);
    		$this->addFinance($two_info['member_id'], 12, '众筹推荐奖励'.$num, $num, 1, $arr['zc_awards_currency_id']);
		}
		if($r){
			return true;
		}
	}
}
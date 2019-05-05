<?php
namespace Home\Controller;
use Common\Controller\CommonController;
use Think\Model;
class GoodsController extends CommonController {
 	public function _initialize(){
 		parent::_initialize();
 		if(!$this->checkLogin()){
 			
 			$this->display('Login/index');
 		}
 		//交易时间段限制
 		$time=strtotime(date('Y-m-d'));
 		$start_time=$time+$this->config['jiaoyi_start_hour']*3600+$this->config['jiaoyi_start_minute']*60;
 		$over_time=$time+$this->config['jiaoyi_over_hour']*3600+$this->config['jiaoyi_over_minute']*60;
 		if(time()<$start_time||time()>$over_time){
 			$data['status']=-10;
 			$data['info']='交易未开启，请在交易时间内进行交易。';
 			$this->ajaxReturn($data);
 		}
 	}
	//空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	public  function index(){
		$where['status']=1;
		if(($_GET['cat_id'])!=''){
			$this->assign("cat_id",$_GET['cat_id']);
			$where['cat_id']=$_GET['cat_id'];
		}else{
			$this->assign("cat_id",'');
		}
		$Goods = M('goods'); 
		$counts = $Goods->where($where)->count();
		$Page=new \Think\Page($counts,15);
		setPageParameter($Page,$where);
		$show=$Page->show();
		$list = $Goods->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
	    foreach ($list as $k=>$v){
			$list[$k]['sellnum']=intval(M("goods_user")->where(array(
			    "goods_id"=>$v['id'],
			    'status'=>array('in','0,1')
			))->sum("num"));
			$list[$k]['shengyu_num']=$v['num']-$list[$k]['sellnum'];
		}
		$this->page=$show;
		$this->assign('list',$list);// 赋值数据集
		//显示商品分类
		$category=M("goods_category")->select();
		$this->assign("category",$category);
		//热销
		$hot=$Goods->where("status = 1 and position =1")->limit(4)->select();
		$this->assign("hot",$hot);
		$this->display('Goods_index');
	}
	public function detail(){
		$id=intval($_GET['id']);
		if(empty($id)) $this->error('参数错误！');
		$goods=M("goods")->where(array('id'=>$id,'status'=>1))->find();
		if(empty($goods)) $this->error('商品不存在或者已下架！');
		$map['goods_id']=$id;
		$map['status']=array('in','0,1');
	    $goods['sell_num']=M("goods_user")->where($map)->sum('num');
	    $goods['shengyu_kucun']=$goods['num']-$goods['sell_num'];
	    $this->assign("goods",$goods);
		//热销
		$hot=M("goods")->where("status = 1 and position =1")->limit(4)->select();
		$this->assign("hot",$hot);
		$this->display('Goods_view');
	}
	//购买商品
	public function goods_buy(){
	    //检查购买数量 电话 地址 备注 是否合法
		if(empty($_POST['num'])){
			$arr['status']=2;
			$arr['info']="请输入数量";
			$this->ajaxReturn($arr);
		}
		if($_POST['num']<0 ||!is_numeric($_POST['num'])){
		    $arr['status']=0;//不能《0，不能非数字
		    $arr['info']="输入数量错误，必须为正整数";
		    $this->ajaxReturn($arr);
		}
		$a=preg_match("/^[0-9]*[1-9][0-9]*$/",$_POST['num'], $m);
		if(!$a){//不能是小数
		    $data['status']=-1;
		    $data['info']='输入数量错误，必须为正整数';
		    $this->ajaxReturn($data);
		}
		if(empty($_POST['tel'])){
			$arr['status']=3;
			$arr['info']="请输入电话号码";
			$this->ajaxReturn($arr);
		}
		$check_tel=preg_match_all("/^((0\d{2,3}-\d{7,8})|(1[3584]\d{9}))$/", $_POST['tel']);
		if(!$check_tel){
		    $arr['status']=3;
		    $arr['info']="请正确输入电话号码";
		    $this->ajaxReturn($arr);
		}
		if(empty($_POST['address'])){
			$arr['status']=4;
			$arr['info']="请输入地址";
			$this->ajaxReturn($arr);
		}
/* 		$address_check=preg_match("/[\d|A-z|\u4E00-\u9FFF]+$/",$_POST['address']);
		if(!$address_check){
		    $arr['status']=3;
		    $arr['info']="收货地址不能含有非法字符";
		    $this->ajaxReturn($arr);
		} */
		//检查商品状态是否可买
		$goodid=$_POST['id'];
		$goods=M('goods')->where("id=".$goodid)->find();
		if(empty($goods)||$goods['status']!=1){
		    $arr['status']=5;
			$arr['info']="无效商品无法购买";
			$this->ajaxReturn($arr);
		}
		
		$goodsuser_count=M('goods_user')->where(array(
		    "goods_id"=>$goodid,
		    "status" =>array('in','0,1')
		))->sum('num');
		if($goodsuser_count+$_POST['num']>$goods['num']){//判断数量
			
		    $arr['status']=8;
			$arr['info']="库存不足，无法购买";
			$this->ajaxReturn($arr);
		}
		//检查购买账户资金是否足够
		$rmb=$_POST['num']*$goods['money_new'];	
		if($goods['currency_id']==-1){
		    $user_rmb=M("member")->where("member_id=".$_SESSION['USER_KEY_ID'])->find();
		    if($user_rmb['rmb']<$rmb ){
		        $arr['status']=6;
		        $arr['info']="您的金额不足，无法购买";
		        $this->ajaxReturn($arr);
		    }
		}else{
		    $user_xnb=M("currency_user")->where(array(
		            'member_id'=>$_SESSION['USER_KEY_ID'],
		            'currency_id'=>$goods['currency_id']
		        ))->find();
		    if($user_xnb['num']<$rmb ){
		        $arr['status']=6;
		        $arr['info']="您的金额不足，无法购买";
		        $this->ajaxReturn($arr);
		   }
		}
		//添加购买订单
		$transDb=new Model();
		$transDb->startTrans();
		$data['uid']=$_SESSION['USER_KEY_ID'];
		$data['goods_id']=$goodid;
		$data['tel']=$_POST['tel'];
		$data['address']=$_POST['address'];
		$data['num']=$_POST['num'];
		$data['money']=$rmb;
		$data['status']=0;
		$data['add_time']=time();
		$data['currency_id']=$goods['currency_id'];
		$data['currency_name']=$goods['currency_name'];
		$data['note']=$_POST['note'];
	    $re=$transDb->table('yang_goods_user')->add($data);
	    //订单添加成功，开始执行预扣款动作
	    if($re){   
	        //人民币冻结金额
		    if($goods['currency_id']==-1){
		        $now_money=$user_rmb['rmb']-$rmb;
		        $frozen_money=$user_rmb['forzen_rmb']+$rmb;
		        $res= $transDb->table('yang_member')->where("member_id=".$_SESSION['USER_KEY_ID'])->save(array(
		            "rmb"=>$now_money,
		            'forzen_rmb'=>$frozen_money
		        ));
		    }else{//虚拟币冻结数量
		        $now_xnb=$user_xnb['num']-$rmb;
		        $frozen_xnb=$user_xnb['forzen_num']+$rmb;
		        $res=$transDb->table('yang_currency_user')->where(array(
		            'member_id'=>$_SESSION['USER_KEY_ID'],
		            'currency_id'=>$goods['currency_id']
		        ))->save(array(
		            "num"=>$now_xnb,
		            'forzen_num'=>$frozen_xnb
		        ));
		    }
		    
		}
		//预扣款成功，确认下单成功
		if($res){
		    $transDb->commit();
		    $arr['status']=1;
		    $arr['info']="下单成功";
		    $this->ajaxReturn($arr);
		}else{
		    $transDb->rollback();
		    $arr['status']=7;
		    $arr['info']="购买失败";
		    $this->ajaxReturn($arr);
	    }
	}
   public  function my_goods(){
	     $map['uid']=$_SESSION['USER_KEY_ID'];
	     $gu_status=$_POST['gu_status'];
	     if($gu_status!=NULL){
	         if($gu_status!=-5){
	             $map['status']=$gu_status;
	         }else{
	             $gu_status=-5;
	             
	         }
	     }
	      
	     $this->pass_status=$gu_status;
	      
	     $GU = M('goods_user'); // 实例化User对象
	     $count      = $GU->where($map)->count();
	     $Page       = new \Think\Page($count,20);
	     if($gu_status!=-5){
	         $Page->parameter['status']=$gu_status;
	     }
	     $show       = $Page->show();
	     $list=$GU->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
	     foreach($list as $k=>$v){
	         $goods_info=M('goods')->where('id='.$list[$k]['goods_id'])->find();
	         $user=M('member')->where('member_id='.$list[$k]['uid'])->field('email')->find();
	         $list[$k]['title']=$goods_info['title'];
	         $list[$k]['pic']=$goods_info['pic'];
	         $list[$k]['add_time']=$goods_info['add_time'];
	         $list[$k]['username']=$user['email'];
	         $list[$k]['status_gu']=$this->gu_status($v['status']);
	     
	     }
	     $this->assign('list',$list);// 赋值数据集
	     $this->assign('page',$show);// 赋值分页输出
	     $this->display('my_goods');
	 }
	 /**
	  * 格式化购买状态
	  */
	 private function gu_status($status){
	     switch ($status){
	         case -1:$name="审核未通过";break;
	         case 0:$name="未审核";break;
	         case 1:$name="审核通过";break;
	         default:$name="未知状态";
	     }
	     return $name;
	 }

}
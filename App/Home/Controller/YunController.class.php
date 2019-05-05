<?php
namespace Home\Controller;
use Common\Controller\CommonController;
use Think\Model;
class YunController extends CommonController {
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
		if(!empty($_GET['cat_id'])){
			$this->assign("cat_id",$_GET['cat_id']);
			$where['cat_id']=$_GET['cat_id'];
		}else{
			$this->assign("cat_id",0);
		}
		if(!empty($_GET['money_cat'])){
			$where['money_cat']=$_GET['money_cat'];
			$this->assign("money_cat",$_GET['money_cat']);
			
		}else{
			$this->assign("money_cat",0);
		}
		$Goods = M('Yun'); // 实例化User对象
		$counts = $Goods->where($where)->count();
		$Page=new \Think\Page($counts,15);
		setPageParameter($Page,$where);
		$show=$Page->show();
		$list = $Goods->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
	    //dump($_GET['money_cat']);dump($list);
		foreach ($list as $k=>$v){
			$list[$k]['count_all']=intval(M("Yun_user")->where("goods_id={$v['id']} and status = 1 and qishu=".$v['qishu'])->sum("num"));
			$list[$k]['shengyu_num']=$v['total_num']-$list[$k]['count_all'];
			$list[$k]['percent']=$list[$k]['count_all']/$v['total_num']*100;
			
			if($v['currency_id']==-1){
				$list[$k]['mark']='RMB';
				$list[$k]['buy_num']=$list[$k]['money'];
				$this->currency='RMB';
			}else{
				$sys=M('currency')->where('currency_id='.$list[$k]['currency_id'])->find();
				$list[$k]['mark']=$sys['currency_mark'];
				$curr=$this->getCurrencyMessageById($sys['currency_id']);
				$curr=array_merge($list,$curr);
				if($curr['new_price']==NULL){
						$list[$k]['buy_num']=$list[$k]['money'];
					}else{
					    $bi_num=round($list[$k]['money']/$curr['new_price'],0);
					    if($bi_num==0){
					        $list[$k]['buy_num']=1;
					    }else{
					        $list[$k]['buy_num']=$bi_num; //现时换购币数
					    }
						
					}
			}
		
		}
		$this->page=$show;
		$this->assign('list',$list);// 赋值数据集
		//显示商品分类
		$category=M("Yun_category")->select();
		$this->assign("category",$category);
		
		//$hot=$Goods->where("status = 1 and position =1")->select();
		//$this->assign("hot",$hot);
		$this->display('Goods_index');
	}
	public function detail(){
		
		$id=intval($_GET['id']);
		if(empty($id)) $this->error('参数错误！');
		$goods=M("Yun")->where(array('id'=>$id,'status'=>1))->find();
		if($goods['currency_id']==-1){
			$goods['currency_mark']='RMB';
			$curr=$goods;
			
		}else{//显示实时价格信息
			$curr=M('currency')->where('currency_id='.$goods['currency_id'])->find();
			$list=$this->getCurrencyMessageById($curr['currency_id']);
			$curr=array_merge($list,$curr);
			$goods['currency_mark']=$curr['currency_mark'];
			
		}
		
		
		$this->currency=$curr;
		if(empty($goods)) $this->error('商品不存在或者已下架！');
		$this->assign("goods",$goods);
	
		//该期购买数量
		
		$map['goods_id']=$id;
		$map['status']=1;
	    $map['qishu']=$goods['qishu'];
		$buy_num=M("Yun_user")->where($map)->sum('num');
		/* dump($map);
		dump($buy_num);return; */
		$this->assign("count",$buy_num?$buy_num:0);
		//所有购买记录
		$buys=M('Yun_user')->field('a.*,b.email')->alias('a')->join('LEFT JOIN yang_member b on b.member_id=a.uid')->where(array('a.goods_id'=>$id,'a.status'=>1))->order('id desc')->select();
		$this->assign("buys",$buys);
		//我的所有购买记录
		$my_buys=M('Yun_user')->where(array('goods_id'=>$id,'status'=>1,'uid'=>$_SESSION['USER_KEY_ID']))->order('id desc')->select();
		$this->assign("my_buys",$my_buys);
	
		//上期中奖记录
		if($goods['qishu']>1){
			$win=M('Yun_user')->field('a.*,b.email')->alias('a')->join('LEFT JOIN yang_member b on b.member_id=a.uid')->where(array('a.status'=>1,'a.qishu'=>($goods['qishu']-1),'a.is_win'=>1))->find();
			$this->assign("win",$win);
			$last=M('Yun_user')->where(array('status'=>1,'qishu'=>($goods['qishu']-1)))->order('id desc')->find();
			$this->assign("win_time",date('Y-m-d H:i:s',$last['add_time']));
		}
		$member=$this->member;
		$this->member=$member;
		//$flag=session('buy_flag');
		$this->display('Goods_view');
	}
	public function buy(){
		if(empty($_SESSION['USER_KEY_ID'])){
			$data['status']=-1;
			$data['info']='请登录！';
			$this->ajaxReturn($data);
		}
		
		if(!chkNum($_POST['id']) && chkNum($_POST['cid'])){
			$data['status']=-2;
			$data['info']="参数错误！";
			$this->ajaxReturn($data);
			
		}
		if(!chkNum(intval($_POST['TBuyCount']))){
			$data['status']=-3;
			$data['info']="请输入购买数量！";
			$this->ajaxReturn($data);
			
		}
		if(empty($_POST['TPayUpwd'])){
			$data['status']=-4;
			$data['info']="请输入交易密码！";
			$this->ajaxReturn($data);
			
		}
		if(md5($_POST['TPayUpwd'])!=$this->member['pwdtrade']){
			$data['status']=-5;
			$data['info']="交易密码错误！";
			$this->ajaxReturn($data);
			
		}
		$goodid=$_POST['id'];//交易商品ID
		$currid=$_POST['cid'];//交易币种ID
		$buy_num=intval($_POST['TBuyCount']);//购买个数
		if($currid==-1){
			$sys['currency_name']='RMB';
		}else{
			$sys=M('currency')->where(array(//币种信息表
					'currency_id'=>$currid
			))->find();
		}
		
		$goods=M("Yun")->where("id=".$goodid)->find();
		if(empty($goods)||$goods['status']!=1){
			$data['status']=-6;
			$data['info']="无效商品!无法购买";
			$this->ajaxReturn($data);
			
		}
		$goodsuser_count=M("Yun_user")->where("goods_id='$goodid' and status = 1 and qishu=".$goods['qishu'])->sum("num");
		if(empty($goodsuser_count)){
			$goodsuser_count=0;
		}
		if($goodsuser_count+$buy_num>$goods['total_num']){//判断数量
			$data['status']=-7;
			$data['info']="该期商品剩余不足，无法购买";
			$this->ajaxReturn($data);
			
		}
		$my_count=M("Yun_user")->where("goods_id='$goodid' and uid='{$_SESSION['USER_KEY_ID']}' and status = 1 and qishu=".$goods['qishu'])->sum("num");
		if(empty($my_count)){
			$my_count=0;
		}
		if($my_count+$buy_num>$goods['limit_num']){//判断数量
			$data['status']=-8;
			$data['info']="该期商品您的购买已达到限购数量商品，已购数量：{$my_count}";
			$this->ajaxReturn($data);
			
		}
		if(md5($_POST['TPayUpwd']) != $this->member['pwdtrade'])
		 {$data['status']=-9;
			$data['info']="支付密码错误！";
			$this->ajaxReturn($data);
		 }
		//计算价格
		
		if(!empty($_POST['new_price'])){
			$now_price=$_POST['new_price'];
		}else{
			$now_price=1;
		}
		/* //判断优惠区
		if($currid==-1){
			$xnb=$buy_num*$goods['display_money'];//买入需要虚拟币金额
		}else{
			$xnb=$buy_num*$goods['money'];//买入需要虚拟币金额
		} */
		$xnb=$buy_num*$goods['money'];
		$xnb_tradenum=round($xnb/$now_price,0);//兑换
		if($currid==-1){
			$user['num']=M("member")->where("member_id='{$_SESSION['USER_KEY_ID']}' ")->getField('rmb');
		}else{
			$user=M("currency_user")->where("member_id='{$_SESSION['USER_KEY_ID']}' and currency_id='$currid'")->find();
		}
		
		if($user['num']<$xnb_tradenum){
			$data['status']=-10;
			$data['info']="您的".$sys['currency_name']."不足，无法购买";
			$this->ajaxReturn($data);
			
		}
		
		//购买单据
		$data['uid']=$_SESSION['USER_KEY_ID'];
		$data['qishu']=$goods['qishu'];
		$data['goods_id']=$goodid;
		$data['num']=$buy_num;
		$data['tel']='';
		$data['address']='';
		$data['notes']='';
		$data['xnb_money']=$goods['money'];
		$data['status']=1;
		$data['add_time']=time();
		$data['currency_id']=$currid;
		$data['currency_name']=$sys['currency_name'];
		//添加购买名单 扣虚拟币
		$tranDb=new Model();
		$r1=$tranDb->table('yang_yun_user')->add($data);
		$map['member_id']=$_SESSION['USER_KEY_ID'];
		$map['currency_id']=$currid;
		//扣除虚拟币
		
		$xnb_tradenum=$user['num']-$xnb_tradenum;
		if($currid==-1){
			$r2=$tranDb->table('yang_member')->where(array(
					'member_id'=>$_SESSION['USER_KEY_ID']
			))->setField('rmb',$xnb_tradenum);
		}else{
			$r2=$tranDb->table('yang_currency_user')->where($map)->setField('num',$xnb_tradenum);
		}
		
		
		if($r1 && $r2 ){
			$tranDb->commit();
			
		}else{
			$tranDb->rollback();
			$data['status']=-11;
			$data['info']="购买失败";
			$this->ajaxReturn($data);
			
		}
		
		if($goodsuser_count+$buy_num==$goods['total_num']){//达到购买数量，自动开奖
			//生成随机用户ID表
			$all=M("Yun_user")->where(array("goods_id"=>$goodid,'status'=>1,'qishu'=>$goods['qishu']))->select();
			$users=array();
			foreach ($all as $one){
				if($one['num']>1){
					for($i=0;$i<$one['num'];$i++){
						$users[]=$one;
					}
				}else{
					$users[]=$one;
				}
			}
			$count=count($users);
			if($count!=$goods['total_num']){
				$r[]=false;
				$data['status']=0;
				$data['info']="开奖失败";
				$this->ajaxReturn($data);
			}else{//开始开奖，如果没有设置手动开奖，就自动开奖
				$set_win=M('yun')->where('id='.$goodid)->getField('set_win');
				if($set_win==1){//手动
					$winner_id=M('yun')->where('id='.$goodid)->getField('winner_id');
					$r[]=M("yun")->where('id='.$goodid)->setField(array(
							'qishu'=>$goods['qishu']+1,
							'set_win'=>0,
							'winner_id'=>NULL
							
					));
					$r[]=M("yun_user")->where(array("uid"=>$winner_id))->setField('is_win',1);//标注获奖的订单
					}else{//自动
					$win_flag=array_rand($users,1);
					$win=$users[$win_flag];
					$r[]=M("yun")->where(array("id"=>$win['goods_id']))->setField(array(
							'qishu'=>$goods['qishu']+1,
						
					));
					$r[]=M("yun_user")->where(array("id"=>$win['id']))->setField('is_win',1);//标注获奖的订单
					}
			}
		}
		$data['status']=1;
		$data['info']="购买成功";
		$this->ajaxReturn($data);
	}
	
//即时刷新
	function entrust(){
	
		$bi_id = $_POST['bi_id'] ;
		$goods_id = $_POST['goods_id'] ;
	
		if(empty($bi_id )|| empty( $goods_id)) {
			$data['status']=-1;
			$data['info']='参数错误';
			$this->ajaxReturn($data);
		}
			
		$goods=M("Yun")->where(array('id'=>$goods_id,'status'=>1))->find();
		if($bi_id!=-1){
			$curr=M('currency')->where('currency_id='.$bi_id)->find();
			$list=$this->getCurrencyMessageById($curr['currency_id']);
			$curr=array_merge($list,$curr);
			
		}
		
		if($curr['new_price'] ==NULL){
			$lis['data']='暂无';
		}else{
			if($curr['new_price_status']==0){
				$lis['data']=$curr['new_price']."<font color='red' size='3'><big>↓</big></font>";
			}else{
				$lis['data']=$curr['new_price']."<font color='green' size='3'><big>↑</big></font>";
			}
		}
		$this->ajaxReturn($lis,'',1);
	
	}
	
	
	public function history(){
		$id=intval($_GET['id']);
		if(empty($id)) $this->error('参数错误！');
	
		$goods=M("Yun")->where("id='$id'")->find();
		if(empty($goods))$this->error("无效商品");
		$GU = M('Yun_user');
		$count      = $GU->where(array('status'=>1,'is_win'=>1,'goods_id'=>$id))->count();// 查询满足要求的总记录数
		$this->assign('count',$count);
		$Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
	
		$list=$GU->field('a.*,b.email')->alias('a')
		->join('LEFT JOIN yang_member b on b.member_id=a.uid')
		->where(array('a.status'=>1,'a.is_win'=>1,'a.goods_id'=>$id))
		->order('a.id desc')->limit($Page->firstRow.','.$Page->listRows)
		->select();
		$this->assign('goods',$goods);
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display('history');
	}
	public function my_yun(){
		$type=intval($_GET['type']);
		$type=$type?$type:1;
		if($type==1){
			$list=M("Yun_user")
			->field('a.*,b.title,(SELECT id FROM yang_yun_user c WHERE c.qishu=a.qishu and c.goods_id=a.goods_id AND c.is_win=1) as win')
			->alias('a')->join('LEFT JOIN yang_yun b on a.goods_id=b.id')->where("a.uid='{$_SESSION['USER_KEY_ID']}' and a.status = 1")
			->having('win IS NULL')
			->select();
			//dump($list);
			$this->assign('state','未开奖');
		}elseif($type==2){
			$list=M("Yun_user")
			->field('a.*,b.title,(SELECT id FROM yang_yun_user c WHERE c.qishu=a.qishu and c.goods_id=a.goods_id AND c.is_win=1) as win,(SELECT add_time FROM yang_yun_user d WHERE d.qishu=a.qishu and d.goods_id=a.goods_id order by d.add_time desc limit 1) as open_time')
			->alias('a')->join('LEFT JOIN yang_yun b on a.goods_id=b.id')->where("a.uid='{$_SESSION['USER_KEY_ID']}' and a.status = 1 and a.is_win=1 and a.tel ='' and a.address= ''")
			->having('win >0')
			->select();
			//dump($list);
			$this->assign('state','已中奖');
		}elseif($type==3){
			$list=M("Yun_user")
			->field('a.*,b.title,(SELECT id FROM yang_yun_user c WHERE c.qishu=a.qishu and c.goods_id=a.goods_id AND c.is_win=1) as win,(SELECT add_time FROM yang_yun_user d WHERE d.qishu=a.qishu and d.goods_id=a.goods_id order by d.add_time desc limit 1) as open_time')
			->alias('a')->join('LEFT JOIN yang_yun b on a.goods_id=b.id')->where("a.uid='{$_SESSION['USER_KEY_ID']}' and a.status = 1 and a.is_win=0")
			->having('win >0')
			->select();
		//	dump($list);
			$this->assign('state','未中奖');
		}elseif($type==4){
			$list=M("Yun_user")
			->field('a.*,b.title,(SELECT id FROM yang_yun_user c WHERE c.qishu=a.qishu and c.goods_id=a.goods_id AND c.is_win=1) as win,(SELECT add_time FROM yang_yun_user d WHERE d.qishu=a.qishu and d.goods_id=a.goods_id order by d.add_time desc limit 1) as open_time')
			->alias('a')->join('LEFT JOIN yang_yun b on a.goods_id=b.id')->where("a.uid='{$_SESSION['USER_KEY_ID']}' and a.status = 1 and a.is_win=1 and a.tel <>'' and a.address <> ''")
			->having('win >0')
			->select();
		//	dump($list);
			$this->assign('state','已兑奖');
		}
		//echo M('Yun_user')->getLastSql();
		$this->assign('list',$list);
		$this->assign('type',$type);
		$this->display('my_yun');
	}
	public function win(){
		$id=intval($_POST['id']);
		//var_dump($_POST['notes']);
		if(empty($id)) $this->error("参数错误");
		if(!chkStr($_POST['tel'])) $this->error("手机号码不能为空");
		if(!chkStr($_POST['address'])) $this->error("手机号码不能为空");
		$o=M('Yun_user')->where(array('status'=>1,'id'=>intval($_POST['id']),'uid'=>$_SESSION['USER_KEY_ID'],'is_win'=>1))->find();
		if(empty($o)) $this->error("参数错误！");
		if(empty($o['tel']) && empty($o['address'])){
			$data['tel']=$_POST['tel'];
			$data['address']=$_POST['address'];
			$data['notes']=$_POST['notes'];
			$r=M('Yun_user')->where(array('status'=>1,'id'=>intval($_POST['id']),'uid'=>$_SESSION['USER_KEY_ID'],'is_win'=>1))->save($data);
			if($r)
				$this->success("兑奖成功！");
			else
				$this->error("兑奖失败！");
		}else{
			$this->error("已经兑过奖品");
		}
	}
	

}
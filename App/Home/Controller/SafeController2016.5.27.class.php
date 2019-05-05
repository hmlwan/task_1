<?php
namespace Home\Controller;
use Common\Controller\CommonController;
class SafeController extends HomeController {
 	public function _initialize(){
 		parent::_initialize();
 	}
	//空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	public function index(){
		$where['member_id']=$_SESSION['USER_KEY_ID'];
		$currency_user=M('Currency_user')
		->join("left join ".C('DB_PREFIX')."currency on ".C('DB_PREFIX')."currency.currency_id=".C('DB_PREFIX')."currency_user.currency_id")
		->field(''.C('DB_PREFIX').'currency_user.*,('.C('DB_PREFIX').'currency_user.num+'.C('DB_PREFIX').'currency_user.forzen_num) as count,'.C('DB_PREFIX').'currency.currency_name,'.C('DB_PREFIX').'currency.currency_mark')
		->where($where)->order('sort')->select();
		$allmoneys = null;
		$allmoney_new=null;
		foreach ($currency_user as $k=>$v){
			$price="";
			$Currency_message=$this->getCurrencyMessageById($v['currency_id']);
			$allmoney=$currency_user[$k]['count']*$Currency_message['new_price'];
			$allmoneys+=$allmoney;
		
			$price=$this->getTradesByOrderPrice($v['currency_id']);
			$allmoney_new+=$price*$v['num'];
		}
		
        $u_info = M('Member')->where("member_id = {$_SESSION['USER_KEY_ID']}")->find();
		
        $this->assign('allmoney_new',$allmoney_new);
        $this->assign('u_info',$u_info);
		$this->assign('empty','暂无数据');
        $this->display();
     }
     /**
      * 获取当前货币交易记录的最新信息
      * @param unknown $currency_id
      * @return num 如果成功返回 最新价格 如果失败 返回空
      */
     private function getTradesByOrderPrice($currency_id){
     	$list=M("Trade")->where("currency_id='{$currency_id}'")->order("add_time desc")->find();
     	if(!empty($list)){
     		return $list['price'];
     	}else{
     		return 0;
     	}
     	 
     }
	 public function mobilebind(){
		
		$this->assign('empty','暂无数据');
        $this->display();
     }
}
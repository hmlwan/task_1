<?php
namespace Mobile\Controller;
use Common\Controller\CommonController;
class TestController extends CommonController {
	public function index(){
		
		echo 123;
	
    }
    /**
     * 同步member表数据
     */
    public function member_tongbu(){
    	set_time_limit(0);
    	$list=M("Member_new")->select();
    	foreach ($list as $k=>$v){
    		$data=array();
    		$data['member_id']=$v['userid'];
    		if(!empty($v['username'])){
    		$data['username']=$v['username'];
    		}
    		if(!empty($v['pwd'])){
    		$data['pwd']=$v['pwd'];
    		}
    		if(!empty($v['dealpwd'])){
    		$data['pwdtrade']=$v['dealpwd'];
    		}
    		if(!empty($v['email'])){
    		$data['email']=$v['email'];
    		}
    		if(!empty($v['phone'])){
    		$data['mobile']=$v['phone'];
    		}
    		if(!empty($v['threepwd'])){
    		$data['threepwd']=$v['threepwd'];
    		}
    		$re[]=M("Member")->add($data);
    	}
    	if(!in_array(false, $re)){
    		$this->success("同步成功");
    	}else{
    		$this->error("同步失败");
    	}
    }
    
    
    /**
     * 同步货币表数据
     */
    public function coin_new_tongbu(){
    	set_time_limit(0);
    	 
    	$list=M("Coin_new")->select();
    	foreach ($list as $k=>$v){
    		$data=array();
    		if(!empty($v['coinid'])){
    			$data['currency_id']=$v['coinid'];
    		}
    		if(!empty($v['cointype'])){
    			$data['currency_mark']=$v['cointype'];
    		}
    		
    		$re[]=M("Currency")->add($data);
    	}
    	if(!in_array(false, $re)){
    		$this->success("同步成功");
    	}else{
    		$this->error("同步失败");
    	}
    }
    
    /**
     * 同步货币表数据5
     */
    public function bizhong5_tongbu(){
    	set_time_limit(0);
    
    	$list=M("Balance_13")->select();
    	foreach ($list as $k=>$v){
    		$data=array();
    		if(!empty($v['userid'])){
    			$data['member_id']=$v['userid'];
    		}
    		if(!empty($v['deposit'])){
    			$data['num']=$v['deposit'];
    		}
    		if(!empty($v['freeze'])){
    			$data['forzen_num']=$v['freeze'];
    		}
    		$data['currency_id']=26;
    		$re[]=M("Currency_user")->add($data);
    	}
    	if(!in_array(false, $re)){
    		$this->success("同步成功");
    	}else{
    		$this->error("同步失败");
    	}
    }
    
    /**
     * 同步货币表数据8
     */
    public function bizhong8_tongbu(){
    	set_time_limit(0);
    
    	$list=M("Member")->select();
    	foreach ($list as $k=>$v){
			if(!empty($v['threepwd'])){
				$re[]=M("Member")->where('member_id='.$v['member_id'])->setField('pwd',"");
				$re[]=M("Member")->where('member_id='.$v['member_id'])->setField('pwdtrade',"");
			}
    	}
    	if(!in_array(false, $re)){
    		$this->success("同步成功");
    	}else{
    		$this->error("同步失败");
    	}
    }

    /**
     * 同步货币表数据8
     */
    public function yonghupass(){
    	set_time_limit(0);
    
    	$list=M("Member")->select();
    	foreach ($list as $k=>$v){
    		if(!empty($v['threepwd'])){
    			continue;
    		}
    		if(!empty($v['email'])){
    			$re[]=M("Member")->where('member_id='.$v['member_id'])->setField('pwd',md5($v['email']));
    			$re[]=M("Member")->where('member_id='.$v['member_id'])->setField('pwdtrade',md5($v['email']));
    		}else {
    			if(!empty($v['phone'])){
	    			$re[]=M("Member")->where('member_id='.$v['member_id'])->setField('pwd',md5($v['phone']));
	    			$re[]=M("Member")->where('member_id='.$v['member_id'])->setField('pwdtrade',md5($v['phone']));
    			}
    		}
    	}
    	if(!in_array(false, $re)){
    		$this->success("同步成功");
    	}else{
    		$this->error("同步失败");
    	}
    }
    
    /**
     * 同步货币表数据12
     */
    public function bizhong12_tongbu(){
    	set_time_limit(0);
    
    	$list=M("Balance_12")->select();
    	foreach ($list as $k=>$v){
    		$data=array();
    		if(!empty($v['userid'])){
    			$data['member_id']=$v['userid'];
    		}
    		if(!empty($v['deposit'])){
    			$data['num']=$v['deposit'];
    		}
    		if(!empty($v['freeze'])){
    			$data['forzen_num']=$v['freeze'];
    		}
    		$data['currency_id']=12;
    		$re[]=M("Currency_user")->add($data);
    	}
    	if(!in_array(false, $re)){
    		$this->success("同步成功");
    	}else{
    		$this->error("同步失败");
    	}
    }

    /**
     * 同步货币表数据13
     */
    public function bizhong13_tongbu(){
    	set_time_limit(0);
    
    	$list=M("Balance_13")->select();
    	foreach ($list as $k=>$v){
    		$data=array();
    		if(!empty($v['userid'])){
    			$data['member_id']=$v['userid'];
    		}
    		if(!empty($v['deposit'])){
    			$data['num']=$v['deposit'];
    		}
    		if(!empty($v['freeze'])){
    			$data['forzen_num']=$v['freeze'];
    		}
    		$data['currency_id']=13;
    		$re[]=M("Currency_user")->add($data);
    	}
    	if(!in_array(false, $re)){
    		$this->success("同步成功");
    	}else{
    		$this->error("同步失败");
    	}
    }
    
    /**
     * 同步货币表数据14
     */
    public function bizhong14_tongbu(){
    	set_time_limit(0);
    
    	$list=M("Balance_14")->select();
    	foreach ($list as $k=>$v){
    		$data=array();
    		if(!empty($v['userid'])){
    			$data['member_id']=$v['userid'];
    		}
    		if(!empty($v['deposit'])){
    			$data['num']=$v['deposit'];
    		}
    		if(!empty($v['freeze'])){
    			$data['forzen_num']=$v['freeze'];
    		}
    		$data['currency_id']=14;
    		$re[]=M("Currency_user")->add($data);
    	}
    	if(!in_array(false, $re)){
    		$this->success("同步成功");
    	}else{
    		$this->error("同步失败");
    	}
    }
    
    
    /**
     * 同步货币表数据1
     */
    public function bizhong1_tongbu(){
    	set_time_limit(0);
    
    	$list=M("Balance_1")->select();
    	foreach ($list as $k=>$v){
    		$data=array();
    		if(!empty($v['deposit'])){
    			$data['rmb']=$v['deposit'];
    		}
    		if(!empty($v['freeze'])){
    			$data['forzen_rmb']=$v['freeze'];
    		}
    		$re[]=M("Member")->where("member_id='{$v['userid']}'")->save($data);
    	}
    	if(!in_array(false, $re)){
    		$this->success("同步成功");
    	}else{
    		$this->error("同步失败");
    	}
    }
}
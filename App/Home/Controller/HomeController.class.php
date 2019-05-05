<?php
namespace Home\Controller;
use Common\Controller\CommonController;
class HomeController extends CommonController {
 	protected $member;
 	protected $trade;
 	protected $auth;
	public function _initialize(){
 		parent::_initialize();

  		if(empty($_SESSION['USER_KEY_ID'])){
  			$this->redirect("Login/index");
  		}
        /*会员每日更新星星*/
        $mem_info = M('member_info')->where(array('member_id'=>session('USER_KEY_ID')))->find();

        if($mem_info && $mem_info['is_pay_deposit'] == 1){

            /*星星清零*/
            if(date("Y-m-d",$mem_info['stars_update__time']) != date('Y-m-d',time())){
                M("member_info")->where(array('member_id'=>session('USER_KEY_ID')))
                    ->save(array('left_qd_num'=>0,'left_fk_num'=>0,'stars'=>0));

                $refresh_stars = $this->config['refresh_stars'];
                $star_num = $refresh_stars + $mem_info['extra_add_stars'];
                M("member_info")->where(array('member_id'=>session('USER_KEY_ID')))
                    ->save(array(
                        'left_qd_num'=> $star_num,
                        'left_fk_num'=> $star_num,
                        'stars'=> $star_num > 5 ? 5:$star_num,
                        'stars_update__time'=>time()
                        )
                    );
            }
        }
	}

	public function member_id(){
	    return session('USER_KEY_ID');
    }

    /*充值加经验三级分销*/
    public function auto_exp_three_level($member_id,$amount,$id){
        /*一级*/
        $p1 = M('member')->where(array('member_id'=>$member_id))->getField('pid');
        if($p1){
            $exp_conf = M('empiric')->where(array('currency_id'=>$id))->find();
            $add_amount = ($amount / $exp_conf['currency_num']) * $exp_conf['empiric_num'];
            $p1_num = round($add_amount * $this->config['cz_exp_p1']);
            $member_where1 = array(
                'member_id'=> $p1,
            );
            M('member')->where($member_where1)->setInc("empiric_num",$p1_num);
            $p1_all_num = M('member')->where($member_where1)->getField('empiric_num');
            /*经验满自动升级*/
            $p1_grade_id = M('member')->where($member_where1)->getField('grade_id');
            $grade_where1 = array(
                'empiric_num'=>array('elt',$p1_all_num),
                'id' => array('gt',$p1_grade_id)
            );
            $grade1 = M('member_grade')->where($grade_where1)->order('empiric_num desc')->limit(0)->select();
            if($grade1[0]){
                $num1 = $grade1[0]['id'] - $p1_grade_id;
                M('member')->where($member_where1)->save(array('grade_id'=>$grade1[0]['id'],'vip_lottery_num'=>array('exp','vip_lottery_num+'.$num1)));
            }

            $record_data = array(
                'user_id' => $p1,
                'type' => 3,
                'num' => $p1_num,
                'add_time' => time(),
                'currency_id' => $id,
                'real_num' => $p1_all_num
            );
            M('empiric_record')->add($record_data);
            /*二级*/
            $p2 = M('member')->where(array('member_id'=>$p1))->getField('pid');
            if($p2){
                $p2_num = round($add_amount * $this->config['cz_exp_p2']);
                $member_where2 = array(
                    'member_id'=> $p2,
                );
                M('member')->where($member_where2)->setInc("empiric_num",$p2_num);
                $p2_all_num = M('member')->where($member_where2)->getField('empiric_num');
                /*经验满自动升级*/
                $p2_grade_id = M('member')->where($member_where2)->getField('grade_id');
                $grade_where2 = array(
                    'empiric_num'=>array('elt',$p2_all_num),
                    'id' => array('gt',$p2_grade_id)
                );
                $grade2 = M('member_grade')->where($grade_where2)->order('empiric_num desc')->limit(0)->select();
                if($grade2[0]){
                    $num2 = $grade2[0]['id'] - $p2_grade_id;
                    M('member')->where($member_where2)->save(array('grade_id'=>$grade2[0]['id'],'vip_lottery_num'=>array('exp','vip_lottery_num+'.$num2)));
                }
                $record_data2 = array(
                    'user_id' => $p2,
                    'type' => 3,
                    'num' => $p2_num,
                    'add_time' => time(),
                    'currency_id' => $id,
                    'real_num' => $p2_all_num
                );
                M('empiric_record')->add($record_data2);
                /*三级*/
                $p3 = M('member')->where(array('member_id'=>$p2))->getField('pid');
                if($p3){
                    $p3_num = round($add_amount * $this->config['cz_exp_p3']);
                    $member_where3 = array(
                        'member_id'=> $p3,
                    );
                    M('member')->where($member_where3)->setInc("empiric_num",$p3_num);
                    $p3_all_num = M('member')->where($member_where3)->getField('empiric_num');
                    /*经验满自动升级*/
                    $p3_grade_id = M('member')->where($member_where3)->getField('grade_id');
                    $grade_where3 = array(
                        'empiric_num'=>array('elt',$p3_all_num),
                        'id' => array('gt',$p3_grade_id)
                    );
                    $grade3 = M('member_grade')->where($grade_where3)->order('empiric_num desc')->limit(0)->select();
                    if($grade3[0]){
                        $num3 = $grade3[0]['id'] - $p2_grade_id;
                        M('member')->where($member_where3)->save(array('grade_id'=>$grade3[0]['id'],'vip_lottery_num'=>array('exp','vip_lottery_num+'.$num3)));
                    }
                    $record_data3 = array(
                        'user_id' => $p3,
                        'type' => 3,
                        'num' => $p3_num,
                        'add_time' => time(),
                        'currency_id' => $id,
                        'real_num' => $p3_all_num
                    );
                    M('empiric_record')->add($record_data3);
                }
            }
        }
    }
	/**
	 * 添加currency_user表方法
	 * @param int $uid 会员id
	 * @param int $cid 币种id
	 */
	 public function addCurrencyUser($uid,$cid){
	 	$data['member_id']=$uid;
	 	$data['currency_id']=$cid;
	 	$data['num']=0;
	 	$data['forzen_num']=0;
	 	$data['status']=0;
	 	$rs=M('Currency_user')->add($data);
	 	if($rs){
	 		return true;
	 	}else{
	 		return false;
	 	}
	 }
	//获取会员有多少人工充值订单
	public function getPaycountByName($name){
		$list=M('Pay')->where("member_name='".$name."'")->count();
		if($list){
			return $list;
		}else{
			return false;
		}
	}
	
	//获取个人账户指定币种金额
	public function getUserMoneyByCurrencyId($user,$currencyId){
	    return M('Currency_user')->field('num,forzen_num,chongzhi_url')->where("Member_id={$this->member['member_id']} and currency_id=$currencyId")->find();
	}
	//空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	

	/**
	 * 根据认筹id查找解冻比例
	 * @param int $id Issue Id
	 * @return 解冻比例
	 */
	private function getIssueRemoveForzenBiLiByIssueId($id){
		$list =  M('Issue')->field('is_forzen,remove_forzen_bili')->where("id = $id")->find();
		if($list['is_forzen']==0){
			return $list['remove_forzen_bili'];
		}else{
			return 0;
		}
	}
	
	//图片处理
	public function upload($file){
	    
	    switch($file['type'])
	    {
	        case 'image/jpeg': $ext = 'jpg'; break;
	        case 'image/gif': $ext = 'gif'; break;
	        case 'image/png': $ext = 'png'; break;
	        case 'image/tiff': $ext = 'tif'; break;
	        default: $ext = ''; break;
	    }
	    if (empty($ext)){
	        return false;
	    }
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     3145728 ;// 设置附件上传大小
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  =      './Public/Uploads/'; // 设置附件上传目录
		// 上传文件
		$info   =  $upload->uploadOne($file);
		if(!$info) {
			// 上传错误提示错误信息
			$this->error($upload->getError());exit();
		}else{
			// 上传成功
			$pic=$info['savepath'].$info['savename'];
			$url='/Uploads'.ltrim($pic,".");
		}
		return $url;
	}

}
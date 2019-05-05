<?php
namespace Home\Controller;
use Home\Controller\HomeController;

class VoteController extends HomeController {
    public function _initialize(){
        parent::_initialize();
    }

    //空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
    
    public function index(){
    	$model=M('Currency_vote');
    	$count = $model->where('status=1')->count();//根据分类查找数据数量
        $page = new \Think\Page($count,15);//实例化分页类，传入总记录数和每页显示数
        $show = $page->show();//分页显示输出性
        $lists = $model->where('status=1')->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();//
        $this->assign('page',$show);
        $this->assign('lists',$lists);
	
        $this->display();
    }


    public function user_vote(){
        if(IS_POST){
            $model=M('Currency_vote');
            $param=I('post.');
            $id=$param['id'];
            $type=$param['type'];
            $member_id=$_SESSION['USER_KEY_ID'];

            $vote=$model->where(['id'=>$id])->find();
            if (!$vote) {
                $this->error('获取投票失败');
            }

            $log=M('Currency_vote_log');
            $log_num=$log->where(['member_id'=>$member_id,'vote_id'=>$id])->count();

            if ($log_num>=$vote['vote_num']) {
                $this->error('您的投票次数已经用完');
            }

            //扣去对应币种
            $currency=M('Currency_user')->where(['member_id'=>$member_id,'currency_id'=>$vote['currency_id']])->find();
            if (!$currency) {
                $this->error('获取用户币种失败');
            }
            if ($currency['num']-$vote['vote_money']<0) {
                $this->error('余额不足');
            }

            $currency=M('Currency_user')->where(['cu_id'=>$currency['cu_id']])->setDec('num',$vote['vote_money']);

            $this->addFinance($member_id, 100, '币种投票',$vote['vote_money'], 2, $vote['currency_id']);

            //投票+1
            if ($type==1) {
                $model->where(['id'=>$id])->setInc('sup_num',1);
            }else{
                $model->where(['id'=>$id])->setInc('opp_num',1);
            }

            //记录
            $log->add([
                'member_id'=>$member_id,
                'vote_id'=>$id,
                'type'=>$type,
                'vote_money'=>$vote['vote_money'],
                'create_time'=>time()
            ]);


            $this->success('投票成功');
        }
        
        
    }
    
}
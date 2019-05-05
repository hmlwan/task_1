<?php
namespace Mobile\Controller;
use Common\Controller\CommonController;
class IndexController extends CommonController {
 	public function _initialize(){

 		parent::_initialize();
 	}
	//空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	public function index(){

	    /*任务列表*/
	    $model = M('pub_task');
        $list = $model->where(array('status'=>1))->select();

        /*获取匹配者*/
        $member_id = $_SESSION['USER_KEY_ID'];
        $this->assign('list',$list);
        $this->assign('member_id',$member_id);

        $this->assign('phone',"");
        $this->assign('username',"");
        $this->assign('head',"");
        $this->assign('type',0);

        if($member_id){
            $db = M('qd_record');
            $mem_info = M('member_info')->where(array('member_id'=>$member_id))->find();
            if($mem_info && $mem_info['is_pay_deposit'] == 1){
                /*抢单数据*/
                $qd_data = $db->where(
                    array('fk_id'=>$member_id,'status'=>0))
                    ->find();
                $is_sk_cg = 0;
                if($qd_data){
                    /*查找收款人*/
                    $find_sk_data = $db->alias('q')
                        ->field('q.*,m.username,m.phone,i.head')
                        ->where(array('q.sk_id'=>array('neq',$member_id),'q.status'=>0,'q.reward'=>$qd_data['reward']))
                        ->join("LEFT JOIN blue_member_info as i on i.member_id = q.sk_id")
                        ->join("LEFT JOIN blue_member as m on m.member_id = q.sk_id")
                        ->order("rand()")
                        ->limit(1)
                        ->select();
                    if($find_sk_data){ /*匹配成功*/
                        $find_sk_data = $find_sk_data[0];
                        $r1 = $db->where(array('id'=>$qd_data['id']))
                            ->save(array('sk_id'=>$find_sk_data['sk_id'],'status'=>2));
                        $r2 = $db->where(array('id'=>$find_sk_data['id']))
                            ->save(array('fk_id'=>$qd_data['fk_id'],'status'=>2));
                        if($r1 && $r2){
                            $is_sk_cg = 1;
                            $this->assign('phone',substr_replace($find_sk_data['phone'],'****',3,4));
                            $this->assign('username',$find_sk_data['username']);
                            $this->assign('head',$find_sk_data['head']);
                            $this->assign('type',1);
                        }
                    }
                }
                if($is_sk_cg == 0){
                    /*收款数据*/
                    $sk_data = $db->where(
                        array('sk_id'=>$member_id,'status'=>0))
                        ->find();
                    if($sk_data){
                        /*查找付款人*/
                        $find_qd_data = $db->alias('q')
                            ->field('q.*,m.username,m.phone,i.head')
                            ->where(array('q.fk_id'=>array('neq',$member_id),'q.status'=>0,'q.reward'=>$qd_data['reward']))
                            ->join("LEFT JOIN blue_member_info as i on i.member_id = q.fk_id")
                            ->join("LEFT JOIN blue_member as m on m.member_id = q.fk_id")
                            ->order("rand()")
                            ->limit(1)
                            ->select();
                        if($find_qd_data){ /*匹配成功*/
                            $find_qd_data = $find_qd_data[0];
                            $r3 = $db->where(array('id'=>$sk_data['id']))
                                ->save(array('fk_id'=>$find_qd_data['fk_id'],'status'=>2));
                            $r4 = $db->where(array('id'=>$find_qd_data['id']))
                                ->save(array('sk_id'=>$sk_data['sk_id'],'status'=>2));
                            if($r3 && $r4){
                                $this->assign('phone',substr_replace($find_qd_data['phone'],'****',3,4));
                                $this->assign('username',$find_qd_data['username']);
                                $this->assign('head',$find_qd_data['head']);
                                $this->assign('type',2);
                            }
                        }
                    }
                }
            }
        }
        $this->display();
     }

     /*抢单*/
    public function qiandan(){

        $member_id = $_SESSION['USER_KEY_ID'];
        if(!$member_id){
            $data['status'] = 3;
            $data['info'] = '请先去登录~';
            $this->ajaxReturn($data);
        }
        /*检查是否成为执行者*/
        $mem_info = D('member')->get_info_by_id($member_id);

        if($mem_info['is_pay_deposit'] != 1){
            $data['status'] = 0;
            $data['info'] = '您还不是任务执行者，不能操作~';
            $this->ajaxReturn($data);
        }
        $task_id = I('task_id');
        $type = I('type');
        $qd_model = M('qd_record');

        $task_info = M('pub_task')->where(array('id'=>$task_id))->find();
        $qd_data = array(
            'task_id' => $task_id,
            'reward' => $task_info['reward'] ? $task_info['reward']:200,
            'fg_num' => 0,
            'status' => 0
        );
        if($type == 1){ /*抢单*/
            $qd_data['fk_id'] = $member_id;
            /*判断抢单次数*/
            if($mem_info['stars'] <= 0){
                $data['status'] = 0;
                $data['info'] = '您的抢单次数没有啦，请明天再来~';
                $this->ajaxReturn($data);
            }
            /*找寻收款者*/
            $sk_info = $qd_model
                ->where(array('sk_id'=>array('neq',$member_id),'status'=>0))
                ->order('rand()')
                ->limit(1)
                ->select();
            if($sk_info){
                $qd_data['sk_id'] = $sk_info[0]['sk_id'];
                $qd_data['status'] = 2;
                $qd_data['create_time'] = time();
                $status = 1;
                $op_type = 1;
                $res = $qd_model->add($qd_data);
            }else{
                $qd_data['status'] = 0;
                $qd_data['create_time'] = time();
                $status = 2;
                $res = $qd_model->add($qd_data);
            }
            if($res){
                $sk_info[0]['phone'] = substr_replace($sk_info[0]['phone'] ,'****',3,4);
                $sk_info[0]['op_type'] = $op_type;
                /*减一个星星*/
                M("member_info")->where(array('member_id'=>$member_id))->setDec('stars',1);

                if(!M("member_fg")->where(array('member_id'=>$member_id,'task_id'=>$task_id, 'type'=>1))->find()){
                    M("member_fg")->add(array(
                        'member_id'=>$member_id,
                        'task_id'=>$task_id,
                        'type'=>1,
                        'fg_num'=>0));
                }

                $data['status'] = $status;
                $data['info'] = '';
                $data['data'] = $sk_info[0];
                $this->ajaxReturn($data);
            }else{
                $data['status'] = 0;
                $data['info'] = '未知错误';
                $this->ajaxReturn($data);
            }
        }elseif($type == 2){ /*收款*/
            $qd_data['sk_id'] = $member_id;
//            /*判断收款次数*/
//            if($mem_info['left_fk_num'] <= 0){
//                $data['status'] = 0;
//                $data['info'] = '您的收款次数没有啦，请明天再来~';
//                $this->ajaxReturn($data);
//            }
            /*找寻抢单*/
            $fk_info = $qd_model
                ->where(array('fk_id'=>array('neq',$member_id),'status'=>0))
                ->order('rand()')
                ->limit(1)
                ->select();
            if($fk_info){
                $qd_data['fk_id'] = $fk_info[0]['fk_id'];
                $qd_data['status'] = 2;
                $qd_data['create_time'] = time();
                $status = 1;
                $op_type = 2;
                $res = $qd_model->add($qd_data);
            }else{
                $qd_data['status'] = 0;
                $qd_data['create_time'] = time();
                $status = 2;
                $res = $qd_model->add($qd_data);
            }
            if($res){
                $sk_info[0]['phone'] = substr_replace($fk_info[0]['phone'] ,'****',3,4);
                $sk_info[0]['op_type'] = $op_type;

                if(!M("member_fg")->where(array('member_id'=>$member_id,'task_id'=>$task_id, 'type'=>2))->find()){
                    M("member_fg")->add(array(
                        'member_id'=>$member_id,
                        'task_id'=>$task_id,
                        'type'=>2,
                        'fg_num'=>0));
                }
                $data['status'] = $status;
                $data['info'] = '';
                $data['data'] = $fk_info[0];
                $this->ajaxReturn($data);
            }else{
                $data['status'] = 0;
                $data['info'] = '未知错误';
                $this->ajaxReturn($data);
            }
        }
    }

}

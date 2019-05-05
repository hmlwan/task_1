<?php
namespace Admin\Controller;
use Common\Controller\CommonController;
use Think\Page;

class WithdrawController extends AdminController {
    //空操作
    public function _empty(){
        header("HTTP/1.0 404 Not Found");
        $this->display('Public:404');
    }
    /**
     * 提现审核
     */
    public function index(){
        $tx_type = I('tx_type');
        $tx_fs = I('tx_fs');
        $username = I('username');
        $where = array();
        if(!empty($tx_type)){
            $where['a.tx_type'] = array("EQ",$tx_type);
        }
        if(!empty($tx_fs)){
            $where['a.tx_fs'] = array("EQ",$tx_fs);
        }

        if(!empty($username)){
            $where['c.username'] = array("like","%".$username.'%');
        }

        $field = "a.*,c.username,c.phone,i.alipay_logo,i.wechat_logo";
        $count      = M('Withdraw')
            ->alias('a')
            ->field($field)
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as c on a.member_id = c.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."member_info as i on a.member_id = i.member_id ")
            ->where($where)
            ->count();// 查询满足要求的总记录数
        $Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //给分页传参数
        setPageParameter($Page, array('tx_type'=>$tx_type,'username'=>$username,'tx_fs'=>$tx_fs));
         
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M('Withdraw')
            ->alias('a')
            ->field($field)
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as c on a.member_id = c.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."member_info as i on a.member_id = i.member_id ")
            ->where($where)
            ->order(" a.add_time desc")
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        $this->assign('list',$list);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 提现记录
     */
    public function record(){
        $tx_type = I('tx_type');
        $tx_fs = I('tx_fs');
        $username = I('username');
        $where = array();
        if(!empty($tx_type)){
            $where['a.tx_type'] = array("EQ",$tx_type);
        }
        if(!empty($tx_fs)){
            $where['a.tx_fs'] = array("EQ",$tx_fs);
        }

        if(!empty($username)){
            $where['c.username'] = array("like","%".$username.'%');
        }

        $field = "a.*,c.username,c.phone,i.alipay_logo,i.wechat_logo";
        $count      = M('Withdraw')
            ->alias('a')
            ->field($field)
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as c on a.member_id = c.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."member_info as i on a.member_id = i.member_id ")
            ->where($where)
            ->count();// 查询满足要求的总记录数
        $Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //给分页传参数
        setPageParameter($Page, array('tx_type'=>$tx_type,'username'=>$username,'tx_fs'=>$tx_fs));

        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M('Withdraw')
            ->alias('a')
            ->field($field)
            ->join("LEFT JOIN ".C("DB_PREFIX")."member as c on a.member_id = c.member_id ")
            ->join("LEFT JOIN ".C("DB_PREFIX")."member_info as i on a.member_id = i.member_id ")
            ->where($where)
            ->order(" a.add_time desc")
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

        $this->assign('list',$list);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    
    public function op_withdraw(){

        $status= I('status');
        $id= I('id');
        if(!$id){
            $this->error('未知错误');
        }
        $res = M('Withdraw')->where(array('id'=>$id))->save(array('status'=>$status));
        if($res !== false){
            $withdraw_info = M("Withdraw")->where(array('id'=>$id))->find();
            if($status == 1){ /*通过*/
            }elseif($status == 3){ /*拒绝*/
                if($withdraw_info['tx_type'] == 1){ /*押金*/
                    M("member_info")->where(array('member_id'=>$res['member_id']))
                        ->save(array("is_pay_deposit"=>1));

                }elseif($withdraw_info['tx_type'] == 2){ /*佣金金*/
                    M("member_info")->where(array('member_id'=>$res['member_id']))
                        ->setInc('commision',$withdraw_info['money']);
                }
            }
            $this->success('操作成功',U('Withdraw/index'));
        }else{
            $this->error('未知错误');
        }
    }

	
}
<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
class ConfigController extends AdminController {
	public function _initialize(){
		parent::_initialize();
	}
	//空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	
	public function index(){
		$list=M('Config')->select();
		foreach ($list as $k=>$v){
               $list[$v['key']]=$v['value'];
				
		}
		$this->assign('config',$list);
       	$this->display();
     }
     
     public function customerService(){
     	$this->display();
     }
     
     public function shortMessage(){
     	$this->display();
     }
     public function finance(){
     	$this->display();
     }
     public function information(){
     	$this->display();
     }
     
     
     public function websiteBank(){
     	$this->display();
     }
     
     public function updateCofig(){
         if($_FILES["logo"]["tmp_name"]){
                $_POST['logo']=$this->upload($_FILES["logo"]);
                if (!$_POST['logo']){
                    $this->error('非法上传');
                }
         }
         if($_FILES["weixin"]["tmp_name"]){
              $_POST['weixin']=$this->upload($_FILES["weixin"]);
              if (!$_POST['weixin']){
                  $this->error('非法上传');
              }
         }
         if (!empty($_POST['friendship_tips'])){
     	      $_POST['friendship_tips'] = I('post.friendship_tips','','html_entity_decode');
         }
         if (!empty($_POST['withdraw_warning'])){
        	$_POST['withdraw_warning'] = I('post.withdraw_warning','','html_entity_decode');
         }
         if (!empty($_POST['risk_warning'])){
             $_POST['risk_warning'] = I('post.risk_warning','','html_entity_decode');
         }
         if (!empty($_POST['VAP_rule'])){
             $_POST['VAP_rule'] = I('post.VAP_rule','','html_entity_decode');
         }
         if (!empty($_POST['disclaimer'])){
         	$_POST['disclaimer'] = I('post.disclaimer','','html_entity_decode');
         }
         if (!empty($_POST['FWTK'])){
         	$_POST['FWTK'] = I('post.FWTK','','html_entity_decode');
         }
         if (!empty($_POST['rolling_num'])){
             $_POST['rolling_num'] = I('post.rolling_num','','html_entity_decode');
         }
         if($_FILES["wx_qrcode"]["tmp_name"]){
             $_POST['wx_qrcode']=$this->upload($_FILES["wx_qrcode"]);
             if (!$_POST['wx_qrcode']){
                 $this->error('非法上传');
             }
         }
         if($_FILES["zfb_qrcode"]["tmp_name"]){
             $_POST['zfb_qrcode']=$this->upload($_FILES["zfb_qrcode"]);
             if (!$_POST['zfb_qrcode']){
                 $this->error('非法上传');
             }
         }
         if($_POST['deposit'] < $_POST['dk_money']){
             $this->error('押金必须大于系统打款金额');
         }
     	foreach ($_POST as $k=>$v){
     		$rs[]=M('Config')->where(C("DB_PREFIX")."config.key='{$k}'")->setField('value',$v);
     	}
     	if($rs){
     		$this->success('配置修改成功');
     	}else{
     		$this->error('配置修改失败');
     	}
     }
}
<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2019/3/17
 * Time: 15:44
 */

namespace Mobile\Controller;

use Home\Controller\HomeController;
class PromoteController extends HomeController
{
    private $member_id;
    public function _initialize()
    {
        parent::_initialize();
        $this->member_id = session('USER_KEY_ID');
    }
    public function _empty(){
        header("HTTP/1.0 404 Not Found");
        $this->display('Public:404');
    }


    /*推广*/
    public function index(){
        $this->display();
    }

    /*团队信息*/
    public function my_promote(){

        $where = array('m.member_id'=>$this->member_id);
        $order = "m.reg_time desc";
        $list = D("member")->get_allinfo($where,$order);

        foreach ($list as &$value){
            $value['phone'] = substr_replace($value['phone'],"****",4,4);
        }
        $this->assign('list',$list);
        $this->display();
    }

    public function qrcodeimg(){
        Vendor('phpqrcode.phpqrcode');
        $QRcode = new \QRcode ();
        $errorCorrectionLevel = 'M';
        $matrixPointSize = 4;
        $member_id = session('USER_KEY_ID');
        $http = $_SERVER['HTTP_HOST'];
        $url = 'http://'.$http.'/Mobile/Reg/index/pid/'.$member_id;
        $QRcode::png($url,false,$errorCorrectionLevel,$matrixPointSize);

    }

}
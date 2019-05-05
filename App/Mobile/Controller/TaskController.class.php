<?php
namespace Mobile\Controller;
use Common\Controller\CommonController;
class TaskController extends CommonController {
	function _initialize(){
		parent::_initialize();
	}
	public function  _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	
	public function index(){
			$this->display();
    }

}
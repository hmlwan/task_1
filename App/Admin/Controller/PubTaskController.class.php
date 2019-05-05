<?php
/*
 * 后台理财管理
 */
namespace Admin\Controller;
use Admin\Controller\AdminController;
class PubTaskController extends AdminController {
    // 空操作
    public function _empty() {
        header ( "HTTP/1.0 404 Not Found" );
        $this->display ( 'Public:404' );
    }
    public function index() {
    $model = M ('PubTask' );

    $where = array();
    // 查询满足要求的总记录数
    $count = $model->where ( $where )->count ();
    // 实例化分页类 传入总记录数和每页显示的记录数
    $Page = new \Think\Page ( $count, 20 );
    //将分页（点击下一页）需要的条件保存住，带在分页中
    // 分页显示输出
    $show = $Page->show ();
    //需要的数据
    $field = "*";
    $info = $model->field ( $field )
        ->where ( $where )
        ->order ("id desc" )
        ->limit ( $Page->firstRow . ',' . $Page->listRows )
        ->select ();

    $this->assign ('info', $info ); // 赋值数据集
    $this->assign ('page', $show ); // 赋值分页输出
    $this->display ();
}

    public function add(){
        if(IS_POST){
            $model= D('PubTask');

            if($r = $model->create()){
                $r['create_time'] = time();
                if($_FILES["logo_img"]["tmp_name"]){
                    $r['logo_img']=$this->upload($_FILES["logo_img"]);
                    if (!$r['logo_img']){
                        $this->error('非法上传');
                    }
                }
                if($model->add($r)){
                    $this->success('添加成功',U('index'));
                    return;
                }else{
                    $this->error('服务器繁忙,请稍后重试');
                    return;
                }
            }else{
                $this->error($model->getError());
                return;
            }
        }else{
            $this->display();
        }
    }
    public function save(){

        $id = I('get.id','','intval');
        $model = D('PubTask');

        if(IS_POST){
            $id = I('post.id','','intval');
            $where['id'] = $id;

            if($r = $model->create()){
                $r['create_time'] = time();
                if($_FILES["logo_img"]["tmp_name"]){
                    $r['logo_img']=$this->upload($_FILES["logo_img"]);
                    if (!$r['logo_img']){
                        $this->error('非法上传');
                    }
                }
                if($model->where($where)->save($r)){
                    $this->success('修改成功',U('index'));
                    return;
                }else{
                    $this->error('服务器繁忙,请稍后重试');
                    return;
                }
            }else{
                $this->error($model->getError());
                return;
            }
        }else{
            if($id){
                $where['id'] = $id;
                $list = $model->where($where)->find();
                $this->assign('list',$list);
                $this->display();
            }else{
                $this->error('参数错误');
                return;
            }
        }
    }
    public function del(){

        if(empty($_POST['id'])){
            $info['status'] = -1;
            $info['info'] ='传入参数有误';
            $this->ajaxReturn($info);
        }

        $id = I('post.id','','intval');
        $r = M('PubTask')->delete($id);

        if(!$r){
            $info['status'] = 0;
            $info['info'] ='删除失败';
            $this->ajaxReturn($info);
        }
        $info['status'] = 1;
        $info['info'] ='删除成功';
        $this->ajaxReturn($info);
    }

}
?>
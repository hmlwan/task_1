<?php if (!defined('THINK_PATH')) exit();?>    <!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" >
	<meta name="renderer" content="webkit">
    <title>网站后台管理</title>
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="/Public/Admin/iconfont/demo.css">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/iconfont/iconfont.css"/>
    <script type="text/javascript" src="/Public/Admin/js/libs/modernizr.min.js"></script>
	<script type="text/javascript" src="/Public/Admin/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/Public/js/layer/layer.js"></script>
    <script type="text/javascript" src="/Public/js/laydate/laydate.js"></script>
<style>
.iconfont{ padding-right:5px;}
.fsize{ font-size:15px;}
</style>
</head>
<body>
<div class="topbar-wrap white">
    <div class="topbar-inner clearfix">
        <div class="topbar-logo-wrap clearfix">
            <h1 class="topbar-logo none"><a href="index.html" class="navbar-brand">后台管理</a></h1>
            <ul class="navbar-list clearfix">
                <li><a class="on" href="<?php echo U('Index/index');?>">首页</a></li>
                <!--<li><a href="<?php echo U('Mobile/Index/index');?>" target="_blank">网站首页</a></li>-->
                <!--<li><a href="<?php echo U('Index/infoStatistics');?>" target="_blank">全站统计信息</a></li>-->
            </ul>
        </div>
        <div class="top-info-wrap">
            <ul class="top-info-list clearfix">
                <li><a href="<?php echo U('Manage/index');?>">管理员</a></li>
                <li><a href="<?php echo U('Manage/pwdUpdate');?>">修改密码</a></li>
                <li><a href="<?php echo U('Login/loginout');?>">退出</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="container clearfix">
<div class="sidebar-wrap">
        <div class="sidebar-title">
            <h1>菜单</h1>
        </div>
        <div class="sidebar-content">
            <ul class="sidebar-list">
                 <?php if(!empty($sys_nav)): ?><li>
                    <a href="#"><i class="iconfont">&#xe614;</i><span class="fsize">系统管理</span></a>
                    <ul class="sub-menu">
                        
                        	<?php if(is_array($sys_nav)): $i = 0; $__LIST__ = $sys_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>#0#<?php echo ($key); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li>
                            <!--<li><a href="system.html"><i class="icon-font">&#xe037;</i>清理缓存</a></li>
                            <li><a href="system.html"><i class="icon-font">&#xe046;</i>数据备份</a></li>
                            <li><a href="system.html"><i class="icon-font">&#xe045;</i>数据还原</a></li>--><?php endforeach; endif; else: echo "" ;endif; ?>
                        
                    </ul>
                </li><?php endif; ?>
			   
                <?php if(!empty($user_nav)): ?><li>
                    <a href="#"><i class="iconfont">&#xe64d;</i><span class="fsize">会员管理</span></a>
                    <ul class="sub-menu">
                        <?php if(is_array($user_nav)): $i = 0; $__LIST__ = $user_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>#1#<?php echo ($key); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        <li><a href="/Admin/Member/auth_list"><i class="iconfont">&#x3434;</i>&nbsp;押金审核</a></li>
                    </ul>
                </li><?php endif; ?>

                <?php if(!empty($withdraw_nav)): ?><li>
                    <a href="#"><i class="iconfont">&#xe6c8;</i><span class="fsize">提现管理</span></a>
                     <ul class="sub-menu">
                        <?php if(is_array($withdraw_nav)): $i = 0; $__LIST__ = $withdraw_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>#2#<?php echo ($key); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </li><?php endif; ?>

                <?php if(!empty($trade_nav)): ?><li>
                        <a href="#"><i class="iconfont">&#xe631;</i><span class="fsize">交易管理</span></a>
                        <ul class="sub-menu">
                            <?php if(is_array($trade_nav)): $i = 0; $__LIST__ = $trade_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>#3#<?php echo ($key); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </li><?php endif; ?>

                <?php if(!empty($pubtask_nav)): ?><li>
                        <a href="#"><i class="iconfont">&#xe6f7;</i><span class="fsize">任务管理</span></a>
                        <ul class="sub-menu">
                            <?php if(is_array($pubtask_nav)): $i = 0; $__LIST__ = $pubtask_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>#6#<?php echo ($key); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </li><?php endif; ?>
                <?php if(!empty($admin_nav)): ?><li>
                        <a href="#"><i class="iconfont">&#xe64d;</i><span class="fsize">管理员管理</span></a>
                        <ul class="sub-menu">
                            <?php if(is_array($admin_nav)): $i = 0; $__LIST__ = $admin_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>#4#<?php echo ($key); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </li><?php endif; ?>
                <!--<?php if(!empty($tongji_nav)): ?>-->
                    <!--<li>-->
                        <!--<a href="#"><i class="iconfont">&#xe64d;</i><span class="fsize">统计</span></a>-->
                        <!--<ul class="sub-menu">-->
                            <!--<?php if(is_array($tongji_nav)): $i = 0; $__LIST__ = $tongji_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
                                <!--<li><a href="<?php echo ($vo["nav_url"]); ?>#5#<?php echo ($key); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li>-->
                            <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
                        <!--</ul>-->
                    <!--</li>-->
                <!--<?php endif; ?>-->

                <!--<?php if(!empty($transfer_nav)): ?>-->
                    <!--<li>-->
                        <!--<a href="#"><i class="iconfont">&#xe6f7;</i><span class="fsize">转账管理</span></a>-->
                        <!--<ul class="sub-menu">-->
                            <!--<?php if(is_array($transfer_nav)): $i = 0; $__LIST__ = $transfer_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
                                <!--<li><a href="<?php echo ($vo["nav_url"]); ?>#7#<?php echo ($key); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li>-->
                            <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
                        <!--</ul>-->
                    <!--</li>-->
                <!--<?php endif; ?>-->
            </ul>
        </div>
    </div>
<script>

$(".sidebar-list li").children("a").on("click",function(){
	$(this).next(".sub-menu").toggle();
});
var num = window.location.hash;
var num =  num.split('#');

$(".sub-menu").eq(num[1]).show();
$(".sub-menu").eq(num[1]).children("li").eq(num[2]).addClass("on");

</script>

    <!--/sidebar-->
    <div class="main-wrap">

        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="<?php echo U('Index/index');?>">首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">会员管理</span></div>
        </div>
        <div class="search-wrap">
            <div class="search-content">
                <form action="<?php echo U('Member/index');?>" method="get">
                    <table class="search-tab">
                        <tr>
                            <td width="70">会员账号:</td>
                            <td><input class="common-text" placeholder="关键字" name="username" value="<?php echo ($_GET['username']); ?>" type="text"></td>
                              <td width="70">会员ID:</td>
                            <td><input class="common-text" placeholder="ID" name="member_id" value="<?php echo ($_GET['member_id']); ?>" type="text"></td>
                            <td><input class="btn btn-primary btn2"  value="查询" type="submit"></td>
                        </tr>
                    </table>
                </form>
				
            </div>
        </div>
        <div class="result-wrap">

            <div class="result-title">
                <div class="result-list">
                    <a href="<?php echo U('Member/addMember');?>"><i class="icon-font"></i>新增会员</a>
                </div>
            </div>
            <div class="result-content">
                <table class="result-tab" width="100%">
                    <tr>
                        <th>ID</th>
                        <th>用户名</th>
						<th>推荐人</th>
                        <th>手机号</th>
                        <th>佣金</th>
                        <th>是否为优先匹配者</th>
                        <th>星星数</th>
                        <th>抢单复购次数</th>
                        <th>收款复购次数</th>
						<th>注册时间</th>
                        <th>登录状态</th>
                        <th>操作</th>
                    </tr>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><?php echo ($vo['member_id']); ?></td>
                            <td><?php echo ($vo['username']); ?></td>
							<td><?php echo ($vo['pid']); ?></td>
                            <td><?php echo ($vo['phone']); ?></td>
                            <td><?php echo ($vo['commision']); ?></td>
                            <td>
                                <?php if($vo['is_priority'] == 0): ?>否<?php endif; ?>
                                <?php if($vo['is_priority'] == 1): ?>是<?php endif; ?>
                            </td>
                            <td><?php echo ($vo['stars']); ?></td>
                            <td><?php echo ($vo['qd_fg_num']); ?></td>
                            <td><?php echo ($vo['fk_fg_num']); ?></td>
							<td><?php echo (date('Y-m-d H:i:s',$vo['reg_time'])); ?></td>
                            <td><?php if($vo['is_lock'] == 0): ?>正常<?php endif; ?>
								<?php if($vo['is_lock'] == 1): ?>禁用<?php endif; ?>

							</td>
                            <td>
                                <!--<a class="link-del" href="<?php echo U('Finance/index',array('member_id'=>$vo['member_id']));?>">查看财务日志</a>-->
                                <!--<br>-->
                                <a class="link-del" href="<?php echo U('Member/show_my_invit',array('member_id'=>$vo['member_id']));?>">查看邀请人员</a><br>
                                <a class="link-update" href="<?php echo U('Member/saveMember',array('member_id'=>$vo['member_id']));?>">修改|</a>
                                <a class="link-update" href="<?php echo U('Member/become_priority',array('member_id'=>$vo['member_id']));?>" onclick="return confirm('确认成为优先匹配者吗？')">成为优先匹配者|</a>
                                <a class="link-del" href="<?php echo U('Member/delMember',array('member_id'=>$vo['member_id']));?>" onclick="return confirm('确认删除吗？')">删除|</a>
                                <?php if($vo['is_lock'] == 0): ?><a class="link-lock" href="<?php echo U('Member/lockMember',array('member_id'=>$vo['member_id'],'is_lock'=>1));?>"  onclick="return confirm('确认禁用吗？')">禁用</a><?php endif; ?>
                                <?php if($vo['is_lock'] == 1): ?><a class="link-lock" href="<?php echo U('Member/lockMember',array('member_id'=>$vo['member_id'],'is_lock'=>0));?>"  onclick="return confirm('确认解封吗？')">解封</a><?php endif; ?>
                                <br>
                                <a class="link-update" href="<?php echo U('Member/show',array('member_id'=>$vo['member_id']));?>">查看会员信息</a><br>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>

                </table>
                <div class="list-page"> <ul><?php echo ($page); ?></ul></div>
            </div>

        </div>
    </div>
    <!--/main-->

</div>
</body>
</html>
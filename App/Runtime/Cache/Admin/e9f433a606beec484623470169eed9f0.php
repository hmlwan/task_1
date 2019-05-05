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
            <div class="crumb-list"><i class="icon-font"></i><a href="<?php echo U('Index/index');?>">首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">系统设置</span></div>
        </div>
        <div class="result-wrap">
            <form action="<?php echo U('Config/updateCofig');?>" method="post" id="myform" name="myform" enctype="multipart/form-data">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe00a;</i>网站财务设置</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                              <tr>
                                    <th><i class="require-red">*</i>交易时间段：开始时间</th>
                                    <td>时：<input type="text" id="" value="<?php echo ($config["jiaoyi_start_hour"]); ?>" size="30" name="jiaoyi_start_hour" class="common-text">&nbsp;&nbsp;&nbsp;分：<input type="text" id="" value="<?php echo ($config["jiaoyi_start_minute"]); ?>" size="30" name="jiaoyi_start_minute" class="common-text"></td>
                                </tr>
                                <tr>
                                    <th><i class="require-red">*</i>交易时间段：结束时间</th>
                                    <td>时：<input type="text" id="" value="<?php echo ($config["jiaoyi_over_hour"]); ?>" size="30" name="jiaoyi_over_hour" class="common-text">&nbsp;&nbsp;&nbsp;分：<input type="text" id="" value="<?php echo ($config["jiaoyi_over_minute"]); ?>" size="30" name="jiaoyi_over_minute" class="common-text"></td>
                                </tr>
                                <tr>
                                    <th><i class="require-red">*</i>人工充值最低限额：</th>
                                    <td><input type="text" id="" value="<?php echo ($config["pay_min_money"]); ?>" size="85" name="pay_min_money" class="common-text"></td>
                                </tr>
                                <tr>
                                    <th><i class="require-red">*</i>人工充值最高限额：</th>
                                    <td><input type="text" id="" value="<?php echo ($config["pay_max_money"]); ?>" size="85" name="pay_max_money" class="common-text"></td>
                                </tr>
                                <tr>
                                    <th><i class="require-red">*</i>人工提现最低限额：</th>
                                    <td><input type="text" id="" value="<?php echo ($config["withdraw_min_money"]); ?>" size="85" name="withdraw_min_money" class="common-text"></td>
                                </tr>
                                <tr>
                                    <th><i class="require-red">*</i>人工提现最高限额：</th>
                                    <td><input type="text" id="" value="<?php echo ($config["withdraw_max_money"]); ?>" size="85" name="withdraw_max_money" class="common-text"></td>
                                </tr>
                                <tr>
                                    <th><i class="require-red">*</i>登录超时退出（分钟）：</th>
                                    <td><input type="num" id="" value="<?php echo ($config["time_limit"]); ?>" size="85" name="time_limit" class="common-text">为0则此项无配置</td>
                                </tr>
                                <tr>
                                    <th><i class="require-red">*</i>人工充值手续费：</th>
                                    <td><input type="text" id="" value="<?php echo ($config["pay_fee"]); ?>" size="85" name="pay_fee" class="common-text">元&nbsp;&nbsp;&nbsp;固定费用，无请填0</td>
                                </tr>
                                <tr>
                                    <th><i class="require-red">*</i>人工提现手续费：</th>
                                    <td><input type="text" id="" value="<?php echo ($config["fee"]); ?>" size="85" name="fee" class="common-text">%&nbsp;&nbsp;&nbsp;比例费用，无请填0</td>
                                </tr>
                                 <tr>
                                    <th><i class="require-red">*</i>提币手续费：</th>
                                    <td><input type="text" id="" value="<?php echo ($config["tcoin_fee"]); ?>" size="85" name="tcoin_fee" class="common-text">%&nbsp;&nbsp;&nbsp;比例费用，无请填0</td>
                                </tr>
                                 <tr>
                                    <th><i class="require-red">*</i>网站总交易额增加：</th>
                                    <td><input type="text" id="" value="<?php echo ($config["transaction_false"]); ?>" size="85" name="transaction_false" class="common-text">用于首页显示</td>
                                </tr>
                                 <tr>
                                    <th><i class="require-red">*</i>解冻比例：</th>
                                    <td><input type="text" id="" value="<?php echo ($config["jiedong_bili"]); ?>" size="85" name="jiedong_bili" class="common-text">%&nbsp;&nbsp;&nbsp;每日按照设置比例解冻</td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td>
                                        <input type="submit" value="提交" class="btn btn-primary btn6 mr10">
                                        <input type="button" value="返回" onclick="history.go(-1)" class="btn btn6">
                                    </td>
                                </tr>
                            </tbody></table>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--/main-->
</div>
</body>
</html>
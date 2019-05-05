<?php if (!defined('THINK_PATH')) exit();?>
<!doctype html>
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
		<div class="crumb-list">
			<i class="icon-font"></i><a href="<?php echo U('Index/index');?>">首页</a><span
				class="crumb-step">&gt;</span><span class="crumb-name">系统设置</span>
		</div>
	</div>
	<div class="result-wrap">
		<form action="<?php echo U('Config/updateCofig');?>" method="post" id="myform"
			name="myform" enctype="multipart/form-data">
			<div class="config-items">
				<div class="config-title">
					<h1>
						<i class="icon-font">&#xe00a;</i>网站信息设置
					</h1>
				</div>
				<div class="result-content">
					<table width="100%" class="insert-tab">
						<tbody>
							<tr>
								<th><i class="require-red">*</i>网站LOGO上传：</th>
								<td><input type="file" name="logo" class="common-text"></td>
								<th width="15%"><i class="require-red">*</i>网站LOGO：</th>
								<td><img style="width: 300px;height: 150px;border-radius: 15px;border: 1px solid;" src="<?php echo ($config["logo"]); ?>"><input type="hidden"
									name="logo" value="<?php echo ($config["logo"]); ?>" class="common-text"></td>
							</tr>
							<tr>
								<th><i class="require-red">*</i>网站标题：</th>
								<td><input type="text" id="" value="<?php echo ($config["title"]); ?>"
										   size="85" name="title" class="common-text"></td>
								<th><i class="require-red">*</i>网站网址：</th>
								<td><input type="text" id="" value="<?php echo ($config["localhost"]); ?>"
									size="85" name="localhost" class="common-text"></td>
							</tr>

							<tr>
								<th><i class="require-red">*</i>网站名称：</th>
								<td><input type="text" id="" value="<?php echo ($config["name"]); ?>"
									size="85" name="name" class="common-text"></td>
								<th><i class="require-red">*</i>网站关键字：</th>
								<td><input type="text" id="" value="<?php echo ($config["keywords"]); ?>"
										   size="85" name="keywords" class="common-text"></td>
							</tr>
							<tr>
								<th><i class="require-red">*</i>网站描述：</th>
								<td><input type="text" id="" value="<?php echo ($config["desc"]); ?>"
										   size="85" name="desc" class="common-text"></td>
								<th><i class="require-red">*</i>地址：</th>
								<td><input type="text" id="" value="<?php echo ($config["address"]); ?>"
										   size="85" name="address" class="common-text"></td>
							</tr>

							<tr>
								<th><i class="require-red">*</i>版权信息：</th>
								<td><input type="text" id="" value="<?php echo ($config["copyright"]); ?>"
									size="85" name="copyright" class="common-text"></td>
								<th><i class="require-red">*</i>备案信息：</th>
								<td><input type="text" id="" value="<?php echo ($config["record"]); ?>"
										   size="85" name="record" class="common-text"></td>
							</tr>
							<tr>
								<th><i class="require-red">*</i>每天抢单次数：</th>
								<td><input type="text" id="" value="<?php echo ($config["dayqdnum"]); ?>"
										   size="85" name="dayqdnum" class="common-text"></td>
								<th><i class="require-red">*</i>每天付款次数：</th>
								<td><input type="text" id="" value="<?php echo ($config["dayfknum"]); ?>"
										   size="85" name="dayfknum" class="common-text"></td>
							</tr>
							<tr>
								<th><i class="require-red">*</i>押金：</th>
								<td><input type="text" id="" value="<?php echo ($config["deposit"]); ?>"
										   size="85" name="deposit" class="common-text"></td>
								<th><i class="require-red">*</i>系统匹配打款金额：</th>
								<td><input type="text" id="" value="<?php echo ($config["dk_money"]); ?>"
										   size="30" name="dk_money" class="common-text">

									佣金率：<input type="text" id="" value="<?php echo ($config["commision_rate"]); ?>"
										   size="30" name="commision_rate" class="common-text">
								</td>

							</tr>
							<tr>
								<th><i class="require-red">*</i>推广达到数量：</th>
								<td><input type="text" id="" value="<?php echo ($config["promote_num"]); ?>"
										   size="85" name="promote_num" class="common-text"></td>
								<th><i class="require-red">*</i>平台合伙人：</th>
								<td>直推：<input type="text" id="" value="<?php echo ($config["direct_push_num"]); ?>"
										   size="15" name="direct_push_num" class="common-text">
									团体：<input type="text" id="" value="<?php echo ($config["team_push_num"]); ?>"
											  size="15" name="team_push_num" class="common-text">
									额外百分比：<input type="text" id="" value="<?php echo ($config["increase_rate"]); ?>"
											  size="15" name="increase_rate" class="common-text">

								</td>

							</tr>
							<tr>
								<th><i class="require-red">*</i>恢复星星个数：</th>
								<td><input type="text" id="" value="<?php echo ($config["refresh_stars"]); ?>"
										   size="85" name="refresh_stars" class="common-text"></td>
								<th><i class="require-red">*</i>提现金额：</th>
								<td><input type="text" id="" value="<?php echo ($config["withdraw_amount"]); ?>"
										   size="85" name="withdraw_amount" class="common-text"></td>

							</tr>
							<tr>
								<th><i class="require-red">*</i>支付宝收款二维码上传：</th>
								<td><input type="file" name="zfb_qrcode" class="common-text"></td>
								<th width="15%"><i class="require-red">*</i>支付宝收款二维码：</th>
								<td><img style="width: 300px;height: 150px;border-radius: 15px;border: 1px solid;" src="<?php echo ($config["zfb_qrcode"]); ?>"><input type="hidden"
								 name="zfb_qrcode" value="<?php echo ($config["zfb_qrcode"]); ?>" class="common-text"></td>
							</tr>
							<tr>
								<th><i class="require-red">*</i>微信收款二维码上传：</th>
								<td><input type="file" name="wx_qrcode" class="common-text"></td>
								<th width="15%"><i class="require-red">*</i>微信收款二维码：</th>
								<td><img style="width: 300px;height: 150px;border-radius: 15px;border: 1px solid;" src="<?php echo ($config["wx_qrcode"]); ?>"><input type="hidden"
																																			   name="wx_qrcode" value="<?php echo ($config["wx_qrcode"]); ?>" class="common-text"></td>
							</tr>

							<tr>
								<th></th>
								<td ><input type="submit" value="提交"
									class="btn btn-primary btn6 mr10"> <input type="button"
									value="返回" onclick="history.go(-1)" class="btn btn6"></td>
							</tr>

						</tbody>
					</table>
				</div>
			</div>

		</form>
	</div>
</div>
<!--/main-->
</div>
</body>
</html>
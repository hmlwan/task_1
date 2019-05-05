<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
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
        <div class="crumb-list"><i class="icon-font"></i>首页</div>
    </div>
    <div class="result-wrap">
        <div class="result-title">
            <h1>快捷操作</h1>
        </div>
        <div class="result-content">
            <div class="short-wrap">
                <li>当前登录管理员：<?php echo ((isset($admin_user["name"]) && ($admin_user["name"] !== ""))?($admin_user["name"]):'admin'); ?></li>
            </div>
        </div>
    </div>
    <!--<div class="result-wrap">-->
        <!--<div class="result-title">-->
            <!--<h1>当前币种信息</h1>-->
        <!--</div>-->
        <!--<div class="result-content">-->
            <!--<table class="result-tab" width="100%">-->
                <!--<tr>-->
                    <!--<th>货币LOGO</th>-->
                    <!--<th>货币名称</th>-->
                    <!--<th>英文标识</th>-->
                    <!--<th>总市值</th>-->
                    <!--<th>钱包余额</th>-->
                    <!--<th>最新价格</th>-->
                    <!--<th>昨日价格</th>-->
                    <!--<th>24H交易量</th>-->
                    <!--<th>全站交易量</th>-->
                    <!--<th>操作</th>-->
                <!--</tr>-->
                <!--<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
                    <!--<tr>-->
                        <!--<td><?php if(!empty($vo["currency_logo"])): ?><img  style="height:40px;"src='<?php echo ($vo["currency_logo"]); ?>' /><?php else: ?>暂无图片数据<?php endif; ?></td>-->
                        <!--<td><?php echo ((isset($vo["currency_name"]) && ($vo["currency_name"] !== ""))?($vo["currency_name"]):'暂无'); ?></td>-->
                        <!--<td><?php echo ((isset($vo["currency_mark"]) && ($vo["currency_mark"] !== ""))?($vo["currency_mark"]):'暂无'); ?></td>-->
                        <!--<td>￥<?php echo ((isset($vo["currency_all_money"]) && ($vo["currency_all_money"] !== ""))?($vo["currency_all_money"]):'0.00'); ?></td>-->
                        <!--<td><?php echo ((isset($vo["balance"]) && ($vo["balance"] !== ""))?($vo["balance"]):'0.00'); ?></td>-->
                        <!--<td><?php echo ((isset($vo["new_price"]) && ($vo["new_price"] !== ""))?($vo["new_price"]):'0.00'); ?></td>-->
                        <!--<td><?php echo ((isset($vo["old_price"]) && ($vo["old_price"] !== ""))?($vo["old_price"]):'0.00'); ?></td>-->
                        <!--<td><?php echo ((isset($vo["24H_done_num"]) && ($vo["24H_done_num"] !== ""))?($vo["24H_done_num"]):'0.00'); ?></td>-->
                        <!--<td><?php echo ((isset($vo["all_done_num"]) && ($vo["all_done_num"] !== ""))?($vo["all_done_num"]):'0.00'); ?></td>-->
                        <!--<td>-->
                            <!--<a class="link-update" href="<?php echo U('Currency/add',array('currency_id'=>$vo['currency_id']));?>">查看币种信息</a><br>-->
                            <!--<a class="link-update" href="<?php echo U('Trade/trade',array('currency_id'=>$vo['currency_id']));?>">查看交易记录</a><br>-->
                            <!--<a class="link-update" href="<?php echo U('Trade/orders',array('currency_id'=>$vo['currency_id']));?>">查看委托记录</a>-->
                        <!--</td>-->
                    <!--</tr>-->
                <!--<?php endforeach; endif; else: echo "$empty" ;endif; ?>-->
            <!--</table>-->
        <!--</div>-->
    <!--</div>-->
    <!--<div class="result-wrap">-->
        <!--<div class="result-title">-->
            <!--<h1>全站统计信息</h1>-->
        <!--</div>-->
        <!--<div class="result-content">-->
            <!--<table class="result-tab" width="100%">-->
                <!--<tr>-->
                    <!--<td>网站总收入</td>-->
                    <!--<td><?php echo ((isset($a) && ($a !== ""))?($a):'0'); ?></td>-->
                    <!--<td>网站总支出</td>-->
                    <!--<td><?php echo ((isset($a) && ($a !== ""))?($a):'0'); ?></td>-->
                <!--</tr>-->
                <!--<tr>-->
                    <!--<td>会员总人数</td>-->
                    <!--<td><?php echo ((isset($member) && ($member !== ""))?($member):'0'); ?>人</td>-->
                    <!--<td>众筹总数量</td>-->
                    <!--<td><?php echo ((isset($a) && ($a !== ""))?($a):'0'); ?></td>-->
                <!--</tr>-->
                <!--<tr>-->
                    <!--<td>人民币收入</td>-->
                    <!--<td>￥<?php echo ((isset($a) && ($a !== ""))?($a):'0'); ?></td>-->
                    <!--<td>人民币支出</td>-->
                    <!--<td>￥<?php echo ((isset($a) && ($a !== ""))?($a):'0'); ?></td>-->
                <!--</tr>-->
                <!--<tr>-->
                    <!--<td><?php echo ($config["xnb"]); ?>收入</td>-->
                    <!--<td><?php echo ((isset($a) && ($a !== ""))?($a):'0'); ?></td>-->
                    <!--<td><?php echo ($config["xnb"]); ?>支出</td>-->
                    <!--<td><?php echo ((isset($a) && ($a !== ""))?($a):'0'); ?></td>-->
                <!--</tr>-->
                <!--<tr>-->
                    <!--<td>充值数量</td>-->
                    <!--<td><?php echo ((isset($a) && ($a !== ""))?($a):'0'); ?></td>-->
                    <!--<td>提现数量</td>-->
                    <!--<td><?php echo ((isset($a) && ($a !== ""))?($a):'0'); ?></td>-->
                <!--</tr>-->
            <!--</table>-->
        <!--</div>-->
    <!--</div>-->
    <div class="result-wrap">
        <div class="result-title">
            <h1>服务器信息</h1>
        </div>
        <div class="result-content">
            <table class="result-tab" width="100%">
                <tr>
                    <td width="20%">系统版本</td>
                    <td width="20%"><?php echo php_uname('r');?></td>
                    <td width="20%">服务器操作系统</td>
                    <td width="40%"><?php echo php_uname('s');?></td>
                </tr>
                <tr>
                    <td>运行环境</td>
                    <td><?php echo php_sapi_name();?></td>
                    <td>PHP版本</td>
                    <td><?php echo PHP_VERSION;?></td>
                </tr>
                <tr>
                    <td>MySql版本</td>
                    <td><?php echo mysqlnd;?></td>
                    <td>服务器IP</td>
                    <td><?php echo GetHostByName($_SERVER['SERVER_NAME']);?></td>
                </tr>
                <tr>
                    <td>你的IP</td>
                    <td><?php echo $_SERVER['REMOTE_ADDR'];?></td>
                    <td>服务器端口</td>
                    <td><?php echo $_SERVER['SERVER_PORT'];?></td>
                </tr>
                <tr>
                    <td>绝对路径</td>
                    <td><?php echo $_SERVER['DOCUMENT_ROOT'];?></td>
                    <td>网站域名</td>
                    <td><?php echo $_SERVER['SERVER_NAME'];?></td>
                </tr>
                <tr>
                    <td>清理缓存</td>
                    <td><input type="button" id="button" value="清理缓存"/></td>
                    <input type="hidden" id="type" value="Runtime-Cache"/>
                    <td>网站开发</td>
                    <td><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=421905074&site=qq&menu=yes"><img border="0" src="/Public/Admin/images/QQ.png" alt="点击这里给我发消息" title="点击这里给我发消息"/></a></td>
                </tr>
                <tr>
                    <td>官网地址</td>
                    <td>www.zhej.com</td>
                    <td>版权所有</td>
                    <td>www.zheyj.com</td>
                </tr>
            </table>
        </div>
    </div>
    <script>
        $(function(){
            $('#button').click(function(){
                if(confirm("确认要清除缓存？")){
                    var $type=$('#type').val();
                    $.post("<?php echo U('Index/cache');?>",{type:$type},function(data){
                        alert("缓存清理成功");
                    });
                }else{
                    return false;
                }});
        });
    </script>
</div>
<!--/main-->
</div>
</body>
</html>
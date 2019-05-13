<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <!--申明当前页面的编码集-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--网页标题-->
    <title></title>
    <!--网页关键词-->
    <meta name="keywords" content="" />
    <!--网页描述-->
    <meta name="description" content="" />
    <!--适配当前屏幕-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <!--禁止自动识别电话号码-->
    <meta name="format-detection" content="telephone=no" />
    <!--禁止自动识别邮箱-->
    <meta content="email=no" name="format-detection" />
    <!--iphone中safari私有meta标签,
        允许全屏模式浏览,隐藏浏览器导航栏-->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!--iphone中safari顶端的状态条的样式black(黑色)-->
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!--如果用户装了谷歌浏览器，用ie浏览时使用谷歌内核-->
    <!--<meta http-equiv=”X-UA-Compatible” content=”IE=edge,chrome=1″/>-->
    <!--css文件-->
    <link type="text/css" rel="stylesheet" href="/Public/Mobile/css/task/reset.css" />
    <link type="text/css" rel="stylesheet" href="/Public/Mobile/css/task/style.css" />
    <script src="/Public/Mobile/js/task/jquery1.10.2.min.js"></script>
    <script src="/Public/Mobile/js/task/fontSize.js"></script>
    <!--<script src="/Public/Mobile/js/layer_mobile/layer.js"></script>-->
    <script src="/Public/Mobile/js/layer/layer.js"></script>
    <script src="/Public/Mobile/js/task/common.js"></script>
</head>

<body>
<div style="background-color:#f8f8f8 ">
    <header>
        <div class="gwc_bt">
            <div class="gwcbt_fh"><a href="javascript:history.back()" class="gwcdd_fh"></a></div>
            <div class="gwcbt_mc">个人中心</div>
        </div>
        <div style="height: .74rem;clear: both;"></div>
    </header>

    <div class="mem_bgk">
       <div class="memtop_left">
           <img src=" <?php if($list['head']): echo ($list["head"]); ?> <?php else: ?>/Public/Mobile/images/task/gwc_spimg.jpg<?php endif; ?>" alt="">
       </div>
       <div class="memtop_middle">
           <p class="memtop_name"><?php echo ($list["username"]); ?></p>
           <p>加入时间：<?php echo (date("Y-m-d",$list["reg_time"])); ?>
               <?php if($list['is_give_deposit'] == 0): ?>(未交押金)
                   <?php elseif($list['is_give_deposit'] == 1): ?>
                   (任务执行者)
                   <?php elseif($list['is_give_deposit'] == 2): ?>
                   (押金审核中)
                   <?php elseif($list['is_give_deposit'] == 3): ?>
                   (押金审核失败)<?php endif; ?>
           </p>
           <p>
               <?php $__FOR_START_19308__=1;$__FOR_END_19308__=6;for($i=$__FOR_START_19308__;$i < $__FOR_END_19308__;$i+=1){ if($list['stars'] >= $i): ?><img src="/Public/Mobile/images/task/stared.png" alt="">
                     <?php else: ?>
                        <img src="/Public/Mobile/images/task/star.png" alt=""><?php endif; } ?>
           </p>
       </div>
    </div>

<div class="srje">
    <h2>收入金额</h2>
    <div class="srje_left">
        <p>佣金  <a href="<?php echo U('Trade/my_commision');?>"><i></i></a></p>
        <p class="srje_money"><?php if($commission_sum): echo ($commission_sum); else: ?>0.00<?php endif; ?> <em>元</em></p>
    </div>
    <div class="srje_riht">
        <p>支出 <a href="<?php echo U('Trade/qd_record');?>"><i></i></a></p>
        <p class="srje_money"><?php if($money_sum): echo ($money_sum); else: ?>0.00<?php endif; ?> <em>元</em></p>
    </div>
</div>

<div class="mem_center">
    <div class="menu_info right_icon">
        <div><a href="<?php echo U('Member/myinfo');?>"><img src="/Public/Mobile/images/task/wdxx.png" alt="">我的信息 <em></em></a> </div>
        <div><a href="<?php echo U('Trade/qd_record');?>"><img src="/Public/Mobile/images/task/wdqd.png" alt="">抢单记录 <em></em></a> </div>
        <div><a href="<?php echo U('Trade/sk_record');?>"><img src="/Public/Mobile/images/task/skjl.png" alt="">收款记录 <em></em></a> </div>
        <div><a href="<?php echo U('Trade/my_commision');?>"><img src="/Public/Mobile/images/task/wdyj.png" alt="">我的佣金 <em></em></a> </div>
        <div><a href="<?php echo U('Promote/my_promote');?>"><img src="/Public/Mobile/images/task/tdxx.png" alt="">团队信息 <em></em></a> </div>
        <div><a href="<?php echo U('Promote/index');?>"><img src="/Public/Mobile/images/task/wytg.png" alt="">我要推广 <em></em></a> </div>
        <div><a href="<?php echo U('Trade/withdraw');?>"><img src="/Public/Mobile/images/task/wytx.png" alt="">我要提现 <em></em></a> </div>
        <div><a href="<?php echo U('Login/loginout');?>"><img src="/Public/Mobile/images/task/tcdl.png" alt="">退出登录 <em></em></a> </div>
    </div>
</div>

<div style="height: .9rem;"></div>
    <div class="footer_main">
        <ul>
            <a href="<?php echo U('Index/index');?>">
                <li class="footer_sy">首页</li>
            </a>
            <a href="<?php echo U('Task/index');?>">
                <li class="footer_fl">任务</li>
            </a>
            <a href="<?php echo U('Promote/index');?>">
                <li class="footer_tg"> 推广</li>
            </a>
            <a href="javascript:;">
                <li class="footer_hyzx">个人中心<i class="footer_active"></i>
                </li>
            </a>
        </ul>
    </div>
</div>
<script>
    var mem_status = <?php echo ($mem_status); ?>;
    if(mem_status == 1){
        layer.msg("请去完善个人信息");
        window.setTimeout(function(){
            window.location="<?php echo U('FullInfo/full_info');?>";
        },1000);
    }else if(mem_status == 2){
        layer.alert('去成为任务执行者', {
            skin: 'layui-layer-molv' //样式类名
            ,closeBtn: 0
        }, function(){
            window.location="<?php echo U('FullInfo/pay_deposit');?>";
        });
    }else if(mem_status == 3){
        layer.msg("失败，请重新上传凭证");
        window.setTimeout(function(){
            window.location="<?php echo U('FullInfo/pay_deposit');?>";
        },1000);
    }

</script>
<script src="/Public/Mobile/js/task/utils.js"></script>

</body>
</htm
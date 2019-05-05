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
    <script src="/Public/Mobile/js/layer/layer.js"></script>
    <script src="/Public/Mobile/js/task/common.js"></script>
</head>

<body>
<div  class="d_line">
    <header>
        <div class="gwc_bt">
            <div class="gwcbt_fh"><a href="javascript:history.back()" class="gwcdd_fh"></a></div>
            <div class="gwcbt_mc">抢单详情</div>
        </div>
        <div style="height: .96rem;clear: both;"></div>
    </header>
    <div class="qdxq">
        <div class="qrcode">
            <p>
                <?php switch($info['status']): case "0": ?>匹配中<?php break;?>
                    <?php case "1": ?>成功<?php break;?>
                    <?php case "2": ?>等待收款<?php break;?>
                    <?php case "3": ?>付款失败<?php break; endswitch;?>
            </p>
            <img src="<?php echo ($info["img"]); ?>" alt="暂无凭证">
        </div>
        <div class="qdxq_item">
            <span class="qdxq_left">任务标题</span>
            <span class="qdxq_right">
                <?php if($info['task_id'] > 0): echo ($info["task_title"]); ?>
                <?php else: ?>
                    系统匹配<?php endif; ?>
            </span>
        </div>
        <div class="qdxq_item">
            <span class="qdxq_left">交易类型</span>
            <span class="qdxq_right">支付</span>
        </div>
        <div class="qdxq_item">
            <span class="qdxq_left">交易金额</span>
            <span class="qdxq_right"><?php echo ($info["reward"]); ?></span>
        </div>
        <div class="qdxq_item">
            <span class="qdxq_left">匹配者</span>
            <span class="qdxq_right"><?php echo ($info["sk_name"]); ?></span>
        </div>
        <div class="qdxq_item">
            <span class="qdxq_left">创建时间</span>
            <span class="qdxq_right"><?php echo (date("Y-m-d H:i:s",$info["create_time"])); ?></span>
        </div>
    </div>
</div>
<script src="/Public/Mobile/js/task/utils.js"></script>

</body>
</htm
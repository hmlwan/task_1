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
<div>
    <header>
        <div class="gwc_bt">
            <div class="gwcbt_fh"><a href="javascript:history.back()" class="gwcdd_fh"></a></div>
            <div class="gwcbt_mc">银行卡信息</div>
        </div>
        <div style="height: .74rem;clear: both;"></div>
    </header>
    <div class="yhkxx xgmm">
        <div>
            <label for="">银行</label>
            <input type="text" name="bankname" value="<?php echo ($info["bankname"]); ?>" <?php if($info['bankname']): ?>readonly<?php endif; ?>   placeholder="请输入银行"/>
        </div>
        <div>
            <label for="">用户名</label>
            <input type="text" name="cardname" value="<?php echo ($info["cardname"]); ?>"  placeholder="请输入用户名" />
        </div>
        <div>
            <label for="">卡号</label>
            <input type="text" name="cardnum" value="<?php echo ($info["cardnum"]); ?>" placeholder="请输入卡号" />
        </div>
        <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
        <div class="confirm_btn" onclick="submit()">确&nbsp;认</div>
    </div>
</div>
<script>
    function submit(){

        var bankname = $("input[name='bankname']").val();
        var cardname = $("input[name='cardname']").val();
        var cardnum = $("input[name='cardnum']").val();
        var id = $("input[name='id']").val();

        if(bankname == ""||bankname == null){
            layer.msg('请输入银行名称');
            return false;
        }
        if(cardname == ""||cardname == null){
            layer.msg('请输入用户名');
            return false;
        }
        if(cardnum == ""||cardnum == null){
            layer.msg('请输入卡号');
            return false;
        }
        $.post("<?php echo U('Member/addbank');?>",{
            bankname:bankname,
            cardname:cardname,
            cardnum:cardnum,
            id:id,
        },function(data){
            if(data.status==1){
                layer.msg(data.info,{icon:1});
                window.location="/Mobile/Member/banklist";
            }else{
                layer.msg(data.info);
                return false;
            }
        })
    };

</script>
<script src="/Public/Mobile/js/task/utils.js"></script>

</body>
</htm
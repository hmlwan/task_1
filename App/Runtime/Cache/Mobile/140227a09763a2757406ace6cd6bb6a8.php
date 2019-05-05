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
<div>
    <div class="login_bgk">
        <div class="jump_btn">
            <a class="jump_active" href="javascript:void(0);">登&nbsp;录</a><a href="<?php echo U('Reg/index');?>">注&nbsp;册</a>
        </div>
    </div>

    <div class="form-login">
        <form action="">
            <div class="nametext"><input type="text" placeholder="请输入用户名" id="username" name="username"><i></i></div>
            <div class="pwdtext"><input type="password" placeholder="请输入密码" id="password" name="password"><i></i></div>
            <div class="logintext" onclick="sub()">登&nbsp;录</div>
            <a href="<?php echo U('Login/forgetpwd');?>"><div class="forgetpwd">忘记密码</div></a>
        </form>
    </div>
</div>
<script>
    function sub(){
        var username = $("#username").val();
        var password = $("#password").val();

        if(username==""||username==null){
            layer.msg('请输入用户名');
            return false;
        }
        if(password == ""||password == null){
            layer.msg('请输入登录密码');
            return false;
        }
        $.post("<?php echo U('Login/op_login');?>",{username:username,pwd:password},function(data){
            if(data.status == 1){
                if(data.is_complete_info == 0){
                    layer.msg("登录成功，请去完善个人信息",{icon:1});
                    window.setTimeout(function(){
                        window.location="<?php echo U('FullInfo/full_info');?>";
                    },1000);
                }else{
                    layer.msg("登录成功",{icon:1});
                    window.setTimeout(function(){
                        window.location="<?php echo U('Member/index');?>";
                    },1000);
                }

            }else{
                layer.msg(data.info);
                return false;
            }
        },"json");
    }
</script>
<script src="/Public/Mobile/js/task/utils.js"></script>

</body>
</htm
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
            <div class="gwcbt_mc">忘记密码</div>
        </div>
        <div style="height: .74rem;clear: both;"></div>
    </header>
    <div class="xgmm">
        <div>
            <label for="">手机号</label>
            <input type="phone" name="phone"  placeholder="请输入手机号"/>
        </div>
        <div>
            <label for="">密码</label>
            <input type="password" name="password"  placeholder="请输入密码"/>
        </div>
        <div>
            <label for="">确认密码</label>
            <input type="password" name="repassword" placeholder="再次输入密码" />
        </div>
        <div class="yzmtext">
            <label for="">验证码</label>
            <input type="text" name="yzm"  placeholder="请输入验证码">
            <a href="javascript:void(0);" id="msgt" onclick="get_code()">获取验证码</a>
        </div>
        <div class="confirm_btn btn_update" onclick="submit()">确&nbsp;认</div>
    </div>
</div>
<script>
    function get_code(){
        var phone = $("input[name='phone']");
        if(phone == ""||phone == null){
            layer.msg('请输入手机号码');
            return false;
        }
        if(!isPoneAvailable(phone)) {
            layer.msg('手机号格式错误');
            return false;
        }
        $("#msgt").attr("data-key","off");
        var i= 60;
        var timer =setInterval(function(){
            if($("#msgt").attr("data-key")=='off'){
                $("#msgt").attr("disabled",true).css("pointer-events","none");
                $("#msgt").css('background-color','#dadada');
                i--;
                $("#msgt").text(i+"s后可重新发送");
                if(i<=0){
                    $("#msgt").removeAttr("disabled").text("重新发送验证码");
                    $("#msgt").attr("data-key","on");
                    $("#msgt").css("pointer-events","auto");
                    $("#msgt").css('background-color','#1da9e8');
                    clearInterval(timer);
                }
            }
        },1000);
        $.post("<?php echo U('Common/ajaxSandPhone');?>",
            {'phone':phone,type:2},function(d){
                if(d.status == 1){
                    layer.msg(d.info,{icon:1});
                }else{
                    layer.msg(d.info);
                }
            });
    }

    function submit(){

        var phone = $("input[name='phone']").val();
        var password = $("input[name='password']").val();
        var repwd = $("input[name='repassword']").val();
        var yzm = $("input[name='yzm']").val();
        if(phone == ""||phone == null){
            layer.msg('请输入手机号码');
            return false;
        }
        if(!isPoneAvailable(phone)) {
            layer.msg('手机号格式错误');
            return false;
        }
        if(password == ""||password == null){
            layer.msg('请输入密码');
            return false;
        }
        if(repwd == ""||repwd == null){
            layer.msg('请输入确认密码');
            return false;
        }
        var pLen = password.length;
        if(pLen < 6 || pLen > 20) {
            layer.msg('密码长度在6-20个字符之间');
            return false;
        }
        if(repwd != password) {
            layer.msg('2次输入密码不一致，请重新输入');
            return false;
        }
        $.post("<?php echo U('Login/forgetpwd_op');?>",{
            phone:phone,
            pwd:password,
            repwd:repwd,
            yzm:yzm
        },function(data){
            if(data.status==1){
                layer.msg(data.info,{icon:1});
                window.location="<?php echo U('Login/index');?>";
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
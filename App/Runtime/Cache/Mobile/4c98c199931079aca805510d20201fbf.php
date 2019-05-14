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

<body class="new_sk_bgk" >
<div  class="d_line">
    <header>
        <div class="gwc_bt" style="background-color: #7497f7;border-bottom: #7497f7 .2rem solid">
            <div class="gwcbt_fh"><a href="javascript:history.back()" class="gwcdd_fh"></a></div>
            <div class="gwcbt_mc" style="color: #ffffff;">收款详情</div>
        </div>
        <div style="height: .96rem;clear: both; " ></div>
    </header>
    <div class="is_zhezhao"></div>
    <div class="comision_item" style="display: none">
        <h2>当前可进取领取</h2>
        <p class="cont"><span>00.00</span> 佣金</p>
        <p class="qr_btn">确认提交</p>
    </div>
    <div class="big_qd_img" style="display:none ">
        <img src="<?php echo ($qd_record_info["img"]); ?>" alt="">
    </div>
    
    <div class="new_sk">

        <input type="hidden" name="id"  value="<?php echo ($qd_record_info["id"]); ?>">
        <input type="hidden" name="img" value="<?php echo ($qd_record_info["img"]); ?>">
        <div class="pay_logo">
            <?php if($qd_record_info['pay_type'] == 1): ?><img src="/Public/Mobile/images/task/yhk_zfbimg1.png" alt="">
            <?php else: ?>
                <img src="/Public/Mobile/images/task/yhk_vximg1.png" alt=""><?php endif; ?>
        </div>
        <div class="pay_img" style="height: 3.2rem;width: 3.2rem;margin-top: .9rem;">
            <img src="<?php echo ($qd_record_info["img"]); ?>" alt=""></div>
        <p class="upload_proof">
            <span>凭证上传</span>
            <span>
               <?php echo date("Y-m-d H:i:s",time()) ?>
            </span>
        </p>
        <p class="new_btn" style=" margin-top: 1.2rem;">
            <span class="new_fb_concell" onclick="sub(3)">拒绝</span>
            <span class="new_fb_confirm" onclick="sub(1)">确定</span>
        </p>
    </div>
</div>
<script>
    function sub(status) {
        var id = $("input[name='id']").val();
        var img = $("input[name='img']").val();
        if(!img){
            layer.msg("抢单者还没有上传凭证，请等待。。");
            return false;
        }

        $.post("<?php echo U('Trade/pay_sk');?>",
            {'id':id,img:img,status:status},function(d){
                if(d.status == 1){
                    $(".comision_item").show();
                    $(".comision_item").find(".cont span").text(d.data);
                    $(".is_zhezhao").attr('class',"is_zhezhao zhezhao");

                }else if(d.status == 2){
                    layer.msg(d.info,{icon:1});
                    window.setTimeout(function(){
                        window.location="<?php echo U('Index/index');?>";
                    },2000);
                } else{
                    layer.msg(d.info);
                }
            });
    }
    $(".comision_item").find(".qr_btn").click(function () {
        layer.msg("领取成功",{icon:1});
        $(".comision_item").hide();
        $(".is_zhezhao").attr('class',"is_zhezhao");
        window.setTimeout(function(){
            window.location="<?php echo U('Index/index');?>";
        },2000);
    });
    $(".pay_img").click(function () {
        var img = $("input[name='img']").val();
        console.log(img);
        if(img){
            $(".big_qd_img").show();
            $(".is_zhezhao").attr('class',"is_zhezhao zhezhao");

        }
    });
    $(".is_zhezhao").click(function () {
        $(".big_qd_img").hide();
        $(".comision_item").hide();
        $(".is_zhezhao").attr('class',"is_zhezhao");
    });
    $(".big_qd_img").click(function () {
        event.stopPropagation();
    });

</script>
<script src="/Public/Mobile/js/task/utils.js"></script>

</body>
</htm
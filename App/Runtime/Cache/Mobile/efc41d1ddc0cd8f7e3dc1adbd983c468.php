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

<body class="new_fk_bgk" >
<div  class="d_line">
    <header>
        <div class="gwc_bt" style="background-color: #7497f7;border-bottom: #7497f7 .2rem solid">
            <div class="gwcbt_fh"><a href="javascript:history.back()" class="gwcdd_fh"></a></div>
            <div class="gwcbt_mc" style="color: #ffffff;"></div>
        </div>
        <div style="height: .96rem;clear: both; " ></div>
    </header>
    <div class="new_fk">
        <input type="hidden" name="img" id="img" value="<?php echo ($qd_record_info["img"]); ?>">
        <input type="hidden" name="id" value="<?php echo ($qd_record_info["id"]); ?>">

        <div class="pay_logo wx_logo" data-show_logo="2">
            <p>微信二维码</p>
            <em class="left_roll"></em>
            <img src="<?php echo ($sk_info["wechat_logo"]); ?>" alt="暂无logo">
            <em class="right_roll"></em>
        </div>
        <div class="pay_logo zfb_logo" style="display: none" data-show_logo="1">
            <p>支付宝二维码</p>
            <em class="left_roll"></em>
            <img src="<?php echo ($sk_info["alipay_logo"]); ?>"  alt="暂无logo">
            <em class="right_roll"></em>
        </div>
        <div class="upload_bgk scpz">
            <a href="javascript:;" class="upload-input">
                <img src="<?php echo ($qd_record_info["img"]); ?>" id="upload_img" alt="">
                <input type="file" data-url="<?php echo U('Upload/upload');?>" name="" id="" class="file_input">
            </a>
            <p>扫&nbsp;码</p>
        </div>
        <p class="upload_proof">
            <span>确认收取</span>
            <span>
               <?php echo date("Y-m-d H:i:s",time()) ?>
            </span>
        </p>
        <p  class="new_btn" style=" margin-top: 1.7rem;">
            <span class="new_fb_concell" onclick="history.go(-1)">取消</span>
            <span class="new_fb_confirm" onclick="sub()">确定</span>
        </p>
    </div>
</div>
<script src="/Public/Mobile/js/task/lrz.min.js"></script>

<script>
    function sub() {
        var id = $("input[name=id]").val();
        var img = $("input[name=img]").val();
        if(!img){
            layer.msg("请上传凭证。。");
            return false;
        }

        $.post("<?php echo U('Trade/pay_fk');?>",
            {'id':id,img:img},function(d){
                if(d.status == 1){
                    layer.msg(d.info,{icon:1});
                    window.setTimeout(function(){
                        window.location="<?php echo U('Index/index');?>";
                    },2000);
                }else{
                    layer.msg(d.info);
                }
            });
    }

    $(function () {
        var f = document.querySelector('.file_input');
        imgs(f);
    });
    function imgs(id) {
        id.onchange = function (e) {
            var _self = $(e.target);
            var files = e.target.files;

            if(files[0]['size'] > 2*1024*1024){
                layer_error('上传图片超过2m');
                return false;
            }
            var url = _self.data('url');
            console.log(url);
            lrz(this.files[0], {width: 600, fieldName: "file"}).then(function (rst) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', url);
                xhr.onload = function () {
                    var b = xhr.responseText;
                    var c = eval('(' + b + ')');
                    console.log(c);
                    if (xhr.status === 200 && c.error == 0) {
                        layer.msg( '上传成功',{icon:1});
                        var obj = eval('(' + xhr.responseText + ')');

                        $("#upload_img").attr('src', c.url);
                        $("#img").val(c.url);
                    } else {
                        layer.msg('上传图片格式不对');
                    }
                };
                xhr.onerror = function () {
                    layer.msg('有异常重新上传');
                };
                xhr.upload.onprogress = function (e) {
                    // 上传进度
                    var percentComplete = ((e.loaded / e.total) || 0) * 100;
                }
                // 添加参数
                rst.formData.append('size', rst.fileLen);
                rst.formData.append('base64', rst.base64);

                // 触发上传
                xhr.send(rst.formData);
                return rst;
            })
                .catch(function (err) {
                    layer.msg(err);
                })
                // 不管是成功失败，这里都会执行
                .always(function () {

                });
        }
    }

    $(".new_fk").find('em').click(function () {
        var show_logo = $(this).closest('div').data('show_logo');
        if(show_logo == '2'){
            $(".wx_logo").hide();
            $(".zfb_logo").show();
        }else{
            $(".wx_logo").show();
            $(".zfb_logo").hide();
        }
    });



</script>
<script src="/Public/Mobile/js/task/utils.js"></script>

</body>
</htm
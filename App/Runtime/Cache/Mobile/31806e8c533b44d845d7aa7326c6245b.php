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
    <script src="/Public/Mobile/js/task/common.js?randomId=<?php echo ($random_math); ?>"></script>
</head>

<body>
<div class="jbzl">
    <header>
        <div class="gwc_bt">
            <div class="gwcbt_fh"><a href="javascript:history.back()" class="gwcdd_fh"></a></div>
            <div class="gwcbt_mc">账户信息</div>
        </div>
        <div style="height: .74rem;clear: both;"></div>
    </header>
    <div class="wxlogo">
        <p></p>
        <div class="show_logo" style="padding-top: .3rem;"><img id="upload_img" src="<?php echo ($logo); ?>" alt=""></div>
        <input type="hidden"  name="logo" class="logo"  value="<?php echo ($logo); ?>">
        <input type="hidden"  name="type" value="<?php echo ($type); ?>">
        <div class="upload_logo">
            <div>
                <a href="javascript:;" class="upload-input">
                    <input type="file" name=""  data-url="<?php echo U('Upload/upload');?>" id="" class="file_input">
                </a>
                <p>上传二维码</p>
            </div>
        </div>
        <div class="confirm_btn" onclick="sub()">保&nbsp;存</div>
    </div>
</div>
<script src="/Public/Mobile/js/task/lrz.min.js"></script>
<script>
$(function () {
    var f = document.querySelector('.file_input');
    imgs(f);
});
function imgs(id) {

    id.onchange = function (e) {
        var _self = $(e.target);
        var files = e.target.files;

        if(files[0]['size'] > 2*1024*1024){
            layer.msg('上传图片超过2m');
            return false;
        }
        var url = _self.data('url');
        lrz(this.files[0], {width: 600, fieldName: "file"}).then(function (rst) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', url);
            xhr.onload = function () {
                var b = xhr.responseText;
                var c = eval('(' + b + ')');
                if (xhr.status === 200 && c.error == '0') {
                    layer.msg('上传成功',{icon:1});
                    var obj = eval('(' + xhr.responseText + ')');
                    $("#upload_img").attr('src', obj.url);
                    $(".logo").val(obj.url);
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

function sub() {
    var logo = $("input[name='logo']").val();
    var type = $("input[name='type']").val();


    if(!logo){
        layer.msg("请上传logo");
        return false;
    }

    $.post("<?php echo U('Member/upload_qrcode_op');?>",{logo:logo,type:type},function(data){
        if(data.status == 1){
            layer.msg(data.info,{icon:1});
            window.setTimeout(function(){
                window.location="<?php echo U('Member/accountinfo');?>";
            },2000);
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
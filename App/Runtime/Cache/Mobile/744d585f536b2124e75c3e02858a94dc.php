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

<body >

<div class="zfyj">
    <header>
        <div class="gwc_bt">
            <div class="gwcbt_fh"><a href="javascript:history.back()" class="gwcdd_fh"></a></div>
            <div class="gwcbt_mc">支付押金/打款</div>
        </div>
        <div style="height: .74rem;clear: both;"></div>
    </header>
    <div class="zfyj_item" style="padding-bottom: .5rem">
        <form action="">
            <input type="hidden" name="pay_money" value="200">
            <input type="hidden"  id="img" name="img" value="">
            <input type="hidden"  id="pay_type" name="pay_type" value="1">
            <input type="hidden"  id="id" name="id" value="<?php echo ($id); ?>">
            <div class="fk_qrcode">
                <div class="zfb_qrcode">
                    <span><?php if($zfb_qrcode): ?>支付宝<?php endif; ?></span>
                    <img src="<?php echo ($zfb_qrcode); ?>" alt="">
                </div>
                <div class="wx_qrcode">
                    <span><?php if($wx_qrcode): ?>微信<?php endif; ?></span>
                    <img src="<?php echo ($wx_qrcode); ?>" alt="">
                </div>
            </div>
            <div class="je_text">
                <h2>金额</h2>
                <div class="je_num">
                    <span data-money="200" class="active">200元</span>
                    <span data-money="500">500元</span>
                    <span data-money="1000">1000元</span>
                    <span data-money="1500">1500元</span>
                </div>
            </div>
            <div class="je_text zffs">
                <h2>支付方式</h2>
                <p><img src="/Public/Mobile/images/task/yhk_zfbimg1.png" alt="">
                    <!--<span>支付宝支付</span>-->
                    <em data-pay_type="1" class="gou_active"></em></p>
                <p><img src="/Public/Mobile/images/task/yhk_vximg1.png" alt="">
                    <!--<span>微信支付</span>-->
                    <em data-pay_type="2"  class="gou_active"></em></p>
            </div>
            <div class="je_text scpz">
                <h2>上传凭证</h2>
                <div>
                    <a href="javascript:;" class="upload-input">
                        <input type="file" data-url="<?php echo U('Upload/upload');?>" name="" id="" class="file_input">
                    </a>
                    <img src="" id="upload_img" alt="">
                </div>
            </div>
            <div class="confirm_btn" onclick="sub()">立即上传</div>
        </form>
    </div>
</div>
<script src="/Public/Mobile/js/task/lrz.min.js"></script>
<script>
//    $(".je_text").find(".je_num span").click(function () {
//        $(this).siblings('span').attr('class','');
//        $(this).attr('class','active');
//        var money = $(this).data('money');
//        $("input[name='pay_money']").val(money);
//    });
    $(".zffs").find("p em").click(function () {
        $(this).parent('p').siblings('p').find('em').attr('class','');
        $(this).attr('class','gou_active');
        var pay_type = $(this).data('pay_type');
        $("input[name='pay_type']").val(pay_type);
    });

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
    function sub() {
        var img = $("input[name='img']").val();
        var pay_money = $("input[name='pay_money']").val();
        var pay_type = $("input[name='pay_type']").val();
        var id = $("input[name='id']").val();

        if(!img){
            layer.msg("请上传凭证");
            return false;
        }
        $.post("<?php echo U('FullInfo/pay_deposit');?>",{id:id ,img:img,pay_money:pay_money,pay_type:pay_type},function(data){
            if(data.status == 1){
                layer.msg(data.info,{icon:1});
                window.setTimeout(function(){
                    window.location="<?php echo U('Index/index');?>";
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
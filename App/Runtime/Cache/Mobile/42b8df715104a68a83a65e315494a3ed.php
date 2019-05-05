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
    <header>
        <div class="gwc_bt">
            <div class="gwcbt_fh"></div>
            <div class="gwcbt_mc">任务汇</div>
        </div>
        <div style="height: .74rem;clear: both;"></div>
    </header>
    <div class="main_page">
        <img src="/Public/Mobile/images/task/login_bgk.png" alt="">
    </div>
    <input type="hidden" name="member_id" value="<?php echo ($member_id); ?>">
    <input type="hidden" name="username" value="<?php echo ($username); ?>">
    <input type="hidden" name="head" value="<?php echo ($head); ?>">
    <input type="hidden" name="type" value="<?php echo ($type); ?>">
    <!--zhezhao-->
    <div class="is_zhezhao"></div>
    <div class="qd_tips" style="display:none;">
        <div>
            <h2>匹配用户结果</h2>
            <div class="qd_user">
                <img class="mem_head" src="/Public/Mobile/images/task/gwc_spimg.jpg" alt="">
                <p><span class="mem_username">雪中行者</span><br>
                   <span class="mem_phone">151****5825</span>
                </p>
            </div>
            <div class="qd_tips_btn">
                <span class="qd_tips_confirm" data-op_type="">确定</span>
            </div>
        </div>
    </div>
    <div class="wyqd">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="wyqd_item index_page">
                <div class="jltext">
                    <a href="javascript:;">
                        <img src="<?php echo ($vo["logo_img"]); ?>" alt="">
                        <div class="jltext_top">
                            <span class="jl_title"><?php echo ($vo["title"]); ?></span>
                            <span class="jl_time"><?php echo ($vo["reward"]); ?></span>
                        </div>
                        <div class="jltext_top" style=" font-size:.24rem ;color: #dadada;">
                            <span  class="jl_title"><?php echo (date("Y-m-d",$vo["create_time"])); ?></span>
                            <span  class="jl_time qd" onclick="qiandan(<?php echo ($vo["id"]); ?>,1)">抢单</span>
                            <span  class="jl_time fk" onclick="qiandan(<?php echo ($vo["id"]); ?>,2)">收款</span>
                        </div>
                    </a>
                </div>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>

<div style="height: .9rem;"></div>
<div class="footer_main">
    <ul>
        <li class="footer_sy">
            <a href="javascript:void(0);">
                首页
            </a>

            <i class="footer_active"></i>
        </li>
        <li class="footer_fl">
            <a href="<?php echo U('Task/index');?>">
                任务
            </a>
        </li>
        <li class="footer_tg">
            <a href="<?php echo U('Promote/index');?>">
                推广
            </a>
        </li>
        <li class="footer_hyzx">
            <a href="<?php echo U('Member/index');?>">
                个人中心
            </a>
        </li>
    </ul>
</div>

<script>
    function qiandan(id,type){
        $.post("<?php echo U('Index/qiandan');?>",{task_id:id,type:type},function(data){
            console.log(data);
            /*匹配成功*/
            if(data['status'] == 1){
                $(".qd_user").find('.mem_head').attr('src',data['data']['head']);
                $(".qd_user").find('.mem_username').html(data['data']['username']);
                $(".qd_user").find('.mem_phone').html(data['data']['phone']);
                $(".qd_user").find('.qd_tips_confirm').attr("data-op_type",data['data']['op_type']);
                $(".qd_tips").show();
                $(".is_zhezhao").attr('class',"is_zhezhao zhezhao");

            }else if(data['status'] == 2){  /*未匹配成功*/
                layer.msg("正在匹配中，请等待...");
            }else if(data['status'] ==3){  /*先登录*/
                layer.msg("请先去登录...");
                window.location="<?php echo U('Login/index');?>";
            }
            else{
                layer.msg(data['info']);
            }
        })
    }
    /*取消按钮*/
    $(".qd_tips_confirm").find('.qd_tips_concel').click(function () {
        $(".qd_tips").hide();
        $(".is_zhezhao").attr('class',"is_zhezhao");
        var op_type = $(this).data('op_type');
        if(op_type == 1){
            layer.msg("请及时上传付款凭证。。",{icon:1});
            window.location="<?php echo U('Trade/qd_record');?>";
        }else if(op_type == 2){
            layer.msg("请及时确认收款。。",{icon:1});
            window.location="<?php echo U('Trade/sk_record');?>";
        }
    });
    var member_id = $("input[name='member_id']").val();
    /*匹配付款/执行者*/
    if(member_id > 0){
        var phone = $("input[name='phone']").val();
        var username = $("input[name='username']").val();
        var head = $("input[name='head']").val();
        var type = $("input[name='type']").val();

        if(type == 1){ /*匹配到收款者*/
            $(".qd_user").find('.mem_head').attr('src',head);
            $(".qd_user").find('.mem_username').html(username);
            $(".qd_user").find('.mem_phone').html(phone);
            $(".qd_user").find('.qd_tips_confirm').attr("data-op_type",2);
            $(".qd_tips").show();
            $(".is_zhezhao").attr('class',"is_zhezhao zhezhao");
            $(".qd_tips").find('h2').text("已匹配到收款者");
        }else if(type == 2){  /*匹配到抢单者*/
            $(".qd_user").find('.mem_head').attr('src',head);
            $(".qd_user").find('.mem_username').html(username);
            $(".qd_user").find('.mem_phone').html(phone);
            $(".qd_user").find('.qd_tips_confirm').attr("data-op_type",2);
            $(".qd_tips").show();
            $(".is_zhezhao").attr('class',"is_zhezhao zhezhao");
            $(".qd_tips").find('h2').text("已匹配到执行者");
        }
    }
</script>


<script src="/Public/Mobile/js/task/utils.js"></script>

</body>
</htm
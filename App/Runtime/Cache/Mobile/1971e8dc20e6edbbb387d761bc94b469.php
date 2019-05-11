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
<div  class="d_line">
    <header>
        <div class="gwc_bt">
            <div class="gwcbt_fh"><a href="javascript:history.back()" class="gwcdd_fh"></a></div>
            <div class="gwcbt_mc">抢单记录</div>
        </div>
        <div style="height: .94rem;clear: both;"></div>
    </header>
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
    <?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="wyqd">
            <div class="wyqd_item">
                <div class="jltext">
                    <a href="javascript:;">
                        <?php if($vo['task_id'] == 0): ?><img src="/Public/Mobile/images/task/gwc_spimg.jpg" alt="">
                        <?php else: ?>
                            <img src="<?php echo ($vo["task_img"]); ?>" alt=""><?php endif; ?>
                        <div class="jltext_top">
                            <span class="jl_title">
                                <?php if($vo['title']): echo ($vo["title"]); ?>
                                <?php else: ?>
                                    系统匹配<?php endif; ?>
                            </span>
                            <span class="jl_time"><?php echo ($vo["reward"]); ?></span>
                        </div>
                        <div class="jltext_top" style=" font-size:.24rem ;color: #dadada;">
                            <span  class="jl_title"><?php echo (date("Y-m-d",$vo["create_time"])); ?></span>
                            <span  class="jl_time">
                                <?php if($vo['status'] == 1 or $vo['status'] == 2): ?>已支付
                                <?php else: ?>
                                    未支付<?php endif; ?>
                            </span>
                        </div>
                    </a>
                </div>
                <div class="jlsh">
                    <a href="<?php echo U('Trade/fk_detail',array('id'=>$vo['id']));?>"><span>
                          查看详情
                    </span></a><em>|</em>
                    <!--<a href=""><span>已发放奖励</span></a><em>|</em>-->
                    <?php if($vo['status'] == 1 or $vo['status'] == 2): ?><a href="javascript:;" onclick="re_buy(<?php echo ($vo['id']); ?>,1)"><span>复购</span></a><em></em>
                    <?php else: ?>
                        <a href="<?php echo U('FullInfo/pay_deposit',array('id'=>$vo['id']));?>"><span>请去支付</span></a><em></em><?php endif; ?>
                </div>
            </div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
<script>
    function re_buy(id,type){
        $.post("<?php echo U('Trade/re_buy');?>",{id:id,type:type},function(data){
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
                layer.msg("复购成功，正在匹配中...");
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
</script>

<script src="/Public/Mobile/js/task/utils.js"></script>

</body>
</htm
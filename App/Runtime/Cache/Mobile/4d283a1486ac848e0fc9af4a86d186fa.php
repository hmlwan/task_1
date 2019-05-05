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
            <div class="gwcbt_mc">收款记录</div>
        </div>
        <div style="height: .94rem;clear: both;"></div>
    </header>

    <?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="wyqd">
            <div class="wyqd_item">
                <div class="jltext">
                    <a href="">
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
                                <?php if($vo['status'] == 1 or $vo['status'] == 2): ?>已收款
                                <?php else: ?>
                                    未收款<?php endif; ?>
                            </span>
                        </div>
                    </a>
                </div>
                <div class="jlsh">
                    <a href="<?php echo U('Trade/sk_detail',array('id'=>$vo['id'],'type'=>1));?>"><span>
                          查看详情
                    </span></a><em>|</em>
                    <!--<a href=""><span>已发放奖励</span></a><em>|</em>-->
                    <?php if($vo['status'] == 1): ?><a href="javascript:;"><span>复购</span></a><em></em>
                    <?php else: ?>
                        <a href="javascript:;" onclick="submit(<?php echo ($vo["id,1"]); ?>)"><span>确认收款</span></a><em></em><?php endif; ?>
                </div>
            </div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
<script>
    function submit(id,status) {
        $.post("<?php echo U('Trade/confirm_sk');?>",
            {'id':id,status:status},function(d){
                if(d.status == 1){
                    layer.msg(d.info,{icon:1});
                }else{
                    layer.msg(d.info);
                }
                window.setTimeout(function(){
                    window.location="<?php echo U('Trade/sk_record');?>";
                },1000);
            });
    }

</script>

<script src="/Public/Mobile/js/task/utils.js"></script>

</body>
</htm
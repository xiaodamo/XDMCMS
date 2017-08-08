<html><!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8" />
    <title><?php echo $webname?></title>
    <meta name="description" content="<?php echo $webname?>，主要写一些IT相关文章，发布Web与测试教程，记录个人笔记，上传一些实用的网络工具与网站模版。" />
    <meta name="keywords" content="达摩,小达摩,个人博客,php,web,测试,资源下载" />
    <!--移动适配Meta声明-->
    <meta name="applicable-device" content="pc" />
    <link  href="<?php echo ASSETS?>web/css/base.css" rel="stylesheet" />
    <link  href="<?php echo ASSETS?>web/css/index.css" rel="stylesheet" />
    <!--[if lt IE 9]>
    <script src="<?php echo ASSETS?>web/js/modernizr.js"></script>
    <![endif]-->
    <!-- logo-->
    <link rel="shortcut icon" href="favicon.ico"  />
</head>
<body>
<header><div class="logo shake shake-slow"><a href="/" ></a><div class="year2016 "></div></div>
    <nav class="topnav" id="topnav">
        <?php foreach ($category as $k=>$v):?>
        <a href="/<?php echo $v['enname']?>"  <?php if(!$k) echo 'id="topnav_current"';?>><span><?php echo $v['text']?></span><span <?php if(!$k) echo 'id="current"';?> class="en shake shake-slow"><?php echo ucfirst($v['enname'])?></span></a>
        <?php endforeach;?>
    </nav>
</header>
<div class="banner">
    <section class="box">
        <ul class="texts">
            <p>早已习惯了生活在自己的世界里</p>
            <p>斗转星移</p>
            <p>乜汻 還恠噫</p>
        </ul>
        <div class="iavatar shake shake-slow"><a href="/aboutme" ><span>小达摩</span></a> </div>
    </section>
</div>
<div class="template">
    <div class="box">
        <h3 class="t"><p><span>热门资源</span>下载 Download</p><div class="gg">
                <marquee scrollamount="5" direction="left" onMouseOver="this.stop()" onMouseOut="this.start()">
                    <span id="toolinfo" >
                    <?php echo htmlspecialchars_decode($notification['content']);?>
                    </span>
                </marquee></div></h3>
        <div class="clear"></div>
        <ul>
            <?php foreach ($hot_download as $hot_down):?>
            <li><a href="/article/detail/<?php echo $hot_down['id']?>" title="<?php echo $hot_down['title']?>" class="shake shake-little"><img src="<?php echo UPLOADS.$hot_down['img_url']?>" alt="<?php echo $hot_down['title']?>" /></a><span><?php echo $hot_down['title']?></span></li>
            <?php endforeach;?>
        </ul>
    </div>
</div>
<article>
    <h2 class="title_tj"><p>最新<span>文章</span></p></h2>
    <div class="bloglist left">
        <?php foreach ($news as $k=>$v):?>
        <h3 class="red"><a href="/article/detail/<?php echo $v['id']?>" ><?php echo $v['title']?></a><?php if(!$k):?><span class="hot"></span><?php endif;?></h3>
        <figure><a href="/article/detail/<?php echo $v['id']?>" title="<?php echo $v['title']?>"><img src="<?php echo $v['img_url']?UPLOADS.$v['img_url']:ASSETS.'web/images/default.jpg'?>" alt="<?php echo $v['title']?>" /></a></figure>
        <ul>
            <p><?php echo char_limit4($v['content'],130)?></p>
            <a title="<?php echo $v['title']?>" href="/article/detail/<?php echo $v['id']?>" class="readmore">阅读全文>></a>
        </ul>
        <p class="dateview"><span><?php echo date("Y-m-d",$v['created_at'])?></span><span>作者：<?php echo $v['author']?></span><span>分类：[<a href="/<?php echo $v['enname']?>" ><?php echo $v['name']?></a>]</span><span>浏览：<?php echo $v['click_nums']?>次</span></p>
        <?php endforeach;?>

    </div>
    <aside class="right">
        <div class="blank"></div>
        <div class="social-icons">
            <ul>
                <li><a class="weibo" title="新浪微博" href="javascript:void(0)"  target="blank"></a></li>
                <li><a class="weixin"  ><i class="weixins fa fa-weixin"></i><div class="weixin-popover"><div class="popover bottom in"><div class="arrow"></div><div class="popover-content"><img src="<?php echo ASSETS?>web/images/weixin.jpg" /></div></div></div></a></li>
                <li><a class="qq" title="QQ" href="http://wpa.qq.com/msgrd?V=3&uin=281530997&Site=www.xiaodamo.com&Menu=yes" target="blank"></a></li>
                <li><a class="blog" title="新浪博客" href="javascript:void(0)" target="blank"></a></li>
            </ul>
        </div>
        <div class="blank"></div>
        <div class="sale">
            <figure>
                <p></p>
            </figure>
        </div>
        <div class="blank"></div>
        <div class="rnavb">
            <h2>学习导航</h2>
            <ul>
                <?php foreach ($category1 as $cat1):?>
                <li><a href="/<?php echo $cat1['enname']?>" ><?php echo $cat1['text']?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="blank"></div>
        <div class="rnavj">
            <h2>生活导航</h2>
            <ul>

                <?php foreach ($category2 as $cat2):?>
                    <li><a href="/<?php echo $cat2['enname']?>" ><?php echo $cat2['text']?></a></li>
                <?php endforeach;?>

            </ul>
        </div>
        <div class="blank"></div>
        <div class="rnav">
            <h2>热门标签</h2>
            <ul>

                <?php foreach ($tags as $tag):?>
                    <li><a href="/tags/index/<?php echo $tag['id']?>" ><?php echo $tag['name']?></a></li>
                <?php endforeach;?>

            </ul>
        </div>
        <div class="blank"></div>
        <div class="news">
            <h3 class="syss">
                <p><a href="javascript:void(0)">碎言<span>碎语</span></a></p>
            </h3>
            <ul class="sysy">
                <li><?php echo $xinqing[0]['title']?$xinqing[0]['title']:'无'?></li>
            </ul>
            <h3>
                <p>文章<span>推荐</span></p>
            </h3>
            <ul class="rank">
                <?php foreach ($recommand as $v):?>
                <li><a href="/article/detail/<?php echo $v['id']?>" title="<?php echo $v['title']?>"><?php echo $v['title']?></a></li>
                <?php endforeach;?>
            </ul>
            <h3 class="ph">
                <p>点击<span>排行</span></p>
            </h3>
            <ul class="paih">
                <?php foreach ($hotviews as $v):?>
                    <li><a href="/article/detail/<?php echo $v['id']?>" title="<?php echo $v['title']?>"><?php echo $v['title']?></a></li>
                <?php endforeach;?>
            </ul>
            <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tieba" data-cmd="tieba" title="分享到百度贴吧"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a></div>
            <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"32"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
            <h3 class="links">
                <p>友情<span>链接</span></p>
            </h3>
            <ul class="website">
                <?php foreach ($friends as $v):?>
                <li><a href="<?php echo $v['link']?>" target='_blank'><?php echo $v['name']?></a> </li>
                <?php endforeach;?>
            </ul>
        </div>
    </aside>
</article>
<footer>
    <?php $this->load->view('_footer');?>
</footer>
</body>
</html>

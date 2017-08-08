<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo $article['title']?></title>
    <meta name="keywords" content="<?php echo $artinfo['text']?>" />
    <meta name="description" content="<?php echo char_limit4($article['content'],80)?>" />
    <!--移动适配Meta声明-->
    <meta name="applicable-device" content="pc" />
    <link  href="<?php echo ASSETS?>web/css/base.css" rel="stylesheet" />
    <link  href="<?php echo ASSETS?>web/css/new.css" rel="stylesheet" />
    <link  href="<?php echo ASSETS?>web/css/style.css" rel="stylesheet" />
    <!-- 禁止转码-->
    <meta http-equiv="Cache-Control" content="no-transform" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!-- 文章原创og声明-->
    <meta property="og:type" content="article"/>
    <meta property="og:image" content="/uploads/allimg/160910/1-1609101643090-L.jpg "/>
    <meta property="og:release_date" content="2016-09-10"/>
    <meta property="og:title" content="<?php echo $article['title']?>"/>
    <meta property="og:description" content="<?php echo char_limit4($article['content'],80)?>"/>
    <meta property="article:published_time" content="<?php echo date("Y-m-d",$article['created_at'])?>" />
    <meta property="article:author" content="<?php echo $article['author']?>" />
    <meta property="article:published_first" content="<?php echo $article['author']?>,<?php echo site_url('article/detail/'.$article['id'])?>" />
    <!--[if lt IE 9]>
    <script src="<?php echo ASSETS?>web/js/modernizr.js"></script>
    <![endif]-->
    <!-- 返回顶部调用 begin -->
    <link  href="<?php echo ASSETS?>web/css/lrtk.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo ASSETS?>web/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo ASSETS?>web/js/js.js"></script>
    <!-- 返回顶部调用 end-->
</head>
<body>
<header> <div class="logo shake shake-slow"><a href="/"></a><div class="year2016 "></div></div>
    <nav class="topnav" id="topnav">
        <?php foreach ($category as $k=>$v):?>
            <?php if(!$v['is_display'] || $v['parent_id']) continue;?>
            <a href="/<?php echo $v['enname']?>"  <?php if($artinfo['id']==$v['id']) echo 'id="topnav_current"';?>><span><?php echo $v['text']?></span><span <?php if($artinfo['id']==$v['id']) echo 'id="current"';?> class="en shake shake-slow"><?php echo ucfirst($v['enname'])?></span></a>
        <?php endforeach;?>
    </nav>
</header>
<article class="blogs">
    <h1 class="t_nav"><span>小达摩笔记，记录生活点滴，总有一些美好的事物值得回忆。</span><a href="/" class="n1">网站首页</a><a href="/<?php echo $artinfo['enname']?>/" class="n2"><?php echo $artinfo['text']?></a></h1>
    <div class="index_about">
        <h2 class="c_titile"><?php echo $article['title']?></h2>
        <p class="box_c"><span class="d_time">发布时间：<?php echo date("Y-m-d",$article['created_at'])?></span><span>编辑：<a href='mailto:wzy2080@163.com'><?php echo $article['author']?></a></span><span>阅读：(<span id="countnum"><?php echo $article['click_nums']?></span>)</span><span>字号： <a href='javascript:FontZoom(15.5)'>大</a> <a href='javascript:FontZoom(14.5)'>中</a> <a href='javascript:FontZoom(13.5)'>小</a></span></p>
        <ul class="infos" id="content">
            <?php echo $article['content']?>
        </ul>
        <!-- //顶踩 -->
        <div class="single-heart">
            <div class="loading-line"></div>
        </div>
        <!-- //顶踩部份的源码结束 -->
        <div class="reward-wrap">
            <p class="text">
                如果您觉得文章对你有帮助，可以进行打赏。<br>
                打赏多少，您高兴就行，谢谢您对小达摩的支持！ ~(@^_^@)~
            </p>
            <div class="img-box-wrap clearfix">
                <p class="img-box fl">
                    微信扫一扫
                    <img class="wechat" src="<?php echo ASSETS?>web/images/weixin_ds.png" width="160" height="162" alt="微信打赏">
                </p>
                <p class="img-box fr">
                    支付宝扫一扫
                    <img class="alipay fl" src="<?php echo ASSETS?>web/images/alipay_ds.png" width="160" height="162" alt="支付宝打赏">
                </p>
            </div>
        </div>
        <div class="keybq clearfix">
            <p><span>关键字词</span>：
                <?php foreach ($mytag as $v):?>
                <a href="/tags/index/<?php echo $v['id']?>" target="_blank"><?php echo $v['name']?></a>
                <?php endforeach;?>
            </p>
            <div class="bds">
                <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tieba" data-cmd="tieba" title="分享到百度贴吧"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a></div>
                <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"16"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
            </div>
        </div>
        <div class="authorbio clearfix">
            <img alt="小达摩" src="<?php echo ASSETS?>web/images/photos2.jpg"  class="avatar"/>
            <ul class="spostinfo">
                <li>
                    <strong>转载请注明：</strong>
                    <a href="<?php echo site_url('article/detail/'.$article['id'])?>" rel="bookmark" title="本文固定链接 <?php echo site_url('article/detail/'.$article['id'])?>"><?php echo $article['title']?></a>
                </li>
                <li>
                    <strong>版权声明：</strong>
                    本站原创文章，由
                    <a href="/" title="由<?php echo $article['author']?>发布" target="_blank" rel="author"><?php echo $article['author']?></a>
                    发表在
                    <a href="/<?php echo $artinfo['ename']?>" rel="category tag"><?php echo $artinfo['text']?></a>
                    分类下，于<?php echo date("Y-m-d",$article['updated_at'])?>最后更新
                </li>
            </ul>
        </div>
        <div class="nextinfo clearfix">
            <p class="pre">上一篇：<?php echo $alongs['pre']?> </p>
            <p class="next">下一篇：<?php echo $alongs['next']?> </p>
        </div>
        <div class="otherlink">
            <h2>相关文章</h2>
            <ul>

            </ul>
        </div>
        <div class="blank"></div>
    </div>
    <aside class="right">
        <div class="container">
            <div id="search">
                <form id="form1" name="form1" method="get"  action="/article/search">
                    <input type="text" name="q" />
                    <input class="button" type="submit" value="Search" />
                </form>
            </div>
        </div>
        <div id="rnav_box">
            <div id="rnav">
                <ul>
                    <?php foreach ($category1 as $k=>$cat):?>
                        <li class="rnav<?php echo $k?>"><a href="/<?php echo $cat['enname']?>"><?php echo $cat['text']?></a></li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <div class="blank"></div>
        <div class="sale">
            <figure>
                <p></p>
            </figure>
        </div>
        <div class="blank"></div>
        <div class="news">
            <h3>
                <p>文章<span>推荐</span></p>
            </h3>
            <ul class="rank">
                <?php foreach ($recommand as $v):?>
                    <li><a href="/article/detail/<?php echo $v['id']?>" title="<?php echo $v['title']?>"><?php echo $v['title']?></a></li>
                <?php endforeach;?>

            </ul>
            <h3 class="ph">
                <p>文章<span>热点</span></p>
            </h3>
            <ul class="paih">
                <?php foreach ($hotviews as $v):?>
                    <li><a href="/article/detail/<?php echo $v['id']?>" title="<?php echo $v['title']?>"><?php echo $v['title']?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="anews">
            <h3>
                <p>热门<span>标签</span></p>
            </h3>
            <ul>

                <?php foreach ($tags as $tag):?>
                    <a class="btn" href="/tags/index/<?php echo $tag['id']?>" target="_blank"><?php echo $tag['name']?></a>
                <?php endforeach;?>

            </ul>
            <div class="blank"></div>
        </div>
        <div class="blank"></div>
        <!--<div class="visitors">
              <h3><p>最近访客</p></h3>
        <ul class="ds-recent-visitors"  data-num-items="30"></ul>
        <script type="text/javascript">
        var duoshuoQuery = {short_name:"xiaodamo"};
        (function() {
            var ds = document.createElement('script');
            ds.type = 'text/javascript';ds.async = true;
            ds.src = 'http://static.duoshuo.com/embed.js';
            ds.charset = 'utf-8';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ds);
        })();
        </script>
            </div>-->
    </aside>
</article>
<div class="prevs"><?php echo $alongs['pre']?> </div>
<div class="nexts"><?php echo $alongs['next']?> </div>
<div id="tbox">
    <a id="ewm" href="" title="二维码" >
        <div class="qr-img">
            <div class="ico"></div>
            <div id="output"></div>
            <div id="msg">手机扫一扫</div>
        </div>
        <script type="text/javascript">
            function utf16to8(str) {
                var out, i, len, c;
                out = "";
                len = str.length;
                for (i = 0; i < len; i++) {
                    c = str.charCodeAt(i);
                    if ((c >= 0x0001) && (c <= 0x007F)) {
                        out += str.charAt(i);
                    } else if (c > 0x07FF) {
                        out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
                        out += String.fromCharCode(0x80 | ((c >> 6) & 0x3F));
                        out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
                    } else {
                        out += String.fromCharCode(0xC0 | ((c >> 6) & 0x1F));
                        out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
                    }
                }
                return out;
            }
            if(!+[1,]){ /* 新增判断：如果是IE浏览器，则使用table兼容方式 */
                Render = "table";
            } else {
                Render = "canvas";
            }
            $('#output').qrcode({width:150,height:150,render:Render,correctLevel:0,text:window.location.href});
        </script>
    </a>
    <a id="togbook" href="/contact" title="给小达摩留言"></a> <a id="gotop" href="javascript:void(0)" title="回到顶部"></a> </div>
<footer>
    <?php $this->load->view('_footer');?>
</footer>
</body>
</html>
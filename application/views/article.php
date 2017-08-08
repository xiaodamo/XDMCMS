
<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo $webname?></title>
    <meta name="description" content="<?php echo $webname?>，主要写一些IT相关文章，发布Web与测试教程，记录个人笔记，上传一些实用的网络工具与网站模版。" />
    <meta name="keywords" content="达摩,小达摩,个人博客,php,web,测试,资源下载" />
    <!--移动适配Meta声明-->
    <meta name="applicable-device" content="pc">
    <link  href="<?php echo ASSETS?>web/css/base.css" rel="stylesheet">
    <link  href="<?php echo ASSETS?>web/css/style.css" rel="stylesheet">
    <!-- 禁止转码-->
    <meta http-equiv="Cache-Control" content="no-transform" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!--[if lt IE 9]>
    <script src="<?php echo ASSETS?>web/js/uaredirect.js"></script>
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
            <a href="/<?php echo $v['enname']?>"  <?php if($current==$v['enname']) echo 'id="topnav_current"';?>><span><?php echo $v['text']?></span><span <?php if($current==$v['enname']) echo 'id="current"';?> class="en shake shake-slow"><?php echo ucfirst($v['enname'])?></span></a>
        <?php endforeach;?>
    </nav>
</header>
<article class="blogs">
    <h1 class="t_nav"><span>小达摩笔记，记录生活点滴，总有一些美好的事物值得回忆。</span><a href="/" class="n1">网站首页</a><a href="/<?php echo $artinfo['enname']?>/" class="n2"><?php echo $artinfo['text']?></a></h1>
    <div class="newblog left">
        <?php foreach ($articles as $art):?>
        <h2><a title="<?php echo $art['title']?>" href="/article/detail/<?php echo $art['id']?>"><?php echo $art['title']?></a></h2>
        <p class="dateview"><span>发布时间：<?php echo date("Y-m-d",$art['updated_at'])?></span><span>作者：<?php echo $art['author']?></span><span>分类：[<a href="/<?php echo $art['enname']?>" ><?php echo $art['name']?></a>]</span><span>浏览：<?php echo $art['click_nums']?>次</span></p>
        <figure><a title="<?php echo $art['title']?>" href="/article/detail/<?php echo $art['id']?>"><img src="<?php echo $art['img_url']?UPLOADS.$art['img_url']:ASSETS.'web/images/default.jpg'?>" alt="<?php echo $art['title']?>"></a></figure>
        <ul class="nlist">
            <p><?php echo char_limit4($art['content'],130)?></p>
            <a href="/article/detail/<?php echo $art['id']?>" title="<?php echo $art['title']?>"  class="readmore">阅读全文>></a>
        </ul>
        <div class="line"></div>
        <?php endforeach;?>

        <div class="blank"></div>
        <div class="page"><?php echo $pages?></div>
    </div>
    <aside class="right">
        <div class="container">
            <div id="search">
                <form id="form1" name="form1" method="get"  action="/article/search">
                    <input type="text" name="q">
                    <input class="button" type="submit" value="Search">
                </form>
            </div>
        </div>
        <div class="blank"></div>
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
        <!-- <div class="blank"></div>
 <div class="visitors">
       <h3><p>最近访客</p></h3>
 <ul class="ds-recent-visitors"  data-num-items="30"></ul>
 <script type="text/javascript">
 var duoshuoQuery = {short_name:"yudouyudou"};
 (function() {
     var ds = document.createElement('script');
     ds.type = 'text/javascript';ds.async = true;
     ds.src = 'http://static.duoshuo.com/embed.js';
     ds.charset = 'gbk';
     (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ds);
 })();
 </script>
     </div>-->
    </aside>
</article>
<div id="tbox">
    <a id="ewm" href="" title="二维码" >
        <div class="qr-img">
            <div class="ico"></div>
            <div id="output"></div>
            <div id="msg">手机扫一扫 随身带着看</div>
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


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
    <link  href="<?php echo ASSETS?>web/css/about.css" rel="stylesheet">
    <!-- 禁止转码-->
    <meta http-equiv="Cache-Control" content="no-transform" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
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
            <a href="/<?php echo $v['enname']?>"  <?php if($current==$v['enname']) echo 'id="topnav_current"';?>><span><?php echo $v['text']?></span><span <?php if($current==$v['enname']) echo 'id="current"';?> class="en shake shake-slow"><?php echo ucfirst($v['enname'])?></span></a>
        <?php endforeach;?>
    </nav>
</header>
<article class="aboutcon">
    <h1 class="t_nav"><span>小达摩从未想过以后的生活是什么样子，反而一味沉寂在过往。</span><a href="/" class="n1">网站首页</a><a href="/<?php echo $artinfo['enname']?>/" class="n2"><?php echo $artinfo['text']?></a></h1>
    <div class="about left">
        <?php echo $about['content']?>
    </div>
    <aside class="right">
        <div class="about_c">
            <p>网名：<span>xiaodamo</span> | 小达摩</p>
            <p>籍贯：辽宁省—沈阳市</p>
            <p>现居：辽宁省—沈阳市</p>
            <p>年龄：33</p>
            <p>职业：PHP开发</p>
            <p>技能掌握：Html、PHP、测试</p>
            <p>喜欢的电影：《速度与激情7》</p>
            <p>喜欢的书：《明朝那些事》</p>
            <p>喜欢的音乐：《绅士》《一半》</p>
            <p><a  href="http://wpa.qq.com/msgrd?V=3&uin=281530997&Site=www.xiaodamo.com&Menu=yes" target="_blank" ><img border="0" src=http://wpa.qq.com/pa?p=1:548474762:13 alt="交流点这里"></a></p>
        </div>
    </aside>
</article>
<footer>
    <?php $this->load->view('_footer');?>
</footer>
</body>
</html>

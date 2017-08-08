<!doctype html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- logo-->
    <title><?php echo $webname ?></title>
    <meta name="description" content="<?php echo $webname ?>，主要写一些IT相关文章，发布Web与测试教程，记录个人笔记，上传一些实用的网络工具与网站模版。"/>
    <meta name="keywords" content="达摩,小达摩,个人博客,php,web,测试,资源下载"/>
    <link href="<?php echo ASSETS ?>web/css/base.css" rel="stylesheet">
    <link href="<?php echo ASSETS ?>web/css/history.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo ASSETS ?>web/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo ASSETS ?>web/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="<?php echo ASSETS ?>web/js/jquery.easing.js"></script>
    <script type="text/javascript" src="<?php echo ASSETS ?>web/js/history.js"></script>
</head>
<body>
<header>
    <div class="logo shake shake-slow"><a href="/"></a>
        <div class="year2016 "></div>
    </div>
    <nav class="topnav" id="topnav">
        <?php foreach ($category as $k=>$v):?>
            <?php if(!$v['is_display'] || $v['parent_id']) continue;?>
            <a href="/<?php echo $v['enname']?>"  <?php if($current==$v['enname']) echo 'id="topnav_current"';?>><span><?php echo $v['text']?></span><span <?php if($current==$v['enname']) echo 'id="current"';?> class="en shake shake-slow"><?php echo ucfirst($v['enname'])?></span></a>
        <?php endforeach;?>
    </nav>
</header>
<div id="arrow">
    <ul>
        <li class="arrowup"></li>
        <li class="arrowdown"></li>
    </ul>
</div>

<div id="history">

    <div class="title">
        <h2>博客时间轴</h2>
        <div id="circle">
            <div class="cmsk"></div>
            <div class="circlecontent">
                <div thisyear="<?php echo date("Y")?>" class="timeblock">
                    <span class="numf"></span>
                    <span class="nums"></span>
                    <span class="numt"></span>
                    <span class="numfo"></span>
                    <div class="clear"></div>
                </div>
                <div class="timeyear">年</div>
            </div>
            <a class="clock"></a>
        </div>
    </div>

    <div id="content">
        <ul class="list">
            <?php foreach ($articles as $v):?>
            <li>
                <div class="liwrap">
                    <div class="lileft">
                        <div class="date">
                            <span class="year"><?php echo date("Y",$v['created_at'])?></span>
                            <span class="md"><?php echo date("m月d日",$v['created_at'])?></span>
                        </div>
                    </div>

                    <div class="point"><b></b></div>

                    <div class="liright">
                        <div class="histt"><a><?php echo $v['title']?></a></div>
                        <div class="hisct"><?php echo $v['content']?></div>
                    </div>
                </div>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
    <footer>
        <?php $this->load->view('_footer');?>
    </footer>
</div>
</body>
</html>
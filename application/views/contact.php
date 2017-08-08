<!doctype html>
<html>
<head>
    <meta charset="gbk">
    <title><?php echo $webname ?></title>
    <meta name="description" content="<?php echo $webname ?>，主要写一些IT相关文章，发布Web与测试教程，记录个人笔记，上传一些实用的网络工具与网站模版。"/>
    <meta name="keywords" content="达摩,小达摩,个人博客,php,web,测试,资源下载"/>
    <!--移动适配Meta声明-->
    <meta name="applicable-device" content="pc">
    <link rel='stylesheet' href='//cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css'/>
    <link href="<?php echo ASSETS ?>web/css/base.css" rel="stylesheet">
    <link  href="<?php echo ASSETS?>web/css/style.css" rel="stylesheet">
    <link href="<?php echo ASSETS ?>web/css/comment.css" rel="stylesheet">
    <link href="<?php echo ASSETS ?>web/css/book.css" rel="stylesheet">
    <link href="<?php echo ASSETS ?>web/css/bootstrap.min.css" rel="stylesheet">
    <!-- 禁止转码-->
    <meta http-equiv="Cache-Control" content="no-transform"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <script src="<?php echo ASSETS ?>web/js/jquery.min.js"></script>
    <script src="<?php echo ASSETS ?>web/js/jquery.qqFace.js"></script>
    <!--[if lt IE 9]>
    <script src="<?php echo ASSETS?>web/js/modernizr.js"></script>
    <![endif]-->
</head>
</head>
<body>
<header>
    <div class="logo shake shake-slow"><a href="/"></a>
        <div class="year2016 "></div>
    </div>
    <nav class="topnav" id="topnav">
        <?php foreach ($category as $k => $v): ?>
            <?php if (!$v['is_display'] || $v['parent_id']) continue; ?>
            <a href="/<?php echo $v['enname'] ?>" <?php if ($current == $v['enname']) echo 'id="topnav_current"'; ?>><span><?php echo $v['text'] ?></span><span <?php if ($current == $v['enname']) echo 'id="current"'; ?>
                        class="en shake shake-slow"><?php echo ucfirst($v['enname']) ?></span></a>
        <?php endforeach; ?>
    </nav>
</header>
<div class="guest_banner"></div>
<article class="aboutcon">
    <h1 class="t_nav"><span>畅所欲言，无拘无束，小达摩竭诚欢迎拍砖。</span><a href="/" class="n1">网站首页</a><a
                href="/<?php echo $artinfo['enname'] ?>/" class="n2"><?php echo $artinfo['text'] ?></a></h1>
    <div class="book left">
        <!-- CY Comment BEGIN -->
        <div id="comment-jump" class="comments">
            <div id="comments" class="clearfix">
                <h3 id="comments-title">共<?php echo $total_nums?>条留言</h3>
                <div id="loading-comments" class="hide"><span><i class="fa fa-spinner fa-pulse"></i> Loading...</span>
                </div>
                <ul class="commentlist comdot" id="commentTarget">
                    <?php foreach ($articles as $k=>$v):?>
                        <?php $img_id = intval($v['id'])%360;?>
                    <li class="comment byuser comment-author-zhw2590582 bypostauthor even thread-even depth-1 clearfix"
                        id="li-comment-22">
                        <div class="comment-block" id="comment-22">
                            <div class="author-img"><img class="lazy"
                                                         src="<?php echo ASSETS.'web/images/avatar/'.$img_id.'.png';?>" class="avatar avatar-100"
                                                         height="100" width="100"></div>
                            <div class="comment-body clearfix">
                                <div class="comment-name">
                                    <span class="arrow left"></span>
                                    <cite class="fn"><?php echo $v['name']?></cite><span class="fr"><?php echo $v['id']?>L</span>
                                </div>
                                <div class="comment-text">
                                    <p><?php echo $v['content']?></p>
                                </div>
                                <div class="comment-info clearfix">
                                    <span class="comment-date">
                                    <a class="comment-time" href="javascript:void(0)"><?php echo date("Y-m-d H:i:s",$v['created_at'])?></a>
                                    </span>
                                    <span class="comment-edit"></span>
                                </div>
                            </div>
                        </div>
                        <?php if($v['reply']):?>
                        <ul class="children">
                            <li class="comment odd alt depth-2 clearfix" id="li-comment-30">
                                <div class="comment-block" id="comment-30">
                                    <div class="author-img"><img src="<?php echo ASSETS.'web/images/photos2.jpg'?>"
                                                                 class="avatar avatar-100" height="100" width="100">
                                    </div>
                                    <div class="comment-body clearfix">
                                        <div class="comment-name">
                                            <span class="arrow left"></span>
                                            <cite class="fn"><a href='javascript:void(0)' rel='external nofollow'
                                                                class='url'>博主小达摩 (回复)</a></cite> <span
                                                    class="fr"></span>
                                        </div>
                                        <div class="comment-text">
                                            <p><?php echo $v['reply']?></p>
                                        </div>
                                        <div class="comment-info clearfix">
                                        <span class="comment-date">
                                        <a class="comment-time" href="javascript:void(0)"><?php echo date("Y-m-d H:i:s",$v['updated_at'])?></a>
                                        </span>
                                            <span class="comment-edit"></span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <?php endif;?>
                    </li>
                    <?php endforeach;?>
                    <div class="clearfix"></div>
                </ul>
                <div style="padding-bottom: 20px;">
                    <div class="page">
                            <?php echo $pages?>
                    </div>
                </div>
                <input type="hidden" value="2" id="pageIndex">
                <input type="hidden" value="/get-comment-*pageNumber*.html" id="pageUrl">
                <input type="hidden" value="7" id="dataCount">
                <div id="respond" class="comment-respond">
                    <h3 id="reply-title" class="comment-reply-title">发表留言
                        <small>
                            <div id="cancel-comment-reply-link" style="display:none;"><i class="icon-remove-sign"></i>
                            </div>
                        </small>
                    </h3>
                    <form method="post" id="commentform" class="comment-form" >
                        <p class="comment-notes"><span id="email-notes">电子邮件地址不会被公开。</span> 必填项已用<span class="required">*</span>标注
                        </p>
                        <p class="comment-form-author"><label for="author">姓名 <span class="required">*</span></label>
                            <input id="author" name="author" type="text" value="" placeholder="请输入您的名字!" size="30"
                                   maxlength="10" required/></p>
                        <p class="comment-form-email"><label for="email">电子邮件 <span class="required">*</span></label>
                            <input id="email" name="email" type="text" value="" placeholder="博主回复你会通知到此邮箱!" size="30"
                                   maxlength="30" required/></p>
                        <p class="comment-form-url"><label for="url">站点</label> <input id="url" name="url" type="text"
                                                                                       value="" size="30"
                                                                                       maxlength="200"/></p>
                        <p class="comment-form-comment"><label for="comment">评论</label> <textarea id="comment"
                                                                                                  name="comment"
                                                                                                  cols="45" rows="8"
                                                                                                  maxlength="65525"
                                                                                                  placeholder="上述电子邮件地址不会被公开,只作为博主回复联系方式!"
                                                                                                  required></textarea>
                        </p>
                        <p class="form-submit">
                            <span class="emotion"></span>
                            <input name="submit" type="submit" id="submit" class="submit" value="发表评论"/>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- CY Comment END -->
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
<script src="<?php echo ASSETS?>web/js/bootstrap.min.js"></script>
<script src="<?php echo ASSETS?>web/js/jquery.validate.min.js"></script>
<script src="<?php echo ASSETS?>web/js/message_zh.js"></script>
<script>
        $(function(){

            $('.emotion').qqFace({

                id : 'facebox',

                assign:'comment',

                path:'<?php echo ASSETS?>web/images/arclist/'	//表情存放的路径

            });

            $("#commentform").validate({
                //debug:true,
                rules: {
                    email: {
                        email: true
                    },
                },
                errorClass: "error",
                success: 'valid',
                unhighlight: function (element, errorClass, validClass) { //验证通过
                    $(element).tooltip('destroy').removeClass(errorClass);
                },
                errorPlacement: function (label, element) {
                    $(element).tooltip('destroy'); /*必需*/
                    $(element).attr('title', $(label).text()).tooltip('show');
                },
                submitHandler: function (form) {
                    var str = $("#comment").val();
                    $("#comment").html(replace_em(str));
                    var newcomment = $("#comment").text();
                    $.ajax({
                        url:"/article/ajaxContact",
                        type:'post',
                        data:$("form").serialize()+"&newcomment="+newcomment,
                        dataType:'json',
                        success:function(data){
                            if(data.status){
                                window.location.reload();
                            }else if(data.status == 0){
                                alert(data.info);
                            }

                        },
                    });
                    return false;
                }
            })
        });

        //查看结果

        function replace_em(str){

            str = str.replace(/\</g,'&lt;');

            str = str.replace(/\>/g,'&gt;');

            str = str.replace(/\n/g,'<br/>');

            str = str.replace(/\[em_([0-9]*)\]/g,'<img src="<?php echo ASSETS?>web/images/arclist/$1.gif" border="0" />');

            return str;

        }
</script>

<!DOCTYPE html>

<html lang="zh-cmn-Hans">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="keywords" content="管理系统">
    <meta name="description" content="管理系统">

    <title>系统登录</title>
    <link href="<?php echo ASSETS;?>Content/ui-2.0-min.css" rel="stylesheet" />
    <script src="<?php echo ASSETS?>Scripts/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS?>Scripts/Account.js" type="text/javascript"></script>
    <link href="<?php echo ASSETS;?>Content/login.css" rel="stylesheet" />
    <style type="text/css">
        .mn-content{margin-top:160px;border-bottom:none;background:0 0}
        .mn-inner{width:445px}
        .pg-form{padding:30px 77px;min-height:300px;border-radius:8px;background:#fff;background:rgba(255,255,255,.88)}
        .pg-form h3{padding-bottom:12px;font-size:21px}
        .td-center{margin:0 auto;}
        .td-center td .m-text{width:220px;height:35px}
        .td-center tr .td-title{width:55px;height:60px;font-size:15px;line-height:58px}
        #mes{color:red;}
    </style>
</head>
<body>
<!--内容页-->
<div class="mn-content">
    <div class="mn-inner">
        <div class="pg-form">
            <h3><?php echo $sysname?></h3>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td class="td-center" valign="top">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="td-title" valign="top">账号</td>
                                <td>
                                    <input type="text" id="UserName" class="m-text" name="UserName" placeholder="请输入账号" requied/>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-title" valign="top">密码</td>
                                <td>
                                    <input type="password" id="Password" class="m-text" data-type="password" name="Password" placeholder="请输入密码"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-title" valign="top">验证码</td>
                                <td>
                                    <table cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>
                                                <input style="width: 100px;" class="m-text" type="text" name="ValidateCode" id="ValidateCode" placeholder="请输入验证码" />
                                            </td>
                                            <td>
                                                <img id="codeImg" alt="刷新验证码！" style="border-color:#eee;cursor:pointer;margin-left:5px;" src="<?php echo site_url('login/get_code')?>" onclick="this.src='<?php echo site_url('login/get_code')?>'+'?'" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-title"></td>
                                <td>
                                    <input id="LoginSys" type="button" class="m-btn m-btn-blue" value="登录" style="height:35px;width:220px" />
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td >
                                    <div id="mes" class="field-validation-error">
                                    </div>
                                    <div id="Loading" style="display: none" class='panel-loading'><font color='#000'>加载中...</font></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>
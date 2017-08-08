<!DOCTYPE html>
<html>
<head>
    <title>系统登录</title>
    <script src="<?php echo ASSETS?>Scripts/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS?>Scripts/Account.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo ASSETS;?>Content/themes/base/easyui.css" />
    <link rel="stylesheet" href="<?php echo ASSETS;?>Content/themes/skin-blue.css" />
    <link rel="stylesheet" href="<?php echo ASSETS;?>Content/icon.css" />
    <link rel="stylesheet" href="<?php echo ASSETS;?>Content/site.css" />
    <style type="text/css">
        body
        {
            letter-spacing: 1px;
            color: #444;
        }

        #LoginTb
        {
            font-size: 14px;
        }

        #LoginTb
        {
            font-size: 12px;
        }

        #LoginTb input
        {
            width: 190px;
            height: 24px;
            line-height: 24px;
        }
    </style>
</head>
<body>

<div>
    <div class="define-head" style="height: 67px;">
        <div class="define-logo">
            <div id="LoginTopLine"></div>
            <div id="LoginBotoomLine"></div>
        </div>

    </div>
</div>
<div style="margin: 0 auto; margin-top: 100px; width: 800px;">
    <table style="width: 800px;height: 290px; margin: 0 auto;">
        <tr>
            <td>
                <img src="<?php echo ASSETS;?>Content/Images/account.jpg"></td>
            <td style="width: 310px;">

                <table id="LoginTb" style="margin-top: 10px; background: #fff; width: 100%; height: 230px; border: 1px #ccc solid;">
                    <tr>
                        <td colspan="2" style="font-size: 22px; font-weight: bold; padding: 5px 20px;">欢迎登录
                        </td>

                    </tr>
                    <tr>
                        <td style="width: 80px; text-align: right">用户名：
                        </td>
                        <td>
                            <input id="UserName" name="UserName" type="text" />
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 80px; text-align: right;">密 码：
                        </td>
                        <td>
                            <input id="Password" name="Password" type="password" />
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 80px; text-align: right">验证码：
                        </td>
                        <td>
                            <input style="width: 50px;display:inline-block;" type="text" name="ValidateCode" id="ValidateCode" />
                            <img id="codeImg" alt="刷新验证码！" style="margin-bottom: -8px; cursor: pointer;" src="<?php echo site_url('login/get_code')?>" onclick="this.src=this.src+'?'" />
                            <a href="javascript:$('#codeImg').trigger('click').void(0)">看不清？</a>
                        </td>
                    </tr>


                    <tr>

                        <td colspan="2">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 40px;">&nbsp;</td>
                                    <td>
                                        <div id="mes" class="field-validation-error">
                                        </div>
                                        <div id="Loading" style="display: none" class='panel-loading'><font color='#000'>加载中...</font></div>
                                    </td>
                                    <td style="width: 100px;"><a id="LoginSys" href="javascript:void(0)" class="easyui-linkbutton l-btn"><span class="l-btn-left"><span class="l-btn-text icon-ok" style="padding-left: 20px;background:url('<?php echo ASSETS?>Content/icons/ok.png') no-repeat left center;">登录</span></span></a>
                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</div>
<div style="background: #f1f1f1; height: 40px; width: 100%; text-align: center; line-height: 40px; border-top: 1px #ccc solid; bottom: 0; position: absolute">
    CopyRight 2016
</div>
</body>
</html>
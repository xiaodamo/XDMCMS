<!DOCTYPE html>

<html>
<head>
    <title>首页</title>
    <meta name="viewport" content="width=device-width" />
    <script src="<?php echo ASSETS?>Scripts/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS?>Scripts/jquery.easyui.min.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS?>Scripts/jquery.easyui.plus.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS?>Content/locale/easyui-lang-zh-CN.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo ASSETS;?>Content/themes/base/easyui.css" />
    <link rel="stylesheet" href="<?php echo ASSETS;?>Content/themes/<?php echo $_SESSION['theme']?'skin-'.$_SESSION['theme']:'skin-blue'?>.css"/>
    <link rel="stylesheet" href="<?php echo ASSETS;?>Content/site.css" />
    <script src="<?php echo ASSETS?>Scripts/common.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS?>Scripts/home.js" type="text/javascript"></script>
    <link href="<?php echo ASSETS;?>Content/fontawesome/css/font-awesome.min.css" rel="stylesheet" />
    <script>
        $(function(){
            $("#RightAccordion").accordion({ //初始化accordion
                fillSpace:true,
                fit:true,
                border:false,
                animate:false
            });
            $.post("<?php echo site_url('main/get_top_menu')?>", //获取第一层目录
                function (data) {
                    $("#miannav").show();
                    var fristTitle;
                    if (data == "0") {
                        window.location = "/admin/login";
                    }
                    $.each(data, function (i, e) {//循环创建手风琴的项
                        if(i==0)
                            fristTitle= e.text;
                        var id = e.id;
                        $('#RightAccordion').accordion('add', {
                            title: e.text,
                            content: "<ul id='tree"+id+"'></ul>",
                            selected: true,//必须展开之后填充
                            iconCls:e.iconCls//e.Icon
                        });
                        $.post("<?php echo site_url('main/get_left_menu')?>?id=" + id, function (data) {//循环创建树的项
                            $("#tree" + id).tree({
                                data: data,
                                onBeforeExpand:function(node){
                                    $("#tree" + id).tree('options').url = "<?php echo site_url('main/get_left_menu')?>?id=" + node.id;
                                },
                                onClick: function (node) {
                                    if (node.state == 'closed'){
                                        $(this).tree('expand', node.target);
                                    }else if (node.state == 'open'){
                                        $(this).tree('collapse', node.target);
                                        if(!node.children){
                                            var tabTitle = node.text;
                                            var url = "<?php echo base_url();?>" + node.url;
                                            var icon = node.iconCls;
                                            addTab(tabTitle, url, icon);
                                        }
                                    }
                                }
                            });
                        }, 'json');
                        $('#RightAccordion').accordion('select',fristTitle);//选中第一个
                        $("#tree"+id+"").parent().css("overflow-y","auto");
                    });
                }, "json");

            $("#RightAccordion ").parent().css("overflow","hidden");//外部隐藏

        });

    </script>
</head>
<body class="easyui-layout" id="easyLayout">

<div id="modalwindow" class="easyui-window" data-options="closed:true,minimizable:false,shadow:false,collapsible:true">
</div>

<div id="north" data-options="region:'north',border:false,split:false" style="height: 50px; padding:0;margin:0">
    <table class="banner" style="border-spacing:0px;">
        <tr>
            <td class="webname">
                <?php echo $sysname?>
            </td>
            <td style=" ">
                <a id="SetThemes" class="l-btn-text fa fa-cogs fa-lg bannerbtn"  href="#"> </a>
            </td>
            <td style="width: 40px;">
            </td>
            <td style="width: 40px;">
                <a class="l-btn-text fa fa-envelope-o  fa-lg bannerbtn" href="#"> </a>
            </td>
            <td style="width: 80px;">
                <a class="l-btn-text fa fa-bell-o  fa-lg bannerbtn" href="#"> </a>
            </td>

            <td style="width:180px; overflow:hidden ">
                <a id="showUserInfo" style="display:inline-block;" class="fa bannerbtn" href="javascript:void(0)">
                    <img src="<?php echo $_SESSION['icon']?UPLOADS.$_SESSION['icon']:ASSETS.'avatars/avatar2.png';?>" class="user-image" alt="User Image">
                    <span class="user-name"><?php echo $_SESSION['name']?$_SESSION['name']:$_SESSION['account']?></span>
                </a>

            </td>
        </tr>
    </table>

</div>
<div id="west" data-options="region:'west',split:true,title:'主菜单',collapsible:false" style="width: 220px; height: 100%; background-color: #fff; overflow: auto; border-bottom:0px;">

    <div id="RightAccordion" class="easyui-accordion"></div>
    <div id="RightTree" class="easyui-tree"></div>

</div>
<div data-options="region:'south',border:false" style="height: 20px;">
</div>
</div>
<div data-options="region:'center',border:false">
    <div id="mainTab" class="easyui-tabs" data-options="fit:true">
    </div>
</div>
<div id="tab_menu" class="easyui-menu" style="width: 150px;">
    <div id="tab_menu-tabrefresh" data-options="iconCls:'icon-reload'">
        刷新
    </div>
    <div id="tab_menu-openFrame">
        在新的窗体打开
    </div>
    <div id="tab_menu-tabcloseall">
        关闭所有
    </div>
    <div id="tab_menu-tabcloseother">
        关闭其他标签页
    </div>
    <div class="menu-sep">
    </div>
    <div id="tab_menu-tabcloseright">
        关闭右边
    </div>
    <div id="tab_menu-tabcloseleft">
        关闭左边
    </div>
    <div id="tab_menu-tabclose" data-options="iconCls:'fa fa-trash'">
        关闭
    </div>
    <div id="menu" class="easyui-menu" style="width: 150px;">
    </div>
</div>

<div id="ModalStyle" class="easyui-dialog" style="width:600px;height:340px" data-options="iconCls:'icon-save',modal:true,closed:true">
    <table style="width:100%; padding:20px; line-height:30px;text-align:center;">
        <tr>
            <td><img src="<?php echo ASSETS;?>Content/Images/skin/skin-red.png" style="width:60px;height:30px;" /><br /><input type="radio" name="themes" value="red" checked="checked" />red</td>
            <td><img src="<?php echo ASSETS;?>Content/Images/skin/skin-green.png" style="width:60px;height:30px;" /><br /><input type="radio" name="themes" value="green" />green</td>
            <td><img src="<?php echo ASSETS;?>Content/Images/skin/skin-purple.png" style="width:60px;height:30px;" /><br /><input type="radio" name="themes" value="purple" />purple</td>
            <td><img src="<?php echo ASSETS;?>Content/Images/skin/skin-blue.png" style="width:60px;height:30px;" /><br /><input type="radio" name="themes" value="blue" />blue</td>
            <td><img src="<?php echo ASSETS;?>Content/Images/skin/skin-yellow.png" style="width:60px;height:30px;" /><br /><input type="radio" name="themes" value="yellow" />yellow</td>
        </tr>
        <tr>
            <td>
                <img src="<?php echo ASSETS;?>Content/Images/skin/skin-redlight.png" style="width:60px;height:30px;" /><br /><input type="radio" name="themes" value="redlight"/>red light
            </td>

            <td><img src="<?php echo ASSETS;?>Content/Images/skin/skin-greenlight.png" style="width:60px;height:30px;" /><br /><input type="radio" name="themes" value="greenlight" />green light</td>

            <td><img src="<?php echo ASSETS;?>Content/Images/skin/skin-purplelight.png" style="width:60px;height:30px;" /><br /><input type="radio" name="themes" value="purplelight" />purple light</td>

            <td> <img src="<?php echo ASSETS;?>Content/Images/skin/skin-bluelight.png" style="width:60px;height:30px;" /><br /><input type="radio" name="themes" value="bluelight" />blue light</td>

            <td><img src="<?php echo ASSETS;?>Content/Images/skin/skin-yellowlight.png" style="width:60px;height:30px;" /><br /><input type="radio" name="themes" value="yellowlight" />yellow light</td>


        </tr>
    </table>
    <table style="width: 100%; padding: 20px; line-height: 30px; text-align: center;">
        <tr>
            <td>
<!--                <input type="radio" name="menustyle" value="accordion" checked="checked" />手风琴-->
            </td>
            <td>
<!--                <input type="radio" name="menustyle" value="tree" />树形结构-->
            </td>
        </tr>
    </table>
    <div class="endbtndiv">
        <a id="btnSave" href="javascript:SetThemes()" class="easyui-linkbutton btns">Save</a>
        <a id="btnReturn" href="javascript:$('#ModalStyle').dialog('close')" class="easyui-linkbutton btnc">Close</a>
    </div>

</div>
</body>
</html>

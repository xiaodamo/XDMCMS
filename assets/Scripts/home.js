
$(function () {

    $('#tab_menu-tabrefresh').click(function () {
        /*重新设置该标签 */
        var url = $(".tabs-panels .panel").eq($('.tabs-selected').index()).find("iframe").attr("src");
        $(".tabs-panels .panel").eq($('.tabs-selected').index()).find("iframe").attr("src", url);
    });
    //在新窗口打开该标签
    $('#tab_menu-openFrame').click(function () {
        var url = $(".tabs-panels .panel").eq($('.tabs-selected').index()).find("iframe").attr("src");
        window.open(url);
    });
    //关闭当前
    $('#tab_menu-tabclose').click(function () {
        var currtab_title = $('.tabs-selected .tabs-inner span').text();
        $('#mainTab').tabs('close', currtab_title);
        if ($(".tabs li").length == 0) {
            //open menu
            $(".layout-button-right").trigger("click");
        }
    });
    //全部关闭
    $('#tab_menu-tabcloseall').click(function () {
        $('.tabs-inner span').each(function (i, n) {
            if ($(this).parent().next().is('.tabs-close')) {
                var t = $(n).text();
                $('#mainTab').tabs('close', t);
            }
        });
        //open menu
        $(".layout-button-right").trigger("click");
    });
    //关闭除当前之外的TAB
    $('#tab_menu-tabcloseother').click(function () {
        var currtab_title = $('.tabs-selected .tabs-inner span').text();
        $('.tabs-inner span').each(function (i, n) {
            if ($(this).parent().next().is('.tabs-close')) {
                var t = $(n).text();
                if (t != currtab_title)
                    $('#mainTab').tabs('close', t);
            }
        });
    });
    //关闭当前右侧的TAB
    $('#tab_menu-tabcloseright').click(function () {
        var nextall = $('.tabs-selected').nextAll();
        if (nextall.length == 0) {
            $.messager.alert('提示', '前面没有了!', 'warning');
            return false;
        }
        nextall.each(function (i, n) {
            if ($('a.tabs-close', $(n)).length > 0) {
                var t = $('a:eq(0) span', $(n)).text();
                $('#mainTab').tabs('close', t);
            }
        });
        return false;
    });
    //关闭当前左侧的TAB
    $('#tab_menu-tabcloseleft').click(function () {

        var prevall = $('.tabs-selected').prevAll();
        if (prevall.length == 0) {
            $.messager.alert('提示', '后面没有了!', 'warning');
            return false;
        }
        prevall.each(function (i, n) {
            if ($('a.tabs-close', $(n)).length > 0) {
                var t = $('a:eq(0) span', $(n)).text();
                $('#mainTab').tabs('close', t);
            }
        });
        return false;
    });
    /*为选项卡绑定右键*/
    $("#mainTab").tabs({
        onSelect: function (title, index) {
            initTabs();
        },
        onContextMenu: function (e) {
            /* 选中当前触发事件的选项卡 */
            var subtitle = $(this).text();
            $('#mainTab').tabs('select', subtitle);
            //显示快捷菜单
            e.preventDefault();
            //阻止冒泡
            $('#tab_menu').menu('show', {
                left: e.pageX,
                top: e.pageY
            });
            return false;
        }
    })
    //加载第一个tabs
    addTab(index_lang_desktop, "/admin/welcome", "fa fa-home");
    $("#mainTab .tabs ").attr("style", "height:34px;line-height:34px");
    $("#mainTab .tabs li").find("a:first").attr("style", "height:32px;line-height:32px");

    $('#showUserInfo').tooltip({
        content: $('<div></div>'),
        showEvent: 'click',
        deltaX: -70,
        onUpdate: function (content) {
            content.panel({
                width: 250,
                border: false,
                
                href: '/admin/welcome/topinfo'
            });
        },
        onShow: function () {
            var t = $(this);
            t.tooltip('tip').unbind().bind('mouseenter', function () {
                t.tooltip('show');
            }).bind('mouseleave', function () {
                t.tooltip('hide');
            });
        }
    });
});


function initTabs() {
    $("#mainTab .tabs ").attr("style", "height:33px;line-height:33px");
    $("#mainTab .tabs li").find("a:first").attr("style", "height:32px;line-height:32px");
}

function Profile()
{
    addTab("Profile","/admin/admin_user/profile", "fa fa-credit-card");
}

function SignOut()
{
   $.messager.confirm(index_lang_tip, '你确定要退出系统吗?', function (r) {
      if (r) {
            $.post("/admin/logout/index", function (data) { location.href = '/admin/login'; }, "json");
          }
   });
}

$(function () {
    
    //tabs页码bug
    $('#easyLayout').layout('panel', 'west').panel({
        onResize: function () {
            setTimeout(function () {
                initTabs()
            }, 100);
        }
    });
});
//tabs页码bug
$(window).resize(function () {
    setTimeout(function(){
        initTabs()
    }, 100);
});

function addTab(subtitle, url, icon) {
    if (!$("#mainTab").tabs('exists', subtitle)) {
        $("#mainTab").tabs('add', {
            title: subtitle,
            content: '<iframe frameborder="0" src="' + url + '" scrolling="auto" style="width:100%; height:100%;overflow:hidden"></iframe>',
            closable: true,
            icon: icon
        });
    } else {
        $("#mainTab").tabs('select', subtitle);
        $("#tab_menu-tabrefresh").trigger("click");
    }
    //$(".layout-button-left").trigger("click");
    //tabClose();
}



function SetThemes() {
    $.messager.confirm(index_lang_tip, '切换皮肤将重新加载系统！', function (r) {
        if (r) {
            var theme = $('input[name="themes"]:checked').val();
            $.post("/admin/main/setthemes", { theme: theme}, function (data) {window.location.reload(); }, "json");
        }
    });
}

$(function () {
    $("#SetThemes").click(function () {
        $("#ModalStyle").dialog({
            title: '个性化设置', 

        }).dialog('open');


    });
    $("#easyMod").click(function () {
        $('#easyLayout').layout('remove', 'north');
        $('#easyLayout').layout('remove', 'south');
    });
});

function fullSetButtonOut()
{
    if ($("#north").is(":hidden")) {
        return "<div class='fullSet'></div><div id='fullSetButton' class='fa fa-compress'></div>";
    } else {
        return "<div class='fullSet'></div><div id='fullSetButton' class='fa fa-expand'></div>";
    }
}
function fullSet()
{   
    $("#north").slideToggle("100", function () { $("#west").resize();});
}


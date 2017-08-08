$(function () {
    $("#LoginSys").click(function () {
        LoginSys();
    });
    $("#UserName").keydown(function (e) {
        var curkey = e.which;
        if (curkey == 13) {
            LoginSys();
            return false;
        }
    });
    $("#Password").keydown(function (e) {
        var curkey = e.which;
        if (curkey == 13) {
            LoginSys();
            return false;
        }
    });
    $("#ValidateCode").keydown(function (e) {
        var curkey = e.which;
        if (curkey == 13) {
            LoginSys();
            return false;
        }
    });



});

function LoginSys() {
    $("#mes").html("");
    $("#UserName").removeClass("input-validation-error");
    $("#Password").removeClass("input-validation-error");
    $("#ValidateCode").removeClass("input-validation-error");
    if ($.trim($("#UserName").val()) == "") {
        $("#UserName").addClass("input-validation-error").focus();
        $("#mes").html("账号不能为空！");
        return;
    }
    if ($.trim($("#Password").val()) == "") {
        $("#Password").addClass("input-validation-error").focus();
        $("#mes").html("密码不能为空！");
        return;
    }
    if ($.trim($("#ValidateCode").val()) == "") {
        $("#ValidateCode").addClass("input-validation-error").focus();
        $("#mes").html("验证码不能为空！");
        return;
    }
    $("#Loading").show();

    $.post('/admin/login/signin', { account: $("#UserName").val(), password: $("#Password").val(), code: $("#ValidateCode").val() },
    function (data) {

        if (data.status == "1") {
            window.location = "/admin/main"
        } else {
            $("#mes").html(data.info);
        }
        $("#Loading").hide();
    }, "json");
    return false;
}

function getTopWindow(){
    var p = window;
    while(p != p.parent){
        p = p.parent;
    }
    return p;
}
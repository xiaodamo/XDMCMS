<?php $this->load->view('_header');?>
<script type="text/javascript">
    $(function () {
        $("#Edit").click(function () {
            $("#modalwindow").window({ title: '修改', width: 330, height: 200, iconCls: 'fa fa-edit'});
            $("#modalwindow").window('open');
        });
        $("#btnSave").click(function () {
            $("#EditMes").html("");
            if ($.trim($("#Password1").val()).length < 5) {
                $("#EditMes").html("旧密码不匹配!");
                $("#Password1").focus();
                return;
            }
            if ($.trim($("#Password2").val()).length < 5) {
                $("#EditMes").html("新密码不匹配!最少5位,不能有空格");
                $("#Password2").focus();
                return;
            }
            if ($.trim($("#Password2").val()) != $.trim($("#Password3").val())) {
                $("#EditMes").html("两次密码不一致");
                $("#Password3").focus();
                return;
            }
            $.post("/admin/admin_user/change_mypassword", { oldPwd: $("#Password1").val(), newPwd: $("#Password2").val() }, function (data) {
                if (data.status == 1) {
                    $.messageBox3s('提示',data.info);
                    $("#modalwindow").window('close');
                }
                else {
                    $.messageBox3s('提示', data.info + '！');
                }
            },"json");
        });
    });
</script>
<div id="modalwindow" class="easyui-window" data-options="closed:true,modal:true">
    <div class="mvctool bgb">
        <a id="btnSave" style="float: left;" class="l-btn l-btn-plain">
                <span class="l-btn-left"><span class="l-btn-text fa fa-save" style="font-size:14px">
                </span><span style="font-size:12px">保存</span></span>
        </a>
    </div>
    <table class="formtable">
        <tbody>
        <tr>
            <td>&nbsp;&nbsp;旧密码：</td><td><input id="Password1" type="password" /></td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;新密码：</td><td><input id="Password2" type="password" /></td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;确认密码：</td><td><input id="Password3" type="password" /></td>
        </tr>
        <tr>
            <td></td><td colspan="3" id="EditMes" style="color:red"></td>
        </tr>
        </tbody>
    </table>
</div>

<div style="width:620px;border-right:dashed 1px #ccc;">

    <div id="ErrMesList">
        <div id="ErrMesListContent">

        </div>
    </div>

    <table class="formtable">
        <tbody>
        <tr>
            <td colspan="6">
                账户信息
            </td>
        </tr>
        <tr>
            <th>
                账户名：
            </th>
            <td>
                <?php echo $myinfo['account']?>
            </td>
            <th>
                密码：
            </th>
            <td>
                ******** <a id="Edit" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-key'">修改密码</a>
            </td>
            <td>
                &nbsp;
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        <tr>
            <th>拥有角色：</td>
            <td colspan="5">
                <?php echo $role['name']?>
            </td>
        </tr>
        </tbody>
    </table>

    <table class="formtable">
        <tr>
            <td colspan="5">
                基本资料
            </td>
        </tr>
        <tr>
            <th>
                姓名：
            </td>
            <td>
                <?php echo $myinfo['name']?>
            </td>
            <th>
                性别：
            </td>
            <td>
                <?php echo $myinfo['sex']?>
            </td>
            <td rowspan="5" style=" border-left:dashed 1px #ccc; text-align:center">
                <img id="PhotoFB" style="cursor:pointer; width:140px; height:140px; border:1px #ccc solid;" src="<?php if($myinfo['icon']) echo UPLOADS.$myinfo['icon'];else echo ASSETS.'Content/Images/NotPic.jpg';?>" />
            </td>
        </tr>
        <tr>
            <th>
                生日：
            </td>
            <td>
                <?php echo $myinfo['birthday']?date('Y-m-d'):''?>
            </td>
            <th>
                加入日期：
            </td>
            <td>
                <?php echo $myinfo['created_at']?date('Y-m-d H:i:s'):''?>
            </td>
        </tr>
    </table>

    <table class="formtable">
        <tbody>
        <tr>
            <td colspan="6">
                联系方式
            </td>
        </tr>
        <tr>
            <th>
                电话：
            </td>
            <td>
                <?php echo $myinfo['tel']?>
            </td>
            <th>
                Email：
            </td>
            <td>
                <?php echo $myinfo['email']?>
            </td>
            <th>
            </td>
            <td>
            </td>
        </tr>
        <tr>
            <th>
                省：
            </td>
            <td>
                <?php echo $province['name']?>
            </td>
            <th>
                市：
            </td>
            <td>
                <?php echo $city?>
            </td>
            <th>
                区：
            </td>
            <td>
                <?php echo $distinct?>
            </td>
        </tr>
        <tr>
            <th>
                地址：
            </td>
            <td colspan="5">
                <?php echo $myinfo['address']?>
            </td>

        </tr>
        </tbody>
    </table>
</div>
<?php $this->load->view('_footer');?>

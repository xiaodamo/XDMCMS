<?php $this->load->view('_header');?>
<script type="text/javascript">
    $(function () {
        $("#Edit").click(function () {
            $("#modalwindow").window({ title: '修改', width: 330, height: 200, iconCls: 'fa fa-edit'});
            $("#modalwindow").window('open');
        });
        $("#btnSave").click(function () {
            $("#EditMes").html("");
            if ($.trim($("#sysname").val()).length == 0) {
                $("#EditMes").html("后台系统名称不能为空!");
                $("#sysname").focus();
                return;
            }
            if ($.trim($("#webname").val()).length == 0) {
                $("#EditMes").html("前台系统名称不能为空!");
                $("#webname").focus();
                return;
            }
            $.post("/admin/webset/makeconfig", { sysname: $("#sysname").val(),webname:$("#webname").val(),used:$('input[name="used"]:checked').val()  }, function (data) {
                if (data.status == 1) {
                    $.messageBox3s('提示',data.info);
                    $("#modalwindow").window('close');
                    window.location.reload();
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
            <td>&nbsp;&nbsp;后台系统名称：</td><td><input id="sysname" type="text" value="<?php echo $sysname?>" /></td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;前台系统名称：</td><td><input id="webname" type="text" value="<?php echo $webname?>" /></td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;是否开启网站：</td><td><input type="checkbox" id="used" name="used" value="1" <?php echo $used?'checked="checked"':''?> /></td>
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
                网站信息
            </td>
        </tr>
        <tr>
            <th>
                后台系统名称：
            </th>
            <td>
                <?php echo $sysname?>
            </td>
            <th>
                前台系统名称：
            </th>
            <td>
                <?php echo $webname?>
            </td>
            <th>
                是否开启网站：
            </th>
            <td>
                <?php echo $used?'开启':'关闭'?>&nbsp;&nbsp;<a id="Edit" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'fa fa-pencil'">修改</a>
            </td>
            <td>
                &nbsp;
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        </tbody>
    </table>

</div>
<?php $this->load->view('_footer');?>

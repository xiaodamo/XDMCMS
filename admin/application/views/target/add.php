<?php $this->load->view('_header');?>
    <script type="text/javascript">
        $(function () {

            $("#btnSave").click(function () {
                if ($("form").valid()) {
                    $.ajax({
                        url: "/admin/target/save",
                        type: "post",
                        data: $("form").serialize(),
                        dataType: "json",
                        success: function (data) {
                            if(data.status){
                                $.messageBox3s('操作成功', data.info);
                                window.parent.frameReturnByClose();
                                window.parent.frameReturnByReload(false);
                            }else{
                                $.messageBox3s('操作失败', data.info);
                            }
                        }
                    });
                }
                return false;
            });
            $("#btnReturn").click(function () {
                window.parent.frameReturnByClose();
            });

        });


    </script>
    <div class="mvctool bgb">
        <a id="btnSave" style="float: left;" class="l-btn l-btn-plain">
                <span class="l-btn-left"><span class="l-btn-text fa fa-save" style="font-size:14px">
                </span><span style="font-size:12px">保存</span></span></a>
        <div class="datagrid-btn-separator"></div>
        <a id="btnReturn" style="float: left;" class="l-btn l-btn-plain">
                <span class="l-btn-left"><span class="l-btn-text fa fa-reply" style="font-size:14px">
                </span><span style="font-size:12px">返回</span></span></a>
    </div>
    <div id="modalwindow" class="easyui-window" data-options="modal:true,closed:true,minimizable:false,shadow:false"></div>
    <form id="EditForm" method="post" enctype="multipart/form-data">
        <table class="formtable">
            <tbody>
            <tr>
                <th>
                    标签名称：
                </th>
                <td>
                    <input name="name" type="text" value="<?php echo $editing['name']; ?>" required/>
                </td>
            </tr>
            <tr>
                <th>
                    说明：
                </th>
                <td>
                    <input name="descinfo" type="text" value="<?php echo $editing['descinfo']; ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    排序：
                </th>
                <td>
                    <input name="sort_order" type="text" value="<?php echo $editing['sort_order']; ?>" />
                </td>
            </tr>

            <tr>
                <th>
                    是否显示：
                </th>
                <td>
                    <input type="checkbox" name="is_display" value="1" <?php echo $editing['is_display']?'checked="checked"':''?> />
                    <input type="hidden" name="id" value="<?php echo $editing['id']; ?>" />
                </td>
            </tr>

            </tbody>
        </table>
    </form>
<?php $this->load->view('_edit');?>
<?php $this->load->view('_footer');?>
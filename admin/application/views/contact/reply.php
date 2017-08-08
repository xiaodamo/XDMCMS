<?php $this->load->view('_header');?>
    <script type="text/javascript">
        $(function () {

            $("#btnSave").click(function () {
                if ($("form").valid()) {
                    $.ajax({
                        url: "/admin/contact/reply",
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
                    昵称：
                </th>
                <td>
                    <?php echo $editing['name']; ?>
                </td>
            </tr>
            <tr>
                <th>
                    内容：
                </th>
                <td>
                    <?php echo $editing['content']; ?>
                    <input type="hidden" name="id" value="<?php echo $editing['id']; ?>"/>
                </td>
            </tr>

            </tbody>
        </table>

        <table class="formtable" >
            <tbody>
            <tr>
                <th>
                    回复：
                </th>
                <td colspan="2">
                    <script type="text/javascript" charset="utf-8" src="<?php echo ASSETS?>Scripts/ueditor1431/ueditor.config.js"></script>
                    <script type="text/javascript" charset="utf-8" src="<?php echo ASSETS?>Scripts/ueditor1431/_examples/editor_api.js"></script>
                    <script type="text/javascript" charset="utf-8" src="<?php echo ASSETS?>Scripts/ueditor1431/lang/zh-cn/zh-cn.js"></script>
                    <script type="text/plain" id="ueditor" name="reply" style="width:600px;height:330px;z-index:-1;" ><?php echo $editing['reply']; ?></script>
                    <script type="text/javascript">
                        UE.getEditor('ueditor', {
                            setContent:"1",
                            lang:'zh-cn'
                        });
                    </script>
                </td>
            </tr>

            </tbody>
        </table>
    </form>
<?php $this->load->view('_edit');?>
<?php $this->load->view('_footer');?>
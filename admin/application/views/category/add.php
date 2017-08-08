<?php $this->load->view('_header');?>
<style>.tree-folder,.tree-file{vertical-align: middle;overflow:auto;}</style>
            <script type="text/javascript">
                $(function () {

                    $("#btnSave").click(function () {
                        if ($("form").valid()) {
                            $.ajax({
                                url: "/admin/category/save",
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

                    $('#parent_id').combotree({
                        url: '/admin/category/index/method/json',
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
                        上级栏目：
                    </th>
                    <td>
                        <input id="parent_id" name="parent_id" value="<?php echo $editing['parent_id']?$editing['parent_id']:'';?>" style="width:200px;height:28px;">
                    </td>
                </tr>
                <tr>
                    <th>
                        栏目名称：
                    </th>
                    <td>
                        <input name="name" class="easyui-textbox" type="text" value="<?php echo $editing['name']; ?>" data-options="required:true" style="height:28px;"/>
                    </td>
                </tr>
                <tr>
                    <th>
                        英文标识：
                    </th>
                    <td>
                        <input name="enname" class="easyui-textbox" type="text" value="<?php echo $editing['enname']; ?>" data-options="required:true" style="height:28px;"/>
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
                        类型：
                    </th>
                    <td>
                        <input name="ctype"  <?php if($editing['ctype']==1) echo 'checked="checked"';?> type="radio" value="1"  />普通文章
                        <input name="ctype" <?php if($editing['ctype']==2) echo 'checked="checked"';?> type="radio" value="2" />首页
                        <input name="ctype" <?php if($editing['ctype']==3) echo 'checked="checked"';?> type="radio" value="3" />日志
                        <input name="ctype" <?php if($editing['ctype']==4) echo 'checked="checked"';?> type="radio" value="4" />留言
                        <input name="ctype" <?php if($editing['ctype']==5) echo 'checked="checked"';?> type="radio" value="5" />关于
                        <input name="id" type="hidden" value="<?php echo $editing['id']; ?>" />
                    </td>
                </tr>
                <tr>
                <th>
                    是否显示：
                </th>
                <td>
                    <input type="checkbox" name="is_display" value="1" <?php echo $editing['is_display']?'checked="checked"':''?> />
                </td>
                </tr>

                </tbody>
            </table>
        </form>
<?php $this->load->view('_edit');?>
<?php $this->load->view('_footer');?>
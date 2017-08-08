<?php $this->load->view('_header');?>
<style>.tree-folder,.tree-file{background: url('');vertical-align: middle;overflow:auto;}</style>
            <script type="text/javascript">
                $(function () {

                    $("#icon").click(function () {
                        //$("#selIcon").toggle();
                        openIconList();
                    });

                    $("#btnSave").click(function () {
                        if ($("form").valid()) {
                            $.ajax({
                                url: "/admin/menu/save",
                                type: "post",
                                data: $("form").serialize(),
                                dataType: "json",
                                success: function (data) {
                                    if(data.status){
                                        $.messageBox3s('操作成功', data.info);
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
                        url: '/admin/role/get_auth',
                    });
                });

                function openIconList(){

                    $("#modalwindow").html("<iframe width='100%' height='100%' scrolling='auto' frameborder='0' src='/admin/menu/get_icons'></iframe>");
                    $("#modalwindow").window({ title: '选择图标', width: 1200, height: 800, iconCls: 'fa fa-list',closed:true,minimizable:false,shadow:false }).window('open');

                }

                function changeicon(iclass){
                    $("#icon").attr("class", iclass);
                    $("#Iconic").val(iclass);
                    $("#modalwindow").window('close');
                }

            </script>
            <div class="mvctool bgb">
                <a id="btnSave" style="float: left;" class="l-btn l-btn-plain">
                <span class="l-btn-left"><span class="l-btn-text fa fa-save" style="font-size:14px">
                </span><span style="font-size:12px">保存</span></span></a>
            </div>
        <div id="modalwindow" class="easyui-window" data-options="modal:true,closed:true,minimizable:false,shadow:false"></div>
        <form id="EditForm" method="post" enctype="multipart/form-data">
            <table class="formtable">
                <tbody>
                <tr>
                    <th>
                        上级菜单：
                    </th>
                    <td>
                        <input id="parent_id" name="parent_id" value="<?php echo $editing['parent_id']?$editing['parent_id']:'';?>" style="width:200px;height:28px;">
                    </td>
                </tr>
                <tr>
                    <th>
                        菜单名称：
                    </th>
                    <td>
                        <input name="name" type="text" value="<?php echo $editing['name']; ?>" data-options="required:true"/>
                    </td>
                </tr>
                <tr>
                    <th>
                        控制器：
                    </th>
                    <td>
                        <input name="c" type="text" value="<?php echo $editing['c']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th>
                        方法：
                    </th>
                    <td>
                        <input name="a" type="text" value="<?php echo $editing['a']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th>
                        ICON图标：
                    </th>
                    <td>
                        <input value="<?php echo $editing['icon']?>" name="icon" id="Iconic" type="hidden" />
                        <div id="icon" title="点我选取图标" style="cursor:pointer" class="<?php echo $editing['icon']?$editing['icon']:'fa fa-hand-pointer-o'?>"></div>
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
                        是否为菜单：
                    </th>
                    <td>
                        <input type="checkbox" name="is_menu" value="1" <?php echo $editing['is_menu']?'checked="checked"':''?> />
                    </td>
                </tr>

                <tr>
                    <th>
                        是否显示：
                    </th>
                    <td>
                        <input type="checkbox" name="is_display" value="1" <?php echo $editing['is_display']?'checked="checked"':''?> />
                        <input type="hidden" name="id" value="<?php echo $editing['auth_id']; ?>" />
                    </td>
                </tr>

                </tbody>
            </table>
        </form>
<?php $this->load->view('_edit');?>
<?php $this->load->view('_footer');?>
<?php $this->load->view('_header');?>
<style>.tree-folder,.tree-file{background: url('');vertical-align: middle;overflow:auto;}</style>
            <script type="text/javascript">
                $(function () {

                    $("#btnSave").click(function () {
                        if (!$("form").valid()) {
                            $.messageBox3s('提示', $("#ErrMesList").html());
                            return;
                        }

                        var nodes = $('#rightList').treegrid('getCheckedNodes');
                        var roles = new Array();
                        for(var n in nodes){
                            roles.push(nodes[n].id);
                        }

                        var roleIds = roles.join();
                        var formdata = $("form").serialize();
                        formdata = formdata+'&roleIds='+roleIds;

                        if ($("form").valid()) {
                                $.ajax({
                                    url: "/admin/role/save",
                                    type: "Post",
                                    data: formdata,
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

                    //模块权限表格
                    var ismycheck = false;
                    $('#rightList').treegrid({
                        onLoadSuccess: function (row, data) {
                            var checkednode = "<?php echo $editing['auth_ids']?>";
                            var checkedarr = checkednode==""?[]:checkednode.split(',');
                            if(checkedarr){
                                for(var n in checkedarr){
                                    $('#rightList').treegrid('checkNode',checkedarr[n]);
                                }
                            }
                            ismycheck = true;
                        },
                        onCheckNode:function(row,checked){
                            if(!ismycheck) return;
                            var id = row.id;
                            var childarr = $('#rightList').treegrid('getChildren',id);
//                            console.log(childarr);return;
                            if(childarr){
                                for(var n in childarr){
                                    if(checked){
                                        $('#rightList').treegrid('checkNode',childarr[n].id);
                                    }else{
                                        $('#rightList').treegrid('uncheckNode',childarr[n].id);
                                    }
                                }
                            }
                        },
                        url: '/admin/role/get_auth',
                        methord: 'post',
                        height: SetGridHeightSub(200),
                        fitColumns: true,
                        idField: 'id',
                        treeField: 'text',
                        rownumbers: true,
                        showFooter: true,
                        checkbox: true,
                        cascadeCheck:false,
                        columns: [[
                            { field: 'id', title: 'ID', width: 80, hidden: true },
                            { field: 'text', title: '模块名称', width: 80, sortable: false },
                            { field: 'url', title: '操作码', width: 80, sortable: false },
                        ]]
                    });
                });

                //ifram 返回
                function frameReturnByClose() {
                    $("#modalwindow").window('close');
                }


            </script>
            <div class="mvctool">
                <a id="btnSave" style="float: left;" class="l-btn l-btn-plain">
                <span class="l-btn-left"><span class="l-btn-text fa fa-save" style="font-size:14px">
                </span><span style="font-size:12px">保存</span></span></a>
            </div>
            <div id="modalwindow" class="easyui-window" data-options="modal:true,closed:true,minimizable:false,shadow:false"></div>
            <div style="width:98%; margin:0 auto;">
                <form id="EditForm" method="post" enctype="multipart/form-data">
                <div id="ErrMesList">
                    <div id="ErrMesListContent">
                        表单数据验证有误，请修改
                    </div>
                </div>
                <div class="easyui-panel" title="基本信息" style="width:100%;height:100px;">
                    <table class="formtable">
                        <tbody>

                        <tr>
                            <th>
                                角色名：
                            </th>
                            <td>
                                <input name="name" type="text" value="<?php echo $editing['name']; ?>" required/>
                            </td>
                            <th>

                            </th>
                            <td></td>
                            <th></th>
                            <td><input type="hidden" name="id" value="<?php echo $editing['role_id'];?>"></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="hr"></div>
                <div class="easyui-panel" title="对应权限" style="width: 100%; height: 700px;">
                    <table class="formtable">
                        <tr>
                            <td style="width: 330px;  vertical-align: top">
                                <table id="roleList"></table>
                            </td>
                            <td style="width:3px;"></td>
                            <td style="vertical-align: top">
                                <table id="rightList">
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>

                </div>
                </form>
            </div>
<?php $this->load->view('_edit');?>
<?php $this->load->view('_footer');?>

<?php $this->load->view('_header');?>
            <div class="mvctool">
                <input id="txtQuery" type="text" class="searchText"/>
                <a id="btnQuery" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-search" style="font-size:14px"></span><span style="font-size:12px">查询</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnCreate" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-plus" style="font-size:14px"></span><span style="font-size:12px">新建</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnEdit" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-edit" style="font-size:14px"></span><span style="font-size:12px">编辑</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnDetails" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-list" style="font-size:14px"></span><span style="font-size:12px">详细</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnDelete" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-trash" style="font-size:14px"></span><span style="font-size:12px">删除</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnReSet" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-key" style="font-size:14px"></span><span style="font-size:12px">修改密码</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnAllot" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-user-plus" style="font-size:14px"></span><span style="font-size:12px">授权</span></span></a>
            </div>
            <div id="modalwindow" class="easyui-window" data-options="modal:true,closed:true,minimizable:false,shadow:false"></div>
            <table id="List"></table>
            <div id="Pager"></div>
            <script type="text/javascript">
                $(function () {

                    $('#List').datagrid({
                        url: '/admin/admin_user/index/method/json',
                        width: SetGridWidthSub(10),
                        methord: 'post',
                        height: SetGridHeightSub(39),
                        fitColumns: true,
                        sortName: 'user_id',
                        sortOrder: 'desc',
                        idField: 'user_id',
                        pageSize: 15,
                        pageList: [15, 20, 30, 40, 50],
                        pagination: true,
                        striped: true, //奇偶行是否区分
                        singleSelect: true,//单选模式
                        columns: [[
                            { field: 'user_id', title: 'ID', width: 25, hidden: true },
                            { field: 'account', title: '账号', width: 55 },
                            { field: 'name', title: '姓名', width: 55 },
                            { field: 'icon', title: '头像', width: 55, formatter: function (value) {return value?"<img src='<?php echo UPLOADS?>"+value+"' style='display:block;width: 100px;height: 100px;'/>":"<img src='<?php echo ASSETS.'Content/Images/NotPic.jpg';?>' style='display:block;width: 100px;height: 100px;'/>"} },
                            { field: 'sex', title: '性别', width: 55 },
                            { field: 'tel', title: '电话', width: 55 },
                            { field: 'email', hidden: true, title: 'Email', width: 55 },
                            { field: 'address', hidden: true, title: '详细地址', width: 55 },
                            {field: 'ustatus', title: '状态', width: 35,align:'center', formatter: function (value) {return EnableFormatter(value)}},
                            { field: 'created_at', title: '创建时间', width: 75 ,formatter: function (value) {return date("Y-m-d H:i:s",value)}},
                            { field: 'updated_at', title: '修改时间', width: 55 ,formatter: function (value) {return date("Y-m-d H:i:s",value)}},
                            { field: 'role_name', title: '拥有角色', width: 255 }
                        ]]
                    });
                });
            </script>
            <script type="text/javascript">
                $(function () {
                    $(window).resize(function () {
                        $('#List').datagrid('resize', {

                        }).datagrid('resize', {
                            width: $(window).width() - 10,
                            height: SetGridHeightSub(39)
                        });
                    });

                });
            </script>
            <script type="text/javascript">
                //ifram 返回
                function frameReturnByClose() {
                    $("#modalwindow").window('close');
                }
                function frameReturnByReload(flag) {
                    if (flag)
                        $("#List").datagrid('load');
                    else
                        $("#List").datagrid('reload');
                }
                function frameReturnByMes(mes) {
                    $.messageBox3s('提示信息', mes);
                }
                $(function () {
                    $("#btnReSet").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {

                            var user_id = row.user_id;
                            $.messager.prompt('初始化密码', row.name + '的密码:', function (r) {
                                if (r == "" || r.length < 6) {
                                    $.messageBox3s('警告信息', '请输入一个5位以上的密码');
                                }
                                else {
                                    $.post("/admin/admin_user/change_password", { id: user_id, password: r }, function (data) {
                                        if (data.status) {
                                            $.messageBox3s('提示信息',data.info);
                                        }
                                        else {
                                            $.messageBox3s('提示信息', data.info);
                                        }
                                    }, "json");
                                }
                            });
                        } else {$.messageBox3s('提示信息','请至少选择一项'); }
                    });
                    $("#btnCreate").click(function () {
                        window.parent.addTab("添加账户", "/admin/admin_user/add", "fa fa-plus");
                    });
                    $("#btnEdit").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {
                            window.parent.addTab("编辑"+row.name+"的基本信息", "/admin/admin_user/edit/id/" + row.user_id , "fa fa-edit");
                        } else { $.messageBox3s('提示信息', '请至少选择一项'); }
                    });
                    $("#btnDetails").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {
                            $("#modalwindow").html("<iframe width='100%' height='100%' scrolling='auto' frameborder='0' src='/admin/admin_user/detail/id/" + row.user_id+"'></iframe>");
                            $("#modalwindow").window({ title: '详细信息', width: 720, height: 400, iconCls: 'fa fa-list' }).window('open');
                        } else { $.messageBox3s('提示信息', '请至少选择一项'); }
                    });
                    $("#btnQuery").click(function () {
                        var queryStr = $("#txtQuery").val();
                        $("#List").datagrid("load", { queryStr: queryStr});
                    });
                    $("#btnDelete").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {
                            $.messager.confirm('删除确认', '是否删除此数据', function (r) {
                                if (r) {
                                    $.post("/admin/admin_user/delete/id/" + row.user_id, function (data) {
                                        if (data.status == 1)
                                            $("#List").datagrid('reload');
                                        $.messageBox3s('提示信息',data.info);
                                    }, "json");

                                }
                            });
                        } else {$.messageBox3s('提示信息', '请至少选择一项'); }
                    });
                    $("#btnAllot").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {

                            $("#modalwindow").html("<iframe width='100%'  height='100%' scrolling='auto' frameborder='0' src='/admin/admin_user/getrole/id/" + row.user_id + "'></iframe>");
                            $("#modalwindow").window({ title: '分配权限', width: 720, height: 400, iconCls: 'fa fa-edit' }).window('open');
                        } else { $.messageBox3s('提示信息', '请至少选择一项'); }
                    });
                });
            </script>
<?php $this->load->view('_footer');?>
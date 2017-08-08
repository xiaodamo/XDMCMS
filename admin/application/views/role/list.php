<?php $this->load->view('_header');?>
            <div class="mvctool">
                <input id="txtQuery" type="text" class="searchText"/>
                <a id="btnQuery" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-search" style="font-size:14px"></span><span style="font-size:12px">查询</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnCreate" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-plus" style="font-size:14px"></span><span style="font-size:12px">新建</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnEdit" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-edit" style="font-size:14px"></span><span style="font-size:12px">编辑</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnDelete" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-trash" style="font-size:14px"></span><span style="font-size:12px">删除</span></span></a><div class="datagrid-btn-separator"></div>
            </div>
            <div id="modalwindow" class="easyui-window" data-options="modal:true,closed:true,minimizable:false,shadow:false"></div>
            <table id="List"></table>
            <div id="Pager"></div>
            <script type="text/javascript">
                $(function () {

                    $('#List').datagrid({
                        url: '/admin/role/index/method/json',
                        width: SetGridWidthSub(10),
                        methord: 'post',
                        height: SetGridHeightSub(39),
                        fitColumns: true,
                        sortName: 'role_id',
                        sortOrder: 'desc',
                        idField: 'role_id',
                        pageSize: 15,
                        pageList: [15, 20, 30, 40, 50],
                        pagination: true,
                        striped: true, //奇偶行是否区分
                        singleSelect: true,//单选模式
                        columns: [[
                            { field: 'role_id', title: 'ID', width: 25, hidden: true },
                            { field: 'name', title: '角色名称', width: 55 },
                            { field: 'created_at', title: '创建时间', width: 75 ,formatter: function (value) {return date("Y-m-d H:i:s",value)}},
                            { field: 'updated_at', title: '修改时间', width: 55 ,formatter: function (value) {return date("Y-m-d H:i:s",value)}},
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
                    $("#btnCreate").click(function () {
                        window.parent.addTab("添加角色", "/admin/role/add", "fa fa-plus");
                    });
                    $("#btnEdit").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {
                            window.parent.addTab("编辑"+row.name+"的基本信息", "/admin/role/edit/id/" + row.role_id , "fa fa-edit");
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
                                    $.post("/admin/role/delete/id/" + row.role_id, function (data) {
                                        if (data.status == 1)
                                            $("#List").datagrid('reload');
                                        $.messageBox3s('提示信息',data.info);
                                    }, "json");

                                }
                            });
                        } else {$.messageBox3s('提示信息', '请至少选择一项'); }
                    });
                });
            </script>
<?php $this->load->view('_footer');?>
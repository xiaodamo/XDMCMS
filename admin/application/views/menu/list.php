<?php $this->load->view('_header');?>
<style>.tree-folder,.tree-file{background: url('');vertical-align: middle;overflow:auto;}</style>
            <div class="mvctool">
                <a id="btnCreate" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-plus" style="font-size:14px"></span><span style="font-size:12px">新建</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnEdit" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-edit" style="font-size:14px"></span><span style="font-size:12px">编辑</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnDelete" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-trash" style="font-size:14px"></span><span style="font-size:12px">删除</span></span></a><div class="datagrid-btn-separator"></div>
            </div>
            <div id="modalwindow" class="easyui-window" data-options="modal:true,closed:true,minimizable:false,shadow:false"></div>
            <table id="List"></table>
            <div id="Pager"></div>
            <script type="text/javascript">
                $(function () {

                    $('#List').treegrid({
                        url: '/admin/role/get_auth/2',
                        methord: 'post',
                        height: SetGridHeightSub(39),
                        fitColumns: true,
                        idField: 'id',
                        treeField: 'text',
                        rownumbers: true,
                        showFooter: true,
                        columns: [[
                            { field: 'id', title: 'ID', width: 25, hidden: true },
                            { field: 'text', title: '模块名称', width: 80 },
                            { field: 'url', title: '链接', width: 80 },
                            { field: 'iconCls', title: '图标', width: 55,align:'center',formatter: function(value) {return '<span class="l-btn-text '+value+'"></span>'} },
                            { field: 'sort_order', title: '排序', width: 55,align:'center', },
                            { field: 'is_display', title: '状态', width: 55,align:'center', formatter: function (value) {return EnableFormatter(value)} },
                            { field: 'is_menu', title: '类型', width: 55 ,align:'center', formatter: function (value) {return formatter_ismenu(value)}}
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
                        window.parent.addTab("添加账户", "/admin/menu/add", "fa fa-plus");
                    });
                    $("#btnEdit").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {
                            window.parent.addTab("编辑"+row.text+"的基本信息", "/admin/menu/edit/id/" + row.id , "fa fa-edit");
                        } else { $.messageBox3s('提示信息', '请至少选择一项'); }
                    });
                    $("#btnDelete").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {
                            $.messager.confirm('删除确认', '是否删除此数据', function (r) {
                                if (r) {
                                    $.post("/admin/menu/delete/id/" + row.id, function (data) {
                                        if (data.status == 1)
                                            $("#List").datagrid('reload');
                                        $.messageBox3s('删除失败',data.info);
                                    }, "json");

                                }
                            });
                        } else {$.messageBox3s('提示信息', '请至少选择一项'); }
                    });
                });

            </script>
<?php $this->load->view('_footer');?>
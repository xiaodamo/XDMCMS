<?php $this->load->view('_header');?>
    <style>.tree-folder,.tree-file{vertical-align: middle;overflow:auto;}</style>
            <div class="mvctool">
                <a id="btnCreate" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-plus" style="font-size:14px"></span><span style="font-size:12px">新建</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnEdit" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-edit" style="font-size:14px"></span><span style="font-size:12px">编辑</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnDelete" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-trash" style="font-size:14px"></span><span style="font-size:12px">删除</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnDisplay" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-smile-o" style="font-size:14px"></span><span style="font-size:12px">显示/隐藏</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnReload" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-refresh" style="font-size:14px"></span><span style="font-size:12px">刷新</span></span></a><div class="datagrid-btn-separator"></div>
            </div>
            <div id="modalwindow" class="easyui-window" data-options="modal:true,closed:true,minimizable:false,shadow:false"></div>
            <table id="List"></table>
            <div id="Pager"></div>
            <script type="text/javascript">
                $(function () {

                    $('#List').treegrid({
                        url: '/admin/category/index/method/json',
                        methord: 'post',
                        height: SetGridHeightSub(39),
                        fitColumns: true,
                        idField: 'id',
                        treeField: 'text',
                        showFooter: true,
                        columns: [[
                            { field: 'id', title: 'ID', width: 25 },
                            { field: 'text', title: '栏目名称', width: 80 },
                            { field: 'enname', title: '英文标识', width: 80 },
                            { field: 'img_url', title: '图标', width: 55,align:'center',formatter: function(value) {return '<span class="l-btn-text '+value+'"></span>'} },
                            { field: 'sort_order', title: '排序', width: 55,align:'center', },
                            { field: 'ctype', title: '类型', width: 55,align:'center', formatter: function (value) {return formatter_ctype(value)} },
                            { field: 'cansee', title: '是否显示', width: 55,align:'center', formatter: function (value) {
                            if(parseInt(value)){
                                return '<span style="font-weight:bold;color:green;">显示</span>';
                            }else{
                                return  '<span style="font-weight:bold;color:mediumvioletred;">隐藏</span>';
                            }
                            }},
                            { field: 'is_display', title: '显示', width: 55,align:'center',hidden:true },
                            { field: 'created_at', title: '创建时间', width: 55,align:'center', formatter: function (value) {return date("Y-m-d H:i:s",value)} },
                            { field: 'updated_at', title: '更新时间', width: 55 ,align:'center', formatter: function (value) {return date("Y-m-d H:i:s",value)}}
                        ]]
                    });
                });
            </script>
            <script type="text/javascript">
                $(function () {
                    $(window).resize(function () {
                        $('#List').treegrid('resize', {

                        }).treegrid('resize', {
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
                        $("#List").treegrid('load');
                    else
                        $("#List").treegrid('reload');
                }
                function frameReturnByMes(mes) {
                    $.messageBox3s('提示信息', mes);
                }
                $(function () {
                    $("#btnCreate").click(function () {
                        $("#modalwindow").html("<iframe width='100%' height='100%' scrolling='auto' frameborder='0' src='/admin/category/add'></iframe>");
                        $("#modalwindow").window({ title: '添加栏目', width: 730, height: 500, iconCls: 'fa fa-add',closed:true,minimizable:false,shadow:false }).window('open');
                    });
                    $("#btnEdit").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {

                            $("#modalwindow").html("<iframe width='100%' height='100%' scrolling='auto' frameborder='0' src='/admin/category/edit/id/" + row.id+"'></iframe>");
                            $("#modalwindow").window({ title: "编辑"+row.text+"的基本信息", width: 730, height: 500, iconCls: 'fa fa-edit',closed:true,minimizable:false,shadow:false }).window('open');

                        } else { $.messageBox3s('提示信息', '请至少选择一项'); }
                    });
                    $("#btnDelete").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {
                            $.messager.confirm('删除确认', '是否删除此数据', function (r) {
                                if (r) {
                                    $.post("/admin/category/delete/id/" + row.id, function (data) {
                                        if (data.status == 1)
                                            frameReturnByReload(false);
                                        $.messageBox3s('提示信息',data.info);
                                    }, "json");

                                }
                            });
                        } else {$.messageBox3s('提示信息', '请至少选择一项'); }
                    });
                    $("#btnDisplay").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {
                            var isdisplay = parseInt(row.is_display);
                            var displaytxt = isdisplay?'隐藏':'显示';
                            $.messager.confirm('显示/隐藏确认', '是否'+displaytxt+'此数据', function (r) {
                                if (r) {
                                    $.ajax({
                                        url: "/admin/category/display",
                                        type: "post",
                                        data: {id:row.id,is_display:isdisplay?0:1},
                                        dataType: "json",
                                        success: function (data) {
                                            if(data.status){
                                                $.messageBox3s('操作成功', data.info);
                                                frameReturnByReload(false);
                                            }else{
                                                $.messageBox3s('操作失败', data.info);
                                            }
                                        }
                                    });
                                }
                            });
                        } else {$.messageBox3s('提示信息', '请至少选择一项'); }
                    });
                    $("#btnReload").click(function () {
                        frameReturnByReload(false);
                    });

                });

            </script>
<?php $this->load->view('_footer');?>
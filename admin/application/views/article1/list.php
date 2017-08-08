<?php $this->load->view('_header');?>
<style>.combo{float:left;margin-right:10px;}</style>
            <?php echo $tools?>
            <div id="modalwindow" class="easyui-window" data-options="modal:true,closed:true,minimizable:false,shadow:false"></div>
            <table id="List"></table>
            <div id="Pager"></div>
            <script type="text/javascript">
                var assert = '<?php echo ASSETS?>';
                var upload_dir = '<?php echo UPLOADS?>';
                $(function () {
                    <?php if($option):?>
                    var option = <?php echo json_encode($option,TRUE)?>;
                    <?php endif;?>
                    $('#List').datagrid({
                        onLoadSuccess: function (data) {
                            $('.seeimg').lightBox();
                        },
                        onDblClickRow: function (index,row) {
                            edit_row(row);
                        },
                        url: '/admin/<?php echo $action_name?>/index',
                        width: SetGridWidthSub(10),
                        methord: 'post',
                        height: SetGridHeightSub(39),
                        fitColumns: true,
                        pageSize: 15,
                        pageList: [15, 20, 30, 40, 50],
                        pagination: true,
                        striped: true, //奇偶行是否区分
                        singleSelect: true,//单选模式
                        columns: [<?php echo $lists?>]
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

                function edit_row(row){
                    if (row != null) {

                        $("#modalwindow").html("<iframe width='100%' height='100%' scrolling='auto' frameborder='0' src='/admin/<?php echo $action_name?>/edit/id/" + row.<?php echo $id?>+"'></iframe>");
                        $("#modalwindow").window({ title: "编辑<?php echo $action_title?>ID："+row.<?php echo $id?>+"的基本信息", width: 1000, height: 800, iconCls: 'fa fa-edit',closed:true,minimizable:false,shadow:false }).window('open');

                    } else { $.messageBox3s('提示信息', '请至少选择一项'); }
                }

                $(function () {
                    $("#btnCreate").click(function () {
                        $("#modalwindow").html("<iframe width='100%' height='100%' scrolling='auto' frameborder='0' src='/admin/<?php echo $action_name?>/add'></iframe>");
                        $("#modalwindow").window({ title: '添加<?php echo $action_title?>', width: 1000, height: 800, iconCls: 'fa fa-add',closed:true,minimizable:false,shadow:false }).window('open');
                    });
                    $("#btnEdit").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        edit_row(row);
                    });
                    $("#btnQuery").click(function () {
                        var queryStr = $("#txtQuery").val();
                        var field = $('#field').textbox('getValue');
                        $("#List").datagrid("load", { queryStr: queryStr,field:field});
                    });

                    $(".btnOthers").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {
                            var field = $(this).data('id');
                            var option = $(this).data('option');
                            var name = $(this).data('name');
                            var current = row[field];
                            //console.log(current);return;
                            if(option === ""){
                                var value = $(this).data('value');
                                var txt = name;
                            }else{
                                var option_arr = option.split("|");
                                var name_arr = name.split("/");
                                var index = indexOf(option_arr,current);
                                option_arr.splice(index,1);
                                name_arr.splice(index,1);
                                var value = option_arr[0];
                                var txt = name_arr[0];
                            }


                            $.messager.confirm(name+'确认', '是否'+txt+'此数据', function (r) {
                                if (r) {
                                    $.ajax({
                                        url: "/admin/<?php echo $action_name?>/tangle",
                                        type: "post",
                                        data: {id:row.<?php echo $id?>,field:field,value:value},
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
                    $("#btnDelete").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {
                            $.messager.confirm('删除确认', '是否删除此数据', function (r) {
                                if (r) {
                                    $.post("/admin/<?php echo $action_name?>/delete/id/" + row.<?php echo $id?>, function (data) {
                                        if (data.status == 1)
                                            $("#List").datagrid('reload');
                                        $.messageBox3s('提示信息',data.info);
                                    }, "json");

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
<?php $this->load->view('_header');?>


            <div class="mvctool bgb">

                <a id="btnSave" style="float: left;" class="l-btn l-btn-plain">
                <span class="l-btn-left"><span class="l-btn-text fa fa-save" style="font-size:14px">
                </span><span style="font-size:12px">保存</span></span></a>


            </div>

            <div class="pd3">
                <table id="UserList"></table>
            </div>
            <script type="text/javascript">

                $(function () {




                    $('#UserList').datagrid({
                        url: '/admin/role/index/method/json',
                        width: SetGridWidthSub(6),
                        methord: 'post',
                        height: SetGridHeightSub(41),
                        fitColumns: true,
                        sortName: 'role_id',
                        idField: 'role_id',
                        pageSize: 12,
                        pageList: [12, 20, 30, 40, 50],
                        pagination: true,
                        striped: true, //奇偶行是否区分
                        singleSelect: true,//单选模式
                        columns: [[
                            { field: 'role_id', title: 'ID', width: 80, hidden: true },
                            { field: 'name', title: '角色名称', width: 120 },
                            { field: 'Flag', title: '选择', width: 80,
                                //调用formater函数对列进行格式化，使其显示单选按钮（所有单选按钮name属性设为统一，这样就只能有一个处于选中状态）
                                formatter: function (value, row, index) {

                                    if (row.IsEnable == 1) {
                                        //如果属性值等于1，则处于选中状态（默认表格中所有单选按钮最多只能有一个值等于1）
                                        var s = '<input name="Flag" type="radio" /> ';

                                    }
                                    else {
                                        var s = '<input name="Flag" type="radio" /> ';
                                    }
                                    return s;
                                }
                            }
                        ]],
                        onLoadSuccess: function () {
                            var rows = $("#UserList").datagrid("getRows");
                            for (var i = 0; i < rows.length; i++) {
                                //获取每一行的数据
                                $('#UserList').datagrid('beginEdit', i);
                            }
                        }
                    });
                });
            </script>

            <script type="text/javascript">
                $(function () {
                    $("#btnSave").click(function () {

                        var rows = $("#UserList").datagrid("getRows"); //这段代码是获取当前页的所有行。
                        var data = new Array();
                        for (var i = 0; i < rows.length; i++) {
                            var setFlag = $("td[field='Flag'] input").eq(i).prop("checked");
                            if (setFlag)//判断是否有作修改
                            {
                                data.push(rows[i].role_id);
                            }
                        }
                        var roleIds = data.join();

                        if(roleIds == ''){
                            $.messageBox3s('提示信息', '请至少选择一项');
                            return false;
                        }

                        //提交数据库
                        $.post("/admin/admin_user/change_role", { id: '<?php echo $editing['user_id']?>', roleIds: roleIds },
                            function (data) {
                                if (data.status == 1) {
                                    window.parent.frameReturnByMes(data.info);
                                    window.parent.frameReturnByReload(true);
                                    window.parent.frameReturnByClose()
                                }
                                else {
                                    window.parent.frameReturnByMes(data.info);
                                }
                            }, "json");
                    });

                });
            </script>
<?php $this->load->view('_footer');?>
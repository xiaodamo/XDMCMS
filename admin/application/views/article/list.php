<?php $this->load->view('_header');?>
<style>.combo{float:left;margin-right:10px;}</style>
            <div class="mvctool">
                <input id="category" name="cid" value="" style="width:200px;height:24px;">
                <input id="txtQuery" type="text" class="searchText"/>
                <a id="btnQuery" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-search" style="font-size:14px"></span><span style="font-size:12px">查询</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnCreate" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-plus" style="font-size:14px"></span><span style="font-size:12px">新建</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnEdit" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-edit" style="font-size:14px"></span><span style="font-size:12px">编辑</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnReview" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-check" style="font-size:14px"></span><span style="font-size:12px">审核通过</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnNoReview" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-close" style="font-size:14px"></span><span style="font-size:12px">审核不通过</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnRecommand" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-thumbs-o-up" style="font-size:14px"></span><span style="font-size:12px">推荐/不推荐</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnDelete" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-trash" style="font-size:14px"></span><span style="font-size:12px">删除</span></span></a><div class="datagrid-btn-separator"></div>
                <a id="btnReload" style="float: left;" class="l-btn l-btn-plain"><span class="l-btn-left"><span class="l-btn-text fa fa-refresh" style="font-size:14px"></span><span style="font-size:12px">刷新</span></span></a><div class="datagrid-btn-separator"></div>
            </div>
            <div id="modalwindow" class="easyui-window" data-options="modal:true,closed:true,minimizable:false,shadow:false"></div>
            <table id="List"></table>
            <div id="Pager"></div>
            <script type="text/javascript">
                $(function () {

                    $('#List').datagrid({
                        onLoadSuccess: function (data) {
                            //点击预览查看大图
                            $(".seeimg").lightBox();
                        },
                        url: '/admin/article/index/method/json',
                        width: SetGridWidthSub(10),
                        methord: 'post',
                        height: SetGridHeightSub(39),
                        fitColumns: true,
                        sortName: 'id',
                        sortOrder: 'sort_order',
                        idField: 'id',
                        pageSize: 15,
                        pageList: [15, 20, 30, 40, 50],
                        pagination: true,
                        striped: true, //奇偶行是否区分
                        singleSelect: true,//单选模式
                        columns: [[
                            { field: 'id', title: 'ID', width: 25, hidden: true },
                            { field: 'title', title: '文章标题', width: 55 },
                            { field: 'img_url', title: '缩略图', width: 55,formatter:function(value){
                                var img_url = value==""?"<?php echo ASSETS.'Content/Images/nopic.gif';?>":"<?php echo UPLOADS?>"+value;
                                return '<a href="'+img_url+'" class="seeimg"><img height="100" width="100"  src="'+img_url+'"/></a>';
                            }},
                            { field: 'catname', title: '栏目', width: 55 ,align:'center', },
                            { field: 'tarname', title: '标签', width: 55 ,align:'center', },
                            { field: 'sort_order', title: '排序', width: 55 ,align:'center', },
                            { field: 'click_nums', title: '点击量', width: 55 ,align:'center',sortable:true},
                            { field: 'is_recommand', title: '是否推荐', width: 55, hidden:true ,align:'center', },
                            { field: 'recommand', title: '是否推荐', width: 55, align:'center', formatter: function (value) {
                                if(parseInt(value)){
                                    return '<span style="font-weight:bold;color:green;">推荐</span>';
                                }else{
                                    return  '<span style="font-weight:bold;color:mediumvioletred;">未推荐</span>';
                                }
                            }},
                            { field: 'status', title: '状态', width: 55,hidden:true,align:'center' },
                            { field: 'review', title: '审核状态', width: 55,align:'center', formatter: function (value) {
                                if(parseInt(value)==1){
                                    return '<span style="font-weight:bold;color:darkorange;">未审核</span>';
                                }else if(parseInt(value)==2){
                                    return  '<span style="font-weight:bold;color:green;">审核通过</span>';
                                }else if(parseInt(value)==3){
                                    return  '<span style="font-weight:bold;color:mediumvioletred;">审核不通过</span>';
                                }else{
                                    return '';
                                }
                            }},
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

                function doreview(reviewvalue) {
                    var row = $('#List').datagrid('getSelected');
                    if (row != null) {
                        var review = parseInt(reviewvalue);
                        var reviewtxt = review?'不通过':'通过';
                        $.messager.confirm('审核通过/不通过确认', '是否'+reviewtxt+'此数据', function (r) {
                            if (r) {
                                $.ajax({
                                    url: "/admin/article/review",
                                    type: "post",
                                    data: {id:row.id,review:review?0:1},
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
                }
                $(function () {
                    $("#btnCreate").click(function () {
                        $("#modalwindow").html("<iframe width='100%' height='100%' scrolling='auto' frameborder='0' src='/admin/article/add'></iframe>");
                        $("#modalwindow").window({ title: '添加文章', width: 1000, height: 800, iconCls: 'fa fa-add',closed:true,minimizable:false,shadow:false }).window('open');
                    });
                    $("#btnEdit").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {

                            $("#modalwindow").html("<iframe width='100%' height='100%' scrolling='auto' frameborder='0' src='/admin/article/edit/id/" + row.id+"'></iframe>");
                            $("#modalwindow").window({ title: "编辑文章ID："+row.id+"的基本信息", width: 1000, height: 800, iconCls: 'fa fa-edit',closed:true,minimizable:false,shadow:false }).window('open');

                        } else { $.messageBox3s('提示信息', '请至少选择一项'); }
                    });
                    $("#btnQuery").click(function () {
                        var queryStr = $("#txtQuery").val();
                        var cid = $("input[name='cid']").val();
                        $("#List").datagrid("load", { queryStr: queryStr,cid:cid});
                    });
                    $("#btnReview").click(function () {
                        doreview(0);
                    });
                    $("#btnNoReview").click(function () {
                        doreview(1);
                    });

                    $("#btnRecommand").click(function () {
                        var row = $('#List').datagrid('getSelected');
                        if (row != null) {
                            var recommand = parseInt(row.is_recommand);
                            var recommandtxt = recommand?'不推荐':'推荐';
                            $.messager.confirm('推荐/不推荐确认', '是否'+recommandtxt+'此数据', function (r) {
                                if (r) {
                                    $.ajax({
                                        url: "/admin/article/recommand",
                                        type: "post",
                                        data: {id:row.id,recommand:recommand?0:1},
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
                                    $.post("/admin/article/delete/id/" + row.id, function (data) {
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

                    $('#category').combotree({
                        url: '/admin/category/index/method/json/type/1',
                    });

                });
            </script>
<?php $this->load->view('_footer');?>
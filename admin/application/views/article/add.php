<?php $this->load->view('_header');?>
    <script type="text/javascript">
        $(function () {

            $("#icon").click(function () {
                //$("#selIcon").toggle();
                openTarget();
            });

            $("#btnSave").click(function () {
                if (!$("form").valid()) {
                    $.messageBox3s('提示', "表单数据验证有误，请修改");
                    return;
                }
                if ($("form").valid()) {
                        $("#EditForm").ajaxSubmit({
                            type: "POST",//提交类型
                            dataType: "json",//返回结果格式
                            url: "/admin/article/save",//请求地址
                            data: $("#EditForm").serialize(),//请求数据
                            success: function (data) {//请求成功后的函数
                                if(data.status){
                                    $.messageBox3s('操作成功', data.info);
                                    window.parent.frameReturnByClose();
                                    window.parent.frameReturnByReload(false);
                                }else{
                                    $.messageBox3s('操作失败', data.info);
                                }
                            },
                            error: function (data) { alert(data.info); },//请求失败的函数
                            async: true
                        });
                }
                return false;
            });


            $("#btnReturn").click(function () {
                window.parent.frameReturnByClose();
            });

            $('#cid').combotree({
                url: '/admin/category/index/method/json',
            });

        });

        function openTarget(){

            $("#modalwindow").html("<iframe width='100%' height='100%' scrolling='auto' frameborder='0' src='/admin/target/get_target/<?php echo $editing['id'];?>'></iframe>");
            $("#modalwindow").window({ title: '选择标签', width: 600, height: 350, iconCls: 'fa fa-list',closed:true,minimizable:false,shadow:false }).window('open');

        }

        function selectTar(ids,names){
            $("#icon").text(names.join(","));
            $("#Iconic").val(ids.join(","));
            $("#modalwindow").window('close');
        }

        function closetarlist(){
            $("#modalwindow").window('close');
        }

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
        <table class="formtable" >
            <tbody>
            <tr>
                <th>
                    所属栏目：
                </th>
                <td>
                    <input id="cid" name="cid" value="<?php echo $editing['cid']?$editing['cid']:'';?>" style="width:200px;height:28px;" required="true">
                </td>
                <td rowspan="7" style="">
                    <div id="localImag">
                        <img class="icon" id="preview" src="<?php if($editing['img_url']) echo UPLOADS.$editing['img_url'];else echo ASSETS.'Content/Images/nopic.gif';?>" style="display: block; width: 140px; height: 140px;" />
                    </div><br />
                    <a href="javascript:$('#FileUpload').trigger('click');void(0);" class="files"></a>
                    <input type="file" class="displaynone" id="FileUpload" name="img_url" onchange="setImagePreview();" />
                    <span class="uploading">请稍候...</span>
                </td>
            </tr>
            <tr>
                <th>
                    文章标题：
                </th>
                <td>
                    <input  class="easyui-validatebox" name="title"  type="text" value="<?php echo $editing['title']; ?>"  required="true"/>
                </td>
            </tr>
            <tr>
                <th>
                    作者：
                </th>
                <td>
                    <input  class="easyui-validatebox" name="author" type="text" value="<?php echo $editing['author']; ?>" required="true"/>
                </td>
            </tr>
            <tr>
                <th>
                    标签：
                </th>
                <td>
                    <?php $tars = array()?>
                    <?php foreach ($editing['tars'] as $v){
                        $tars[$v['id']] = $v['name'];
                    }?>
                    <input value="<?php echo $tars?implode(",",array_keys($tars)):''?>" name="tids" id="Iconic" type="hidden" />
                    <div id="icon" title="点我选取标签" style="cursor:pointer" class="fa fa-hand-pointer-o">
                        <?php echo $tars?implode(",",array_values($tars)):''?>
                    </div>
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
                    点击量：
                </th>
                <td>
                    <input name="click_nums" type="text" value="<?php echo $editing['click_nums']; ?>" />
                </td>
            </tr>

            <tr>
                <th>
                    是否推荐：
                </th>
                <td>
                    <input type="checkbox" name="is_recommand" value="1" <?php echo $editing['is_recommand']?'checked="checked"':''?> />
                    <input type="hidden" name="id" value="<?php echo $editing['id']; ?>" />
                </td>
            </tr>

            <tr>
                <th>
                    审核状态：
                </th>
                <td>
                    <input name="status"  <?php if($editing['status']==1) echo 'checked="checked"';?> type="radio" value="1" />未审核
                    <input name="status" <?php if($editing['status']==2) echo 'checked="checked"';?> type="radio" value="2" />审核通过
                    <input name="status" <?php if($editing['status']==3) echo 'checked="checked"';?> type="radio" value="3" />审核不通过
                </td>
            </tr>

            <tr>
                <th>
                    创建日期：
                </th>
                <td>
                    <input name="created_at" type="text" onclick="WdatePicker()" value = "<?php echo $editing['created_at']?date("Y-m-d",$editing['created_at']):'';?>" style = "width:105px"/>
                </td>
            </tr>

            </tbody>
        </table>

        <table class="formtable" >
            <tbody>
            <tr>
                <th>
                    内容：
                </th>
                <td colspan="2">
                    <script type="text/javascript" charset="utf-8" src="<?php echo ASSETS?>Scripts/ueditor1431/ueditor.config.js"></script>
                    <script type="text/javascript" charset="utf-8" src="<?php echo ASSETS?>Scripts/ueditor1431/_examples/editor_api.js"></script>
                    <script type="text/javascript" charset="utf-8" src="<?php echo ASSETS?>Scripts/ueditor1431/lang/zh-cn/zh-cn.js"></script>
                    <script type="text/plain" id="ueditor" name="content" style="width:900px;height:330px;z-index:-1;" ><?php echo $editing['content']; ?></script>
                    <script type="text/javascript">
                        UE.getEditor('ueditor', {
                            setContent:"1",
                            lang:'zh-cn'
                        });
                    </script>
                </td>
            </tr>

            </tbody>
        </table>
    </form>
<?php $this->load->view('_edit');?>
<?php $this->load->view('_footer');?>
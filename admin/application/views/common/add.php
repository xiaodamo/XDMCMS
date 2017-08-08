<?php $this->load->view('_header');?>
    <link rel="stylesheet" type="text/css" href="<?php echo ASSETS;?>Scripts/diyUpload/css/webuploader.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ASSETS;?>Scripts/diyUpload/css/diyUpload.css">
    <script src="<?php echo ASSETS;?>Scripts/diyUpload/js/webuploader.html5only.min.js"></script>
    <script src="<?php echo ASSETS;?>Scripts/diyUpload/js/diyUploadimg.js"></script>
    <script type="text/javascript">
        $(function () {

            $("#btnSave").click(function () {
                if ($("#EditForm").form('validate')) {
                        $("#EditForm").ajaxSubmit({
                            type: "POST",//提交类型
                            dataType: "json",//返回结果格式
                            url: "/admin/<?php echo $action_name?>/save",//请求地址
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
                }else{
                    $.messageBox3s('提示', "表单数据验证有误，请修改");
                    return false;
                }

                return false;
            });


            $("#btnReturn").click(function () {
                window.parent.frameReturnByClose();
            });

            $('#test').diyUpload({
                url:"/admin/imageupload/index",
                success:function( data ) {
                    console.info( data );
                    if(data.name){
                        $('#preview_img').hide();
                        $("#box").append("<input type='hidden'  name='imgs[]' value='"+data.name+"' >");
                    }
                },
                error:function( err ) {
                    console.info( err );
                },
                buttonText : '上传图片',
                //fileNumLimit:4,
                //fileSingleSizeLimit:500 * 1024,
            });

        });



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
                <?php echo $attr_info;?>
            </tbody>
        </table>
    </form>
<?php $this->load->view('_edit');?>
<?php $this->load->view('_footer');?>
<?php $this->load->view('_header');?>
            <script type="text/javascript">
                $(function () {

                    $("#btnSave").click(function () {
                        if (!$("form").valid()) {
                            $.messageBox3s('提示', $("#ErrMesList").html());
                            return;
                        }
                        if ($("form").valid()) {
                                $("#EditForm").ajaxSubmit({
                                    type: "POST",//提交类型
                                    dataType: "json",//返回结果格式
                                    url: "/admin/admin_user/save",//请求地址
                                    data: $("#EditForm").serialize(),//请求数据
                                    success: function (data) {//请求成功后的函数
                                        if(data.status){
                                            $.messageBox3s('操作成功', data.info);
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
                });

                function frameReturnByReload(flag) {
                    if (flag)
                        $("#List").datagrid('load');
                    else
                        $("#List").datagrid('reload');
                }

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
                <div class="easyui-panel" title="账号信息" style="width:100%;height:150px;">
                    <table class="formtable">
                        <tbody>

                        <tr>
                            <th>
                                账户名：
                            </th>
                            <td>
                                <input name="account" type="text" value="<?php echo $editing['account']; ?>" <?php if (!empty($editing['account'])){?> readonly = "true"  <?php }?> required/>
                            </td>
                            <th>
                               密码：
                            </th>
                            <td><input name="password" type="password" value="" /></td>
                            <th></th>
                            <td><input type="hidden" name="id" value="<?php echo $editing['user_id'];?>"></td>
                        </tr>

                        <tr>
                            <th>
                                角色：
                            </th>
                            <td>
                                <select id="role" name="role" required>
                                    <?php foreach($role_list as $key=>$item){ ?>
                                        <option value="<?php echo $item['role_id']?>" <?php if($item['role_id']==$editing['role_id']) {?> selected<?php }?> ><?php echo $item['name']?></option>
                                    <?php }?>
                                </select>
                            </td>
                            <th>
                                启用帐户：
                            </th>
                            <td>
                                <input type="checkbox" name="ustatus" value="1" <?php echo $editing['ustatus']?'checked="checked"':''?> <?php if($editing['user_id']==1) echo "disabled"?>/>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="hr"></div>
                <div class="easyui-panel" title="基本资料" style="width: 100%; height: 230px;">
                    <table class="formtable">

                        <tr>
                            <th>
                                用户名：
                            </th>
                            <td>
                                <input name="name" type="text" value="<?php echo $editing['name']; ?>" required/>
                            </td>
                            <th>
                                性别：
                            </th>
                            <td>
                                <input name="sex"  <?php if($editing['sex']=='男') echo 'checked="checked"';?> value="男" type="radio" />男
                                <input name="sex" <?php if($editing['sex']=='女') echo 'checked="checked"';?> type="radio" value="女" />女
                            </td>
                            <td rowspan="6" style=" border-left:dashed 1px #ccc; padding-left:30px">
                                <div id="localImag_1">
                                <img class="icon" id="preview_1" src="<?php if($editing['icon']) echo UPLOADS.$editing['icon'];else echo ASSETS.'Content/Images/NotPic.jpg';?>" style="display: block; width: 140px; height: 140px;" />
                                </div><br />
                                <a href="javascript:$('#FileUpload_1').trigger('click');void(0);" class="files"></a>
                                <input type="file" class="displaynone" id="FileUpload_1" name="icon" onchange="setImagePreview(1);" />
                                <span class="uploading">请稍候...</span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                生日：
                            </th>
                            <td>
                                <input name="birthday" type="text" onclick="WdatePicker()" value = "<?php echo $editing['birthday'];?>" style = "width:105px"/>
                            </td>
                            <th>
                               加入日期：
                            </th>
                            <td>
                                <input name="created_at" type="text" onclick="WdatePicker()" value = "<?php if($editing['created_at']) echo date("Y-m-d",$editing['created_at']);?>" style = "width:105px"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="hr"></div>
                <div class="easyui-panel" title="联系方式" style="width:100%;height:230px;">
                    <table class="formtable">
                        <tbody>
                        <tr>
                            <th>
                                手机：
                            </th>
                            <td>
                                <input name="tel" type="text" value="<?php echo $editing['tel'];?>"/>
                            </td>
                            <th>
                                Email：
                            </th>
                            <td>
                                <input name="email" type="text" value="<?php echo $editing['email'];?>"/>
                            </td>
                        </tr>
                        <tr>
                                <?php
                                $data['province_selected'] = $editing['province_id'];
                                $data['city_selected'] = $editing['city_id'];
                                $data['district_selected'] = $editing['district_id'];
                                $this->load->view('widget/district_select', $data);
                                ?>
                        </tr>
                        <tr>
                            <th>
                                地址：
                            </th>
                            <td colspan="5">
                                <input name="address" type="text" value="<?php echo $editing['address'];?>" style = "width:250px;"/>
                            </td>

                        </tr>
                        </tbody>
                    </table>

                </div>
                </form>
            </div>
<?php $this->load->view('_edit');?>
<?php $this->load->view('_footer');?>
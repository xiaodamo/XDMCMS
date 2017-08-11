<?php $this->load->view('_header');?>
<style>
    .error{color:red;padding-left:20px;}
    #autoForm .autofield{width:100px;}
    #autoForm .fieldform{margin-bottom: 50px;}
</style>
    <script type="text/javascript">
        $(function () {

            $("#btnReturn").click(function () {
                window.parent.frameReturnByClose();
            });

            $('#tt').tabs({
                border:false,
                onSelect:function(title,index){
                }
            });

            $("#enname").blur(function(){
                if($(this).val()=="") return false;
                ajax_check_moudle(this);
            });
            
            $(".cname").change(function(){
                ajax_field_config(this);
                ajax_check_moudle(this);
            });

            $("#btnSave1").click(function () {
                if ($("#configForm").form('validate')) {
                    $.ajax({
                        url: "/admin/make_code/save_fromconfig",
                        type: "post",
                        data: $("#configForm").serialize(),
                        dataType: "json",
                        success: function (data) {
                            if(data.error.length === 0){
                                $.messageBox3s('操作成功', '生成相关代码成功！');
                            }else{
                                $.messageBox3s('操作失败', data.error.join(','));
                            }
                        }
                    });
                }else{
                    $.messageBox3s('提示', "表单数据验证有误，请修改");
                    return false;
                }

                return false;
            });

            $("#btnSave2").click(function () {
                if ($("#autoForm").form('validate')) {
                    $(".fieldform").find("input[type='checkbox']").each(function (index) {
                        var id = $(this).parents(".fieldform").find("input[name='field_enname[]']").val();
                        $(this).val(id);
                    });
                    $.ajax({
                        url: "/admin/make_code/save_fromauto",
                        type: "post",
                        data: $("#autoForm").serialize(),
                        dataType: "json",
                        success: function (data) {
                            if(data.error.length === 0){
                                $.messageBox3s('操作成功', '生成相关代码成功！');
                            }else{
                                $.messageBox3s('操作失败', data.error.join(','));
                            }
                        }
                    });

                }else{
                    $.messageBox3s('提示', "表单数据验证有误，请修改");
                    return false;
                }

                return false;
            });

            $('#add_field').bind('click', function(){
                var field_txt = '<table class="formtable fieldform"><tbody><tr><td>';
                field_txt += '<input name="field_enname[]" type="text" class="easyui-validatebox autofield" data-options="required:true" placeholder="字段名(英文)" value="" />';
                field_txt += '</td><td><input name="field_name[]" type="text" class="easyui-validatebox autofield" data-options="required:true" placeholder="字段名(中文)" value="" /></td><td>';
                field_txt += '<select name="field_type[]" class="easyui-validatebox" data-options="required:true"><option value="">请选择字段类型</option>';
                field_txt += '<option value="char">char</option>';
                field_txt += '<option value="varchar">varchar</option>';
                field_txt += '<option value="int">int</option>';
                field_txt += '<option value="mediumint">mediumint</option>';
                field_txt += '<option value="smallint">smallint</option>';
                field_txt += '<option value="tinyint">tinyint</option>';
                field_txt += '<option value="decimal">decimal</option>';
                field_txt += '<option value="double">double</option>';
                field_txt += '<option value="enum">enum</option>';
                field_txt += '<option value="float">float</option>';
                field_txt += '<option value="text">text</option>';
                field_txt += '<option value="date">date</option>';
                field_txt += '<option value="datetime">datetime</option>';
                field_txt += '<option value="time">time</option>';
                field_txt += '<option value="timestamp">timestamp</option>';
                field_txt += '</select></td><td><input name="field_option[]" type="text" class="autofield" placeholder="字段附加项" value="" /></td><td>';
                field_txt += '字段长度:<input name="field_size[]" class="easyui-numberspinner autofield" data-options="min:1" value="" /></td><td>';
                field_txt += '<input name="field_default[]" type="text" class="autofield" placeholder="默认值" value="" /></td><td>';
                field_txt += '主键？<input name="is_pri[]" type="checkbox" value="1" /></td><td>';
                field_txt += 'unsigned？<input name="is_unsign[]" type="checkbox" value="1" /></td></tr>';
                field_txt += '<tr><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td></tr><tr><td>';
                field_txt += '<select name="html_type[]" class="easyui-validatebox" data-options="required:true"><option value="">请选择页面控件类型</option>';
                field_txt += '<option value="text">text</option>';
                field_txt += '<option value="password">password</option>';
                field_txt += '<option value="number">number</option>';
                field_txt += '<option value="int">int</option>';
                field_txt += '<option value="smallint">smallint</option>';
                field_txt += '<option value="tinyint">tinyint</option>';
                field_txt += '<option value="decimal">decimal</option>';
                field_txt += '<option value="combotree">combotree</option>';
                field_txt += '<option value="textarea">textarea</option>';
                field_txt += '<option value="ueditor">ueditor</option>';
                field_txt += '<option value="radio">radio</option>';
                field_txt += '<option value="checkbox">checkbox</option>';
                field_txt += '<option value="singlecheck">singlecheck</option>';
                field_txt += '<option value="select">select</option>';
                field_txt += '<option value="image">image</option>';
                field_txt += '<option value="mult_image">mult_image</option>';
                field_txt += '<option value="date">date</option>';
                field_txt += '<option value="datetime">datetime</option>';
                field_txt += '</select></td><td>';
                field_txt += '<select name="list_display[]" >';
                field_txt += '<option value="1">列表页显示</option>';
                field_txt += '<option value="0">列表页不显示</option>';
                field_txt += '<option value="-1">列表页隐藏</option>';
                field_txt += '</select></td><td>';
                field_txt += '<input name="field_tools[]" type="text" class="autofield" placeholder="列表工具栏" value="" /></td><td>';
                field_txt += '列表排序？<input name="is_order[]" type="checkbox" value="1" /></td><td>';
                field_txt += '搜索项？<input name="is_search[]" type="checkbox" value="1" /></td><td>';
                field_txt += '<select name="edit_display[]" >';
                field_txt += '<option value="1">编辑页显示</option>';
                field_txt += '<option value="0">编辑页不显示</option>';
                field_txt += '<option value="-1">编辑页隐藏</option>';
                field_txt += '</select></td><td>';
                field_txt += '<input name="field_rule[]" type="text" class="autofield" placeholder="编辑页验证规则" value="" /></td><td>';
                field_txt += '<a class="easyui-linkbutton" iconCls="fa fa-trash" onClick="del_point(this)"></a></td></tr></tbody></table>';
                $("#selectbuild").before(field_txt);
                $(".fieldform .easyui-validatebox").validatebox({required:true});
                $.parser.parse();
            });

        });

        function del_point(my){
            var fieldform = $(".fieldform");
            if(fieldform.length===1){
                $.messageBox3s('提示', "请至少保留一个字段！");
                return false;
            }
            $(my).parents(".fieldform").remove();
        }

        function ajax_check_moudle(obj){
            var cname = $(obj).val();
            var parent = $(obj).data('form');
            $.ajax({
                url:"/admin/make_code/check_module",
                type:'post',
                data:{cname:cname},
                dataType:'json',
                success:function(data){
                    if(data.status){
                        if(data.table!=""){
                            $(parent+" .maketable").removeAttr("checked");
                            $(parent+" .maketable").next().html(data.table);
                        }else{
                            $(parent+" .maketable").prop("checked",true);
                            $(parent+" .maketable").next().html("");
                        }
                        if(data.model!=""){
                            $(parent+" .makemodel").removeAttr("checked");
                            $(parent+" .makemodel").next().html(data.model);
                        }else{
                            $(parent+" .makemodel").prop("checked",true);
                            $(parent+" .makemodel").next().html("");
                        }
                        if(data.controller!=""){
                            $(parent+" .makecontroller").removeAttr("checked");
                            $(parent+" .makecontroller").next().html(data.controller);
                        }else{
                            $(parent+" .makecontroller").prop("checked",true);
                            $(parent+" .makecontroller").next().html("");
                        }
                        if(data.view!=""){
                            $(parent+" .makeview").removeAttr("checked");
                            $(parent+" .makeview").next().html(data.view);
                        }else{
                            $(parent+" .makeview").prop("checked",true);
                            $(parent+" .makeview").next().html("");
                        }
                        if(data.auth!=""){
                            $(parent+" .makeauth").removeAttr("checked");
                            $(parent+" .makeauth").next().html(data.auth);
                        }else{
                            $(parent+" .makeauth").prop("checked",true);
                            $(parent+" .makeauth").next().html("");
                        }

                        if(data.config!=""){
                            $(parent+" .makeconfig").removeAttr("checked");
                            $(parent+" .makeconfig").next().html(data.config);
                        }else{
                            $(parent+" .makeconfig").prop("checked",true);
                            $(parent+" .makeconfig").next().html("");
                        }
                    }else{
                        $(parent+" .error").html("");
                        $.messageBox3s('获取数据失败', data.info);
                    }

                },
                beforeSend: function(){
                    $("<div class='loadingWrap'></div>").appendTo("body");
                },
                complete: function(){
                    $(".loadingWrap").remove();
                }
            });
        }

        function ajax_field_config(obj){
            var parent = $(obj).data('form');
            if(parent=="#configForm"){
                return false;
            }
            var cname = $(obj).val();
            $.ajax({
                url:"/admin/make_code/get_field_config",
                type:'post',
                data:{cname:cname},
                dataType:'json',
                success:function(data){
                    $("input[name='enname']").val(cname);
                    $("input[name='name']").val(data.name);
                    if(data.fields){
                        $(".fieldform").each(function () {
                            $(this).remove();
                        });

                        $("#selectbuild").before(data.fields);
                        $(".fieldform .easyui-validatebox").validatebox({required:true});
                        $.parser.parse();
                    }
                },
                beforeSend: function(){
                    $("<div class='loadingWrap'></div>").appendTo("body");
                },
                complete: function(){
                    $(".loadingWrap").remove();
                }
            });
        }

    </script>
    <div id="modalwindow" class="easyui-window" data-options="modal:true,closed:true,minimizable:false,shadow:false"></div>
    <div id="tt" class="easyui-tabs" style="width:100%;">
        <div title="从配置文件生成" data-options="iconCls:'fa fa-fw fa-cog'" style="overflow:auto;padding:20px;display:none;">
            <div class="mvctool bgb">
                <a id="btnSave1" style="float: left;" class="l-btn l-btn-plain">
                <span class="l-btn-left"><span class="l-btn-text fa fa-save" style="font-size:14px">
                </span><span style="font-size:12px">生成</span></span></a>
                <div class="datagrid-btn-separator"></div>
            </div>
            <form id="configForm" method="post" enctype="multipart/form-data">
                <table class="formtable" >
                    <tbody>
                    <tr>
                        <th>
                            从配置选择相应模块：
                        </th>
                        <td>
                            <select name="cname"  class="easyui-validatebox cname" data-form="#configForm" data-options="required:true">
                                <option value="">请选择</option>
                                <?php foreach ($items as $v):?>
                                    <option value="<?php echo $v?>"><?php echo $v?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            是否生成数据表：
                        </th>
                        <td>
                            <input type="checkbox" class="maketable" name="maketable" value="1"  checked/><span class="error"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            是否生成Model：
                        </th>
                        <td>
                            <input type="checkbox" class="makemodel" name="makemodel" value="1"  checked/><span class="error"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            是否生成Ctroller：
                        </th>
                        <td>
                            <input type="checkbox" class="makecontroller" name="makecontroller" value="1" checked /><span class="error"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            是否生成View：
                        </th>
                        <td>
                            <input type="checkbox" class="makeview" name="makeview" value="1"  checked/><span class="error"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            是否生成菜单权限：
                        </th>
                        <td>
                            <input type="checkbox" class="makeauth" name="makeauth" value="1" checked /><span class="error"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            说明：
                        </th>
                        <td>
                            1.勾选生成View后，生成了该模块的view文件，其目的是方便开发自定义的页面内容，需要在Controller中设置属性protected $self_view=true;才可有效！<br /><br />
                            2.勾选生成菜单权限后，生成了该模块的菜单权限，其后您可以手动调整菜单及权限的名称和图标等配置！
                        </td>
                    </tr>

                    </tbody>
                </table>
            </form>
        </div>
        <div title="手动配置生成" data-options="iconCls:'fa fa-fw fa-hand-paper-o'" style="overflow:auto;padding:20px;display:none;">
            <div class="mvctool bgb">
                <a id="btnSave2" style="float: left;" class="l-btn l-btn-plain">
                <span class="l-btn-left"><span class="l-btn-text fa fa-save" style="font-size:14px">
                </span><span style="font-size:12px">生成</span></span></a>
                <div class="datagrid-btn-separator"></div>
                <a id="add_field" class="easyui-linkbutton l-btn" iconCls="fa fa-plus">添加字段</a>
            </div>
            <form id="autoForm" method="post" enctype="multipart/form-data">
                <table class="formtable" style="width:450px;">
                    <tbody>
                    <tr>
                        <th>
                            从配置选择自动填写：
                        </th>
                        <td>
                            <select class="cname" name="autocname" data-form="#autoForm">
                                <option value="">请选择</option>
                                <?php foreach ($items as $v):?>
                                    <option value="<?php echo $v?>"><?php echo $v?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            模块英文标识：
                        </th>
                        <td>
                            <input  id="enname" name="enname"  type="text" class="easyui-validatebox" data-options="required:true" data-form="#autoForm" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            模块名(中文)：
                        </th>
                        <td>
                            <input name="name" type="text" class="easyui-validatebox" data-options="required:true" value="" />
                        </td>
                    </tr>
                    </tbody>
                </table>


                <table class="formtable fieldform">
                    <tbody>
                    <tr>
                        <td>
                            <input name="field_enname[]" type="text" class="easyui-validatebox autofield" data-options="required:true" placeholder="字段名(英文)" value="" />
                        </td>
                        <td>
                            <input name="field_name[]" type="text" class="easyui-validatebox autofield" data-options="required:true" placeholder="字段名(中文)" value="" />
                        </td>
                        <td>
                            <select name="field_type[]" class="easyui-validatebox" data-options="required:true">
                                <option value="">请选择字段类型</option>
                                <?php foreach ($db_fields as $v):?>
                                    <option value="<?php echo $v?>"><?php echo $v?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                        <td>
                            <input name="field_option[]" type="text" class="autofield" placeholder="字段附加项" value="" />
                        </td>
                        <td>
                            字段长度:<input name="field_size[]" class="easyui-numberspinner autofield" data-options="min:1" value="" />
                        </td>
                        <td>
                            <input name="field_default[]" type="text" class="autofield" placeholder="默认值" value="" />
                        </td>
                        <td>
                            主键？<input name="is_pri[]" type="checkbox" value="1" />
                        </td>
                        <td>
                            unsigned？<input name="is_unsign[]" type="checkbox" value="1" />
                        </td>
                    </tr>
                    <tr><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td></tr>
                    <tr>
                        <td>
                            <select name="html_type[]" class="easyui-validatebox" data-options="required:true">
                                <option value="">请选择页面控件类型</option>
                                <?php foreach ($html_objs as $v):?>
                                    <option value="<?php echo $v?>"><?php echo $v?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                        <td>
                            <select name="list_display[]" >
                                <option value="1">列表页显示</option>
                                <option value="0">列表页不显示</option>
                                <option value="-1">列表页隐藏</option>
                            </select>
                        </td>
                        <td>
                            <input name="field_tools[]" type="text" class="autofield" placeholder="列表工具栏" value="" />
                        </td>
                        <td>
                            列表排序？<input name="is_order[]" type="checkbox" value="1" />
                        </td>
                        <td>
                            搜索项？<input name="is_search[]" type="checkbox" value="1" />
                        </td>
                        <td>
                            <select name="edit_display[]" >
                            <option value="1">编辑页显示</option>
                            <option value="0">编辑页不显示</option>
                            <option value="-1">编辑页隐藏</option>
                            </select>
                        </td>
                        <td>
                            <input name="field_rule[]" type="text" class="autofield" placeholder="编辑页验证规则" value="" />
                        </td>
                        <td><a class="easyui-linkbutton" iconCls="fa fa-trash" onClick="del_point(this)"></a></td>
                    </tr>
                    </tbody>
                </table>


                <table id="selectbuild" class="formtable">
                    <tbody>
                    <tr>
                        <th>
                            是否生成数据表：
                        </th>
                        <td>
                            <input type="checkbox" class="maketable" name="maketable" value="1"  checked/><span class="error"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            是否生成Model：
                        </th>
                        <td>
                            <input type="checkbox" class="makemodel" name="makemodel" value="1"  checked/><span class="error"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            是否生成Ctroller：
                        </th>
                        <td>
                            <input type="checkbox" class="makecontroller" name="makecontroller" value="1" checked /><span class="error"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            是否生成View：
                        </th>
                        <td>
                            <input type="checkbox" class="makeview" name="makeview" value="1"  checked/><span class="error"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            是否生成菜单权限：
                        </th>
                        <td>
                            <input type="checkbox" class="makeauth" name="makeauth" value="1" checked /><span class="error"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            是否生成配置：
                        </th>
                        <td>
                            <input type="checkbox" class="makeconfig" name="makeconfig" value="1" checked /><span class="error"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            说明：
                        </th>
                        <td>
                            1.勾选生成View后，生成了该模块的view文件，其目的是方便开发自定义的页面内容，需要在Controller中设置属性protected $self_view=true;才可有效！<br /><br />
                            2.勾选生成菜单权限后，生成了该模块的菜单权限，其后您可以手动调整菜单及权限的名称和图标等配置！<br /><br />
                            3.添加字段需要按照规则添加，比如字段长度一般情况是需要添写的(当字段类型选择为text、date、datetime时请不要填写)！具体规则请参考说明文档。
                        </td>
                    </tr>

                    </tbody>
                </table>
            </form>
        </div>
    </div>
<?php $this->load->view('_edit');?>
<?php $this->load->view('_footer');?>
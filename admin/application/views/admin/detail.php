<?php $this->load->view('_header');?>
<table class="formtable">
    <tbody>
    <tr>
        <td colspan="6">
            账户信息
        </td>
    </tr>
    <tr>
        <th>
            账户名：
        </th>
        <td>
            <?php echo $editing['account']; ?>
        </td>
        <th>
            密码：
        </th>
        <td>
            ********
        </td>
        <td>
            &nbsp;
        </td>
        <td>
            &nbsp;
        </td>
    </tr>
    <tr>
        <th>拥有角色：</td>
        <td>
            <?php echo $role['name']?>
        </td>
        <th>
            帐户状态：
        </th>
        <td>
            <?php echo $editing['ustatus']?"已启用":"未启用"?>
        </td>
        <td>
            &nbsp;
        </td>
        <td>
            &nbsp;
        </td>
    </tr>
    </tbody>
</table>

<table class="formtable">
    <tr>
        <td colspan="5">
            基本资料
        </td>
    </tr>
    <tr>
        <th>
            用户名：
        </th>
        <td>
            <?php echo $editing['name']; ?>
        </td>
        <th>
            性别：
        </th>
        <td>
            <?php echo $editing['sex']; ?>
        </td>
        <td rowspan="5" style=" border-left:dashed 1px #ccc; text-align:center">
            <img id="PhotoFB" style="cursor:pointer; width:140px; height:140px; border:1px #ccc solid;" src="<?php if($editing['icon']) echo UPLOADS.$editing['icon'];else echo ASSETS.'Content/Images/NotPic.jpg';?>" />
        </td>
    </tr>
    <tr>
        <th>
            生日：
        </th>
        <td>
            <?php echo $editing['birthday'];?>
        </td>
        <th>
            加入日期：
        </th>
        <td>
            <?php if($editing['created_at']) echo date("Y-m-d",$editing['created_at']);?>
        </td>
    </tr>
</table>

<table class="formtable">
    <tbody>
    <tr>
        <td colspan="6">
            联系方式
        </td>
    </tr>
    <tr>
        <th>
            手机：
        </th>
        <td>
            <?php echo $editing['tel'];?>
        </td>
        <th>
            Email：
        </th>
        <td>
            <?php echo $editing['email'];?>
        </td>
    </tr>
    <tr>
        <th>
            省市区：
        </th>
        <td>
            <?php echo $distinct;?>
        </td>
        <th>
        </th>
        <td>
        </td>
    </tr>
    <tr>
        <th>
            地址：
        </th>
        <td>
            <?php echo $editing['address'];?>
        </td>
    </tr>
    </tbody>
</table>
<?php $this->load->view('_footer');?>
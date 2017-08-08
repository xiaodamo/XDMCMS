<ul class="dropdown-menu">
    <!-- User image -->
    <li class="user-header">
        <img src="<?php echo $_SESSION['icon']?UPLOADS.$_SESSION['icon']:ASSETS.'avatars/avatar2.png';?>" class="img-circle" alt="User Image">
        <p>
            <?php echo $_SESSION['name']?$_SESSION['name']:$_SESSION['account']?>
        </p>
    </li>
    <!-- Menu Footer-->
    <li class="endbtndiv" style="margin-top:5px;">
        <a href="javascript:Profile();" class="easyui-linkbutton btns" style="float:left; margin-left:10px;">简介</a>
        <a href="javascript: SignOut();" class="easyui-linkbutton btnc" style="width:80px;">退出</a>
    </li>
</ul>
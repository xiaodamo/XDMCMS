<?php $this->load->helper('url')?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="utf-8">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
<meta http-equiv="expires" content="Wed, 26 Feb 1997 08:21:57 GMT">
<meta http-equiv="expires" content="0">
<title>提 示 信 息</title>
<link href="<?php echo ASSETS?>Content/message.css" rel="stylesheet" type="text/css" />
</head>
<body class="x-border-layout-ct"style="position: relative;">
<div align="center">
    <div id="messagebox">
      <div id="messagebox-title" align="center">提示信息</div>
      <div id="messagebox-content">
        <div style="font-size:14px;"><?php echo $message;?></div>
        <ul style="font-size:14px;line-height:30px;">
            <?php foreach ($gotos as $goto):?>
             <li><a href="<?php echo site_url($goto['uri'])?>"><?php echo $goto['label']?></a></li>
            <?php endforeach;?>
        </ul>
      </div>
    </div>
</div>
</body>
</html>
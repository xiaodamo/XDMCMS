<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 不需要登陆就可执行的在这里配置
 */
$config = array();

/* 强制用户登录 */
// 控制器名 => array(方法明的形式)，若方法名为空则开放整个控制器，否则只开放对应的方法
$config['public_controllers'] = array( 
    'login' => array(),
    'logout' => array(),
    'script' => array(),
);

/*不判断权限*/
// 控制器名 => array(方法明的形式)，若方法名为空则开放整个控制器，否则只开放对应的方法
$config['public_roles'] = array(
    'login' => array(),  
    'logout' => array(),  
    'main' => array(),
    'welcome' => array(),
    'script' => array(),
    'region_change' => array(),
    'imageupload' => array(),
    'admin_user' => array('profile','change_mypassword'),
);
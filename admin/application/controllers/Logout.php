<?php
/**
 * 注销
 *
 *
 */
class Logout extends CI_Controller
{
	function __construct()
    {
        parent::__construct();  
    }
    
    function index()
    {
        $this->session->sess_destroy();
		ajaxReturn('退出登录');
    }
   
}
?>
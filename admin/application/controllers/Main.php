<?php
/**
 * 主页面
 *
 *
 */
class main extends CI_Controller
{

	/**
	 * 构造函数
	 *
	 * 登陆检验
	 */	
	function __construct()
    {
        parent::__construct();
    }
    
	// --------------------------------------------------------------------

    /**
	 * 判断所属部门进行跳转相关页面
	 *
	 *
	 */	
    function index()
    {
        if(empty($_SESSION['admin_id'])){
	        show_message2('用户不存在或信息已过期，请重新登录!', 'login');
	    }
        $this->config->load('webset');
        $data = $this->config->item('webset');
	    $this->load->view('main',$data);
    }

	//获取页面一级菜单
	function get_top_menu()
	{
		$this->load->model('auth_model');
		$menus = $this->auth_model->topmenu();
		ajaxReturn($menus);
	}

	//获取页面指定id菜单
	function get_left_menu()
	{
		$parentid = intval($this->input->post_get('id'));
		$this->load->model('auth_model');
		$menus = $this->auth_model->menu();
		$left_menus = _make_left_menu($menus,$parentid);
		ajaxReturn($left_menus);
	}

	function setthemes(){
        $theme = $this->input->post('theme');
        $themes = array(
            'theme'  => $theme,
        );
        $this->session->set_userdata($themes);
        ajaxReturn(1);
    }

    // --------------------------------------------------------------------


}
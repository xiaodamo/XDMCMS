<?php
/**
 * 登陆
 *
 *
 */
class Login extends CI_Controller
{
	/**
	 * 构造函数
	 *
	 * 
	 */	
	function __construct()
    {
        parent::__construct();                
    }
    
	// --------------------------------------------------------------------

    /**
	 * 登陆界面
	 *
	 *
	 */	
    function index()
    {
        $this->config->load('webset');
        $data = $this->config->item('webset');
        $this->load->view('_login',$data);
    }
    
    // --------------------------------------------------------------------

    /**
	 * 登陆检验
	 *
	 *
	 */	
	function signin()
	{
			//接受客户端数据
            $code = $this->input->post('code');
            if(!isset($_SESSION['code']) || strtolower($code)!=$_SESSION['code'])
            {
				show_message3('','验证码错误!', false);
            }
            
			$account = $this->input->post('account');
			$password = $this->input->post('password');

			// 把数据提交给模型
			$this->load->model('admin_user_model');
			$this->admin_user_model->account = $account;
			$this->admin_user_model->password = $password;	

			if ($user = $this->admin_user_model->signin()){

               // session记录登陆者信息
               $users = array(
                   'account'  => $user['account'],
                   'name'  => $user['name'],
				   'admin_id'  => $user['user_id'],
				   'role_id'  => $user['role_id'],
				   'auth_ids'  => $user['auth_ids'],
				   'auth_ca'  => $user['auth_ca'],
				   'icon'  => $user['icon'],
               );
               $this->session->set_userdata($users);

			   show_message3('登录成功');

			// 用户名称和密码不匹配
			}else{
				show_message3('','账号或者密码错误!', false);
			}
	}
	
	public function get_code()
	{
	    $this->load->library('captcha');
        $this->captcha = new Captcha(111,37);
	    $code = $this->captcha->getCaptcha();
	    $this->session->set_userdata('code', strtolower($code));
	    $this->captcha->showImg();
	}
	
}

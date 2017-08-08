<?php 
class Rbac {

	/*
	 * 权限验证(Hook自动加载)
	 */
    static $config = null;
    
	public function auto_verify()
	{
	    if( is_cli() )
	    {
	        // CLI 模式，不进行登陆及权限检查
	        return;
	    }
	    
	    if( !isset($_SERVER['REQUEST_URI']) )
	    {
	        self::goWebLogin();
	        exit;
	    }
	    
	    $_CI = &get_instance();
	    $class = $_CI->router->class;
	    $method = $_CI->router->method;
	    
	    //控制器自己处理登录状态
	    if( !self::$config )
	    {
	        $_CI->config->load('rbac');
	        self::$config = $_CI->config->item('public_controllers');
	    }
	    
	    if( isset(self::$config[$class]) )
        {
            if( empty(self::$config[$class]) || in_array($method,self::$config[$class]) )
            {
                // 开放的控制器 或者 方法
                self::auth_check();
                return;
            }
        }
	    
	    self::goWebLogin();
	    self::auth_check();
	    
	}
	
	public static function goWebLogin()
	{
	    if (empty($_SESSION['admin_id'])){
			exit( "
            <script type=\"text/javascript\" src=". ASSETS."Scripts/jquery.min.js></script>
            <script type=\"text/javascript\" src=". ASSETS."Scripts/Account.js></script>
            <script language=\"javascript\" type=\"text/javascript\">
 			var top = getTopWindow();top.location.href = '". site_url('login')."';
    		</script> ");
	    }
	}
	
	/**
	 *权限检查
	 */
	public static function auth_check()
	{
	   
	    //当前请求操作
	    $_CI = &get_instance();
	    $class = $_CI->router->class;
	    $method = $_CI->router->method;
	
	    //过滤控制器和方法，避免用户非法请求
	    //通过角色获得用户可以访问的控制器和方法信息
	    if(isset($_SESSION['auth_ca']) && isset($_SESSION['auth_ids']))
	    {
	        $auth_ca = explode(',',$_SESSION['auth_ca']);
	    }
	    else
	    {
	        $_SESSION['role_id'] = empty($_SESSION['role_id'])?0:$_SESSION['role_id'];
	        $_CI->load->model('role_model');
	        $auth = $_CI->role_model->get_role_auth();
	        $auth_ca = $auth?explode(',',$auth['auth_ca']):array();
	    }
	    
	    //当前访问路径
	    $now_ca = $class.'/'.$method;
	    
	
	    //判断$now_ca是否在$auth_ca字符串里边有出现过
	    //超级管理员不限制
	    //默认以下权限没有限制
	    $_CI->config->load('rbac');
	    $allow_ca = $_CI->config->item('public_roles');
	    
	    if((!isset($allow_ca[$class]) || ($allow_ca[$class] && !in_array($method,$allow_ca[$class]))) && (@$_SESSION['admin_id']!=1 && !in_array($now_ca, $auth_ca)))
	    {
			show_message1('您没有此操作的权限');
	    }
	    
	}
	
}
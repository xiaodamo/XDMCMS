<?php
/**
 * 后台执行脚本
 * @author Administrator
 *
 */
class Script extends CI_Controller
{
    public function __construct()
    {
		parent::__construct();
        error_reporting(E_ALL);
        if( !is_cli() )
        {
            if( !isset($_SESSION['admin_id']) || !intval($_SESSION['admin_id']) )
            {
                redirect('/');exit;
            }
        }
        @set_time_limit(0);

        global $argv;
        if( isset($argv) && $argv )
        {
            $this->args = implode('" "',array_slice($argv,3));
        }
        else
        {
            $this->args = '';
        }
        log_message('info',"script::{$this->router->method}: \"{$this->args}\" start.");
	}

    public function __destruct()
    {
        $memory = memory_get_peak_usage();
        log_message('info',"script::{$this->router->method}: \"{$this->args}\" stop. memory usage: $memory");
    }
    
}

<?php
/*
 * base文件
 */
class Base_Controller extends CI_Controller
{

    protected $webset = array();
    /**
     * 构造函数
     *
     * 登陆检验，权限验证
     */
    function __construct()
    {
        parent::__construct();
        $this->common();
    }

    // --------------------------------------------------------------------
    private function common()
    {
        $config = new CI_Config();
        $path = realpath('admin/application');
        $config->_config_paths = array($path.DIRECTORY_SEPARATOR);
        $config->load('webset');
        $data = $config->item('webset');
        if(!$data['used']){
            $this->load->view('noused',$data);
            $this->output->_display();
            die();
        }

        $this->webset = $data;
    }
    
}
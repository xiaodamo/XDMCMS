<?php
/**
 * 网站设置
 *
 *
 */
class Webset extends CI_Controller
{
	/**
	 * 构造函数
	 *
	 */	
	function __construct()
    {
        parent::__construct();  
    }
  
    // --------------------------------------------------------------------

    /**
	 * 列表
	 *
	 *
	 */	
    function index()
    {
        $this->config->load('webset');
        $data = $this->config->item('webset');
        $this->load->view('webset/index',$data);
    }
    
	// --------------------------------------------------------------------

    function makeconfig()
    {
        $sysname = $this->input->post('sysname',true);
        $webname = $this->input->post('webname',true);
        $used = (int)$this->input->post('used');
        $data = array(
            'sysname'=>$sysname,
            'webname'=>$webname,
            'used'=>$used,
        );

        //保存配置文件
        $content  = "<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');" . PHP_EOL . PHP_EOL . "/**" . PHP_EOL . " * 网站配置表" . PHP_EOL . " */" . PHP_EOL . "\$config['webset'] = array(" . PHP_EOL;
        foreach ($data as $k=>$v){
            $content .= "'" . $k . "'  => '" . $v . "',". PHP_EOL;
        }
        $content .= PHP_EOL . ");";
        $dir = APPPATH.'config'.DIRECTORY_SEPARATOR;
        $filename = 'webset.php';
        $res = makefile($dir, $filename,$content);
        if($res){
            show_message3('操作成功','操作失败',false);
        }

        show_message3('操作成功');
    }
    

}
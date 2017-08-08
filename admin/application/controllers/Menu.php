<?php
/**
 * 后台菜单
 *
 *
 */
class Menu extends CI_Controller
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
        $this->load->view('menu/list',array());
    }

    // --------------------------------------------------------------------

	/**
	 * 添加
	 *
	 */	
    function add()
    {
        $this->edit();
    }

    // --------------------------------------------------------------------

    /**
	 * 更新
	 *
	 *
	 */	
	function edit()
    {
		$params = $this->uri->uri_to_assoc(3);
		$this->load->model('auth_model');
        if (!empty($params['id']) && $params['id'] > 0){
            $id = $params['id'];
            $data['editing'] = $this->auth_model->load($id);
            if (!$data['editing']){
                show_message1('无效ID:'.$id);
            }
        } else {
            $data['editing'] = array(
                'auth_id' => null,
                'parent_id' => null,
                'name' => null,
				'm' => null,
				'c' => null,
				'a' => null,
				'icon' => null,			
				'is_menu' => null,			
				'is_display' => null,			
				'sort_order' => null,			
            );
        }
        
		$this->load->view('menu/add',$data);
	}
    
	// --------------------------------------------------------------------

    /**
	 * 提交数据
	 *
	 *
	 */	
	function save()
    {
        //id
        $id = $this->input->post('id');
        
		// 接收客户端提交数据
        $parent_id = (int)$this->input->post('parent_id');
        $name = $this->input->post('name',true);
        //$m = trim($this->input->post('m',true));
        $c = trim($this->input->post('c',true));
        $a = trim($this->input->post('a',true));
        $icon = $this->input->post('icon',true);
        $is_menu = (int)$this->input->post('is_menu');
		$is_display = (int)$this->input->post('is_display');
		$sort_order = (int)$this->input->post('sort_order');
		//跳转url
		$jump_url = 'menu/index';
        // 加载表单验证类
        $this->load->library('form_validation');

		// 设置表单数据规则
        $this->set_save_form_rules();
        
		// 如果提交数据符合所设置的规则，则继续运行
		if (TRUE == $this->form_validation->run()){
            
			//把数据提交给模型
            $this->load->model('auth_model');       
            $data['parent_id'] = $parent_id;
            $data['name'] = $name;
            //$data['m'] = $m;
            $data['c'] = $c;
            $data['a'] = $a;
            $data['url'] = $c?$c.($a?'/'.$a:''):'';
            $data['icon'] = $icon;
            $data['is_menu'] = $is_menu;
            $data['is_display'] = $is_display;
            $data['sort_order'] = $sort_order;
            
			// 更新管理员资料
            if ($id){
				$data['auth_id'] = $id;

                $res = $this->auth_model->update($data);

                if ($res){
                    show_message3('操作成功');
                }else{
                    show_message3('操作成功','操作失败',false);
                }

            // 添加新管理员
            } else {
               $res = $this->auth_model->create($data);
                if ($res){
                    show_message3('操作成功');
                }else{
                    show_message3('操作成功','操作失败',false);
                }
            }
        
		// 对提交的数据不符合表单验证规则情况的处理
		}else{
          //获取错误信息
		    $errorinfo = $this->form_validation->error_string();
            show_message3('操作成功','操作失败：'.$errorinfo,false);
		}
	}
    
	// --------------------------------------------------------------------

    /**
	 * 删除
	 *
	 *
	 */	
	function delete()
    {
        $params = $this->uri->uri_to_assoc(3);
        if (isset($params['id']) && ($id = $params['id']) > 0){

            $this->load->model('auth_model');

            if ($this->auth_model->delete($id)){
                show_message3('"(ID:'.$id.')" 已被删除!');
            } else {
                show_message3('操作成功','无效ID:'.$id,false);
            }
        }
	}
	
	/**
	 * 批量删除
	 */
	function delete_banch()
	{
	    $ids = $this->input->post('ids');
	    $m = $this->input->post('m');
	    $jump_url = 'menu/index?m='.$m;
	    
	    if(!is_array($ids))
	    {
	        show_message2('参数错误',$jump_url);
	    }
	    
	    $this->load->model('auth_model');
	    $ids_str = array();
	    foreach ($ids as $v){
	        $res = $this->auth_model->delete($v);
	        if (!$res){
	            $ids_str[] =  $v;
	        }
	    }
	
        if (!$ids_str){
            show_message2('所选项删除成功!', $jump_url);
        }else{
            show_message2('ID:'.implode(',', $ids_str).'删除失败！', $jump_url);
        }
	}

	function get_icons(){
        $this->load->view('menu/iconlist');
    }
	
    // --------------------------------------------------------------------

    /**
	 * 设置表单数据规则
	 *
	 */	
	function set_save_form_rules()
    {
        $this->form_validation->set_rules('name', '菜单名', 'trim|required');
        //$this->form_validation->set_rules('m', '模块名', 'trim|required|alpha');
        $this->form_validation->set_rules('c', '控制器名', 'trim|alpha_dash');
        $this->form_validation->set_rules('a', '方法名', 'trim|alpha_dash');
        $this->form_validation->set_rules('parent_id', '上级菜单', 'integer');
        $this->form_validation->set_rules('is_menu', '是否为菜单', 'integer');
        $this->form_validation->set_rules('is_display', '显示状态', 'integer');
        $this->form_validation->set_rules('sort_order', '排序', 'integer');
    }

}
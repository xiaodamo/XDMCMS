<?php
/**
 * 角色
 *
 *
 */
class Role extends CI_Controller
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

        $params = $this->uri->uri_to_assoc ();
        $this->load->model('role_model');

        $per_page=$this->input->post_get('rows');
        $per_page = empty( $per_page) ? 20 : $per_page;//每页显示的记录数
        //当前页
        $p = (int)$this->input->get_post('page');
        $p = $p?$p:1;
        
        $condition = array(
            'conditions'=>array(
                'name'  	 => $this->input->get_post('queryStr'),
            )
        );
        //总条数
        $counts =  $this->role_model->counts($condition);
		$roles = $this->role_model->finds($condition,$per_page, ($p-1)*$per_page);

        $data = array(
            'rows'  => $roles,
            'total' => $counts,
        );

        if (!empty( $params ['method'] ) && $params ['method'] == 'json') {
            ajaxReturn($data);
        }else{
            $this->load->view('role/list');
        }

    }
    
	// --------------------------------------------------------------------

    /**
	 * 添加
	 *
	 *
	 */	  
	function add()
    {
        $this->edit();
    }
    
	
	// --------------------------------------------------------------------

    /**
	 * 编辑
	 *
	 *
	 */	  
	function edit()
    {
		$params = $this->uri->uri_to_assoc(3);
        if (!empty($params['id']) && $params['id'] > 0){
            $id = $params['id'];
            $this->load->model('role_model');
            $data['editing'] = $this->role_model->load($id);
            if (!$data['editing']){
                show_message1('无效ID:'.$id);
            }

        } else {
            $data['editing'] = array(
                'role_id' => null,
                'name' => null,			
                'auth_ids' => '',
            );
        }
        
		$this->load->view('role/add',$data);
	}

	function get_auth($display=1){
        $this->load->model('auth_model');
        $role = $this->auth_model->get_auth_menu('',2,'',$display);
        ajaxReturn(getMenu($role,'id'));
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
        $id = (int)$this->input->post('id');

        $name = $this->input->post('name',true);
        $auth_ids = $this->input->post('roleIds') ;
	    //跳转url
	    $jump_url = 'role/index';

        // 加载表单验证类
        $this->load->library('form_validation');

		// 设置表单数据规则
        $this->set_save_form_rules();

		// 如果提交数据符合所设置的规则，则继续运行
		if (TRUE == $this->form_validation->run()){
            
			//把数据提交给模型
            $this->load->model('role_model');            
            $data['name'] = $name;
            $data['auth_ids'] = $auth_ids;
            //获取权限对应url列表
            $this->load->model('auth_model');
            $auths = $this->auth_model->get_auth_menu($auth_ids);
            $auth_ca = array();
            foreach ($auths as $auth){
                if($auth['url']){
                    $auth_ca[] = $auth['url'];
                }
            }
            $data['auth_ca'] = $auth_ca?implode(',', $auth_ca):'';
            
			// 更新管理员资料
            if ($id){
                
				$data['role_id'] = $id;

                $res = $this->role_model->update($data);

				if ($res){ 
                    show_message3('操作成功');
			    }else{
                    show_message3('操作成功','操作失败',false);
				}

            // 添加新管理员
            } else {
               $res = $this->role_model->create($data);
			   if($res){ 
                   show_message3('操作成功');
			   }else{
                   show_message3('操作成功','操作失败',false);
			   }
            }
        
		// 对提交的数据不符合表单验证规则情况的处理
		}else{
          //获取错误信息
		    $errorinfo = $this->form_validation->error_string();
            show_message3('操作成功',$errorinfo,false);
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

			if($params['id'] == $_SESSION['role_id']){
                show_message3('操作成功','你不能删除自己所属的角色!',false);
			}
            
			$this->load->model('admin_user_model');	
            if ($this->admin_user_model->role_user($params['id'])){
                show_message3('操作成功','存在属于该角色的人员，你不能删除!',false);
			}

            $this->load->model('role_model');
            if ($this->role_model->delete($id)){
                show_message3('操作成功');
            } else {
                show_message3('操作成功','无效ID:'.$id,false);
            }
        }
	}
	
	// --------------------------------------------------------------------

    /**
	 * 设置表单数据规则
	 *
	 */	
	function set_save_form_rules()
    {
        $this->form_validation->set_rules('name', '角色名', 'trim|required');
    }
    

}
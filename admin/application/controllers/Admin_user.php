<?php
/**
 * 员工
 *
 *
 */
class Admin_user extends CI_Controller
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
	 * 员工列表
	 *
	 *  
	 */	
  	
	 function index()
     {
	    $params = $this->uri->uri_to_assoc ();
		$this->load->model('admin_user_model');
		//$this->load->library('pagination');
		
		$condition = array(
			'code'	 => 'name',
			'key'  	 => $this->input->get_post('queryStr'),
		);

        $per_page=$this->input->post_get('rows');
        $per_page = empty( $per_page) ? 20 : $per_page;//每页显示的记录数
        //当前页
        $p = (int)$this->input->get_post('page');
        $p = $p?$p:1;

        //总条数
		$admin_user_list = $this->admin_user_model->admin_user_list(($p-1)*$per_page , $per_page , $condition);
		
        $data = array(
		
             'rows'  => $admin_user_list['res'],
             'total'  			=> $admin_user_list['total'],
            );
			
			
		//根据部门id 角色id  查询角色名称	
		foreach($data['rows'] as $key=>$value)
		{
    		$role_name 	=$this->admin_user_model->get_role_name($value['role_id']);
    		$data['rows'][$key]['role_name']		= $role_name['name'];
		}

		 if (!empty( $params ['method'] ) && $params ['method'] == 'json') {
			 ajaxReturn($data);
		 }else{
			 $this->load->view('admin/list');
		 }
    }
	
	

    // --------------------------------------------------------------------

	/**
	 * 添加新员工
	 *
	 */	
    function add()
    {
		  $this->edit();
    }

    // --------------------------------------------------------------------

    /**
	 * 更新员工资料
	 *
	 *
	 */	
	function edit()
    {
		    $this->load->model('admin_user_model');

            $params = $this->uri->uri_to_assoc(3);
            if (!empty($params['id']) && $params['id'] > 0){
                $id = intval($params['id']);
      
      			
    		    $data['editing'] = $this->admin_user_model->load($id);//根据id获取员工信息		
    
                if (!$data['editing']){
                    return show_message1('无效ID:'.$id);
                }
            } else {
                $data['editing'] = array(
                    'account' 	=> null,
    				'name' 		=> null,
    				'icon' 		=> null,
    				'sex' 		=> null,
    				'birthday' 		=> null,
    				'tel' 		=> null,
    				'height' 		=> null,
    				'weight' 		=> null,
    				'expertise' 		=> null,
    				'city_id' 		=> null,
    				'district_id' 		=> null,
    				'address' 		=> null,
    				'descinfo' 		=> null,
    				'ustatus' 		=> null,
    				'email' 	=> null,
    				'role_id'	=> null,
    				'user_id' 		=> null,
    				//'company' 	=> $_SESSION['company'],	
    				//'depart' 	=> null,			
                );
            }
        
//             if($_SESSION['admin_id']==1){
//                 $this->load->model('company_model');
//                 $data['company_list'] = $this->company_model->finds();
//             }
        
		  // 部门列表
// 		  $depart_list = $this->admin_user_model->get_all_depart($data['editing']['company']);		  
// 		  $data['depart_list'] =   $depart_list;

          $role_list   = $this->admin_user_model->get_role_list();//根据用户信息部门id 获取该部门下角色列表
          $data['role_list']   =   $role_list;
		  
		  //省市区列表
		  $data['editing']['district_id'] = $data['editing']['district_id']?$data['editing']['district_id']:0;
		  $data['editing']['city_id'] = $data['editing']['city_id']?$data['editing']['city_id']:0;
		  $this->load->model('region_model', 'region');
		  $parent = $this->region->load($data['editing']['city_id']);
		  $data['editing']['province_id'] = isset($parent['parent_id'])?$parent['parent_id']:0;

		  $this->load->view('admin/add',$data);
	}
    
	// --------------------------------------------------------------------

    function detail(){
	    $data = array();
        $params = $this->uri->uri_to_assoc(3);
        if (!empty($params['id']) && $params['id'] > 0){
            $id = intval($params['id']);
            $this->load->model('admin_user_model');
            $data['editing'] = $this->admin_user_model->load($id);//根据id获取员工信息

            if (!$data['editing']){
                return show_message1('无效ID:'.$id);
            }

            $data['role'] = $this->admin_user_model->get_role_name($data['editing']['role_id']);

            $this->load->model('region_model');
            $city = $this->region_model->get_name($data['editing']['city_id']);
            $distinct = $this->region_model->get_name($data['editing']['district_id']);
            $province = $this->region_model->parent_of($data['editing']['city_id']);

            $data['distinct'] = $province['name'].'-'.$city.'-'.$distinct;
        }
        $this->load->view('admin/detail',$data);
    }

    function getrole(){
        $params = $this->uri->uri_to_assoc(3);
        $data = array();
        if (!empty($params['id']) && $params['id'] > 0){
            $id = intval($params['id']);
            $this->load->model('admin_user_model');
            $data['editing'] = $this->admin_user_model->load($id);//根据id获取员工信息

            if (!$data['editing']){
                return show_message1('无效ID:'.$id);
            }
        }
        $this->load->view('admin/getrole',$data);
    }
    /**
	 * 提交数据
	 *
	 *
	 */	
	function save()
    {

	     // 员工id
        $admin_id = (int)$this->input->post('id');
		
	     //加载ci表单验证类
	    $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

		//验证规则，具体规则参数看CI手册
		if(empty($admin_id)){
			//编辑角色时 不修改不判断账号和密码
 			$this->form_validation->set_rules('account', '员工账号', 'trim|required|alpha_numeric|min_length[3]|max_length[24]');
			$this->form_validation->set_rules('password', '员工密码', 'required|min_length[5]|max_length[24]');
		
		}
		$this->form_validation->set_rules('name', '员工名字', 'trim|required');
		$this->form_validation->set_rules('tel', '员工电话', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('city_id', '城市', 'required');
		$this->form_validation->set_rules('district_id', '区县', 'required');
		$this->form_validation->set_rules('email', '员工邮箱', 'trim|required|valid_email');
 		//$this->form_validation->set_rules('depart', '员工部门', 'required');
		$this->form_validation->set_rules('role', '员工角色', 'required');		
	
		if ($this->form_validation->run() == FALSE)
        {
			//验证是否为空返回值
			$errorinfo = $this->form_validation->error_string();				
			//跳转页面
            show_message3('操作成功',$errorinfo,false);
		
		}else{
		
			$this->load->model('admin_user_model');
			//对有上传头像的情况的处理
			$icon = '';
			if (!empty($_FILES['icon']['name']) && $admin_id){
			     
			    // 定义和创建图片保存位置
			    $save_path = 'adminuser/'.$admin_id.'/';
			    $path = '..'.UPLOADS.$save_path;
			    mkdirsByPath($path);

			    // CI文件上传类 数据初始化
			    $config['file_name']  = date("YmdHis");
			    $config['upload_path']  = $path ;
			    $config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
			    $config['max_size'] = '10000';
			    $config['max_filename'] = '50';
			    $this->load->library('upload', $config);

			    if (!$this->upload->do_upload('icon'))
			    {
                    show_message3('操作成功',$this->upload->display_errors(),false);
			    }

			    $uploaded = $this->upload->data();
			    $icon = $save_path.$uploaded['file_name'];

			    //删除原图
			    $user_icon = $this->admin_user_model->load($admin_id);
			    if (file_exists('..'.UPLOADS.$user_icon['icon'])){
			        @unlink('..'.UPLOADS.$user_icon['icon']);
			    }
			}
			// 接收客户端提交数据
			$this->admin_user_model->account  	= $this->input->post('account');
			$this->admin_user_model->name  		= $this->input->post('name');
			$this->admin_user_model->password 	= $this->input->post('password');
			$this->admin_user_model->icon = $icon;
			$this->admin_user_model->email	  	= $this->input->post('email');
			//$this->admin_user_model->company_id  = $this->input->post('company');
			//$this->admin_user_model->depart_id  = $this->input->post('depart');
			$this->admin_user_model->role_id  = $this->input->post('role');
			$this->admin_user_model->sex	= $this->input->post('sex');
			$this->admin_user_model->birthday	= $this->input->post('birthday');
			$this->admin_user_model->tel	= $this->input->post('tel');
//			$this->admin_user_model->height	= $this->input->post('height');
//			$this->admin_user_model->weight	= $this->input->post('weight');
//			$this->admin_user_model->expertise	= $this->input->post('expertise');
			$this->admin_user_model->city_id	= (int)$this->input->post('city_id');
			$this->admin_user_model->district_id	= (int)$this->input->post('district_id');
			$this->admin_user_model->address	= $this->input->post('address');
//			$this->admin_user_model->descinfo	= $this->input->post('descinfo');
            $this->admin_user_model->ustatus	= (int)$this->input->post('ustatus');

			$this->admin_user_model->created_at	= strtotime($this->input->post('created_at'));

		   if(empty($admin_id)){
    			//数据插入
    			$res = $this->admin_user_model->create();
    			if(empty($res))
    			{
                    show_message3('操作成功','员工添加失败',false);

    			}else{
    			    // 定义和创建图片保存位置
    			    $save_path = 'adminuser/'.$res.'/';
    			    $path = '..'.UPLOADS.$save_path;
    			    mkdirsByPath($path);
    			    
    			    // CI文件上传类 数据初始化
    			    $config['file_name']  = date("YmdHis");
    			    $config['upload_path']  = $path ;
    			    $config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
    			    $config['max_size'] = '10000';
    			    $config['max_filename'] = '50';
    			    $this->load->library('upload', $config);
    			     
    			    if (!$this->upload->do_upload('icon'))
    			    {
                        show_message3('操作成功','员工添加成功！但头像上传失败:'.$this->upload->display_errors(),false);
    			    }
    			    
    			    $uploaded = $this->upload->data();
    			    $icon = $save_path.$uploaded['file_name'];
    			    $this->admin_user_model->update_one($res,array('icon'=>$icon));
                    show_message3('员工添加成功！');
    			
    			}
		  
		  }else{
		        //数据更新
		         $res = $this->admin_user_model->update($admin_id);
    			 if(empty($res))
    			 {

                     show_message3('操作成功','员工修改失败',false);
    			
    			 }else{

                     show_message3('员工修改成功！');
    			
    			 } 
		  }
		}
	}
    
	// --------------------------------------------------------------------

    /**
	 * 删除员工
	 *
	 *
	 */	
	function delete()
    {
		
		$params = $this->uri->uri_to_assoc(3);
		
        if (isset($params['id']) && ($params['id']) > 1){
		
			$id=intval($params['id']);
            // 禁止用户删除自己
			if($id == $this->session->userdata('admin_id')){
                show_message3('操作成功','你不能删除自己!',false);
			}

            $this->load->model('admin_user_model');
			//$admin_user = $this->admin_user_model->load($id);
			
            if ($this->admin_user_model->delete($id)){
                show_message3('操作成功');
            } else {
                show_message3('操作成功','无效ID!',false);
            }
        }else{

            show_message3('操作成功','无效ID!',false);
		
		}
	}

	// --------------------------------------------------------------------
	//角色联动
	function role_list() {
	
		$depart_id=$this->input->post('depart');
		//获取该部门id下的角色
		$this->load->model('admin_user_model');
		$role_list = $this->admin_user_model->get_role_list($depart_id);

		echo json_encode($role_list);
	}
	
	//部门联动
	function depart_list() {
	
	    $company_id=$this->input->post('company');
	    //获取该部门id下的角色
	    $this->load->model('admin_user_model');
	    $depart_list = $this->admin_user_model->get_depart_list($company_id);
	
	    echo json_encode($depart_list);
	}

	function change_password(){
        $id=intval($this->input->post('id'));
        $password=$this->input->post('password');
        $this->load->model('admin_user_model');
        $res = $this->admin_user_model->change_password($id,$password);
        $res?show_message3('操作成功'):show_message3('操作成功','操作失败',false);
    }

    function change_mypassword(){
        $id=intval($_SESSION['admin_id']);
        $oldPwd=$this->input->post('oldPwd');
        $newPwd=$this->input->post('newPwd');
        if(!$oldPwd || !$newPwd){show_message3('操作成功','旧密码和新密码不能为空',false);}
        if(count_chars($newPwd)<5){show_message3('操作成功','新密码长度不能小于5位',false);}
        if($oldPwd==$newPwd){show_message3('操作成功','旧密码不能和新密码相同',false);}

        $this->load->model('admin_user_model');
        $myinfo = $this->admin_user_model->load($id);
        if(!$myinfo){show_message3('操作成功','用户不存在',false);}
        //比较密码
        if(md5(md5($oldPwd).$myinfo['salt'])!==$myinfo['password']){
            show_message3('操作成功','旧密码错误',false);
        }

        $this->load->model('admin_user_model');
        $res = $this->admin_user_model->change_password($id,$newPwd);
        $res?show_message3('操作成功'):show_message3('操作成功','操作失败',false);

    }

    function change_role(){
        $id=intval($this->input->post('id'));
        $roleIds=$this->input->post('roleIds');
        $this->load->model('admin_user_model');
        $res = $this->admin_user_model->change_role($id,$roleIds);
        $res?show_message3('操作成功'):show_message3('操作成功','操作失败',false);
    }

    function profile(){
        $this->load->model('admin_user_model');
        $this->load->model('role_model');
        $myinfo = $this->admin_user_model->load($_SESSION['admin_id']);
        $role = $this->role_model->load($myinfo['role_id']);

        $this->load->model('region_model');
        $city = $this->region_model->get_name($myinfo['city_id']);
        $distinct = $this->region_model->get_name($myinfo['district_id']);
        $province = $this->region_model->parent_of($myinfo['city_id']);

        $data['distinct'] = $province['name'].'-'.$city.'-'.$distinct;

        $this->load->view('admin/profile',array('myinfo'=>$myinfo,'role'=>$role,'province'=>$province,'city'=>$city,'distinct'=>$distinct));
    }



	
	// --------------------------------------------------------------------

}
<?php
/**
 * 文章栏目
 *
 *
 */
class Category extends CI_Controller
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
        if (!empty( $params ['method'] ) && $params ['method'] == 'json') {
            $this->load->model('category_model');
            $data = $this->category_model->finds(array(),0);
            $menu = getMenu($data,'id');
            if($params ['type']) $menu = array_merge(array(0=>array("id"=>0,"text"=>"全部栏目","ename"=>"all","parent_id"=>0)),$menu);
            ajaxReturn($menu);
        }else{
            $this->load->view('category/list');
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
            $this->load->model('category_model');
            $data['editing'] = $this->category_model->load($id);
            if (!$data['editing']){
                show_message1('无效ID:'.$id);
            }

        } else {
            $data['editing'] = array(
                'id' => null,
                'name' => null,			
                'parent_id' => null,
                'enname' => null,
                'img_url' => null,
                'sort_order' => null,
                'ctype' => 1,
                'is_display' => 1,
                'created_at' => null,
                'updated_at' => null,
            );
        }
        
		$this->load->view('category/add',$data);
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
        $enname = $this->input->post('enname',true);
        $parent_id = intval($this->input->post('parent_id'));
        $sort_order = intval($this->input->post('sort_order'));
        $ctype = intval($this->input->post('ctype'));
        $is_display = intval($this->input->post('is_display'));

        // 加载表单验证类
        $this->load->library('form_validation');

		// 设置表单数据规则
        $this->set_save_form_rules();

		// 如果提交数据符合所设置的规则，则继续运行
		if (TRUE == $this->form_validation->run()){
            
			//把数据提交给模型
            $this->load->model('category_model');
            $data['name'] = $name;
            $data['enname'] = $enname;
            $data['parent_id'] = $parent_id;
            $data['sort_order'] = $sort_order;
            $data['ctype'] = $ctype;
            $data['is_display'] = $is_display;

			// 更新资料
            if ($id){
                
				$data['id'] = $id;

                $res = $this->category_model->update($data);

				if ($res){ 
                    show_message3('操作成功');
			    }else{
                    show_message3('操作成功','操作失败',false);
				}

            // 添加新管理员
            } else {
               $res = $this->category_model->create($data);
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

			$this->load->model('category_model');
            if ($this->category_model->delete($id)){
                show_message3('操作成功');
            } else {
                show_message3('操作成功','无效ID:'.$id,false);
            }
        }
	}

    /**
     * 显示/隐藏
     *
     *
     */
    function display()
    {
        $id = (int)$this->input->post('id');
        $is_display = intval($this->input->post('is_display'));

        if ($id > 0){
            $this->load->model('category_model');
            $res = $this->category_model->update_one(array('is_display'=>$is_display),$id);

            if ($res){
                show_message3('操作成功');
            } else {
                show_message3('操作成功','操作失败:'.$id,false);
            }
        }

        show_message3('操作成功','无效ID:'.$id,false);
    }
	
	// --------------------------------------------------------------------

    /**
	 * 设置表单数据规则
	 *
	 */	
	function set_save_form_rules()
    {
        $this->form_validation->set_rules('name', '栏目名', 'trim|required');
        $this->form_validation->set_rules('enname', '英文名', 'trim|required');
        $this->form_validation->set_rules('is_display', '显示状态', 'integer');
        $this->form_validation->set_rules('sort_order', '排序', 'integer');
        $this->form_validation->set_rules('ctype', '类型', 'integer');
    }
    

}
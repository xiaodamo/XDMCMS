<?php
/**
 * 留言管理
 *
 *
 */
class Contact extends CI_Controller
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
        $this->load->model('contact_model');

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
        $counts =  $this->contact_model->counts($condition);
		$contacts = $this->contact_model->finds($condition,$per_page, ($p-1)*$per_page);

        $data = array(
            'rows'  => $contacts,
            'total' => $counts,
        );

        if (!empty( $params ['method'] ) && $params ['method'] == 'json') {
            ajaxReturn($data);
        }else{
            $this->load->view('contact/list');
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
            $this->load->model('contact_model');
            $data['editing'] = $this->contact_model->load($id);
            if (!$data['editing']){
                show_message1('无效ID:'.$id);
            }

        } else {
            $data['editing'] = array(
                'id' => null,
                'name' => null,
                'email' => null,
                'icon' => null,
                'content' => null,
                'reply' => null,
                'is_display' => 1,
                'created_at' => null,
                'updated_at' => null,
            );
        }
        
		$this->load->view('contact/add',$data);
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
        $email = $this->input->post('email',true);
        $content = $this->input->post('content',true) ;
        $url = $this->input->post('url',true) ;
        $reply = $this->input->post('reply') ;
        $is_display = intval($this->input->post('is_display'));

        // 加载表单验证类
        $this->load->library('form_validation');

		// 设置表单数据规则
        $this->set_save_form_rules();

		// 如果提交数据符合所设置的规则，则继续运行
		if (TRUE == $this->form_validation->run()){
            
			//把数据提交给模型
            $this->load->model('contact_model');            
            $data['name'] = $name;
            $data['email'] = $email;
            $data['url'] = $url;
            $data['content'] = $content;
            $data['reply'] = $reply;
            $data['is_display'] = $is_display;

			// 更新管理员资料
            if ($id){
                
				$data['id'] = $id;

                $res = $this->contact_model->update($data);

				if ($res){ 
                    show_message3('操作成功');
			    }else{
                    show_message3('操作成功','操作失败',false);
				}

            // 添加新管理员
            } else {
               $res = $this->contact_model->create($data);
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
     * 显示/隐藏
     *
     *
     */
    function display()
    {
        $id = (int)$this->input->post('id');
        $is_display = intval($this->input->post('is_display'));

        if ($id > 0){
            $this->load->model('contact_model');
            $res = $this->contact_model->update_one(array('is_display'=>$is_display),$id);

            if ($res){
                show_message3('操作成功');
            } else {
                show_message3('操作成功','操作失败:'.$id,false);
            }
        }

        show_message3('操作成功','无效ID:'.$id,false);
    }

    /**
     * 去回复
     *
     *
     */
    function replyto()
    {
        $params = $this->uri->uri_to_assoc(3);
        if (empty($params['id']) || intval($params['id']) <= 0){
            show_message1('无效ID');
        }

        $id = $params['id'];
        $this->load->model('contact_model');
        $data['editing'] = $this->contact_model->load($id);
        if (!$data['editing']){
            show_message1('无效ID:'.$id);
        }

        $this->load->view('contact/reply',$data);
    }

    /**
     * 回复
     *
     *
     */
    function reply()
    {
        $id = (int)$this->input->post('id');
        $reply = $this->input->post('reply');

        if ($id > 0){
            $this->load->model('contact_model');
            $res = $this->contact_model->update(array('reply'=>$reply,'id'=>$id));

            if ($res){
                show_message3('操作成功');
            } else {
                show_message3('操作成功','操作失败:'.$id,false);
            }
        }

        show_message3('操作成功','无效ID:'.$id,false);
    }

    /**
	 * 删除
	 *
	 *
	 */	
	function delete()
    {
		$params = $this->uri->uri_to_assoc(3);
        if (isset($params['id']) && ($id = $params['id']) > 0){

            $this->load->model('contact_model');
            if ($this->contact_model->delete($id)){
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
        $this->form_validation->set_rules('name', '昵称', 'trim|required');
        $this->form_validation->set_rules('content', '内容', 'trim|required');
        $this->form_validation->set_rules('reply', '内容', 'trim');
        $this->form_validation->set_rules('is_display', '显示状态', 'integer');
    }
    

}
<?php
/**
 * 文章
 *
 *
 */
class Article extends CI_Controller
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
        $this->load->model('article_model');

        $per_page=$this->input->post_get('rows');
        $per_page = empty( $per_page) ? 20 : $per_page;//每页显示的记录数
        //当前页
        $p = (int)$this->input->get_post('page');
        $p = $p?$p:1;

        $condition = array(
            'conditions'=>array(
                'title'  	 => $this->input->get_post('queryStr'),
                'cid'  	 => (int)$this->input->get_post('cid')?(int)$this->input->get_post('cid'):"",
            )
        );
        //总条数
        $counts =  $this->article_model->counts($condition);
        $articles = $this->article_model->finds($condition,$per_page, ($p-1)*$per_page);
        foreach ($articles as $k=>$v){
            $tarlist = $this->article_model->get_article_tarinfo($v['id']);
            $tarname = array();
            foreach ($tarlist as $kk=>$vv){
                $tarname[] = $vv['name'];
            }

            $articles[$k]['tarname'] = $tarname?implode("、",$tarname):"";
            $articles[$k]['catname'] = $this->article_model->get_category_name($v['cid']);
        }

        $data = array(
            'rows'  => $articles,
            'total' => $counts,
        );

        if (!empty( $params ['method'] ) && $params ['method'] == 'json') {
            ajaxReturn($data);
        }else{
            $this->load->view('article/list');
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
            $this->load->model('article_model');
            $data['editing'] = $this->article_model->load($id);
            if (!$data['editing']){
                show_message1('无效ID:'.$id);
            }

            $data['editing']['tars'] = $this->article_model->get_article_tarinfo($id);

        } else {
            $data['editing'] = array(
                'id' => null,
                'title' => null,
                'img_url' => null,
                'click_nums' => 0,
                'sort_order' => null,
                'is_recommand' => 0,
                'author' => 'xiaodamo',
                'status' => 2,
                'created_at' => null,
                'updated_at' => null,
                'tars' => array(),
            );
        }

        $this->load->view('article/add',$data);
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
        $cid = (int)$this->input->post('cid');
        $title = $this->input->post('title',true);
        $author = $this->input->post('author',true) ;
        $content = $this->input->post('content') ;
        $sort_order = intval($this->input->post('sort_order'));
        $is_recommand = intval($this->input->post('is_recommand'));
        $status = intval($this->input->post('status'));
        $click_nums = intval($this->input->post('click_nums'));
        $created_at = $this->input->post('created_at');
        $tids = $this->input->post('tids');
        $tidarr = explode(",",$tids);

        // 加载表单验证类
        $this->load->library('form_validation');

        // 设置表单数据规则
        $this->set_save_form_rules();

        // 如果提交数据符合所设置的规则，则继续运行
        if (TRUE == $this->form_validation->run()){

            //把数据提交给模型
            $this->load->model('article_model');
            $data['cid'] = $cid;
            $data['title'] = $title;
            $data['author'] = $author;
            $data['content'] = $content;
            $data['click_nums'] = $click_nums;
            $data['is_recommand'] = $is_recommand;
            $data['sort_order'] = $sort_order;
            $data['status'] = $status;
            $data['created_at'] = $created_at;

            // 更新管理员资料
            if ($id){

                $data['id'] = $id;

                $img_url = '';
                if (!empty($_FILES['img_url']['name'])){

                    // 定义和创建图片保存位置
                    $save_path = 'article/'.$id.'/';
                    $path = '..'.UPLOADS.$save_path;
                    mkdirsByPath($path);

                    // CI文件上传类 数据初始化
                    $config['file_name']  = date("YmdHis");
                    $config['upload_path']  = $path ;
                    $config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
                    $config['max_size'] = '10000';
                    $config['max_filename'] = '50';
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('img_url'))
                    {
                        show_message3('操作成功',$this->upload->display_errors(),false);
                    }

                    $uploaded = $this->upload->data();
                    $img_url = $save_path.$uploaded['file_name'];
                    $data['img_url'] = $img_url;

                    //删除原图
                    $user_icon = $this->article_model->load($id);
                    if (file_exists('..'.UPLOADS.$user_icon['img_url'])){
                        @unlink('..'.UPLOADS.$user_icon['img_url']);
                    }
                }

                $res = $this->article_model->update($data);

                if ($res){
                    $this->article_model->update_article_tar($id,$tidarr);
                    show_message3('操作成功');
                }else{
                    show_message3('操作成功','操作失败',false);
                }

                // 添加新管理员
            } else {
                $res = $this->article_model->create($data);
                if($res){
                        if(!empty($_FILES['img_url']['name'])){
                            // 定义和创建图片保存位置
                            $save_path = 'article/'.$res.'/';
                            $path = '..'.UPLOADS.$save_path;
                            mkdirsByPath($path);

                            // CI文件上传类 数据初始化
                            $config['file_name']  = date("YmdHis");
                            $config['upload_path']  = $path ;
                            $config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
                            $config['max_size'] = '10000';
                            $config['max_filename'] = '50';
                            $this->load->library('upload', $config);

                            if (!$this->upload->do_upload('img_url'))
                            {
                                show_message3('操作成功','员工添加成功！但头像上传失败:'.$this->upload->display_errors(),false);
                            }

                            $uploaded = $this->upload->data();
                            $icon = $save_path.$uploaded['file_name'];
                            $this->article_model->update_one(array('img_url'=>$icon),$res);
                        }

                        $this->article_model->update_article_tar($res,$tidarr);
                        show_message3('操作成功！');
                }
                show_message3('操作成功','操作失败',false);
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
     * 审核
     *
     *
     */
    function review()
    {
        $id = (int)$this->input->post('id');
        $review = intval($this->input->post('review'));
        if(!in_array($review,array(0,1))) show_message3('操作成功','操作失败:参数错误',false);

        $review = $review?2:3;

        if ($id > 0){
            $this->load->model('article_model');
            $res = $this->article_model->update_one(array('status'=>$review),$id);

            if ($res){
                show_message3('操作成功');
            } else {
                show_message3('操作成功','操作失败:'.$id,false);
            }
        }

        show_message3('操作成功','无效ID:'.$id,false);
    }


    /**
     * 审核
     *
     *
     */
    function recommand()
    {
        $id = (int)$this->input->post('id');
        $recommand = intval($this->input->post('recommand'));

        if ($id > 0){
            $this->load->model('article_model');
            $res = $this->article_model->update_one(array('is_recommand'=>$recommand),$id);

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

            $this->load->model('article_model');
            if ($this->article_model->delete($id)){
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
        $this->form_validation->set_rules('cid', '所属栏目', 'integer|required');
        $this->form_validation->set_rules('title', '文章标题', 'trim|required');
        $this->form_validation->set_rules('author', '作者', 'trim|required');
        $this->form_validation->set_rules('is_recommand', '是否推荐', 'integer');
        $this->form_validation->set_rules('status', '审核状态', 'integer');
        $this->form_validation->set_rules('sort_order', '排序', 'integer');
        $this->form_validation->set_rules('click_nums', '点击量', 'integer');
    }


}
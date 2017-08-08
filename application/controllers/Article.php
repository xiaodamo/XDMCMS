<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends Base_Controller {

	/**
     *
	 */
    function __construct()
    {
        parent::__construct();
    }

	public function index()
	{

        $current = $this->router->class;
        $data = $this->webset;
        $this->load->model('category_model');
        $data['category'] = $this->category_model->finds(array(),0,0);
        $data['current'] = $current;
        $data['artinfo'] = array();
        foreach ($data['category'] as $v){
            if($v['enname'] === $current){
                $data['artinfo'] = $v;
                break;
            }
        }

        if($data['artinfo']){
            // 每页显示数据条数
            $per_page = 10;
            $p = (int)$this->uri->segment(3);
            $p = $p?$p:1;
            switch (intval($data['artinfo']['ctype'])){
                case 1:
                    $this->load->model('article_model');
                    $this->load->model('target_model');
                    $cid = intval($data['artinfo']['id']);
                    $pid = intval($data['artinfo']['parent_id']);

                    if($pid){
                        $conditions1 = array('conditions'=>array(
                            'cid'=>$cid,
                            'status'=>2,
                            'catname'=>true
                        ));
                        $conditions2 = array('conditions'=>array(
                            'cid'=>$cid,
                            'is_recommand'=>1,
                            'status'=>2
                        ));
                        $conditions3 = array('conditions'=>array(
                            'cid'=>$cid,
                            'status'=>2,
                        ));
                    }else{
                        $cid_arr = array($cid);
                        foreach ($data['category'] as $cat){
                            if($cat['parent_id'] == $cid){
                                $cid_arr[] = $cat['id'];
                            }
                        }

                        $conditions1 = array('conditions'=>array(
                            'cid_in'=>$cid_arr,
                            'status'=>2,
                            'catname'=>true
                        ));
                        $conditions2 = array('conditions'=>array(
                            'cid_in'=>$cid_arr,
                            'is_recommand'=>1,
                            'status'=>2
                        ));
                        $conditions3 = array('conditions'=>array(
                            'cid_in'=>$cid_arr,
                            'status'=>2,
                        ));
                    }

                    $data['articles'] = $this->article_model->finds($conditions1,$per_page,($p-1)*$per_page);

                    $count = $this->article_model->counts($conditions1);
                    //二级分类
                    $data['category1'] = $this->category_model->finds(array('conditions'=>array('parent_id'=>$cid,'is_display'=>1)),0,0);
                    //文章推荐
                    $data['recommand'] = $this->article_model->finds($conditions2,10,0);
                    //点击排行
                    $data['hotviews'] = $this->article_model->finds($conditions3,10,0,'click_nums desc');

                    //热门标签
                    $data['tags'] = $this->target_model->finds(array('conditions'=>array('is_display'=>1)),20,0);

                    $this->load->library('pagination');

                    $config['base_url'] = "/$current/page/";
                    $config['total_rows'] = $count;
                    $config['per_page'] = $per_page;

                    $this->pagination->initialize($config);
                    $pages = $this->pagination->create_links();
                    $data['pages'] = $pages?'<a title="Total record"><b>'.$count.'</b></a>'.$pages:'';

                    $this->load->view('article',$data);
                    break;
                case 3:
                    $this->load->model('article_model');
                    $data['articles'] = $this->article_model->finds(array('conditions'=>array('cid'=>3,'status'=>2)),50,0,'created_at desc');
                    $this->load->view('diary',$data);
                    break;
                case 4:
                    $this->load->model('contact_model');

                    $data['articles'] = $this->contact_model->finds(array('conditions'=>array('is_display'=>1)),$per_page,($p-1)*$per_page);
                    $count = $this->contact_model->counts(array('conditions'=>array('is_display'=>1)));
                    $this->load->library('pagination');
                    $config['base_url'] = "/$current/page/";
                    $config['total_rows'] = $count;
                    $config['per_page'] = $per_page;

                    $this->pagination->initialize($config);
                    $pages = $this->pagination->create_links();
                    $data['pages'] = $pages?'<a title="Total record"><b>'.$count.'</b></a>'.$pages:'';
                    $data['total_nums'] = $count;
                    $this->load->view('contact',$data);
                    break;
                case 5:
                    $this->load->model('about_model');
                    $data['about'] = $this->about_model->load(1);
                    $this->load->view('aboutme',$data);
                    break;
                default:
                    $this->load->view('404');
            }

        }else{
            $this->load->view('404');
        }

	}

	public function detail($id=0){
        $id = intval($id);
        if(!$id){
            return $this->load->view('404');
        }

        $data = $this->webset;
        $this->load->model('category_model');
        $this->load->model('article_model');
        $this->load->model('target_model');
        //添加访问记录
        $this->article_model->click_nums($id);

        $data['article'] = $this->article_model->load($id);
        if(!$data['article']){
            return $this->load->view('404');
        }
        $data['category'] = $this->category_model->finds(array(),0,0);
        $data['artinfo'] = array();
        foreach ($data['category'] as $v){
            if($v['id'] === $data['article']['cid']){
                $data['artinfo'] = $v;
                break;
            }
        }

        //二级分类
        $data['category1'] = $this->category_model->finds(array('conditions'=>array('parent_id'=>$data['article']['cid'],'is_display'=>1)),0,0);
        //文章推荐
        $data['recommand'] = $this->article_model->finds(array('conditions'=>array('cid'=>$data['article']['cid'],'is_recommand'=>1,'status'=>2)),10,0);
        //点击排行
        $data['hotviews'] = $this->article_model->finds(array('conditions'=>array('cid'=>$data['article']['cid'],'status'=>2)),10,0,'click_nums desc');

        //热门标签
        $data['tags'] = $this->target_model->finds(array('conditions'=>array('is_display'=>1)),20,0);

        //相关标签
        $data['mytag'] = $this->article_model->get_article_tarinfo($id);

        //上下篇文章
        $alongs = $this->article_model->get_along_article($id,intval($data['artinfo']['id']));
        $data['alongs'] = array(
            'pre' => '没有了',
            'next' => '没有了',
        );
        foreach ($alongs as $along){
            if($along['id']>$id){
                $data['alongs']['next'] = "<a href='/article/detail/".$along['id']."' title='".$along['title']."'>".$along['title']."</a>";
            }else{
                $data['alongs']['pre'] =  "<a href='/article/detail/".$along['id']."' title='".$along['title']."'>".$along['title']."</a>";
            }
        }

        $this->load->view('detail',$data);
    }

    public function search(){
        $q = $this->input->get('q',true);

        $current = $this->router->method;
        $data = $this->webset;
        $this->load->model('category_model');
        $this->load->model('target_model');
        $data['category'] = $this->category_model->finds(array(),0,0);
        $data['current'] = $current;

        // 每页显示数据条数
        $per_page = 10;
        $p = (int)$this->uri->segment(4);
        $p = $p?$p:1;

        $this->load->model('article_model');
        $this->load->model('target_model');
        $data['articles'] = $this->article_model->finds(array('conditions'=>array('title'=>$q,'status'=>2)),$per_page,($p-1)*$per_page);
        $count = $this->article_model->counts(array('conditions'=>array('title'=>$q,'status'=>2)));
        foreach ($data['articles'] as $k=>$v){
            $catinfo = $this->category_model->load($v['cid']);
            $data['articles'][$k]['enname'] = $catinfo['enname'];
            $data['articles'][$k]['name'] = $catinfo['name'];
        }
        //文章推荐
        $data['recommand'] = $this->article_model->finds(array('conditions'=>array('is_recommand'=>1,'status'=>2)),10,0);
        //点击排行
        $data['hotviews'] = $this->article_model->finds(array('conditions'=>array('nocid'=>array(3,15),'status'=>2)),10,0,'click_nums desc');

        //热门标签
        $data['tags'] = $this->target_model->finds(array('conditions'=>array('is_display'=>1)),20,0);

        $this->load->library('pagination');

        $config['reuse_query_string'] = TRUE;
        $config['uri_segment'] = 4;
        $config['base_url'] = "/article/$current/page/";
        $config['total_rows'] = $count;
        $config['per_page'] = $per_page;

        $this->pagination->initialize($config);
        $pages = $this->pagination->create_links();
        $data['pages'] = $pages?'<a title="Total record"><b>'.$count.'</b></a>'.$pages:'';

        $data['type'] = "search";

        $this->load->view('tags',$data);


    }

    public function ajaxContact(){
        $name = $this->input->post('author',true);
        $email = $this->input->post('email',true);
        $content = $this->input->post('newcomment',true) ;
        $url = $this->input->post('url',true) ;

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

            $res = $this->contact_model->create($data);
            if($res){
                show_message3('操作成功');
            }else{
                show_message3('操作成功','操作失败',false);
            }

            // 对提交的数据不符合表单验证规则情况的处理
        }else{
            //获取错误信息
            $errorinfo = $this->form_validation->error_string();
            show_message3('操作成功',$errorinfo,false);
        }
    }

    /**
     * 设置表单数据规则
     *
     */
    function set_save_form_rules()
    {
        $this->form_validation->set_rules('author', '昵称', 'trim|required');
        $this->form_validation->set_rules('email', '邮箱', 'trim|required|valid_email');
        $this->form_validation->set_rules('comment', '内容', 'trim|required');
    }
}

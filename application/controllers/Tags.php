<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tags extends Base_Controller {

	/**
     *
	 */
    function __construct()
    {
        parent::__construct();
    }

	public function index($id)
	{
        $id = intval($id);
        if(!$id){
            return $this->load->view('404');
        }
        $current = $this->router->class;
        $data = $this->webset;
        $this->load->model('category_model');
        $this->load->model('target_model');
        $data['info'] = $this->target_model->load($id);
        if(!$data['info']){
            return $this->load->view('404');
        }
        $data['category'] = $this->category_model->finds(array(),0,0);
        $data['current'] = $current;

        // 每页显示数据条数
        $per_page = 10;
        $p = (int)$this->uri->segment(5);
        $p = $p?$p:1;

        $this->load->model('article_model');
        $this->load->model('target_model');
        $data['articles'] = $this->article_model->get_tar_articles($id,$per_page,($p-1)*$per_page);
        $count = $this->article_model->get_tar_artcount($id);
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

        $config['uri_segment'] = 5;
        $config['base_url'] = "/$current/index/$id/page/";
        $config['total_rows'] = $count;
        $config['per_page'] = $per_page;

        $this->pagination->initialize($config);
        $pages = $this->pagination->create_links();
        $data['pages'] = $pages?'<a title="Total record"><b>'.$count.'</b></a>'.$pages:'';
        $data['type'] = "tags";

        $this->load->view('tags',$data);

	}

}

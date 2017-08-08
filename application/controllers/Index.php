<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Base_Controller {

	/**
     *
	 */
    function __construct()
    {
        parent::__construct();
    }

	public function index()
	{

        $data = $this->webset;
        $this->load->model('notification_model');
        $data['notification'] = $this->notification_model->load(1);

        $this->load->model('category_model');
        $data['category'] = $this->category_model->finds(array('conditions'=>array('parent_id'=>0,'is_display'=>1)),0,0);
        $data['current'] = $this->router->method;
        $this->load->model('article_model');
        //热门资源下载
        $data['hot_download'] = $this->article_model->finds(array('conditions'=>array('cid'=>16,'status'=>2)),5,0);

        //最新文章10条
        $data['news'] = $this->article_model->finds(array('conditions'=>array('nocid'=>array(3,15),'status'=>2,'catname'=>true)),10,0,'created_at desc');

        //学海无涯分类
        $data['category1'] = $this->category_model->finds(array('conditions'=>array('parent_id'=>2,'is_display'=>1)),8,0);

        //杂七杂八分类
        $data['category2'] = $this->category_model->finds(array('conditions'=>array('parent_id'=>4,'is_display'=>1)),8,0);

        $this->load->model('target_model');
        //热门标签
        $data['tags'] = $this->target_model->finds(array('conditions'=>array('is_display'=>1)),8,0);

        //碎言碎语
        $data['xinqing'] = $this->article_model->finds(array('conditions'=>array('cid'=>15,'status'=>2)),1,0,'created_at desc');

        //文章推荐
        $data['recommand'] = $this->article_model->finds(array('conditions'=>array('is_recommand'=>1,'status'=>2)),10,0);

        //点击排行
        $data['hotviews'] = $this->article_model->finds(array('conditions'=>array('nocid'=>array(3,15),'status'=>2)),10,0,'click_nums desc');

        //友情链接
        $this->load->model('friends_model');
        $data['friends'] = $this->friends_model->finds(array('conditions'=>array('is_display'=>1)),12,0);

        $this->load->view('index',$data);
	}
}

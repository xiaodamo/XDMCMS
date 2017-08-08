<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends Base_Controller {

	/**
     *
	 */
    function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
        $this->load->model('test_model', 'test');
//        $data = $this->test->get_all();
        $data = $this->test->get_many_by("name like '%吕%'");
        var_dump($data);exit;
//        $data = $this->test->query_sql("select t2.id,t2.name from __article_tar t1 inner join __target t2 on t1.tid =  t2.id
//                inner join __article t3 on t1.aid = t3.id where t2.is_display=? and t1.aid = ?",array(1,2));
        //$data = $this->test->insert(array( 'name' => 'blah','tel'=>'1335435345435' ));


        $str = '{"title":"状态","type":{"obj":"select","default":1,"option":{"1":"未审核","2":"审核通过","3":"审核不通过"}},"rule":"trim|integer"}';


        var_dump(json_decode($str,true));exit;
        $this->load->view('tags',$data);

	}

	public function test(){
        $str = "[1,2,3,4,5,6,7,8,9]";
        var_dump(json_decode($str));
    }

}

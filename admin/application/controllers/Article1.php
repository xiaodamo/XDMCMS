<?php

/**
 * 文章
 */
class Article1 extends Base_Controller
{
    protected $my_model = 'article1_model';
    protected $fields = array (
  'fields' => 
  array (
    0 => 
    array (
      'field' => 'id',
      'key' => 'PRI',
      'comment' => '{"obj":{"type":"text","title":"\\u72b6\\u6001","default":"","placeholder":""},"list":{"display":"hidden"},"add":{"display":"hidden","rule":"trim|integer"}}',
    ),
    1 => 
    array (
      'field' => 'cid',
      'key' => '',
      'comment' => '{"obj":{"type":"combotree","title":"\\u680f\\u76ee","default":"","placeholder":"","with":["\\/admin\\/category\\/index\\/method\\/json"]},"list":{"display":false,"search":true},"add":{"display":true,"rule":"trim|integer|required"}}',
    ),
    2 =>
    array (
      'field' => 'title',
      'key' => '',
      'comment' => '{"obj":{"type":"text","title":"\\u6807\\u9898","default":"","placeholder":"\\u8bf7\\u8f93\\u5165\\u6807\\u9898"},"list":{"display":true,"search":true},"add":{"display":true,"rule":"trim|required"}}',
    ),
    3 =>
    array (
      'field' => 'img_url',
      'key' => '',
      'comment' => '{"obj":{"type":"image","title":"\\u7f29\\u7565\\u56fe"},"list":{"display":true},"add":{"display":true}}',
    ),
    4 =>
    array (
      'field' => 'author',
      'key' => '',
      'comment' => '{"obj":{"type":"text","title":"\\u4f5c\\u8005","default":"xiaodamo","placeholder":""},"list":{"display":false},"add":{"display":true,"rule":"trim|required"}}',
    ),
    5 =>
    array (
      'field' => 'sort_order',
      'key' => '',
      'comment' => '{"obj":{"type":"number","title":"\\u6392\\u5e8f","default":"0","placeholder":""},"list":{"display":true,"sortable":true},"add":{"display":true,"rule":"trim|integer"}}',
    ),
    6 =>
    array (
      'field' => 'click_nums',
      'key' => '',
      'comment' => '{"obj":{"type":"number","title":"\\u70b9\\u51fb\\u91cf","default":"0","placeholder":""},"list":{"display":true,"sortable":true},"add":{"display":true,"rule":"trim|integer"}}',
    ),
    7 =>
    array (
      'field' => 'is_recommand',
      'key' => '',
      'comment' => '{"obj":{"type":"radio","option":{"1":"\\u662f","0":"\\u5426"},"title":"\\u662f\\u5426\\u63a8\\u8350","default":"0"},"list":{"display":true,"sortable":true,"toolbar":"fa-thumbs-o-up|\\u63a8\\u8350\\/\\u4e0d\\u63a8\\u8350"},"add":{"display":true,"rule":"trim|integer"}}',
    ),
    8 =>
    array (
      'field' => 'status',
      'key' => '',
      'comment' => '{"obj":{"type":"radio","option":{"1":"\\u672a\\u5ba1\\u6838","2":"\\u5ba1\\u6838\\u901a\\u8fc7","3":"\\u5ba1\\u6838\\u4e0d\\u901a\\u8fc7"},"title":"\\u5ba1\\u6838\\u72b6\\u6001","default":"2"},"list":{"display":true,"sortable":true,"toolbar":{"2":"fa-check|\\u5ba1\\u6838\\u901a\\u8fc7","3":"fa-close|\\u5ba1\\u6838\\u4e0d\\u901a\\u8fc7"}},"add":{"display":true,"rule":"trim|integer"}}',
    ),
    9 =>
    array (
      'field' => 'content',
      'key' => '',
      'comment' => '{"obj":{"type":"ueditor","title":"\\u5185\\u5bb9","default":"","placeholder":"\\u8bf7\\u8f93\\u5165\\u5185\\u5bb9"},"list":{"display":false},"add":{"display":true,"rule":"trim|required"}}',
    ),
    10 =>
    array (
      'field' => 'created_at',
      'key' => '',
      'comment' => '{"obj":{"type":"datetime","title":"\\u521b\\u5efa\\u65f6\\u95f4","default":""},"list":{"display":true,"sortable":true},"add":{"display":true}}',
    ),
    11 =>
    array (
      'field' => 'updated_at',
      'key' => '',
      'comment' => '{"obj":{"type":"datetime","title":"\\u521b\\u5efa\\u65f6\\u95f4","default":""},"list":{"display":true,"sortable":true},"add":{"display":false}}',
    ),
  ),
  'table_comment' => '文章',
);

    function __construct()
    {
       parent::__construct();
    }

    function test(){

    }


}
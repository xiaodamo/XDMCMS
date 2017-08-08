<?php

/**
 * 活动
 */
class Activity extends Base_Controller
{
    protected $my_model = 'activity_model';
    protected $fields = array (
  'fields' => 
  array (
    0 => 
    array (
      'field' => 'id',
      'key' => 'PRI',
      'comment' => '{"obj":{"type":"text","title":"ID","default":""},"list":{"display":"hidden","search":true,"sortable":false,"toolbar":""},"add":{"display":"hidden","rule":"integer"}}',
    ),
    1 => 
    array (
      'field' => 'name',
      'key' => '',
      'comment' => '{"obj":{"type":"text","title":"\\u59d3\\u540d","default":""},"list":{"display":true,"search":true,"sortable":false,"toolbar":""},"add":{"display":true,"rule":"trim|required"}}',
    ),
    2 => 
    array (
      'field' => 'mobile',
      'key' => '',
      'comment' => '{"obj":{"type":"text","title":"\\u624b\\u673a\\u53f7","default":""},"list":{"display":true,"search":false,"sortable":false,"toolbar":""},"add":{"display":true,"rule":"trim|required"}}',
    ),
    3 => 
    array (
      'field' => 'icon',
      'key' => '',
      'comment' => '{"obj":{"type":"image","title":"\\u56fe\\u7247","default":""},"list":{"display":true,"search":false,"sortable":false,"toolbar":""},"add":{"display":true,"rule":""}}',
    ),
  ),
  'table_comment' => '活动',
);

    function __construct()
    {
       parent::__construct();
    }


}
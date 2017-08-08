<?php

require_once APP_COMMON . 'core/Base_Model.php';

/**
 * 文章
 */
class Article1_model extends Base_Model
{
   protected $_table = 'article1';
   public $before_update = array( 'updated_at' );
   public $before_create = array('created_at','updated_at');
   public $validate = array(
      array('field'  => 'id',
            'label'  => '状态',
            'rules'  => 'trim|integer'),
      array('field'  => 'cid',
            'label'  => '栏目',
            'rules'  => 'trim|integer|required'),
      array('field'  => 'title',
            'label'  => '标题',
            'rules'  => 'trim|required'),
      array('field'  => 'author',
            'label'  => '作者',
            'rules'  => 'trim|required'),
      array('field'  => 'sort_order',
            'label'  => '排序',
            'rules'  => 'trim|integer'),
      array('field'  => 'click_nums',
            'label'  => '点击量',
            'rules'  => 'trim|integer'),
      array('field'  => 'is_recommand',
            'label'  => '是否推荐',
            'rules'  => 'trim|integer'),
      array('field'  => 'status',
            'label'  => '审核状态',
            'rules'  => 'trim|integer'),
      array('field'  => 'content',
            'label'  => '内容',
            'rules'  => 'trim|required'),

    );
}
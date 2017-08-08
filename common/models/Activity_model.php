<?php

require_once APP_COMMON . 'core/Base_Model.php';

/**
 * 活动
 */
class Activity_model extends Base_Model
{
   protected $_table = 'activity';
   public $validate = array(
      array('field'  => 'id',
            'label'  => 'ID',
            'rules'  => 'integer'),
      array('field'  => 'name',
            'label'  => '姓名',
            'rules'  => 'trim|required'),
      array('field'  => 'mobile',
            'label'  => '手机号',
            'rules'  => 'trim|required'),

    );
}
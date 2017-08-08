<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 自动生成数据表
 */
$config['table']['article1'] = array (
  'fields' => 
  array (
    'id' => 
    array (
      'type' => 'INT',
      'constraint' => 11,
      'unsigned' => true,
      'auto_increment' => true,
      'primary_key' => true,
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'text',
          'title' => '状态',
          'default' => '',
          'placeholder' => '',
        ),
        'list' => 
        array (
          'display' => 'hidden',
        ),
        'add' => 
        array (
          'display' => 'hidden',
          'rule' => 'trim|integer',
        ),
      ),
    ),
    'cid' => 
    array (
      'type' => 'INT',
      'constraint' => 11,
      'unsigned' => true,
      'default' => 0,
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'combotree',
          'title' => '栏目',
          'default' => '',
          'placeholder' => '',
          'with' => 
          array (
            0 => '/admin/category/index/method/json',
          ),
        ),
        'list' => 
        array (
          'display' => false,
          'search' => true,
        ),
        'add' => 
        array (
          'display' => true,
          'rule' => 'trim|integer|required',
        ),
      ),
    ),
    'title' => 
    array (
      'type' => 'VARCHAR',
      'constraint' => '200',
      'default' => '',
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'text',
          'title' => '标题',
          'default' => '',
          'placeholder' => '请输入标题',
        ),
        'list' => 
        array (
          'display' => true,
          'search' => true,
        ),
        'add' => 
        array (
          'display' => true,
          'rule' => 'trim|required',
        ),
      ),
    ),
    'img_url' => 
    array (
      'type' => 'VARCHAR',
      'constraint' => '255',
      'default' => '',
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'image',
          'title' => '缩略图',
        ),
        'list' => 
        array (
          'display' => true,
        ),
        'add' => 
        array (
          'display' => true,
        ),
      ),
    ),
    'author' => 
    array (
      'type' => 'VARCHAR',
      'constraint' => '64',
      'default' => '',
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'text',
          'title' => '作者',
          'default' => 'xiaodamo',
          'placeholder' => '',
        ),
        'list' => 
        array (
          'display' => false,
        ),
        'add' => 
        array (
          'display' => true,
          'rule' => 'trim|required',
        ),
      ),
    ),
    'sort_order' => 
    array (
      'type' => 'INT',
      'constraint' => 11,
      'default' => 0,
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'number',
          'title' => '排序',
          'default' => '0',
          'placeholder' => '',
        ),
        'list' => 
        array (
          'display' => true,
          'sortable' => true,
        ),
        'add' => 
        array (
          'display' => true,
          'rule' => 'trim|integer',
        ),
      ),
    ),
    'click_nums' => 
    array (
      'type' => 'INT',
      'constraint' => 11,
      'default' => 0,
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'number',
          'title' => '点击量',
          'default' => '0',
          'placeholder' => '',
        ),
        'list' => 
        array (
          'display' => true,
          'sortable' => true,
        ),
        'add' => 
        array (
          'display' => true,
          'rule' => 'trim|integer',
        ),
      ),
    ),
    'is_recommand' => 
    array (
      'type' => 'TINYINT',
      'constraint' => 1,
      'default' => 0,
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'radio',
          'option' => 
          array (
            1 => '是',
            0 => '否',
          ),
          'title' => '是否推荐',
          'default' => '0',
        ),
        'list' => 
        array (
          'display' => true,
          'sortable' => true,
          'toolbar' => 'fa-thumbs-o-up|推荐/不推荐',
        ),
        'add' => 
        array (
          'display' => true,
          'rule' => 'trim|integer',
        ),
      ),
    ),
    'status' => 
    array (
      'type' => 'TINYINT',
      'constraint' => 1,
      'default' => 0,
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'radio',
          'option' => 
          array (
            1 => '未审核',
            2 => '审核通过',
            3 => '审核不通过',
          ),
          'title' => '审核状态',
          'default' => '2',
        ),
        'list' => 
        array (
          'display' => true,
          'sortable' => true,
          'toolbar' => 
          array (
            2 => 'fa-check|审核通过',
            3 => 'fa-close|审核不通过',
          ),
        ),
        'add' => 
        array (
          'display' => true,
          'rule' => 'trim|integer',
        ),
      ),
    ),
    'content' => 
    array (
      'type' => 'TEXT',
      'null' => true,
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'ueditor',
          'title' => '内容',
          'default' => '',
          'placeholder' => '请输入内容',
        ),
        'list' => 
        array (
          'display' => false,
        ),
        'add' => 
        array (
          'display' => true,
          'rule' => 'trim|required',
        ),
      ),
    ),
    'created_at' => 
    array (
      'type' => 'DATETIME',
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'datetime',
          'title' => '创建时间',
          'default' => '',
        ),
        'list' => 
        array (
          'display' => true,
          'sortable' => true,
        ),
        'add' => 
        array (
          'display' => true,
        ),
      ),
    ),
    'updated_at' => 
    array (
      'type' => 'DATETIME',
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'datetime',
          'title' => '创建时间',
          'default' => '',
        ),
        'list' => 
        array (
          'display' => true,
          'sortable' => true,
        ),
        'add' => 
        array (
          'display' => false,
        ),
      ),
    ),
  ),
  'attributes' => 
  array (
    'ENGINE' => 'InnoDB',
    'DEFAULT CHARACTER SET' => 'utf8',
    'COLLATE' => 'utf8_general_ci',
    'COMMENT' => '"文章"',
  ),
);

$config['table']['activity'] = array (
  'fields' => 
  array (
    'id' => 
    array (
      'type' => 'int',
      'constraint' => '11',
      'auto_increment' => true,
      'primary_key' => true,
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'text',
          'title' => 'ID',
          'default' => '',
        ),
        'list' => 
        array (
          'display' => 'hidden',
          'search' => true,
          'sortable' => false,
          'toolbar' => '',
        ),
        'add' => 
        array (
          'display' => 'hidden',
          'rule' => 'integer',
        ),
      ),
    ),
    'name' => 
    array (
      'type' => 'varchar',
      'constraint' => '64',
      'default' => '',
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'text',
          'title' => '姓名',
          'default' => '',
        ),
        'list' => 
        array (
          'display' => true,
          'search' => true,
          'sortable' => false,
          'toolbar' => '',
        ),
        'add' => 
        array (
          'display' => true,
          'rule' => 'trim|required',
        ),
      ),
    ),
    'mobile' => 
    array (
      'type' => 'varchar',
      'constraint' => '11',
      'default' => '',
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'text',
          'title' => '手机号',
          'default' => '',
        ),
        'list' => 
        array (
          'display' => true,
          'search' => false,
          'sortable' => false,
          'toolbar' => '',
        ),
        'add' => 
        array (
          'display' => true,
          'rule' => 'trim|required',
        ),
      ),
    ),
    'icon' => 
    array (
      'type' => 'varchar',
      'constraint' => '200',
      'default' => '',
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'image',
          'title' => '图片',
          'default' => '',
        ),
        'list' => 
        array (
          'display' => true,
          'search' => false,
          'sortable' => false,
          'toolbar' => '',
        ),
        'add' => 
        array (
          'display' => true,
          'rule' => '',
        ),
      ),
    ),
  ),
  'attributes' => 
  array (
    'ENGINE' => 'InnoDB',
    'DEFAULT CHARACTER SET' => 'utf8',
    'COLLATE' => 'utf8_general_ci',
    'COMMENT' => '"活动"',
  ),
);

$config['table']['homesub'] = array (
  'fields' => 
  array (
    'id' => 
    array (
      'type' => 'int',
      'constraint' => '11',
      'auto_increment' => true,
      'primary_key' => true,
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'text',
          'title' => 'ID',
          'default' => '',
        ),
        'list' => 
        array (
          'display' => 'hidden',
          'search' => true,
          'sortable' => false,
          'toolbar' => '',
        ),
        'add' => 
        array (
          'display' => 'hidden',
          'rule' => 'integer',
        ),
      ),
    ),
    'name' => 
    array (
      'type' => 'varchar',
      'constraint' => '64',
      'default' => '',
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'text',
          'title' => '姓名',
          'default' => '',
        ),
        'list' => 
        array (
          'display' => true,
          'search' => true,
          'sortable' => false,
          'toolbar' => '',
        ),
        'add' => 
        array (
          'display' => true,
          'rule' => 'trim|required',
        ),
      ),
    ),
    'sno' => 
    array (
      'type' => 'varchar',
      'constraint' => '30',
      'default' => '',
      'comment' => 
      array (
        'obj' => 
        array (
          'type' => 'text',
          'title' => '身份证',
          'default' => '',
        ),
        'list' => 
        array (
          'display' => true,
          'search' => false,
          'sortable' => false,
          'toolbar' => '',
        ),
        'add' => 
        array (
          'display' => true,
          'rule' => 'trim|required',
        ),
      ),
    ),
  ),
  'attributes' => 
  array (
    'ENGINE' => 'InnoDB',
    'DEFAULT CHARACTER SET' => 'utf8',
    'COLLATE' => 'utf8_general_ci',
    'COMMENT' => '"主题"',
  ),
);


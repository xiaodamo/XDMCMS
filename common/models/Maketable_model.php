<?php
/**
 * 关于
 *
 *
 */
require_once APP_COMMON . 'core/Base_Model.php';
class Maketable_model extends Base_Model
{

    public $_database = 'dati';
    protected $_table = 'person';

	function __construct($table="",$database="")
    {
        $this->_database = $database;
        $this->_table = $table;
        parent::__construct();
    }


}
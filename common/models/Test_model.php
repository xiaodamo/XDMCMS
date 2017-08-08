<?php
/**
 * 关于
 *
 *
 */
require_once APP_COMMON . 'core/Base_Model.php';
class Test_model extends Base_Model
{

    public $_database = 'dati';
    protected $_table = 'person';
    public $protected_attributes = array( 'id');

	function __construct()
    {
        parent::__construct();
    }


}
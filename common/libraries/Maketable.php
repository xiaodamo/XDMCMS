<?php


class Maketable{

    private $__CI;

    private $_database = "";

    private $_table;

    private $_content;

    private $_primary_key;

    public function __construct($table_name="",$database="")
    {
      $this->__CI = &get_instance();
      $this->_table = $table_name;

      if($database){
          $this->_database = $this->__CI->load->database($database,TRUE);
      }
      $this->__CI->load->dbforge($this->_database);
    }


    function create($content = array()){
          $this->_content = $content;
          if(!$this->_content) exit("table is empty!");

          $fields = empty($this->_content['fields'])?array():$this->_content['fields'];
          foreach ($fields as $k=>$v){
             if(isset($v['primary_key']) && $v['primary_key']===true){
                 $this->_primary_key[] = $k;
             }

             if(in_array(strtolower($v['type']),array('text','date','datetime'))){
                 unset($fields[$k]['constraint']);
                 unset($fields[$k]['default']);
             }

             if(in_array(strtolower($v['type']),array('int','tinyint','smallint','mediumint','bigint')) && $fields[$k]['default']=="" ){
                 unset($fields[$k]['default']);
             }

             if(isset($v['comment'])){
                 $fields[$k]['comment'] = json_encode($v['comment'],TRUE);
             }
          }

          if(!$this->_primary_key) exit("table is not has primary key!");

          $attributes = empty($this->_content['attributes'])?array():$this->_content['attributes'];

          $this->delete(TRUE);
          $res = $this->__CI->dbforge->add_field($fields)->add_key($this->_primary_key,TRUE)->create_table($this->_table, TRUE, $attributes);

          return $res;
    }

    /**
     * $fields = array(
     * 'preferences' => array('type' => 'TEXT')
     * );
     */
    function add_colum($fields = array()){
        $this->__CI->dbforge->add_column($this->_table, $fields);
    }

    function drop_column($field = ""){
        $this->__CI->dbforge->drop_column($this->_table, $field);
    }

    /**
     * $fields = array(
     *   'old_name' => array(
     *   'name' => 'new_name',
     *   'type' => 'TEXT',
     *   ),
     *   );
     */
    function modify_column($fields = array()){
        $this->__CI->dbforge->modify_column($this->_table, $fields);
    }

    function rename($new_name=""){
        $this->__CI->dbforge->rename_table($this->_table, $new_name);
    }

    function delete($exist = FALSE){
        $this->__CI->dbforge->drop_table($this->_table,$exist);
    }

    function read_table(){
        $this->__CI->load->model("maketable_model");
        $maketable = new Maketable_model($this->_table,$this->_database);
        $dbname = $this->_database?$this->_database->database:$this->__CI->db->database;
        $data['fields'] = $maketable->query_sql("SELECT  COLUMN_NAME AS `field` ,COLUMN_KEY AS `key` ,DATA_TYPE AS `type` ,COLUMN_TYPE AS `size`, COLUMN_COMMENT AS `comment` FROM information_schema.`COLUMNS` WHERE TABLE_NAME = '__".$this->_table."' AND table_schema = '".$dbname."'");
        $table_comment = $maketable->query_sql("SELECT  `TABLE_COMMENT` AS `table_comment` FROM information_schema.`TABLES` WHERE TABLE_NAME = '__".$this->_table."' AND table_schema = '".$dbname."'");
        $data['table_comment'] = $table_comment?$table_comment[0]['table_comment']:'';
        return $data;
    }


}


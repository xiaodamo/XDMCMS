<?php
/**
 * 栏目
 *
 *
 */
class Category_model extends CI_Model
{

    private $table_name = 'category';

	function __construct()
    {
        parent::__construct();
    }

    //-------------------------------------------------------------------------
    function load($id)
    {
        if (!$id){
            return array();
        }
        $query = $this->db->get_where($this->table_name,array('id' => $id));
        if ($row = $query->row_array()){
            return $row;
        }
        return array();
    }
    
    
    function finds($options = array(),$count=20, $offset=0)
    {
        if (!is_array($options)){
            return array();
        }

        if (!empty($options['conditions'])){
            foreach($options['conditions'] as $key => $value ):
            if($value!==""){
                switch($key){
                    case 'name' : 
                            $this->db->like($key,$value);break;
                    default : 
                            $this->db->where($key,$value);
                }
            }
            endforeach;
        }
        
        if ($count){
            $this->db->limit((int)$count, (int)$offset);
        }
    
        $this->db->order_by('sort_order asc');
    
        $query = $this->db->select('id,name as text,enname,parent_id,img_url,sort_order,ctype,is_display,is_display as cansee,created_at,updated_at')->from($this->table_name)->get();
        
        return $query->result_array();
        
    }
    
    /**
     * 总数
     *
     *
     */
    function counts($options = array())
    {
        if (!is_array($options)){
            return array();
        }
        
        if (!empty($options['conditions'])){
            foreach($options['conditions'] as $key => $value ):
            if($value!==""){
                switch($key){
                    case 'name' : 
                            $this->db->like($key,$value);break;
                    default : 
                            $this->db->where($key,$value);
                }
            }
            endforeach;
        }
        
        $query = $this->db->select('COUNT(DISTINCT(id)) as total')->from($this->table_name)->get();
    
        $total = 0;
        if ($row = $query->row_array()){
            $total = (int)$row['total'];
        }
        return $total;
    }
    
    /**
     * 添加
     *
     *
     */
    function create($data)
    {
        if(empty($data) || !is_array($data)){
            return false;
        }
        $datetime = time();
        $data['created_at'] = $datetime;
        $data['updated_at'] = $datetime;
        $table = $this->db->dbprefix.$this->table_name;
        //过滤字符防sql注入
        foreach ($data as $key => $val) {
            $data[$key] = $this->db->escape_str($val);
        }
        $keys = "`" . implode("`,`", array_keys($data)) . "`";
        $values = "'" . implode("','", array_values($data)) . "'";
        $sql = "insert into {$table} ({$keys}) values({$values}) ";
        $this->db->query($sql);
    
        return $this->db->insert_id();
    }
    
    /**
     * 更新
     *
     *
     */
    function update($data)
    {
        if(empty($data) || !is_array($data)){
            return false;
        }
        $id = intval($data['id']);
        unset($data['id']);
        $data['updated_at'] = time();
        $table = $this->db->dbprefix.$this->table_name;
        $sets = array();
        foreach ($data as $key => $val) {
            //过滤sql注入
            $val = $this->db->escape_str($val);
            $sets[] = " `{$key}` = '{$val}'";
        }
        $sets = implode(',', $sets);
        $sql = "UPDATE {$table} SET {$sets} WHERE id = '{$id}'";
        return $this->db->query($sql);
    }
    
    /**
     * 更新单个字段
     *
     *
     */
    function update_one($fields=array(),$id)
    {
        $this->db->set(key($fields),current($fields));
        $this->db->where('id', $id);
        return $this->db->update($this->table_name);
    }
    
    /**
     * 删除
     * 
     */
    function delete($id)
    {
        $table = $this->db->dbprefix.$this->table_name;
        $sql = "SELECT id FROM $table WHERE parent_id =".intval($id);
        if($this->db->query($sql)->result_array()){show_message3('操作成功','此栏目下存在子栏目，不能删除，请先删除子栏目!',false);}

		$this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }
    
}
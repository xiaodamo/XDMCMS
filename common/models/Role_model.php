<?php
/**
 * 角色
 *
 *
 */
class Role_model extends CI_Model
{

    private $table_name = 'role';
    private $table_name_user = 'admin_user';
    private $table_name_auth = 'auth';
    	
	function __construct()
    {
        parent::__construct();
    }

    //-------------------------------------------------------------------------
    function get_role_auth()
    {
        $table = $this->db->dbprefix.$this->table_name;
        $table1 = $this->db->dbprefix.$this->table_name_user;
        $sql ="select auth_ids,auth_ca from {$table} r join {$table1} a on r.role_id=a.role_id where r.is_delete = 0 and a.is_delete=0 and r.role_id=".$_SESSION['role_id'];
        return $this->db->query($sql)->row_array();
    }
    
    
    //-------------------------------------------------------------------------
    function load($id)
    {
        if (!$id){
            return array();
        }
        $query = $this->db->get_where($this->table_name,array('role_id' => $id));
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
            if($value){
                switch($key){
                    case 'name' : 
                            $this->db->like($key,$value);break;
                    default : 
                            $this->db->where($key,$value);
                }
            }
            endforeach;
        }
        
        $this->db->where('is_delete',0);
    
        if ($count){
            $this->db->limit((int)$count, (int)$offset);
        }
    
        $this->db->order_by('role_id asc');
    
        $query = $this->db->get($this->table_name);
        
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
            if($value){
                switch($key){
                    case 'name' : 
                            $this->db->like($key,$value);break;
                    default : 
                            $this->db->where($key,$value);
                }
            }
            endforeach;
        }
        
        $this->db->where('is_delete',0);
        
        $query = $this->db->select('COUNT(DISTINCT(role_id)) as total')->from($this->table_name)->get();
    
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
        $id = intval($data['role_id']);
        unset($data['role_id']);
        $data['updated_at'] = time();
        $table = $this->db->dbprefix.$this->table_name;
        $sets = array();
        foreach ($data as $key => $val) {
            //过滤sql注入
            $val = $this->db->escape_str($val);
            $sets[] = " `{$key}` = '{$val}'";
        }
        $sets = implode(',', $sets);
        $sql = "UPDATE {$table} SET {$sets} WHERE role_id = '{$id}'";
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
        $this->db->where('role_id', $id);
        $this->db->update($this->table_name);
    
        $this->db->select(key($fields));
        $this->db->where('role_id', $id);
        $query = $this->db->get($this->table_name);
        $field =  $query->row_array();
        return $field[key($fields)];
    
    }
    
    /**
     * 删除
     * 
     */
    function delete($id)
    {
		$this->db->where('role_id', $id);
        return $this->db->delete($this->table_name);
    }
    
    /**
     * 通过role_ids获取角色相关信息
     */
    function get_role_byids($ids='')
    {
        $table = $this->db->dbprefix.$this->table_name;
        $sql ="select role_id,name from {$table} where is_delete = 0 and role_id in($ids)";
        return $this->db->query($sql)->result_array();
        
    }
    
}
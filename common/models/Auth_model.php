<?php
/**
 * 权限
 *
 *
 */
class Auth_model extends CI_Model
{

    private $table_name = 'auth';
    	
	function __construct()
    {
        parent::__construct();
    }

    
    //-------------------------------------------------------------------------
    function get_auth_menu($ids='',$menu=2,$model='',$display = 2)
    {
        $table = $this->db->dbprefix.$this->table_name;
        $sql ="select `auth_id` as id,`name` as text,`icon` as iconCls,`parent_id`,`url`,`is_menu`,`m`,`c`,`a`,`sort_order`,`is_display` from {$table} where 1=1 ";
        if($ids){
            $ids = $this->db->escape_str($ids);
            $sql.=" AND `auth_id` IN($ids) ";
        }
        if($model){
            $model = $this->db->escape_str($model);
            $sql.=" AND `m` = '$model'";
        }
        if($menu<2){
            $sql.=" AND `is_menu` = $menu ";
        }
        if($display<2){
            $sql.=" AND `is_display` = $display ";
        }
        
        $sql.=" order by sort_order desc";
    
        return $this->db->query($sql)->result_array();
    }

    function get_menu_byids($top=0,$ids='',$menu=2, $display = 2)
    {
        $table = $this->db->dbprefix.$this->table_name;
        $sql ="select `auth_id` as id,`name` as text,`icon` as iconCls,`parent_id`,`url`,`is_menu`,`m`,`c`,`a`,`sort_order`,`is_display` from {$table} where 1=1 ";

        if($top){
            $sql.=" AND `parent_id` = 0 ";
        }

        if($ids){
            $ids = $this->db->escape_str($ids);
            $sql.=" AND `auth_id` IN($ids) ";
        }
        if($menu<2){
            $sql.=" AND `is_menu` = $menu ";
        }
        if($display<2){
            $sql.=" AND `is_display` = $display ";
        }

        $sql.=" order by sort_order desc";
        return $this->db->query($sql)->result_array();
    }

    function topmenu()
    {
        $menus = array();
        //超级管理员默认获取部门1菜单
        if($_SESSION['admin_id']==1){
            $menus = $this->get_menu_byids(1,'',1,1);
        }else{
            if ($_SESSION['auth_ids']){
                $menus = $this->get_menu_byids(1,$_SESSION['auth_ids'],1,1);
            }
        }

        return $menus;
    }

    function menu()
    {
        $menus = array();
        //超级管理员默认获取部门1菜单
        if($_SESSION['admin_id']==1){
            $menus = $this->get_menu_byids(0,'',1,1);
            $menus = getMenu($menus,'id');
        }else{
            if ($_SESSION['auth_ids']){
                $menus = $this->get_menu_byids(0,$_SESSION['auth_ids'],1,1);
                $menus = getMenu($menus,'id');
            }
        }

        return $menus;
    }
    
    function load($id)
    {
        if (!$id){
            return array();
        }
        $query = $this->db->get_where($this->table_name,array('auth_id' => $id));
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
            switch($key){
                case 'is_display' : $this->db->where($key,$value);break;
                case 'name' : $this->db->like($key,$value);break;
                default : $this->db->where($key,$value);
            }
            endforeach;
        }
    
        if ($count){
            $this->db->limit((int)$count, (int)$offset);
        }
    
        $this->db->order_by('auth_id desc');
    
        $query = $this->db->get($this->table_name);
    
        if ($row = $query->result_array()){
            return $row;
        }
    
        return array();
    }
    
    /**
     * 总数
     *
     *
     */
    function counts()
    {
        $query = $this->db->select('COUNT(DISTINCT(auth_id)) as total')->from($this->table_name)->get();
    
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
        $id = intval($data['auth_id']);
        unset($data['auth_id']);
        $table = $this->db->dbprefix.$this->table_name;
        $sets = array();
        foreach ($data as $key => $val) {
            //过滤sql注入
            $val = $this->db->escape_str($val);
            $sets[] = " `{$key}` = '{$val}'";
        }
        $sets = implode(',', $sets);
        $sql = "UPDATE {$table} SET {$sets} WHERE auth_id = '{$id}'";
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
        $this->db->where('auth_id', $id);
        $this->db->update($this->table_name);
    
        $this->db->select(key($fields));
        $this->db->where('auth_id', $id);
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
        $this->db->where('auth_id', $id);
        return $this->db->delete($this->table_name);
    }
    
}
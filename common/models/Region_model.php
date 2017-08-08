<?php
/**
 * 省 市 县/区 街道
 *
 *
 */
class Region_Model extends CI_Model
{
    /**
     * 
     *
     * @return Region_Model
     */

    private $table_name = 'region';

    function __construct()
    {
        parent::__construct();
    }
    
	// --------------------------------------------------------------------

    function load($id)
    {
        if (!$id){
            return array();
        }

        $query = $this->db->get_where('region',array('region_id' => $id));

        if ($row = $query->row_array()){
            return $row;
        }

        return array();
    }

    /**
     * 孩子节点
     *
     * @param integer $parent_id
     */
    function children_of($parent_id, $select="*")
    {
        $parent_id = (int)$parent_id;
        
		if ($parent_id>0){
			$this->db->select($select);
			$this->db->where('parent_id', $parent_id);
			$query = $this->db->get('region');       
			return $query->result_array(); 
		}else{
			return array();
		}
    }

    // --------------------------------------------------------------------

	/**
     * 省份
     *
     * @return array
     */
    function provinces()
    {
        $this->db->select('*');
		$this->db->where('level', 1);
		$query = $this->db->get('region');       
		return $query->result_array(); 
    }
    
	// --------------------------------------------------------------------

    /**
     * 非叶节点
     *
     * @return array
     */
    function get_parent_ids()
	{
        $query = $this->db->get('region');
        $rows = array();
        foreach ($query->result_array() as $row){
            $rows[$row['id']] = $row['parent_id'];
        }
        return $rows;
	}
    
    // --------------------------------------------------------------------

    /**
     * 区域名
     *
     * @return array
     */

	function get_name($id)
	{
		if (!$id){
            return '';
        }
		$this->db->select('name');
        $query = $this->db->get_where('region',array('region_id' => $id));

        if ($row = $query->row_array()){
            return $row['name'];
        }
		return '';
	}
	
	/**
	 * 父亲节点
	 *
	 * @param integer $parent_id
	 */
	function parent_of($children_id)
	{
		$children_id = (int)$children_id;
	
		if ($children_id>0){

            $table = $this->db->dbprefix.$this->table_name;
            $sql ="select t1.* from {$table} t1 inner join {$table} t2 on t1.region_id=t2.parent_id where t2.region_id = $children_id ";
            return $this->db->query($sql)->row_array();

		}else{
			return array();
		}
	}

}
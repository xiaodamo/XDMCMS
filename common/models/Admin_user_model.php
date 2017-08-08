<?php
/**
 * 管理员
 *
 *
 */ 
class Admin_user_model extends CI_Model
{
 
    public $account;
    
    public $name;
    
	public $email;

	public $password;
	
	public $company_id;
	
	public $depart_id;
	
	public $role_id;
	
	public $icon;
	public $sex;
	public $birthday;
	public $tel;
	public $height;
	public $weight;
	public $expertise;
	public $city_id;
	public $district_id;
	public $address;
	public $descinfo;
	public $ustatus;
	public $created_at;

	private $table_name_depart = 'admin_depart'; 
	
	private $table_name_role = 'role';
	
	private $table_admin_user = 'admin_user';
	

	function __construct()
    {
        parent::__construct();
    }

	// --------------------------------------------------------------------
 
    /**
	 * load by id
	 *
	 *
	 */	  
    function load($id)
    {
        if (!$id){
            return array();
        }

        $query = $this->db->get_where('admin_user',array('user_id' => $id));

        if ($row = $query->row_array()){
            return $row;
        }

        return array();
    }

	// --------------------------------------------------------------------

    /**
	 * 创建
	 *
	 *
	 */	
    function create()
    { 
		$datetime = time();
        $this->db->set('account', $this->account);
        $this->db->set('name', $this->name);
		$this->db->set('email', $this->email);
		$salt = randomCode();
		$this->db->set('password', md5(md5($this->password).$salt));
		$this->db->set('salt', $salt);
		$this->db->set('role_id', $this->role_id);
//		$this->db->set('company', $this->company_id);
//		$this->db->set('depart', $this->depart_id);
		$this->db->set('icon', $this->icon);
		$this->db->set('sex', $this->sex);
		$this->db->set('birthday', $this->birthday);
		$this->db->set('tel', $this->tel);
//		$this->db->set('height', $this->height);
//		$this->db->set('weight', $this->weight);
//		$this->db->set('expertise', $this->expertise);
		$this->db->set('city_id', $this->city_id);
		$this->db->set('district_id', $this->district_id);
		$this->db->set('address', $this->address);
//		$this->db->set('descinfo', $this->descinfo);
		$this->db->set('ustatus', $this->ustatus);
		if($this->created_at){
            $this->db->set('created_at', $this->created_at);
        }else{
            $this->db->set('created_at', $datetime);
        }

		$this->db->set('updated_at', $datetime);
              
        $this->db->insert('admin_user');
        return $this->db->insert_id();
    }

	// --------------------------------------------------------------------

    /**
	 * 更新
	 *
	 *
	 */	
    function update($id)
    {
        $datetime = time();
      //  $this->db->set('account', $this->account);
        $this->db->set('name', $this->name);
		$this->db->set('email', $this->email);
		if($this->password){
		    $salt = randomCode();
		    $this->db->set('password', md5(md5($this->password).$salt));
		    $this->db->set('salt', $salt);
		}
		if($this->icon){
		    $this->db->set('icon', $this->icon);
		}
		$this->db->set('role_id', $this->role_id);
		if($this->company_id){
		    $this->db->set('company', $this->company_id);
		}
		$this->db->set('sex', $this->sex);
		$this->db->set('birthday', $this->birthday);
		$this->db->set('tel', $this->tel);
//		$this->db->set('height', $this->height);
//		$this->db->set('weight', $this->weight);
//		$this->db->set('expertise', $this->expertise);
		$this->db->set('city_id', $this->city_id);
		$this->db->set('district_id', $this->district_id);
		$this->db->set('address', $this->address);
//		$this->db->set('descinfo', $this->descinfo);
        if($id!==1){
            $this->db->set('ustatus', $this->ustatus);
        }

//		$this->db->set('depart', $this->depart_id);
        if($this->created_at){
            $this->db->set('created_at', $this->created_at);
        }else{
            $this->db->set('created_at', $datetime);
        }
		$this->db->set('updated_at', $datetime);

        $this->db->where('user_id', $id);
        return $this->db->update('admin_user');
    }
       
	// --------------------------------------------------------------------

    /**
	 * 总数
	 *
	 *
	 */	
	function total_rows()
    {
        return $this->db->count_all_results('admin_user');
    }

    // --------------------------------------------------------------------

    /**
	 * 删除
	 *
	 *
	 */	
    function delete($id)
    {        
		$this->db->where('user_id', $id);
		$this->db->where('user_id<>', 1);

        return $this->db->delete('admin_user');
    }

    // --------------------------------------------------------------------

    /**
	 * 获取最新添加的数据
	 *
	 *
	 */
	function get_newly_one()
    {
        $this->db->from('admin_user');
        $this->db->order_by("user_id", "desc");
        $this->db->limit('1');
        $query =  $this->db->get();
        return $query->row_array();
    }

    // --------------------------------------------------------------------

    /**
	 * 登陆后获取操作权限
	 *
	 *
	 */
	function signin()
	{
        $query = $this->db->get_where('admin_user',array('account' => $this->account,'is_delete'=>0));

        if ($row = $query->row_array()){
			//比较密码
			if(md5(md5($this->password).$row['salt'])!==$row['password']){
			    return array();
			}
			
			// 角色
			$query1 = $this->db->get_where('role',array('role_id' => $row['role_id'],'is_delete'=>0));
			$row1 = $query1->row_array();
			if (!empty($row1)){
				$row['auth_ca'] = $row1['auth_ca'];
				$row['auth_ids'] = $row1['auth_ids'];
			}
			else{
				$row['auth_ca'] = $row['auth_ids'] = '';			
			}
            return $row;
        }

        return array();
	}
    
	// --------------------------------------------------------------------

    /**
	 * 获取该用户角色
	 *
	 *
	 */
	function role_user($role_id)
    {
        if (!$role_id){
            return array();
        }

        $query = $this->db->get_where('admin_user',array('role_id' => $role_id,'is_delete'=>0));

        if ($row = $query->row_array()){
            return $row;
        }

        return array();
    }
	
	
	
	
    // --------------------------------------------------------------------
    
	
	
	/**
     * 私有函数
     *
     *
     */
    function _query_admins($options = null)
    {
        $this->db->from('admin_user');
    
        if (!empty($options['conditions'])){
            foreach($options['conditions'] as $key => $value ):
            switch($key){
                case 'account' : $this->db->where($key,$value);break;
                case 'name' : $this->db->like($key,$value);break;
                default : $this->db->where($key,$value);
            }
            endforeach;
        }
    
        $this->db->where('is_delete',0);
        
        if (isset($options['order'])){
            $this->db->order_by($options['order']);
        } else {
            $this->db->order_by('user_id ASC');
        }
    
        return $this->db->get();
    }
    
	// --------------------------------------------------------------------
    /**
     * 总数
     *
     *
     */
    function count_admins($options = array())
    {
        $this->db->select('COUNT(DISTINCT(user_id)) as total');
    
        $query = $this->_query_admins($options);
    
        $total = 0;
        if ($row = $query->row_array()){
            $total = (int)$row['total'];
        }
        return $total;
    }
	// --------------------------------------------------------------------
     /**
     * 获取全部部门
     *
     *
     */
	function get_all_depart($companyid=0)
	{
		
        $table = $this->db->dbprefix.$this->table_name_depart;		
		
        $companystr = $companyid?' and company_id='.$companyid:'';
		$sql="select depart_id,name,action from  {$table} where is_delete=0 {$companystr} ORDER BY depart_id asc";

		$query = $this->db->query ($sql);
	
		return $query->result_array();

	
    }
	
	// -------------------------------------------------------------------- 
	 /**
     * 获取角色名称
     *
     *
     */
	function get_role_name($role_id)
	{
		
		$table = $this->db->dbprefix.$this->table_name_role;	
		
		$sql="select name from  {$table} where role_id=".$role_id;

		$query = $this->db->query ($sql);
	
		return $query->row_array();

    }
	 // --------------------------------------------------------------------
	 /**
     * 获取部门名称
     *
     *
     */
	function get_depart_name($depart_id)
	{
		
		$table = $this->db->dbprefix.$this->table_name_depart;
		
		$sql="select name from  {$table} where depart_id=".$depart_id;

		$query = $this->db->query ($sql);
	
		return $query->row_array();

    }
		 // --------------------------------------------------------------------
	 /**
     * 获取角色列表
     *
     *
     */
	function get_role_list()
	{

		$table = $this->db->dbprefix.$this->table_name_role;

		$sql="select name,role_id from  {$table} where is_delete=0 ";

		$query = $this->db->query ($sql);
	
		return $query->result_array();

    }
    
    
    /**
     * 根据公司获取部门列表
     *
     *
     */
    function get_depart_list($company_id)
    {
    
        $table = $this->db->dbprefix.$this->table_name_depart;
    
        $sql="select name,depart_id,action from  {$table} where is_delete=0 and company_id=".$company_id." order by depart_id asc";
    
        $query = $this->db->query ($sql);
    
        return $query->result_array();
    
    }
	
	
	
	
	// --------------------------------------------------------------------

    /**
	 * 用户列表
	 *
	 *
	 */	
	function admin_user_list( $offset, $num, $condition=array('company'=>'','code'=>'','key'=>'','depart'=>'','order'=>'',))
	{


          	 $table = $this->db->dbprefix.$this->table_admin_user;
  
       
  			  $where ="where is_delete=0 ";
//   			  if($condition['company'])
//   			  {
  			      	
//   			      $where .= " and company = ". $condition['company'];
  			  
//   			  }
			  if($condition['code']&&$condition['key'])
			  {
			  
					$where .= " and ". $condition['code'] . " like '%" . $condition['key'] . "%'";
			   
			  }
			  
// 			  if($condition['depart'])
// 			  {
			  
// 					$where .= " and depart =". $condition['depart'];
			   
// 			  }
		 
		 		if($condition['order'])
			  {
			  
					$where .= " order by user_id ". $condition['order'];
			   
			  }else{
			  
			 		$where .= " order by user_id asc";
				 
			  }
		 
		  
		  	$str_sql  = "select user_id,account,name,icon,sex,tel,email,depart,role_id,ustatus,created_at,updated_at from {$table} ".$where. " limit {$offset},{$num}";
		    $str_count  = "select count(user_id) as num from {$table} ".$where;
		
			$query = $this->db->query($str_count);
			$count = $query->row_array();
		 	 return array(
                    'total' => $count['num'],			
                    'res' => $this->db->query($str_sql)->result_array(),
                    ); 
	
	}
	
	/**
	 * 更新某字段
	 * $options为数组，key为字段名,value为字段值
	 *
	 */
	function update_one($id,$options)
	{
	    if($id && $options && is_array($options)){
	        foreach ($options as $key => $v){
	            $this->db->set($key, $v);
	        }
	        $this->db->set('updated_at', time());
	        $this->db->where('user_id', $id);
	        return $this->db->update('admin_user');
	    }
	}


    /**
     * 更新
     *
     *
     */
    function change_password($id,$password)
    {
        if(!$id || !$password){
            return false;
        }

        $salt = randomCode();
        $this->db->set('password', md5(md5($password).$salt));
        $this->db->set('salt', $salt);

        $this->db->where('user_id', $id);
        return $this->db->update('admin_user');
    }

    /**
     * 更新
     *
     *
     */
    function change_role($id,$role_ids)
    {
        if(!$id || !$role_ids){
            return false;
        }

        $this->db->set('role_id', $role_ids);
        $this->db->where('user_id', $id);
        return $this->db->update('admin_user');
    }

}
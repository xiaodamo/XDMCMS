<?php
/**
 * 文章
 *
 *
 */
class Article_model extends CI_Model
{

    private $table_name = 'article';
    private $table_article_tar = 'article_tar';
    private $table_tar = 'target';
    private $table_cat = 'category';

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
    
    
    function finds($options = array(),$count=20, $offset=0,$orderby = "")
    {
        if (!is_array($options)){
            return array();
        }

        $tablename = $this->db->dbprefix.$this->table_name;
        $table_cat = $this->db->dbprefix.$this->table_cat;
        $getcat = "";
        if (!empty($options['conditions'])){
            foreach($options['conditions'] as $key => $value ):
            if($value!==""){
                switch($key){
                    case 'title' :
                            $this->db->like($tablename.'.'.$key,$value);break;
                    case 'catname' :
                            $getcat = ','.$table_cat.'.enname,'.$table_cat.'.name';break;
                    case 'nocid' :
                            foreach ($value as $vv){
                                $this->db->where($tablename.'.cid !=', $vv);
                            }
                            break;
                    case 'cid_in' :
                        $this->db->where_in($tablename.'.cid', $value);
                        break;
                    default :
                            $this->db->where($tablename.'.'.$key,$value);
                }
            }
            endforeach;
        }

        if ($count){
            $this->db->limit((int)$count, (int)$offset);
        }

        if($orderby){
            $this->db->order_by($tablename.'.'.$orderby);
        }else{
            $this->db->order_by($tablename.'.sort_order asc,id desc');
        }


        $this->db->select("$tablename.id,cid,title,$tablename.img_url,content,$tablename.sort_order,click_nums,is_recommand,is_recommand as recommand,author,status,status as review,$tablename.created_at,$tablename.updated_at$getcat")->from($this->table_name);
        if($getcat){
            $this->db->join($this->table_cat, $tablename.'.cid = '.$table_cat.'.id');
        }
        $query = $this->db->get();

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

        $tablename = $this->db->dbprefix.$this->table_name;
        $table_cat = $this->db->dbprefix.$this->table_cat;
        $getcat = "";
        if (!empty($options['conditions'])){
            foreach($options['conditions'] as $key => $value ):
            if($value!==""){
                switch($key){
                    case 'title' :
                            $this->db->like($tablename.'.'.$key,$value);break;
                    case 'catname' :
                        $getcat = 1;break;
                    case 'nocid' :
                        foreach ($value as $vv){
                            $this->db->where($tablename.'.cid !=', $vv);
                        }
                        break;
                    case 'cid_in' :
                        $this->db->where_in($tablename.'.cid', $value);
                        break;
                    default :
                            $this->db->where($tablename.'.'.$key,$value);
                }
            }
            endforeach;
        }
        
        $this->db->select("COUNT(DISTINCT($tablename.id)) as total")->from($this->table_name);
        if($getcat){
            $this->db->join($this->table_cat, $tablename.'.cid = '.$table_cat.'.id');
        }
        $query = $this->db->get();
    
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

    function click_nums($id){
        $table = $this->db->dbprefix.$this->table_name;
        $sql = "UPDATE {$table} SET click_nums = click_nums + 1 WHERE id = '{$id}'";
        return $this->db->query($sql);
    }
    
    /**
     * 删除
     * 
     */
    function delete($id)
    {
		$this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    function get_category_name($cid){
        $table_cat = $this->db->dbprefix . $this->table_cat;
        $sql = "select name from {$table_cat} where id =" . $cid;
        $query = $this->db->query($sql);
        $cat = $query->row_array();
        return $cat?$cat['name']:'';
    }

    function get_article_tar($aid){
        $this->db->where('aid',$aid);
        $query = $this->db->select('tid')->from($this->table_article_tar)->get();
        $tars = $query->result_array();
        $new_data = array();
        foreach ($tars as $k=>$v){
            $new_data[] = $v['tid'];
        }

        return $new_data;
    }

    function get_article_tarinfo($aid){
        $table_atar = $this->db->dbprefix . $this->table_article_tar;
        $table_tar = $this->db->dbprefix . $this->table_tar;
        $table_article = $this->db->dbprefix . $this->table_name;
        $sql = "select t2.id,t2.name from {$table_atar} t1 inner join {$table_tar} t2 on t1.tid =  t2.id 
                inner join {$table_article} t3 on t1.aid = t3.id where t2.is_display=1 and t1.aid =" . $aid;
        $query = $this->db->query($sql);
        $tars = $query->result_array();
        return $tars;
    }

    function get_tar_articles($id,$count=20, $offset=0){
        $table_atar = $this->db->dbprefix . $this->table_article_tar;
        $table_tar = $this->db->dbprefix . $this->table_tar;
        $table_article = $this->db->dbprefix . $this->table_name;
        $sql = "select t3.title,t3.id,t3.cid,t3.img_url,t3.content,t3.click_nums,t3.author,t3.created_at,t3.updated_at from {$table_atar} t1 inner join {$table_tar} t2 on t1.tid =  t2.id 
                inner join {$table_article} t3 on t1.aid = t3.id where t3.status = 2 and t1.tid =" . intval($id)." order by t3.sort_order asc";

        if ($count){
            $sql.= " limit ".(int)$offset.",".(int)$count;
        }
        $query = $this->db->query($sql);
        $articles = $query->result_array();
        return $articles;
    }

    function get_tar_artcount($id){
        $table_atar = $this->db->dbprefix . $this->table_article_tar;
        $table_tar = $this->db->dbprefix . $this->table_tar;
        $table_article = $this->db->dbprefix . $this->table_name;
        $sql = "select count(DISTINCT(t2.id)) as total from {$table_atar} t1 inner join {$table_tar} t2 on t1.tid =  t2.id 
                inner join {$table_article} t3 on t1.aid = t3.id where t3.status = 2 and t1.tid =" . intval($id)." order by t3.sort_order asc";
        $query = $this->db->query($sql);
        $total = 0;
        if ($row = $query->row_array()){
            $total = (int)$row['total'];
        }
        return $total;
    }

    //相邻文章
    function get_along_article($id=0,$cid=0){
        $table_article = $this->db->dbprefix . $this->table_name;
        $sql = "SELECT id,title FROM $table_article 
                WHERE id IN (SELECT CASE WHEN SIGN(id - $id) > 0 THEN MIN(id) WHEN SIGN(id - $id) < 0 THEN MAX(id) END AS id 
                FROM $table_article WHERE id <> $id and cid = $cid GROUP BY SIGN(id - $id) ORDER BY SIGN(id - $id)) and status = 2 ORDER BY id ASC";
        $query = $this->db->query($sql);
        $articles = $query->result_array();
        return $articles;
    }



    function update_article_tar($aid,$tids = array()){
        if(!is_array($tids) || !$tids) return false;

        $table_atar = $this->db->dbprefix . $this->table_article_tar;
        $del_sql = "delete from {$table_atar} where aid =" . $aid;
        $this->db->query($del_sql);

        $insert_data = $temp = array();
        $now = time();
        foreach ($tids as $kk=>$vv){
            $temp[0] = $aid;
            $temp[1] = $vv;
            $temp[2] = $now;
            $insert_data[] = $temp;
        }

        $insert_sql =  $this->_make_batch_insert_sql($table_atar,array("aid","tid","created_at"),$insert_data);
        $res = $this->db->query($insert_sql);

        return $res;
    }

    private function _make_batch_insert_sql($table,$colum,$data){

        $sql_val = '';
        foreach ($data as $k=>$v)
        {
            $values = "'" . implode("','", $v) . "'";
            $sql_val .= "($values),";
        }

        $keys = "`" . implode("`,`", $colum) . "`";
        $sql_insert = "insert into `{$table}` ({$keys}) values ".$sql_val;

        $sql_insert = substr($sql_insert,0,strlen($sql_insert)-1);

        return $sql_insert;
    }


}
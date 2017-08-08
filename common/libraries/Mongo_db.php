<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter MongoDB Active Record Library
 *
 * A library to interface with the NoSQL database MongoDB. For more information see http://www.mongodb.org
 * Originally created by Alex Bilbie, but extended and updated by Kyle J. Dye
 *
 * @package		CodeIgniter
 * @author		Alex Bilbie | www.alexbilbie.com | alex@alexbilbie.com
 * @author		Kyle J. Dye | www.kyledye.com | kyle@kyledye.com
 * @copyright	Copyright (c) 2010, Alex Bilbie.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://alexbilbie.com
 * @link		http://kyledye.com
 * @version		Version 0.3.7
 */

class Mongo_db {
	
	private $CI;
	private $config_file = 'mongodb';
	
	private $connection;
	private $db;
	private $connection_string;
	
	private $host;
	private $port;
	private $user;
	private $pass;
	private $dbname;
	private $persist;
	private $persist_key;
	private $query_safety = 'safe';
	
	private $selects = array();
	public  $wheres = array(); // Public to make debugging easier
	private $sorts = array();
	
	private $limit = 999999;
	private $offset = 0;
	
	/**
	*	--------------------------------------------------------------------------------
	*	CONSTRUCTOR
	*	--------------------------------------------------------------------------------
	*
	*	Automatically check if the Mongo PECL extension has been installed/enabled.
	*	Generate the connection string and establish a connection to the MongoDB.
	*/
	
	public function __construct()
	{
		if ( ! class_exists('Mongo'))
		{
			show_error("The MongoDB PECL extension has not been installed or enabled", 500);
		}
		
		$this->CI =& get_instance();
		$this->connection_string();
		$this->connect();
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	Switch_db
	*	--------------------------------------------------------------------------------
	*
	*	Switch from default database to a different db
	*/
	
	public function switch_db($database = '')
	{
		if (empty($database))
		{
			show_error("To switch MongoDB databases, a new database name must be specified", 500);
		}
		
		$this->dbname = $database;
		
		try
		{
			$this->db = $this->connection->{$this->dbname};
			return (TRUE);
		}
		catch (Exception $e)
		{
			show_error("Unable to switch Mongo Databases: {$e->getMessage()}", 500);
		}
	}
		
	/**
	*	--------------------------------------------------------------------------------
	*	Drop_db
	*	--------------------------------------------------------------------------------
	*
	*	Drop a Mongo database
	*	@usage: $this->mongo_db->drop_db("foobar");
	*/
	public function drop_db($database = '')
	{
		if (empty($database))
		{
			show_error('Failed to drop MongoDB database because name is empty', 500);
		}
		
		else
		{
			try
			{
				$this->connection->{$database}->drop();
				return (TRUE);
			}
			catch (Exception $e)
			{
				show_error("Unable to drop Mongo database `{$database}`: {$e->getMessage()}", 500);
			}
			
		}
	}
		
	/**
	*	--------------------------------------------------------------------------------
	*	Drop_collection
	*	--------------------------------------------------------------------------------
	*
	*	Drop a Mongo collection
	*	@usage: $this->mongo_db->drop_collection('foo', 'bar');
	*/
	public function drop_collection($db = "", $col = "")
	{
		if (empty($db))
		{
			show_error('Failed to drop MongoDB collection because database name is empty', 500);
		}
	
		if (empty($col))
		{
			show_error('Failed to drop MongoDB collection because collection name is empty', 500);
		}
		
		else
		{
			try
			{
				$this->connection->{$db}->{$col}->drop();
				return TRUE;
			}
			catch (Exception $e)
			{
				show_error("Unable to drop Mongo collection `{$col}`: {$e->getMessage()}", 500);
			}
		}
		
		return($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	SELECT FIELDS
	*	--------------------------------------------------------------------------------
	*
	*	Determine which fields to include OR which to exclude during the query process.
	*	Currently, including and excluding at the same time is not available, so the 
	*	$includes array will take precedence over the $excludes array.  If you want to 
	*	only choose fields to exclude, leave $includes an empty array().
	*
	*	@usage: $this->mongo_db->select(array('foo', 'bar'))->get('foobar');
	*/
	
	public function select($includes = array(), $excludes = array())
	{
	 	if ( ! is_array($includes))
	 	{
	 		$includes = array();
	 	}
	 	
	 	if ( ! is_array($excludes))
	 	{
	 		$excludes = array();
	 	}
	 	
	 	if ( ! empty($includes))
	 	{
	 		foreach ($includes as $col)
	 		{
	 			$this->selects[$col] = 1;
	 		}
	 	}
	 	else
	 	{
	 		foreach ($excludes as $col)
	 		{
	 			$this->selects[$col] = 0;
	 		}
	 	}
	 	return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	WHERE PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents based on these search parameters.  The $wheres array should 
	*	be an associative array with the field as the key and the value as the search
	*	criteria.
	*
	*	@usage : $this->mongo_db->where(array('foo' => 'bar'))->get('foobar');
	*/
	
	public function where($wheres = array())
	 {
	 	foreach ($wheres as $wh => $val)
	 	{
	 		$this->wheres[$wh] = $val;
	 	}
	 	return ($this);
	 }
	
	/**
	*	--------------------------------------------------------------------------------
	*	OR_WHERE PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents where the value of a $field may be something else
	*
	*	@usage : $this->mongo_db->or_where(array( array('foo'=>'bar', 'bar'=>'foo' ))->get('foobar');
	*/
	
	public function or_where($wheres = array())
	{
		if (count($wheres) > 0)
		{
			if ( ! isset($this->wheres['$or']) || ! is_array($this->wheres['$or']))
			{
				$this->wheres['$or'] = array();
			}
			
			foreach ($wheres as $wh => $val)
			{
				$this->wheres['$or'][] = array($wh=>$val);
			}
		}
		return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	WHERE_IN PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents where the value of a $field is in a given $in array().
	*
	*	@usage : $this->mongo_db->where_in('foo', array('bar', 'zoo', 'blah'))->get('foobar');
	*/
	
	public function where_in($field = "", $in = array())
	{
		$this->_where_init($field);
		$this->wheres[$field]['$in'] = $in;
		return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	WHERE_IN_ALL PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents where the value of a $field is in all of a given $in array().
	*
	*	@usage : $this->mongo_db->where_in('foo', array('bar', 'zoo', 'blah'))->get('foobar');
	*/
	
	public function where_in_all($field = "", $in = array())
	{
		$this->_where_init($field);
		$this->wheres[$field]['$all'] = $in;
		return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	WHERE_NOT_IN PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents where the value of a $field is not in a given $in array().
	*
	*	@usage : $this->mongo_db->where_not_in('foo', array('bar', 'zoo', 'blah'))->get('foobar');
	*/
	
	public function where_not_in($field = "", $in = array())
	{
		$this->_where_init($field);
		$this->wheres[$field]['$nin'] = $in;
		return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	WHERE GREATER THAN PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents where the value of a $field is greater than $x
	*
	*	@usage : $this->mongo_db->where_gt('foo', 20);
	*/
	
	public function where_gt($field = "", $x)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$gt'] = $x;
		return ($this);
	}

	/**
	*	--------------------------------------------------------------------------------
	*	WHERE GREATER THAN OR EQUAL TO PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents where the value of a $field is greater than or equal to $x
	*
	*	@usage : $this->mongo_db->where_gte('foo', 20);
	*/
	
	public function where_gte($field = "", $x)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$gte'] = $x;
		return($this);
	}

	/**
	*	--------------------------------------------------------------------------------
	*	WHERE LESS THAN PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents where the value of a $field is less than $x
	*
	*	@usage : $this->mongo_db->where_lt('foo', 20);
	*/
	
	public function where_lt($field = "", $x)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$lt'] = $x;
		return($this);
	}

	/**
	*	--------------------------------------------------------------------------------
	*	WHERE LESS THAN OR EQUAL TO PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents where the value of a $field is less than or equal to $x
	*
	*	@usage : $this->mongo_db->where_lte('foo', 20);
	*/
	
	public function where_lte($field = "", $x)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$lte'] = $x;
		return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	WHERE BETWEEN PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents where the value of a $field is between $x and $y
	*
	*	@usage : $this->mongo_db->where_between('foo', 20, 30);
	*/
	
	public function where_between($field = "", $x, $y)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$gte'] = $x;
		$this->wheres[$field]['$lte'] = $y;
		return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	WHERE BETWEEN AND NOT EQUAL TO PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents where the value of a $field is between but not equal to $x and $y
	*
	*	@usage : $this->mongo_db->where_between_ne('foo', 20, 30);
	*/
	
	public function where_between_ne($field = "", $x, $y)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$gt'] = $x;
		$this->wheres[$field]['$lt'] = $y;
		return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	WHERE NOT EQUAL TO PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents where the value of a $field is not equal to $x
	*
	*	@usage : $this->mongo_db->where_not_equal('foo', 1)->get('foobar');
	*/
	
	public function where_ne($field = '', $x)
	{
		$this->_where_init($field);
		$this->wheres[$field]['$ne'] = $x;
		return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	WHERE NOT EQUAL TO PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents nearest to an array of coordinates (your collection must have a geospatial index)
	*
	*	@usage : $this->mongo_db->where_near('foo', array('50','50'))->get('foobar');
	*/
	
	function where_near($field = '', $co = array())
	{
		$this->__where_init($field);
		$this->where[$what]['$near'] = $co;
		return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	LIKE PARAMETERS
	*	--------------------------------------------------------------------------------
	*	
	*	Get the documents where the (string) value of a $field is like a value. The defaults
	*	allow for a case-insensitive search.
	*
	*	@param $flags
	*	Allows for the typical regular expression flags:
	*		i = case insensitive
	*		m = multiline
	*		x = can contain comments
	*		l = locale
	*		s = dotall, "." matches everything, including newlines
	*		u = match unicode
	*
	*	@param $enable_start_wildcard
	*	If set to anything other than TRUE, a starting line character "^" will be prepended
	*	to the search value, representing only searching for a value at the start of 
	*	a new line.
	*
	*	@param $enable_end_wildcard
	*	If set to anything other than TRUE, an ending line character "$" will be appended
	*	to the search value, representing only searching for a value at the end of 
	*	a line.
	*
	*	@usage : $this->mongo_db->like('foo', 'bar', 'im', FALSE, TRUE);
	*/
	
	public function like($field = "", $value = "", $flags = "i", $enable_start_wildcard = TRUE, $enable_end_wildcard = TRUE)
	 {
	 	$field = (string) trim($field);
	 	$this->where_init($field);
	 	$value = (string) trim($value);
	 	$value = quotemeta($value);
	 	
	 	if ($enable_start_wildcard !== TRUE)
	 	{
	 		$value = "^" . $value;
	 	}
	 	
	 	if ($enable_end_wildcard !== TRUE)
	 	{
	 		$value .= "$";
	 	}
	 	
	 	$regex = "/$value/$flags";
	 	$this->wheres[$field] = new MongoRegex($regex);
	 	return ($this);
	 }
	
	/**
	*	--------------------------------------------------------------------------------
	*	ORDER BY PARAMETERS
	*	--------------------------------------------------------------------------------
	*
	*	Sort the documents based on the parameters passed. To set values to descending order,
	*	you must pass values of either -1, FALSE, 'desc', or 'DESC', else they will be
	*	set to 1 (ASC).
	*
	*	@usage : $this->mongo_db->where_between('foo', 20, 30);
	*/
	
	public function order_by($fields = array())
	{
		foreach ($fields as $col => $val)
		{
			if ($val == -1 || $val === FALSE || strtolower($val) == 'desc')
			{
				$this->sorts[$col] = -1; 
			}
			else
			{
				$this->sorts[$col] = 1;
			}
		}
		return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	LIMIT DOCUMENTS
	*	--------------------------------------------------------------------------------
	*
	*	Limit the result set to $x number of documents
	*
	*	@usage : $this->mongo_db->limit($x);
	*/
	
	public function limit($x = 99999)
	{
		if ($x !== NULL && is_numeric($x) && $x >= 1)
		{
			$this->limit = (int) $x;
		}
		return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	OFFSET DOCUMENTS
	*	--------------------------------------------------------------------------------
	*
	*	Offset the result set to skip $x number of documents
	*
	*	@usage : $this->mongo_db->offset($x);
	*/
	
	public function offset($x = 0)
	{
		if ($x !== NULL && is_numeric($x) && $x >= 1)
		{
			$this->offset = (int) $x;
		}
		return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	GET_WHERE
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents based upon the passed parameters
	*
	*	@usage : $this->mongo_db->get_where('foo', array('bar' => 'something'));
	*/
	
	public function get_where($collection = "", $where = array())
	{
		return ($this->where($where)->get($collection));
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	GET
	*	--------------------------------------------------------------------------------
	*
	*	Get the documents based upon the passed parameters
	*
	*	@usage : $this->mongo_db->get('foo', array('bar' => 'something'));
	*/
	
	 public function get($collection = "")
	 {
	 	if (empty($collection))
	 	{
	 		show_error("In order to retreive documents from MongoDB, a collection name must be passed", 500);
	 	}
	 	
	 	$results = array();
	 	
	 	$documents = $this->db->{$collection}->find($this->wheres, $this->selects)->limit((int) $this->limit)->skip((int) $this->offset)->sort($this->sorts);
	 	
	 	// Clear
	 	$this->_clear();
	 	
	 	$returns = array();
	 	
	 	foreach ($documents as $doc)
	 	{
	 		$returns[] = $doc;
	 	}
	 	
	 	if ($this->CI->config->item('mongo_return') == 'object')
		{
			return (object)$returns;
		}
		
		else
		{
			return $returns;
		}

	 }
	
	/**
	*	--------------------------------------------------------------------------------
	*	COUNT
	*	--------------------------------------------------------------------------------
	*
	*	Count the documents based upon the passed parameters
	*
	*	@usage : $this->mongo_db->get('foo');
	*/
	
	public function count($collection = "") {
		if (empty($collection))
		{
			show_error("In order to retreive a count of documents from MongoDB, a collection name must be passed", 500);
		}
		
		$count = $this->db->{$collection}->find($this->wheres)->limit((int) $this->limit)->skip((int) $this->offset)->count();
		$this->_clear();
		return ($count);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	INSERT
	*	--------------------------------------------------------------------------------
	*
	*	Insert a new document into the passed collection
	*
	*	@usage : $this->mongo_db->insert('foo', $data = array());
	*/
	
	public function insert($collection = "", $insert = array())
	{
		if (empty($collection))
		{
			show_error("No Mongo collection selected to insert into", 500);
		}
		
		if (count($insert) == 0 || !is_array($insert))
		{
			show_error("Nothing to insert into Mongo collection or insert is not an array", 500);
		}
		
		try
		{
			$this->db->{$collection}->insert($insert, array($this->query_safety	 => TRUE));
			if (isset($insert['_id']))
			{
				return ($insert['_id']);
			}
			else
			{
				return (FALSE);
			}
		}
		catch (MongoCursorException $e)
		{
			show_error("Insert of data into MongoDB failed: {$e->getMessage()}", 500);
		}
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	UPDATE
	*	--------------------------------------------------------------------------------
	*
	*	Updates a single document
	*
	*	@usage: $this->mongo_db->update('foo', $data = array());
	*/
	
	public function update($collection = "", $data = array(), $options = array())
	{
		if (empty($collection))
		{
			show_error("No Mongo collection selected to update", 500);
		}
		
		if (count($data) == 0 || ! is_array($data))
		{
			show_error("Nothing to update in Mongo collection or update is not an array", 500);
		}
		
		try
		{
			$options = array_merge($options, array($this->query_safety => TRUE, 'multiple' => FALSE));
			$this->db->{$collection}->update($this->wheres, array('$set' => $data), $options);
			$this->_clear();
			return (TRUE);
		}
		catch (MongoCursorException $e)
		{
			show_error("Update of data into MongoDB failed: {$e->getMessage()}", 500);
		}
		
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	UPDATE_ALL
	*	--------------------------------------------------------------------------------
	*
	*	Updates a collection of documents
	*
	*	@usage: $this->mongo_db->update_all('foo', $data = array());
	*/
	
	public function update_all($collection = "", $data = array())
	{
		if (empty($collection))
		{
			show_error("No Mongo collection selected to update", 500);
		}
		
		if (count($data) == 0 || ! is_array($data))
		{
			show_error("Nothing to update in Mongo collection or update is not an array", 500);
		}
		
		try
		{
			$this->db->{$collection}->update($this->wheres, array('$set' => $data), array($this->query_safety => TRUE, 'multiple' => TRUE));
			$this->_clear();
			return (TRUE);
		}
		catch (MongoCursorException $e)
		{
			show_error("Update of data into MongoDB failed: {$e->getMessage()}", 500);
		}
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	DELETE
	*	--------------------------------------------------------------------------------
	*
	*	delete document from the passed collection based upon certain criteria
	*
	*	@usage : $this->mongo_db->delete('foo', $data = array());
	*/
	
	public function delete($collection = "")
	{
		if (empty($collection))
		{
			show_error("No Mongo collection selected to delete from", 500);
		}
		
		try
		{
			$this->db->{$collection}->remove($this->wheres, array($this->query_safety => TRUE, 'justOne' => TRUE));
			$this->_clear();
			return (TRUE);
		}
		catch (MongoCursorException $e)
		{
			show_error("Delete of data into MongoDB failed: {$e->getMessage()}", 500);
		}
		
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	DELETE_ALL
	*	--------------------------------------------------------------------------------
	*
	*	Delete all documents from the passed collection based upon certain criteria
	*
	*	@usage : $this->mongo_db->delete_all('foo', $data = array());
	*/
	
	 public function delete_all($collection = "")
	 {
		if (empty($collection))
		{
			show_error("No Mongo collection selected to delete from", 500);
		}
		
		try
		{
			$this->db->{$collection}->remove($this->wheres, array($this->query_safety => TRUE, 'justOne' => FALSE));
			$this->_clear();
			return (TRUE);
		}
		catch (MongoCursorException $e)
		{
			show_error("Delete of data into MongoDB failed: {$e->getMessage()}", 500);
		}
		
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	COMMAND
	*	--------------------------------------------------------------------------------
	*
	*	Runs a MongoDB command (such as GeoNear). See the MongoDB documentation for more usage scenarios:
	*	http://dochub.mongodb.org/core/commands
	*
	*	@usage : $this->mongo_db->command(array('geoNear'=>'buildings', 'near'=>array(53.228482, -0.547847), 'num' => 10, 'nearSphere'=>true));
	*/
	
	public function command($query = array())
	{
		try
		{
			$run = $this->db->command($query);
			return $run;
		}
		
		catch (MongoCursorException $e)
		{
			show_error("MongoDB command failed to execute: {$e->getMessage()}", 500);
		}
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	ADD_INDEX
	*	--------------------------------------------------------------------------------
	*
	*	Ensure an index of the keys in a collection with optional parameters. To set values to descending order,
	*	you must pass values of either -1, FALSE, 'desc', or 'DESC', else they will be
	*	set to 1 (ASC).
	*
	*	@usage : $this->mongo_db->add_index($collection, array('first_name' => 'ASC', 'last_name' => -1), array('unique' => TRUE));
	*/
	
	public function add_index($collection = "", $keys = array(), $options = array())
	{
		if (empty($collection))
		{
			show_error("No Mongo collection specified to add index to", 500);
		}
		
		if (empty($keys) || ! is_array($keys))
		{
			show_error("Index could not be created to MongoDB Collection because no keys were specified", 500);
		}

		foreach ($keys as $col => $val)
		{
			if($val == -1 || $val === FALSE || strtolower($val) == 'desc')
			{
				$keys[$col] = -1; 
			}
			else
			{
				$keys[$col] = 1;
			}
		}
		
		if ($this->db->{$collection}->ensureIndex($keys, $options) == TRUE)
		{
			$this->_clear();
			return ($this);
		}
		else
		{
			show_error("An error occured when trying to add an index to MongoDB Collection", 500);
		}
	}
	
	
	
	/**
	*	--------------------------------------------------------------------------------
	*	REMOVE_INDEX
	*	--------------------------------------------------------------------------------
	*
	*	Remove an index of the keys in a collection. To set values to descending order,
	*	you must pass values of either -1, FALSE, 'desc', or 'DESC', else they will be
	*	set to 1 (ASC).
	*
	*	@usage : $this->mongo_db->remove_index($collection, array('first_name' => 'ASC', 'last_name' => -1));
	*/
	
	public function remove_index($collection = "", $keys = array())
	{
		if (empty($collection))
		{
			show_error("No Mongo collection specified to remove index from", 500);
		}
		
		if (empty($keys) || ! is_array($keys))
		{
			show_error("Index could not be removed from MongoDB Collection because no keys were specified", 500);
		}
		
		if ($this->db->{$collection}->deleteIndex($keys, $options) == TRUE)
		{
			$this->_clear();
			return ($this);
		}
		else
		{
			show_error("An error occured when trying to remove an index from MongoDB Collection", 500);
		}
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	REMOVE_ALL_INDEXES
	*	--------------------------------------------------------------------------------
	*
	*	Remove all indexes from a collection.
	*
	*	@usage : $this->mongo_db->remove_all_index($collection);
	*/
	
	public function remove_all_indexes($collection = "")
	{
		if (empty($collection))
		{
			show_error("No Mongo collection specified to remove all indexes from", 500);
		}
		$this->db->{$collection}->deleteIndexes();
		$this->_clear();
		return ($this);
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	LIST_INDEXES
	*	--------------------------------------------------------------------------------
	*
	*	Lists all indexes in a collection.
	*
	*	@usage : $this->mongo_db->list_indexes($collection);
	*/
	public function list_indexes($collection = "")
	{
		if (empty($collection))
		{
			show_error("No Mongo collection specified to remove all indexes from", 500);
		}
		
		return ($this->db->{$collection}->getIndexInfo());
	}
	

	/**
	*	--------------------------------------------------------------------------------
	*	CONNECT TO MONGODB
	*	--------------------------------------------------------------------------------
	*
	*	Establish a connection to MongoDB using the connection string generated in
	*	the connection_string() method.  If 'mongo_persist_key' was set to true in the
	*	config file, establish a persistent connection.  We allow for only the 'persist'
	*	option to be set because we want to establish a connection immediately.
	*/
	
	private function connect()
	{
		$options = array();
		if ($this->persist === TRUE)
		{
			$options['persist'] = isset($this->persist_key) && !empty($this->persist_key) ? $this->persist_key : 'ci_mongo_persist';
		}
		
		try
		{
			$this->connection = new Mongo($this->connection_string, $options);
			$this->db = $this->connection->{$this->dbname};
			return ($this);	
		} 
		catch (MongoConnectionException $e)
		{
			if($this->CI->config->item('mongo_supress_connect_error'))
			{
				show_error("Unable to connect to MongoDB", 500);
			}
			else
			{
				show_error("Unable to connect to MongoDB: {$e->getMessage()}", 500);
			}
		}
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	BUILD CONNECTION STRING
	*	--------------------------------------------------------------------------------
	*
	*	Build the connection string from the config file.
	*/
	
	private function connection_string() 
	{
		$this->CI->config->load($this->config_file);
		
		$this->host	= trim($this->CI->config->item('mongo_host'));
		$this->port = trim($this->CI->config->item('mongo_port'));
		$this->user = trim($this->CI->config->item('mongo_user'));
		$this->pass = trim($this->CI->config->item('mongo_pass'));
		$this->dbname = trim($this->CI->config->item('mongo_db'));
		$this->persist = trim($this->CI->config->item('mongo_persist'));
		$this->persist_key = trim($this->CI->config->item('mongo_persist_key'));
		$this->query_safety = trim($this->CI->config->item('mongo_query_safety'));
		$dbhostflag = (bool)$this->CI->config->item('host_db_flag');
		
		$connection_string = "mongodb://";
		
		if (empty($this->host))
		{
			show_error("The Host must be set to connect to MongoDB", 500);
		}
		
		if (empty($this->dbname))
		{
			show_error("The Database must be set to connect to MongoDB", 500);
		}
		
		if ( ! empty($this->user) && ! empty($this->pass))
		{
			$connection_string .= "{$this->user}:{$this->pass}@";
		}
		
		if (isset($this->port) && ! empty($this->port))
		{
			$connection_string .= "{$this->host}:{$this->port}";
		}
		else
		{
			$connection_string .= "{$this->host}";
		}
		
		if ($dbhostflag === TRUE)
		{
			$this->connection_string = trim($connection_string) . '/' . $this->dbname;
		}
		
		else
		{
			$this->connection_string = trim($connection_string);
		}
	}
	
	/**
	*	--------------------------------------------------------------------------------
	*	_clear
	*	--------------------------------------------------------------------------------
	*
	*	Resets the class variables to default settings
	*/
	
	private function _clear()
	{
		$this->selects	= array();
		$this->wheres	= array();
		$this->limit	= 999999;
		$this->offset	= 0;
		$this->sorts	= array();
	}

	/**
	*	--------------------------------------------------------------------------------
	*	WHERE INITIALIZER
	*	--------------------------------------------------------------------------------
	*
	*	Prepares parameters for insertion in $wheres array().
	*/
	
	private function _where_init($param)
	{
		if ( ! isset($this->wheres[$param]))
		{
			$this->wheres[ $param ] = array();
		}
	}
	
}

// EOF
<?php

class MySQL {
	
	private $conn;
	
	
	public function __construct($host, $username, $password, $db = null){
		
		//create connection
		$this->conn = mysql_connect($host, $username, $password);
		if(!$this->conn){
			$this->error("Failure on connection.");
		}
		
		//if a database was passed:
		if($db !== null){
			$this->db_connect($db);
		}
		
	}
	
	
	/*
	 * connects to a database, given the name of the database
	 */
	public function db_connect($database_namebase){
		mysql_select_db($database_name, $this->conn) or $this->error("Failure on db selection");
	}
	
	
	/*
	 * Queries and returns an array of the extracted data
	 */ 

	public function getOneRow($sql){
		$result = $this->query($sql);
		if($this->num_rows($result) == 1){
			return $this->fetch_array($result);
		}else{
			return false;
		}
	}

	/*
	 * Executes the SQL, then returns the insert_id of the record
	 */ 
	public function insertAndReturnID($sql){
		$this->query($sql);
		return @mysql_insert_id($this->conn);
	}


	
	
	
	
	/*
	 * CORE FUNCTIONS:
	 * 		query
	 * 		fetch_array
	 * 		num_rows
	 */ 
	
	
	
	
	/*
	 * returns an executed query resource, given the SQL
	 */
	public function query($sql){
		$result = mysql_query($sql, $this->conn) or $this->error("Failure on Query.");
		return $result;
	}
	
	/*
	 * returns an array of the data, given a resource
	 */  	
	public function fetch_array($resource){
		return @mysql_fetch_array($resource, $this->conn);
	}
	
	/*
	 * return the number of rows, given a resource
	 */
	public function num_rows($resource){
		return @mysql_num_rows($resource, $this->conn);
	}
	
	
	public function escape($text){
		return mysql_real_escape_string($text, $this->conn);
	}
	
	public function __destruct(){
		@mysql_close();
	}
	
}


?>
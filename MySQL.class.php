<?php

class MySQL_Wrapper {
	
	private $conn = null;
	private $error_level = 1;//default to die on error, with detailed message
	
	/*
	 * call on object construction
	 */ 
	public function __construct($host, $username, $password, $database = null, $error_level = null){
		
		//check, before anything, if they are setting the error level
		if(is_numeric($error_level)){
			$this->setErrorLevel($error_level);
		}
		
		//create connection
		$this->connect($host, $username, $password);
		
		
		//if a database was passed, then select it
		if($database !== null){
			$this->db_select($database);
		}
		
	}	
	
	/*
	 * call on object destruction
	 * 		closes the connection to the database
	 */
	public function __destruct(){
		@mysql_close($this->conn);
	}



	/*
	 * establishes connection to db and saved in data member conn
	 */ 
	public function connect($host, $username, $password){
		
		//create connection
		$this->conn = @mysql_connect($host, $username, $password);
		if(!$this->conn){
			$this->error("Failure on connection.");
		}
		
	}
	
	/*
	 * connects to a database, given the name of the database
	 */
	public function db_select($database_name){
		@mysql_select_db($database_name, $this->conn) or $this->error("Failure on db selection");
	}
	
	
	
	

	
	
	
	
	/*
	 * BEGIN CORE FUNCTIONS: [these will be used internally and can/will be used externally as well]
	 * 		query
	 * 		fetch_array
	 * 		num_rows
	 * 		insert_id
	 * 		escape
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
		return @mysql_fetch_array($resource);
	}
	
	/*
	 * return the number of rows, given a resource
	 */
	public function num_rows($resource){
		return @mysql_num_rows($resource);
	}
	
	/*
	 * return the last insert_id
	 */ 
	public function insert_id(){
		return @mysql_insert_id($this->conn);
	}
	
	
	/*
	 * return an escaped (safe) text string, given a raw string
	 */
	
	public function escape($text){
		return @mysql_real_escape_string($text, $this->conn);
	}
	
	/*
	 * END CORE FUNCTIONS
	 */ 
	
	
	
	
	
	
	
	
	
	/*
	 * BEGIN 'unneccesary' FUNCTIONS [go beyond simple implementation, used only externally]
	 * 		getOneRow
	 * 		getMultipleRows
	 * 		insertAndReturnID
	 * 		
	 */ 
	
	
	
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
	 * Queries and returns an array of arrays
	 * 
	 * returned example : Array ( Array("name" => "Tommy", "twitter" => "ThomasTommyTom"), Array("name" => "Ruby", "twitter" => null) )
	 */ 
	public function getMultipleRows($sql){
		$result = $this->query($sql);
		if($this->num_rows($result) > 0){
			$data = array();
			while($row = $this->fetch_array($result)){
				$data[] = $row;
			}
			return $data;
		}else{
			return false;
		}
	}



	/*
	 * Executes the SQL, then returns the insert_id of the record
	 */ 
	public function insertAndReturnID($sql){
		$this->query($sql);
		return $this->insert_id();
	}	
	

	/*
	 * END 'unneccesary' FUNCTIONS
	 */ 	
	
	
	
	

	
	
	
	
	
	
	
	
	
	
	/*
	 * ERROR HANDLING FUNCTIONS:
	 * 		error
	 * 		setErrorLevel
	 */ 
	
	
	/*
	 * handles the error function
	 */ 
	public function error($msg){
		switch($this->error_level){
			case 1:
				die($msg." [".mysql_error($this->conn)."]");
			break;
			
			case 2: 
				die($msg);
			break;
			
			case 3:
				die();
			break;
		}
	}
	
	/*
	 * sets the error level [1 = die with all mesage data, 2 = die with basic message, 3 = die with no message, 4 = continue];
	 */ 
	public function setErrorLevel($level){
		$this->error_level = $level;	
	}
	
	/*
	 * END ERROR HANDLING FUNCTIONS
	 * 
	 */
	
}


?>
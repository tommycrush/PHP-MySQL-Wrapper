<?php

class MySQLi_Wrapper {
	
	private $conn = null;
	private $error_level = 1;//default to die on error, with detailed message
	
	/*
	 * call on object construction
	 */ 	
	public function __construct($host, $username, $password, $database = null, $error_level = null){
		
		//check, before anything, if they are setting a custom error level
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
		if($this->conn){
			@mysqli_close($this->conn);
		}
		return;
	}
	


	/*
 	*  connects to the server, given the required params
	 * saves the connection in the object variable of "conn"
 	*/ 	
	
	public function connect($host, $username, $password){
		$this->conn = @mysqli_connect($host, $username, $password);		
		
		if(!$this->conn){
			$this->error("Failure on connection.");
		}
	}




	
	/*
	 * connects to a database, given the name of the database
	 */
	public function db_select($database_name){
		@mysqli_select_db($this->conn, $database_name) or $this->error("Failure on db selection");
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
		$result = mysqli_query($this->conn, $sql) or $this->error("Failure on query");
		return $result;
	}
	
	/*
	 * returns an array of the data, given a resource
	 */  	
	public function fetch_array($resource){
		return @mysqli_fetch_array($resource);
	}
	
	/*
	 * return the number of rows, given a resource
	 */
	public function num_rows($resource){
		return @mysql_num_rows($resource);
	}
	
	
	/*
	 * return an escaped (safe) text string, given a raw string
	 */
	
	public function escape($text){
		return @mysqli_real_escape_string($this->conn, $text);
	}


	/*
	 * return the last insert_id
	 */ 
	public function insert_id(){
		return @mysqli_insert_id($this->conn);
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
		return $this->insert_id($this->conn);
	}	
	
	/*
	 * data passed as arguements and is escaped for safer insertion
	 */ 

	public function smartInsert($table, $columns, $data){
		//compose SQL
		$sql = "INSERT INTO `".$table."` (";
	
		//create COLUMNS
		$x = 0;
		foreach($columns as $column){
			if($x > 0){
				$sql .= ",";
			}

			$sql .= "`".$column."`";
			
			$x++;
		}
		
		//end COLUMNS
		$sql .= ") VALUES ";
		
		
	
		if(count($data) != count($data, COUNT_RECURSIVE)){
			//multidimensional, a.k.a : insert more than 1 row
			
			$rows = array();
			
			//loop through 2d ARRAy
			foreach($data as $row){
				
				//build values
				$row_sql = "(";
				$x = 0;
				foreach($row as $value){
					if($x > 0){
						$row_sql .= ",";
					}
					$row_sql .= "'".$this->escape($value)."'";
					
					$x++;
				}
				$row_sql .= ")";
				$rows[] = $row_sql;
			}
			
			
			$sql .= implode(",",$rows);
			
			//because its multiple rows, let execute the query and just return true
			$this->query($sql);
			return true;
			
		}else{
			
			//not multidimensional, a.k.a : insert 1 row

			//build values
			$row_sql = "(";
			$x = 0;
			foreach($data as $value){

				if($x > 0){
					$row_sql .= ",";
				}
				
				$row_sql .= "'".$this->escape($value)."'";
					
				$x++;
			}
		
			$row_sql .= ")";		
			
			$sql .= $row_sql;
			
			//because its only 1 row, lets just go ahead and return the insert_id
			return $this->insertAndReturnID($sql);
			
		}//end if not multidimensional
		
		
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
				die($msg." [".($this->conn ? mysqli_error($this->conn) : "requires connection for more info")."]");
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
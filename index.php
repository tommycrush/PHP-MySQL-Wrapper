<?php
//for initial debugging
ini_set("display_errors",1);


//connection example for MySQL:
require("MySQL.class.php");
$db = new MySQL_Wrapper("localhost", "root", "root", "test_database");

/*
//connection example for MySQLi
require('MySQLi.class.php');
$db = new MySQLi_Wrapper("localhost", "root", "root", "test_database");
*/



/* All wrappers contain the following functions:
 * 
 * 		CORE FUNCTIONS:
 * 
 * 		connect (called when created)
 * 		select_db (called when created, can be called to change db)
 * 		query
 * 		fetch_array
 * 		num_rows
 * 		insert_id
 * 		escape
 * 
 * 
 * 		MORE FUNCTIONS:
 * 
 * 		getOneRow(string $query)
 * 			returns an array of the data, or false if anything but 1 was returned by the query
 * 			note, use LIMIT 1 in the query
 * 	
 * 		getMultipleRows(string $query)
 * 			returns a 2D array of the data, or false is nothing was returned by the query
 * 
 * 		insertAndReturnID(string $query)
 * 			returns the insert_id
 * 
 * 		smartInsert(string $table_name, array $columns, array $values); 1 row insert
 * 			returns the insert_id
 * 		smartInsert(string $table_name, array $columns, array array $values); multi row insert
 * 			return true;
 */

 
 //insert and return ID
$new_id = $db->insertAndReturnID("INSERT INTO `users` (`email`,`first_name`,`last_name`) VALUES ('email','fist','name') ");



//escape data
$name = $db->escape('tommy');


//if we only want 1 row of data, we can get it returned as an array to simplify things
$data  = $db->getOneRow("SELECT * FROM `users` WHERE first_name='$name' LIMIT 1");

if(!$data){
	echo "No rows were found :(";
}else{

	//lets see what we got:
	echo "<pre>";
	print_r($data);
	echo "</pre>";

}	



//lets get a lot of data returned to us in a 2D array
foreach($db->getMultipleRows("SELECT `first_name` FROM `users`") as $row){
	echo $row["first_name"]."<br/>";
}



//'Smart' Inserts:

$columns =  array('email','first_name','last_name');
$data = array(
			array('me@tommycrush.com','tommy','crush'),
			array('you@you.com','John','Doe')
			);

$success = $db->smartInsert("users", $columns, $data);

if($success){
	echo "Sweet!";
}
?>
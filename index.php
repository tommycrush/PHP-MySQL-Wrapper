<?php

//connection example for MySQL:
require("MySQL.class.php");
$db = new MySQL_Wrapper("localhost", "root", "root", "test_database");
unset($db);


//connection example for MySQLi
require('MySQLi.class.php');
$db = new MySQLi_Wrapper("localhost", "root", "root", "test_database");
unset($db);


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
 * 		getOneRow($query)
 * 			returns an array of the data, or false if anything but 1 was returned by the query
 * 			note, use LIMIT 1 in the query
 * 	
 * 		getMultipleRows($query)
 * 			returns a 2D array of the data, or false is nothing was returned by the query
 * 
 * 		insertAndReturnID($query)
 * 			returns the insert_id
 * 
 */

 
//insert a new row
$insert_id = $db->insertAndReturnID("INSERT INTO `test` (`col1`,`col2`) VALUES ('v1', 'v2')");

//OR, lets insert mutiple rows using the raw "query" function
$db->query("INSERT INTO `test` (`col1`,`col2`) VALUES ('v2', 'v2'),('v3','v3')");



//get one row of data
$data = $db->getOneRow("SELECT * FROM `test` WHERE `col1`='v1' LIMIT 1");

//get a 2d array of the data
foreach($db->getMultipleRows("SELECT * FROM `test`") as $row){
	echo $row["col1"]."<br/>";
}





?>
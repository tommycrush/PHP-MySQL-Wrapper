<?php

//example for MySQL:
require("MySQL.class.php");
$db = new MySQL_Wrapper("localhost", "root", "root", "test_database");
unset($db);


//example for MySQLi
require('MySQLi.class.php');
$db = new MySQLi_Wrapper("localhost", "root", "root", "test_database");
unset($db);
?>
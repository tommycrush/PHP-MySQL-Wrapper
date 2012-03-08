# MySQL/MySQLi Wrapper for PHP
a wrapper written in PHP designed to:

* more easily maintain MySQL database interaction [i.e. - bug fixes, passwords, etc.]
* can efficiently switch between MySQL and MySQLi (no code changes, just the class needs to be changed)
* makes runtime queries much more simple and organized.

###Instructions:
1) download files, and upload MySQL or MYSQLi class file to server
 
2) require that file

3) create the object: `$db = new MySQL_Wrapper("localhost", "user", "pass", "db_name");`


####Raw Query
`$result = $db->query("SELECT * FROM users LIMIT 1");
if($db->num_rows($result) == 1){
    $data = $db->fetch_array($result);
}`

while that's still useful in the sense we can now move between MySQL and MySQL, we can make this a lot cleaner using a few extra functions:

####getOneRow
`$data  = $db->getOneRow("SELECT * FROM users LIMIT 1");`

if `$data` is false, then the query came up empty, else `$data` is an array of whats been returned

####getMultipleRows
`$rows = $db->getMultipleRows("SELECT * FROM users"); \n
if(!$rows){
echo "No rows found!";
}else{
foreach($rows as $row){
echo $row["name"];
}
}`

`$rows` is a 2D array of rows of data, or its false is nothing is returned

Created by Tommy Crush
-
* [ThomasTommyTom](http://twitter.com/ThomasTommyTom)
* [tommycrush.com](http://tommycrush.com)
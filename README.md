# MySQL/MySQLi Wrapper for PHP
a wrapper written in PHP designed to:

* more easily maintain MySQL database interaction [i.e. - bug fixes, passwords, etc.]
* can efficiently switch between MySQL and MySQLi (no code changes, just the class needs to be changed)
* makes runtime queries much more simple and organized.

###Instructions:
1) download files, and upload MySQL or MYSQLi class file to server
 
2) require that file

3) create the object: `$db = new MySQL_Wrapper("localhost", "user", "pass", "db_name");`


####getOneRow
```php
$data  = $db->getOneRow("SELECT * FROM users LIMIT 1");
```

if `$data` is false, then the query came up empty, else `$data` is an array of whats been returned


####getMultipleRows
```php
$rows = $db->getMultipleRows("SELECT * FROM users");
```

`$rows` is a 2D array of rows of data, or its false is nothing is returned

####insertAndReturnId
```php 
$new_id = $db->insertAndReturnId("INSERT INTO users (`name`) VALUES ('tommy')");
```
`$new_id` contains the mysql(i)_insert_id. Use only if you are inserting 1 row


####smartInsert
```php
$columns =  array('email','first_name','last_name');
$data = array(
		array('me@tommycrush.com','tommy','crush'),
		array('you@you.com','John','Doe')
	     );

$success = $db->smartInsert("users", $columns, $data);
```

this function is called "smartInsert" because it escapes all data before it is inserted. all parameters are required `(string)$table_name`, `(array)$columns`, `(array or 2D array)$data`. Returns true on success. 



####Raw Query 
this is a core functionality and _not necessarily recommended_, but is available for rare cases where it is needed

```php
$result = $db->query("SELECT * FROM users LIMIT 1");

if($db->num_rows($result) == 1){
    $data = $db->fetch_array($result);
}
```

while that's still useful in the sense we can now move between MySQL and MySQL, we can make this a lot cleaner using a few extra functions:


Created by Tommy Crush
-
* [ThomasTommyTom](http://twitter.com/ThomasTommyTom)
* [tommycrush.com](http://tommycrush.com)
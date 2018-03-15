

<?php
/*
* Purpose: Connect to database.
*/
define('DB_DSN','mysql:host=localhost;dbname=eventproject');
define('DB_USER','sAdmin');
define('DB_PASS','YearBeast28?');     

try 
{
	$db = new PDO(DB_DSN, DB_USER, DB_PASS);
} catch (PDOException $e) 
{
	print "Error: " . $e->getMessage();
    die(); // Force execution to stop on errors.
}
?>
<?php
$connect_error='Sorry, the Factionizer is not working right now.';
mysql_connect('localhost', 'faction', 'qwer1234') or die($connect_error);
mysql_select_db('faction_factionizer') or die($connect_error);
/*
define("HOST", "factionizer.com"); // The host you want to connect to.
define("USER", "faction"); // The database username.
define("PASSWORD", "qwer1234"); // The database password. 
define("DATABASE", "faction_factionizer"); // The database name.
 
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
global $connection;
// Check connection
if (mysqli_connect_errno($connection))
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }*/

?>
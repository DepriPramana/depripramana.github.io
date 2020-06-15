<?php

$dbhost='localhost';
$dbuser='root';
$dbpass='';
$dbname='wilayah';
$db_dsn = "mysql:dbname=$dbname;host=$dbhost";
$tbl_wilayah="wilayah_2020";
try {
  $db = new PDO($db_dsn, $dbuser, $dbpass);
} catch (PDOException $e) {
  echo 'Connection failed: '.$e->getMessage();
}

?>
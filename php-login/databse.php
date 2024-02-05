<?php

$server = 'sql10.freesqldatabase.com';
$username = 'sql10681356';
$password = 'IupH4tX2dN';
$database = 'sql10681356'; 




try {
   $conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
 } catch (PDOException $e) {
   die('Connection Failed: ' . $e->getMessage());
    }



?>

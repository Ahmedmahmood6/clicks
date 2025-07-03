<?php 
$db_username = 'root';
$db_password = '';
$db_name = 'website';
$db_host = 'localhost';
$conn = mysqli_connect($db_host ,$db_username, $db_password, $db_name);
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
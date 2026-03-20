<?php
$host = "localhost";
$users = "root";
$pass = "";
$database = "user_system";

$conn = mysqli_connect($host, $users, $pass, $database);

if(!$conn){
    die("Connection Failed:". mysqli_connect_error());
}
?>
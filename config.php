<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "user_db";
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connect failed: ".$conn->connect_error);
}

?>
<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "form_builder";

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
    die("Connection Failed");
}

?>
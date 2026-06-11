<?php

// $host = "localhost";
// $user = "root";
// $pass = "";
// $db   = "form_builder";

// $conn = new mysqli($host, $user, $pass, $db);

// if($conn->connect_error){
//     die("Connection Failed");
// }
?>
<?php
$host = "acela.proxy.rlwy.net";
$user = "root";
$pass = "DlEyosACpXDntezmCtiyCrBAGgSucSWm";
$db   = "railway";
$port = "59922";

$conn = new mysqli($host, $user, $pass, $db, $port);

if($conn->connect_error){
    die("Connection Failed");
}
?>

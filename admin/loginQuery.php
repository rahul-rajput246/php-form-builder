<?php

session_start();

require_once '../config/database.php';

$email = trim($_POST['email']);
$password = trim($_POST['password']);

$stmt = $conn->prepare("
    SELECT *
    FROM admins
    WHERE email = ?
");

$stmt->bind_param("s",$email);
$stmt->execute();

$admin = $stmt
    ->get_result()
    ->fetch_assoc();

if(
    $admin &&
    password_verify(
        $password,
        $admin['password']
    )
)
{
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];

    header("Location: forms.php");
    exit;
}

header(
    "Location: login.php?error=Invalid Credentials"
);
exit;
?>
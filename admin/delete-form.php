<?php

require_once '../config/database.php';

$id = (int)$_GET['id'];

$deleteFields = $conn->prepare("
    DELETE FROM fields
    WHERE form_id = ?
");

$deleteFields->bind_param("i", $id);
$deleteFields->execute();

$deleteForm = $conn->prepare("
    DELETE FROM forms
    WHERE id = ?
");

$deleteForm->bind_param("i", $id);

if ($deleteForm->execute()) {

    header("Location: forms.php?success=Form Deleted Successfully");
    exit;

} else {

    die($deleteForm->error);
}

?>
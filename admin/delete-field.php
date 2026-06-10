<?php

require_once '../config/database.php';

$id = (int)$_GET['id'];
$form_id = (int)$_GET['form_id'];

$stmt = $conn->prepare("
    DELETE FROM fields
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

header(
    "Location: manage-fields.php?form_id=" . $form_id
);

exit;
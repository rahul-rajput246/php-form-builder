<?php
require_once '../config/database.php';

$type = $_GET['type'] ?? '';
$id   = (int)($_GET['id'] ?? 0);

if (!$type || !$id) {
    die("Invalid request");
}

switch ($type) {

    /* Delete Submissions */

    case 'submission':

        // delete values first
        $stmt = $conn->prepare("
            DELETE FROM submission_values
            WHERE submission_id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // delete submission
        $stmt = $conn->prepare("
            DELETE FROM submissions
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: manage-submissions.php");
        exit;

    /* Delete Form */

    case 'form':

        $conn->query("
            DELETE sv FROM submission_values sv
            JOIN submissions s ON sv.submission_id = s.id
            WHERE s.form_id = $id
        ");

        $stmt = $conn->prepare("
            DELETE FROM submissions
            WHERE form_id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $stmt = $conn->prepare("
            DELETE FROM fields
            WHERE form_id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $stmt = $conn->prepare("
            DELETE FROM forms
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: forms.php?success=Form deleted");
        exit;

    /* Delete Field */
    case 'field':

        $form_id = (int)($_GET['form_id'] ?? 0);

        $stmt = $conn->prepare("
            DELETE FROM fields
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        
        if($stmt->execute()) {

        header(
            "Location: manage-fields.php?form_id=" .
            $form_id .
            "&success=Field deleted successfully"
        );

    } else {

        header(
            "Location: manage-fields.php?form_id=" .
            $form_id .
            "&error=Failed to delete field"
        );

    }

    exit;

    default:
        die("Invalid delete type");
}
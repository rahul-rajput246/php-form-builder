<?php
require_once '../config/database.php';

$slug = $_GET['slug'] ?? '';

$stmt = $conn->prepare("SELECT * FROM forms WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$form = $stmt->get_result()->fetch_assoc();

if (!$form) {
    die("Form not found");
}

$fieldStmt = $conn->prepare("SELECT * FROM fields WHERE form_id = ?");
$fieldStmt->bind_param("i", $form['id']);
$fieldStmt->execute();
$fields = $fieldStmt->get_result()->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("
        INSERT INTO submissions (form_id)
        VALUES (?)
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $form['id']);

    if (!$stmt->execute()) {
        die("Submission failed: " . $stmt->error);
    }

    $submission_id = $conn->insert_id;

    if (!$submission_id) {
        die("Submission ID not generated");
    }

    $fieldStmt = $conn->prepare("
        SELECT id, type
        FROM fields
        WHERE form_id = ?
    ");

    $fieldStmt->bind_param("i", $form['id']);
    $fieldStmt->execute();

    $result = $fieldStmt->get_result();

    while ($field = $result->fetch_assoc()) {

        $fieldName = 'field_' . (int)$field['id'];

        $value = '';

        if ($field['type'] === 'checkbox') {
            $value = isset($_POST[$fieldName])
                ? implode(',', $_POST[$fieldName])
                : '';
        } else {
            $value = $_POST[$fieldName] ?? '';
        }

        $save = $conn->prepare("
            INSERT INTO submission_values
            (submission_id, field_id, value)
            VALUES (?, ?, ?)
        ");

        if (!$save) {
            die("Prepare failed: " . $conn->error);
        }

        $save->bind_param(
            "iis",
            $submission_id,
            $field['id'],
            $value
        );

        if (!$save->execute()) {
            die("Insert failed: " . $save->error);
        }
    }

    $success = "Form submitted successfully!";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= htmlspecialchars($form['name']) ?></title>

    <style>
    body {
        font-family: Arial;
        background: #f4f6f8;
        margin: 0;
    }

    .container {
        max-width: 700px;
        margin: 40px auto;
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
    }

    .field {
   
    margin-bottom: 20px;
}

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input,
    textarea,
    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }

    button {
        width: 100%;
        padding: 12px;
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    button:hover {
        background: #3730a3;
    }

    .success {
        background: #d4edda;
        color: #155724;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
    }

    .error {
        background: #f8d7da;
        color: #721c24;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
    }
    </style>
</head>

<body>

    <div class="container">

        <?php if (isset($success)): ?>
        <div class="success">
            <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <h2><?= htmlspecialchars($form['name']) ?></h2>

        <form method="POST">

            <?php foreach ($fields as $field): ?>

            <?php $name = "field_" . $field['id']; ?>

            <div class="field">

                <label><?= htmlspecialchars($field['label']) ?></label>

                <?php switch ($field['type']) {

                    case 'text': ?>
                <input type="text" name="<?= $name ?>">
                <?php break;

                    case 'email': ?>
                <input type="email" name="<?= $name ?>">
                <?php break;

                    case 'number': ?>
                <input type="number" name="<?= $name ?>">
                <?php break;

                    case 'textarea': ?>
                <textarea name="<?= $name ?>"></textarea>
                <?php break;

                    case 'dropdown': ?>
                <select name="<?= $name ?>">
                    <?php foreach (explode("\n", $field['options']) as $opt): ?>
                    <option value="<?= trim($opt) ?>"><?= trim($opt) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php break;

                    case 'radio': ?>
                <?php foreach (explode("\n", $field['options']) as $opt): ?>
                <label>
                    <input type="radio" name="<?= $name ?>" value="<?= trim($opt) ?>">
                    <?= trim($opt) ?>
                </label><br>
                <?php endforeach; ?>
                <?php break;

                    case 'checkbox': ?>
                <?php foreach (explode("\n", $field['options']) as $opt): ?>
                <label>
                    <input type="checkbox" name="<?= $name ?>[]" value="<?= trim($opt) ?>">
                    <?= trim($opt) ?>
                </label><br>
                <?php endforeach; ?>
                <?php break;

                } ?>

            </div>

            <?php endforeach; ?>

            <button type="submit">Submit</button>

        </form>

    </div>

</body>

</html>
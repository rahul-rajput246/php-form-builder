<?php

require_once '../config/database.php';
require_once 'includes/auth.php';

$id = (int)$_GET['id'];
$form_id = (int)$_GET['form_id'];

$stmt = $conn->prepare("
    SELECT *
    FROM fields
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$field = $stmt->get_result()->fetch_assoc();

if(!$field){
    die("Field not found");
}

if(isset($_POST['update_field']))
{
    $label = trim($_POST['label']);
    $placeholder = trim($_POST['placeholder']);
    $type = $_POST['type'];
    $required = isset($_POST['required']) ? 1 : 0;
    $options = trim($_POST['options']);

    $update = $conn->prepare("
        UPDATE fields
        SET
            label = ?,
            placeholder = ?,
            type = ?,
            required = ?,
            options = ?
        WHERE id = ?
    ");

    $update->bind_param(
        "sssisi",
        $label,
        $placeholder,
        $type,
        $required,
        $options,
        $id
    );

    $update->execute();

    header(
        "Location: manage-fields.php?form_id=" . $form_id
    );

    exit;
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="page-wrapper">

    <?php include 'includes/sidebar.php'; ?>

    <div class="content-area">

        <div class="container">

            <h2>Edit Field</h2>

            <form method="POST">

                <div class="form-group">

                    <label>Label</label>

                    <input type="text" name="label" value="<?= htmlspecialchars($field['label']) ?>" required>

                </div>

                <div class="form-group">

                    <label>Placeholder</label>

                    <input type="text" name="placeholder" value="<?= htmlspecialchars($field['placeholder']) ?>">

                </div>

                <div class="form-group">

                    <label>Field Type</label>

                    <select name="type">

                        <option value="text" <?= $field['type']=='text'?'selected':'' ?>>
                            Text
                        </option>

                        <option value="email" <?= $field['type']=='email'?'selected':'' ?>>
                            Email
                        </option>

                        <option value="number" min="0" max="10" <?= $field['type']=='number'?'selected':'' ?>>
                            Number
                        </option>

                        <option value="textarea" <?= $field['type']=='textarea'?'selected':'' ?>>
                            Textarea
                        </option>

                        <option value="dropdown" <?= $field['type']=='dropdown'?'selected':'' ?>>
                            Dropdown
                        </option>

                        <option value="radio" <?= $field['type']=='radio'?'selected':'' ?>>
                            Radio
                        </option>

                        <option value="checkbox" <?= $field['type']=='checkbox'?'selected':'' ?>>
                            Checkbox
                        </option>

                        <option value="file" <?= $field['type']=='file'?'selected':'' ?>>
                            File Upload
                        </option>

                    </select>

                </div>

                <div class="form-group">

                    <label>Options</label>

                    <textarea name="options"><?= htmlspecialchars($field['options']) ?></textarea>

                </div>

                <button type="submit" name="update_field" class="btn btn-edit">

                    Update Field

                </button>

                <a href="manage-fields.php?form_id=<?= $form_id ?>" class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>
<?php include 'includes/footer.php'; ?>
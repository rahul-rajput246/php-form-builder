<?php

require_once '../config/database.php';
require_once 'includes/auth.php';

$form_id = isset($_GET['form_id']) ? (int)$_GET['form_id'] : 0;

$formStmt = $conn->prepare("SELECT * FROM forms WHERE id = ?");

$formStmt->bind_param("i", $form_id);
$formStmt->execute();

$form = $formStmt->get_result()->fetch_assoc();

if(!$form){
    die("Form not found");
}

if(isset($_POST['add_field']))
{
    $label = trim($_POST['label']);
    $placeholder = trim($_POST['placeholder']);
    $type = $_POST['type'];
    $required = isset($_POST['required']) ? 1 : 0;
    $options = trim($_POST['options']);

$stmt = $conn->prepare("
    INSERT INTO fields
    (
        form_id,
        type,
        label,
        placeholder,
        required,
        options
    )
    VALUES(?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "isssis",
    $form_id,
    $type,
    $label,
    $placeholder,
    $required,
    $options
);

$stmt->execute();

header("Location: manage-fields.php?form_id=".$form_id);
exit;
}

$fieldStmt = $conn->prepare("SELECT * FROM fields WHERE form_id = ?");

$fieldStmt->bind_param("i",$form_id);
$fieldStmt->execute();

$fields = $fieldStmt->get_result();

?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="page-wrapper">

    <?php include 'includes/sidebar.php'; ?>

    <div class="content-area">

        <div class="container">



            <div class="main-heading-box">
                <h2>Manage Fields :<?= htmlspecialchars($form['name']) ?></h2>
                <a href="forms.php" class="btn btn-secondary">
                    Back to Forms
                </a>
            </div>

            <div class="form-builder-card">
                <form method="POST">

                    <div class="form-group">
                        <label>Label</label>
                        <input type="text" id="label" name="label" placeholder="Enter field label" required>
                    </div>

                    <div class="form-group">
                        <label>Placeholder</label>
                        <input type="text" id="placeholder" name="placeholder" placeholder="Enter placeholder">
                    </div>

                    <div class="form-group">
                        <label>Field Type</label>

                        <select name="type" id="fieldType" required>

                            <option value="text">Text</option>
                            <option value="email">Email</option>
                            <option value="number" min="0" max="10">Number</option>
                            <option value="textarea">Textarea</option>
                            <option value="dropdown">Dropdown</option>
                            <option value="radio">Radio</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="file">File Upload</option>

                        </select>
                    </div>

                    <div class="form-group" id="optionsBox" style="display:none;">
                        <label>Options</label>
                        <textarea name="options"
                            placeholder="One option per line&#10;Male&#10;Female&#10;Other"></textarea>
                    </div>

                    <div class="builder-preview">
                        <div id="previewContainer"></div>
                    </div>

                    <div class="form-group required-checkbox">

                        <input type="checkbox" id="requiredField" name="required">

                        <label for="requiredField">
                            Required Field
                        </label>

                    </div>

                    <button type="submit" name="add_field" class="btn btn-save">
                        Save Field
                    </button>

                </form>

            </div>

            <div class="main-table-container">
                <h3>Existing Fields</h3>
                <table class="fields-table">

                    <tr>
                        <th>ID</th>
                        <th>Label</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th>Actions</th>
                    </tr>

                    <?php while($field = $fields->fetch_assoc()): ?>

                    <tr>

                        <td><?= $field['id']; ?></td>

                        <td><?= htmlspecialchars($field['label']); ?></td>

                        <td><?= ucfirst($field['type']); ?></td>

                        <td><?= $field['required'] ? 'Yes' : 'No'; ?></td>

                        <td>

                            <a href="edit-field.php?id=<?= $field['id']; ?>&form_id=<?= $form_id; ?>"
                                class="btn btn-edit">
                                Edit
                            </a>

                            <a href="delete.php?type=field&id=<?= $field['id']; ?>&form_id=<?= $form_id; ?>"
                                class="btn btn-delete" onclick="return confirm('Delete this field?')">
                                Delete
                            </a>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                </table>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
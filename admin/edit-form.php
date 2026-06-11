<?php

require_once '../config/database.php';
require_once 'includes/auth.php';

$id = (int)$_GET['id'];

$stmt = $conn->prepare("
    SELECT *
    FROM forms
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$form = $stmt->get_result()->fetch_assoc();

if (!$form) {
    die("Form not found");
}

$error = '';

if (isset($_POST['update_form'])) {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (empty($name)) {

        $error = "Form Name is required.";

    } else {

        $update = $conn->prepare("
            UPDATE forms
            SET
                name = ?,
                description = ?
            WHERE id = ?
        ");

        $update->bind_param(
            "ssi",
            $name,
            $description,
            $id
        );

        $update->execute();

        header("Location: forms.php?success=Form Updated Successfully");
        exit;
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="page-wrapper">

    <?php include 'includes/sidebar.php'; ?>

    <div class="content-area">

        <div class="container">

            <h2 style="margin-bottom: 20px;">Edit Form</h2>

            <?php if(!empty($error)): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>Form Name</label>

                    <input type="text" name="name" value="<?= htmlspecialchars($form['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Description</label>

                    <textarea name="description"><?= htmlspecialchars($form['description']) ?></textarea>
                </div>

                <button type="submit" name="update_form" class="btn btn-edit">
                    Update Form
                </button>

                <a href="forms.php" class="btn btn-secondary">
                    Back
                </a>

            </form>

        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
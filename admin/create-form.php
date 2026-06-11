<?php

require_once '../config/database.php';
require_once 'includes/auth.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (empty($name)) {

        $error = "Form Name is required.";

    } else {

        // Generate slug
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');

        // Check if slug already exists
        $check = $conn->prepare("SELECT id FROM forms WHERE slug = ?");
        $check->bind_param("s", $slug);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $slug .= '-' . time();
        }

        // Insert Form
        $stmt = $conn->prepare("
            INSERT INTO forms (name, description, slug)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param(
            "sss",
            $name,
            $description,
            $slug
        );

        if ($stmt->execute()) {

            header("Location: forms.php?success=Form Created Successfully");
            exit;

        } else {

            $error = "Something went wrong.";

        }
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="page-wrapper">

    <?php include 'includes/sidebar.php'; ?>

    <div class="content-area">

        <div class="container">

            <h2 style="margin-bottom: 20px;">Create New Form</h2>

            <?php if(!empty($error)): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">

                    <label>Form Name</label>

                    <input type="text" name="name"
                        value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>

                </div>

                <div class="form-group">

                    <label>Description</label>

                    <textarea
                        name="description"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>

                </div>

                <button type="submit" class="btn btn-add">
                    Create Form
                </button>

                <a href="forms.php" class="btn btn-secondary">
                    Back
                </a>

            </form>

        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
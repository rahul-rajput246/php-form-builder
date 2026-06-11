<?php
require_once '../config/database.php';
require_once 'includes/auth.php';

$form_id = $_GET['form_id'] ?? '';

$forms = $conn->query("SELECT id, name FROM forms ORDER BY id DESC");

if ($form_id) {
    $stmt = $conn->prepare("
        SELECT s.*, f.name AS form_name
        FROM submissions s
        JOIN forms f ON s.form_id = f.id
        WHERE s.form_id = ?
        ORDER BY s.id DESC
    ");
    $stmt->bind_param("i", $form_id);
    $stmt->execute();
    $submissions = $stmt->get_result();
} else {
    $submissions = $conn->query("
        SELECT s.*, f.name AS form_name
        FROM submissions s
        JOIN forms f ON s.form_id = f.id
        ORDER BY s.id DESC
    ");
}
?>

    <?php include 'includes/header.php'; ?>
    <?php include 'includes/navbar.php'; ?>

    <div class="page-wrapper">

        <?php include 'includes/sidebar.php'; ?>

        <div class="content-area">

            <div class="main">

                <div class="top-bar">

                    <h2>All Submissions</h2>

                    <form method="GET">
                        <select name="form_id" onchange="this.form.submit()">
                            <option value="">All Forms</option>

                            <?php while($f = $forms->fetch_assoc()): ?>
                            <option value="<?= $f['id'] ?>" <?= $form_id == $f['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($f['name']) ?>
                            </option>
                            <?php endwhile; ?>

                        </select>
                    </form>

                </div>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Form</th>
                        <th>Actions</th>
                    </tr>

                    <?php while($row = $submissions->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['form_name']) ?></td>
                        <td>
                            <a class="btn view" href="view-submission.php?id=<?= $row['id'] ?>">
                                View
                            </a>

                            <a class="btn delete" href="delete.php?type=submission&id=<?= $row['id'] ?>"
                                onclick="return confirm('Delete this submission?')">
                                Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>

                </table>

            </div>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
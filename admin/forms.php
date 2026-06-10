<?php
require_once '../config/database.php';

$result = $conn->query("SELECT * FROM forms ORDER BY id DESC");
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="page-wrapper">

    <?php include 'includes/sidebar.php'; ?>

    <div class="content-area">

        <div class="container">

            <?php if(isset($_GET['success'])): ?>
            <div class="success">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
            <?php endif; ?>

            <div class="header">

                <h2>Manage Forms</h2>

                <a href="create-form.php" class="btn btn-add">
                    Add New Form
                </a>

            </div>

            <table class="forms-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Form Name</th>
                        <th>Description</th>
                        <th>Slug</th>
                        <th>Created At</th>
                        <th>Public URL</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if($result->num_rows > 0): ?>

                    <?php while($row = $result->fetch_assoc()): ?>

                    <tr>

                        <td><?= $row['id']; ?></td>

                        <td><?= htmlspecialchars($row['name']); ?></td>

                        <td><?= htmlspecialchars($row['description']); ?></td>

                        <td><?= htmlspecialchars($row['slug']); ?></td>

                        <td><?= $row['created_at']; ?></td>

                        <td>
                            <a target="_blank" href="../public/form.php?slug=<?= $row['slug'] ?>">
                                Open Form
                            </a>
                        </td>

                        <td class="actions">

                            <a href="manage-fields.php?form_id=<?= $row['id']; ?>" class="btn btn-secondary">
                                Manage Fields
                            </a>

                            <a href="edit-form.php?id=<?= $row['id']; ?>" class="btn btn-edit">
                                Edit
                            </a>

                            <a href="delete-form.php?id=<?= $row['id']; ?>" class="btn btn-delete"
                                onclick="return confirm('Delete this form?')">
                                Delete
                            </a>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                    <?php else: ?>

                    <tr>
                        <td colspan="6">No Forms Found</td>
                    </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>
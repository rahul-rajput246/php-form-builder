<?php
require_once '../config/database.php';

$result = $conn->query("SELECT * FROM forms ORDER BY id DESC");

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Forms</title>

    <style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #eef2ff, #f8fafc);
        color: #1f2937;
    }

    .navbar {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 0;
    }

    .logo {
        font-weight: 700;
        font-size: 18px;
    }

    .nav-links a {
        margin-left: 15px;
        text-decoration: none;
        color: #333;
        font-weight: 500;
    }

    .nav-links a:hover {
        color: #4f46e5;
    }

    .hero {
        text-align: center;
        padding: 50px 20px 20px;
    }

    .hero h1 {
        font-size: 34px;
        margin-bottom: 8px;
    }

    .hero p {
        color: #6b7280;
    }

    .container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 20px;
    }

    .card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 16px;
        padding: 18px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: 0.25s ease;
        backdrop-filter: blur(8px);
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .card h3 {
        margin: 0 0 8px;
        font-size: 17px;
    }

    .card p {
        font-size: 13px;
        color: #6b7280;
        min-height: 40px;
    }

    .btn {
        display: inline-block;
        margin-top: 12px;
        padding: 9px 12px;
        background: #4f46e5;
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        transition: 0.2s;
    }

    .btn:hover {
        background: #3730a3;
    }

    .empty {
        text-align: center;
        color: #6b7280;
        margin-top: 50px;
    }

    .navbar-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .navbar-logo {
        font-size: 28px;
        font-weight: 800;
        color: #000000;
    }

    .navbar-logo span {
        color: #3b82f6;
    }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="navbar-left">

            <div class="navbar-logo">
                Form<span>Builder</span>
            </div>

        </div>

        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="../admin/login.php">Admin</a>
        </div>
    </div>

    <div class="hero">
        <h1>Available Forms</h1>
        <p>Fill out dynamic forms created by admin</p>
    </div>

    <div class="container">

        <?php if ($result->num_rows === 0): ?>

        <p class="empty">No forms available right now.</p>

        <?php else: ?>

        <div class="grid">

            <?php while($form = $result->fetch_assoc()): ?>

            <div class="card">

                <h3>
                    <?= htmlspecialchars($form['name']) ?>
                </h3>

                <p>
                    <?= htmlspecialchars($form['description']) ?>
                </p>

                <a class="btn" href="form.php?slug=<?= $form['slug'] ?>">
                    Open Form →
                </a>

            </div>

            <?php endwhile; ?>

        </div>

        <?php endif; ?>

    </div>

</body>

</html>
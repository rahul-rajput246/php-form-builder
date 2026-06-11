<?php
require_once '../config/database.php';
require_once 'includes/auth.php';

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("
    SELECT s.*, f.name AS form_name
    FROM submissions s
    JOIN forms f ON s.form_id = f.id
    WHERE s.id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();
$submission = $stmt->get_result()->fetch_assoc();

if (!$submission) {
    die("Submission not found");
}

$stmt = $conn->prepare("
    SELECT sv.*, f.label
    FROM submission_values sv
    JOIN fields f ON sv.field_id = f.id
    WHERE sv.submission_id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();
$values = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Submission</title>

    <style>
       body{
    font-family: "Segoe UI", sans-serif;
    background: #f8fafc;
    margin: 0;
    padding: 40px;
}

.container{
    max-width: 900px;
    margin: auto;
}

.submission-card{
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,.08);
}

.card-header{
    background: linear-gradient(
        135deg,
        #2563eb,
        #1d4ed8
    );
    color: #fff;
    padding: 30px;
}

.card-header h2{
    margin: 0;
    font-size: 28px;
}

.card-header p{
    margin-top: 8px;
    opacity: .85;
}

.card-body{
    padding: 30px;
}

.field-row{
    display: flex;
    justify-content: space-between;
    gap: 20px;
    padding: 18px 0;
    border-bottom: 1px solid #e5e7eb;
}

.field-row:last-child{
    border-bottom: none;
}

.field-label{
    font-weight: 600;
    color: #334155;
    min-width: 220px;
}

.field-value{
    flex: 1;
    color: #0f172a;
    word-break: break-word;
}

.back-btn{
    display: inline-block;
    margin-top: 25px;
    background: #2563eb;
    color: white;
    padding: 12px 18px;
    border-radius: 10px;
    text-decoration: none;
    transition: .3s;
}

.back-btn:hover{
    background: #1d4ed8;
}

@media(max-width:768px){

    .field-row{
        flex-direction: column;
        gap: 8px;
    }

    .field-label{
        min-width: auto;
    }

}
    </style>
</head>

<body>

<div class="container">

    <div class="submission-card">

        <div class="card-header">
            <h2>
                <?= htmlspecialchars($submission['form_name']) ?>
            </h2>

            <p>
                Submission #<?= $submission['id'] ?>
            </p>
        </div>

        <div class="card-body">

            <?php while($row = $values->fetch_assoc()): ?>

                <div class="field-row">

                    <div class="field-label">
                        <?= htmlspecialchars($row['label']) ?>
                    </div>

                    <div class="field-value">
                        <?= nl2br(htmlspecialchars($row['value'])) ?>
                    </div>

                </div>

            <?php endwhile; ?>

            <a href="manage-submissions.php" class="back-btn">
                ← Back to Submissions
            </a>

        </div>

    </div>

</div>
</body>
</html>
<?php

require_once '../config/database.php';

$slug = $_GET['slug'] ?? '';

$stmt = $conn->prepare("
    SELECT *
    FROM forms
    WHERE slug = ?
");

$stmt->bind_param("s", $slug);
$stmt->execute();

$form = $stmt->get_result()->fetch_assoc();

if(!$form){
    die("Form not found");
}

$fieldStmt = $conn->prepare("
    SELECT *
    FROM fields
    WHERE form_id = ?
");

$fieldStmt->bind_param("i", $form['id']);
$fieldStmt->execute();

$fields = $fieldStmt->get_result();

?>

<div class="public-form-container">

    <h2><?= htmlspecialchars($form['name']) ?></h2>

    <form method="POST" enctype="multipart/form-data">

        <?php while($field = $fields->fetch_assoc()): ?>

        <div class="form-group">

            <label>
                <?= htmlspecialchars($field['label']) ?>
            </label>

            <?php

        $name = "field_" . $field['id'];
        $placeholder = htmlspecialchars($field['placeholder']);

        switch($field['type']) {

            case 'text':
                echo "
                    <input
                        type='text'
                        name='{$name}'
                        placeholder='{$placeholder}'
                    >
                ";
                break;

            case 'email':
                echo "
                    <input
                        type='email'
                        name='{$name}'
                        placeholder='{$placeholder}'
                    >
                ";
                break;

            case 'number':
                echo "
                    <input
                        type='number'
                        name='{$name}'
                        placeholder='{$placeholder}'
                    >
                ";
                break;

            case 'textarea':
                echo "
                    <textarea
                        name='{$name}'
                        placeholder='{$placeholder}'
                    ></textarea>
                ";
                break;

            case 'dropdown':

                $options = explode("\n", $field['options']);

                echo "<select name='{$name}'>";

                foreach($options as $option) {

                    $option = trim($option);

                    echo "
                        <option value='{$option}'>
                            {$option}
                        </option>
                    ";
                }

                echo "</select>";

                break;

            case 'radio':

                $options = explode("\n", $field['options']);

                foreach($options as $option) {

                    $option = trim($option);

                    echo "
                        <label>
                            <input
                                type='radio'
                                name='{$name}'
                                value='{$option}'
                            >
                            {$option}
                        </label><br>
                    ";
                }

                break;

            case 'checkbox':

                $options = explode("\n", $field['options']);

                foreach($options as $option) {

                    $option = trim($option);

                    echo "
                        <label>
                            <input
                                type='checkbox'
                                name='{$name}[]'
                                value='{$option}'
                            >
                            {$option}
                        </label><br>
                    ";
                }

                break;

            case 'file':

                echo "
                    <input
                        type='file'
                        name='{$name}'
                    >
                ";

                break;
        }

        ?>

        </div>

        <?php endwhile; ?>

        <button type="submit">
            Submit
        </button>

    </form>
</div>
<?php
require_once __DIR__ . '/../config.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php');
    exit;
}
$product = [];
$prepare = mysqli_prepare($conn, "SELECT id, name FROM product WHERE id = ?");
if ($prepare) {
    mysqli_stmt_bind_param($prepare, 'i', $id);
    mysqli_stmt_execute($prepare);
    $result = mysqli_stmt_get_result($prepare);
    $product = mysqli_fetch_assoc($result);
    mysqli_stmt_close($prepare);
}

if (!$product) {
    header('Location: index.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    $prepare_delete = mysqli_prepare($conn, "UPDATE product SET is_deleted = 1 WHERE id = ?");
    if ($prepare_delete) {
        mysqli_stmt_bind_param($prepare_delete, 'i', $id);
        mysqli_stmt_execute($prepare_delete);
        mysqli_stmt_close($prepare_delete);
    }
    header('Location: index.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel'])) {
    header('Location: index.php');
    exit;
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Удаление продукта</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="\printing\ctyle\css_factory.css" rel="stylesheet">
</head>
<body class="container mt-5">
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">🗑️ Подтверждение удаления</h4>
            </div>
            <div class="card-body text-center">
                <p>Вы действительно хотите удалить продукт?</p>
                <p class="fw-bold text-danger">「 <?php echo htmlspecialchars($product['name']); ?> 」</p>
                <form method="POST" class="d-flex gap-2 justify-content-center">
                    <button type="submit" name="confirm" class="btn btn-danger">Да, удалить</button>
                    <button type="submit" name="cancel" class="btn btn-secondary">Отмена</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>

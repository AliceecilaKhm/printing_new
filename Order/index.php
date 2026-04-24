<?php
require_once __DIR__ . '/../config.php';
$orders = [];
$errorMessage = '';
$filterDeleted = isset($_GET['filter']) ? (int)$_GET['filter'] : 0;


$sql = "SELECT 
    orders.id,
    orders.contract_id,
    orders.Quantity,
    orders.status,
    orders.is_deleted,
    product.name AS product_name
FROM orders 
LEFT JOIN product ON orders.product_id = product.id";


if ($filterDeleted == 0) {
    $sql .= " WHERE orders.is_deleted = 0";
} elseif ($filterDeleted == 1) {
    $sql .= " WHERE orders.is_deleted = 1";
}

$order_select = mysqli_prepare($conn, $sql);
if($order_select){
    mysqli_stmt_execute($order_select);
    $result = mysqli_stmt_get_result($order_select);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
    }
        else {
            $errorMessage = mysqli_error($conn);
        }  mysqli_stmt_close($order_select);
}
else {
    $errorMessage = mysqli_error($conn);
}

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link href="\printing\ctyle\css_factory.css" rel="stylesheet">
    <title>Factory</title>
</head>
<body class="container-fluid bg-light">
<div class="container mt-4">
    <div class="mb-3">
        <a href="?filter=0" class="btn btn-success btn-sm">Активные</a>
        <a href="?filter=1" class="btn btn-warning btn-sm">Удаленные</a>
        <a href="?filter=2" class="btn btn-secondary btn-sm">Все записи</a>
        <a href="add.php" class="btn btn-primary btn-sm float-end">Добавить заказ</a>
        <a href="../index.php" class="btn btn-outline-secondary btn-sm float-end me-2">На главную</a>
<div class = "table-responsive">
    <h2> Заказы </h2>
    <table class="table">
        <thead>
        <tr>
            <th> ID</th>
            <th>Номер контракта </th>
            <th>Продукт </th>
            <th> Количество</th>
            <th>Статус </th>
            <th>Удален </th>
            <th> </th>
            <th> </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
        <tr>
        <td> <?php echo htmlspecialchars($order['id']); ?> </td>
        <td> <?php echo htmlspecialchars($order['contract_id']); ?></td>
        <td> <?php echo htmlspecialchars($order['product_name']); ?></td>

        <td> <?php echo htmlspecialchars($order['Quantity']); ?></td>
        <td> <?php echo htmlspecialchars($order['status']); ?></td>
            <td>
                <?php if ($order['is_deleted'] == 0): ?>
                    <span class="badge bg-success">Нет</span>
                <?php else: ?>
                    <span class="badge bg-danger">Да</span>
                <?php endif; ?>
            </td>
            <td><a href="edit.php?id=<?php echo (int)$order['id']; ?>" class="btn btn-sm btn-primary">Редактировать</a>
            </td><td><a href="delete.php?id=<?php echo (int)$order['id']; ?>" class="btn btn-danger btn-sm">Удалить</a> </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

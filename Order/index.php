<?php
require_once __DIR__ . '/../config.php';
$orders = [];
$errorMessage = '';
$order_select = mysqli_prepare($conn, "SELECT * FROM orders");
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
<body class = "container-fluid">
<div class = "table ">
    <h2> Заказы </h2>
    <table>
        <thead>
        <tr>
            <th> ID</th>
            <th>Номер контракта </th>
            <th>Продукт </th>
            <th> Количество</th>
            <th>Статус </th>
            <th>Удален </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
        <tr>
        <td> <?php echo htmlspecialchars($order['id']); ?> </td>
        <td> <?php echo htmlspecialchars($order['contract_id']); ?></td>
        <td> <?php echo htmlspecialchars($order['product_id']); ?></td>
        <td> <?php echo htmlspecialchars($order['Quantity']); ?></td>
        <td> <?php echo htmlspecialchars($order['status']); ?></td>
        <td> <?php echo htmlspecialchars($order['is_deleted']); ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

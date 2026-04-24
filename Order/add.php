<?php

require_once __DIR__ . '/../config.php';
$errorMessage = '';
$contract_id = (int)($_POST['contract_id'] ?? 0);
$product_id = (int)($_POST['product_id'] ?? 0);
$Quantity = (int)($_POST['Quantity'] ?? 0);
$status = (string)($_POST['status'] ?? '');
$status_order = [
    'В обработке',
    'Принят в работу',
    'Выполнен',
    'Отменен',
    'Выдан'];
$order_number = [];
$products = [];
$parce_order_number = mysqli_prepare($conn, "SELECT id from contract WHERE is_deleted = 0");
if ($parce_order_number) {
    mysqli_stmt_execute($parce_order_number);
    $result = mysqli_stmt_get_result($parce_order_number);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $order_number[] = $row;
        }
    }
    else {
            $errorMessage = mysqli_error($conn);
        }
        mysqli_stmt_close($parce_order_number);
    } else {
        $errorMessage = mysqli_error($conn);
    }


$prepare_products = mysqli_prepare($conn, "SELECT id, name FROM product WHERE is_deleted = 0");
if ($prepare_products) {
    mysqli_stmt_execute($prepare_products);
    $result = mysqli_stmt_get_result($prepare_products);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;

        }
    }
    else{
        $errorMessage = mysqli_error($conn);
    }
    mysqli_stmt_close($prepare_products);
}
else{
    $errorMessage = mysqli_error($conn);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $errorMessage === '') {
    if (empty($contract_id)) {
        $errorMessage = "Выберите контракт";
    } elseif (empty($product_id)) {
        $errorMessage = "Выберите продукт";
    } elseif ($Quantity <= 0) {
        $errorMessage = "Количество должно быть больше 0";
    } elseif (empty($status)) {
        $errorMessage = "Выберите статус";
    } else {
        $prepare_add = mysqli_prepare($conn, "Insert into orders (contract_id, product_id, Quantity, status) values (?, ?, ?, ?)");
        if ($prepare_add) {
            mysqli_stmt_bind_param($prepare_add, 'iiis', $contract_id, $product_id, $Quantity, $status);


            if (mysqli_stmt_execute($prepare_add)) {
                mysqli_stmt_close($prepare_add);
                header('Location: index.php');
                exit;
            } else {
                $errorMessage = "Ошибка добавления: " . mysqli_error($conn);
            }
            mysqli_stmt_close($prepare_add);
        }
        else {
            $errorMessage = "Ошибка подготовки запроса: " . mysqli_error($conn);
        }
    }
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
<div class = " ">

    <form method="post">
        <div class="container mt-5">
            <h2> Добавить заказ </h2>
            <?php if ($errorMessage): ?>
                <div class="alert alert-danger">
                    <strong>Ошибка!</strong> <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>
            <div>
            <label for="contract_id" class="form-label">Номер контракта </label>
            <select name="contract_id">
             <option value="">Выберите контракт</option>
                <?php foreach ($order_number as $item): ?>
                <option value="<?php echo (int)($item['id']); ?>" >
                    <?php echo htmlspecialchars($item['id']); ?>
                </option>
                <?php endforeach; ?>
            </select>
            </div>
            <div>
            <label for="product_id" class="form-label"> Продукт </label>
            <select name = 'product_id'>
                <option value = " "> Выберите продукт </option>
                <?php foreach ($products as $item): ?>
                <option value = " <?php echo (int)$item['id']; ?>">
                    <?php echo htmlspecialchars($item['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
            </div>
            <label for="Quantity" class="form-label">Количество</label>
            <input type="number" class="form-control" id="Quantity" name="Quantity" required
                   value=" "
            >
            <label for="status" class="form-label">Статус</label>
            <select type="text" class="form-control" id="status" name="status">
                <<option value="">Выберите статус</option>
                <?php foreach ($status_order as $status): ?>
                <option value="<?php echo htmlspecialchars($status); ?>">
                    <?php echo htmlspecialchars($status); ?>
                </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn" > Сохранить </button>
        </div>
    </form>
</div>
</body>
</html>



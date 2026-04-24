<?php
require_once __DIR__ .'/../config.php';
$errorMessage = '';
$status_order = [
    'В обработке',
    'Принят в работу',
    'Выполнен',
    'Отменен',
    'Выдан'];
$order_number = [];
$products = [];
$parce_order_number = mysqli_prepare($conn, "SELECT id from contract");
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


$prepare_products = mysqli_prepare($conn, "SELECT id, name FROM product");
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

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$errorMessage = '';
$contract_id ='';
$product_id='';
$Quantity ='';
$status='';
$del = 0;
if ($id < 0) {
    header('Location: index.php');
    exit;

}
$Factory_old=[];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contract_id = $_POST['contract_id'];
    $product_id = $_POST['product_id'];
    $Quantity = $_POST['Quantity'];
    $status = $_POST['status'];
    $del = $_POST['is_deleted'];
    if (empty($contract_id)) {
    $errorMessage = "Выберите контракт";
} elseif (empty($product_id)) {
    $errorMessage = "Выберите продукт";
} elseif ($Quantity <= 0) {
    $errorMessage = "Количество должно быть больше 0";
} elseif (empty($status)) {
    $errorMessage = "Выберите статус";
} else {
    $prepare_Factory = mysqli_prepare($conn, 'Update orders set contract_id=?, product_id=?, Quantity=?, status=?,  is_deleted=? where id =?');
    if ($prepare_Factory) {
        mysqli_stmt_bind_param($prepare_Factory, 'iiisii',  $contract_id, $product_id, $Quantity, $status,  $del, $id );
        if (mysqli_stmt_execute($prepare_Factory)) {
            mysqli_stmt_close($prepare_Factory);
            header("Location: index.php");
            exit;
        }
        else { $errorMessage = mysqli_error($conn);}
    }
    else {$errorMessage = mysqli_error($conn);}
}
}
else {
    $prep_factor = mysqli_prepare($conn, 'SELECT contract_id, product_id, Quantity, status,  is_deleted FROM orders WHERE id = ?');
    if ($prep_factor) {
        mysqli_stmt_bind_param($prep_factor, 'i', $id);
        mysqli_stmt_execute($prep_factor);
        $result = mysqli_stmt_get_result($prep_factor);
        if ($result) {

            $Factory_old = mysqli_fetch_assoc($result);

            if ($Factory_old) {
                $contract_id = $Factory_old['contract_id'] ?? '';
                $product_id = $Factory_old['product_id'] ?? '';
                $Quantity = $Factory_old['Quantity'] ?? '';
                $status = $Factory_old['status'] ?? '';
                $del = $Factory_old['is_deleted'] ?? 0;
            } else {
                $errorMessage = mysqli_error($conn);
            }
            mysqli_stmt_close($prep_factor);
        } else {
            $errorMessage = mysqli_error($conn);
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
    <title>Document</title>
</head>
<body>
<div><form method="post">

        <div class="container mt-5">
            <h2> Редактировать</h2>
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
                        <option value="<?php echo (int)($item['id']); ?>"
                            <?php echo ($contract_id == $item['id']) ? 'selected' : ''; ?>>>
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
                        <option value = "<?php echo (int)$item['id']; ?>"
                            <?php echo ($product_id == $item['id']) ? 'selected' : ''; ?>>>
                            <?php echo htmlspecialchars($item['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <label for="Quantity" class="form-label">Количество</label>
            <input type="number" class="form-control" id="Quantity" name="Quantity" required
                   value="<?php echo (int)($Quantity); ?>">
            >
            <label for="status" class="form-label">Статус</label>
            <select type="text" class="form-control" id="status" name="status">
                <<option value="">Выберите статус</option>
                <?php foreach ($status_order as $stat): ?>
                    <option value="<?php echo htmlspecialchars($stat); ?>"
                    <?php echo ($status == $stat) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($stat); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div>
                <label for="is_deleted" class="form-label">Удалено</label>
                <select name="is_deleted" class="form-select">
                    <option value="0" <?php echo ($del == 0) ? 'selected' : ''; ?>>Активен</option>
                    <option value="1" <?php echo ($del == 1) ? 'selected' : ''; ?>>Удален</option>
                </select>
            </div>
            <button type="submit" class="btn" > Сохранить </button>
        </div>
    </form>
</div>
</body>
</html>
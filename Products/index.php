<?php
require_once __DIR__ . '/../config.php';
$errorMessage = '';
$products = [];
$filterDeleted = isset($_GET['filter']) ? (int)$_GET['filter'] : 0;


$sql = "SELECT 
    product.id,
    product.name,
    product.ManufacturingCost,
    product.Description,
    product.is_deleted,
    factory.Name AS factory_name
FROM product 
LEFT JOIN factory ON product.Factory_Id = factory.id";


if ($filterDeleted == 0) {
    $sql .= " WHERE product.is_deleted = 0";
} elseif ($filterDeleted == 1) {
    $sql .= " WHERE product.is_deleted = 1";
}


$prepareFactory = mysqli_prepare($conn, $sql);
if ($prepareFactory) {
    if (mysqli_stmt_execute($prepareFactory)) {
       $result = mysqli_stmt_get_result($prepareFactory);
       if ($result){
           while ($row = mysqli_fetch_assoc($result)) {
               $products[] = $row;
           }
       }
       else  {$errorMessage = mysqli_error($conn);}
        mysqli_stmt_close($prepareFactory);
    }

} else {
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
<body  class="container-fluid" >
<div class="container mt-4">
    <div class="mb-4">
        <a href="?filter=0" class="btn btn-success btn-sm">Активные</a>
        <a href="?filter=1" class="btn btn-warning btn-sm">Удаленные</a>
        <a href="?filter=2" class="btn btn-secondary btn-sm">Все записи</a>
        <a href="../index.php" class="btn btn-primary btn-sm">На главную</a>
        <a href="add.php" class="btn btn-primary btn-sm float-end">Добавить продукт</a>

    </div>

<div class = "container mt-4">
    <h2> Продукция</h2>

    <div class = "table-responsive ">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th> ID</th>
                <th>Фабрика </th>
                <th> Название </th>
                <th> Стоимость</th>
                <th>Описание </th>
                <th>Удален </th>
                <th> </th>
                <th> </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $prod): ?>
                <tr>
                    <td> <?php echo htmlspecialchars($prod['id']); ?> </td>
                    <td> <?php echo htmlspecialchars($prod['factory_name']); ?></td>
                    <td> <?php echo htmlspecialchars($prod['name']); ?></td>
                    <td> <?php echo htmlspecialchars($prod['ManufacturingCost']); ?></td>
                    <td> <?php echo htmlspecialchars($prod['Description']); ?></td>
                    <td>
                    <?php if ($prod['is_deleted'] == 0): ?>
                        <span class="badge bg-success">Нет</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Да</span>
                    <?php endif; ?>
                    </td>
                    <td><a href="edit.php?id=<?php echo (int)$prod['id']; ?>" class="btn btn-sm btn-primary">Редактировать</a>
                    </td><td><a href="delete.php?id=<?php echo (int)$prod['id']; ?>" class="btn btn-danger btn-sm">Удалить</a> </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>


<?php
require_once __DIR__ . '/../config.php';

$sql = 'SELECT * FROM factory';
$factory = [];
$factory_deleted = isset($_GET['filter']) ? (int)$_GET['filter'] : 0;

if ($factory_deleted == 0) {
    $sql .= " WHERE factory.is_deleted = 0";
} elseif ($factory_deleted == 1) {
    $sql .= " WHERE factory.is_deleted = 1";
}

$prepare = mysqli_prepare($conn, $sql);

if (!$prepare) {
    die('Ошибка подготовки: ' . mysqli_error($conn));
}

if (!mysqli_stmt_execute($prepare)) {
    die('Ошибка выполнения: ' . mysqli_error($conn));
}

$result = mysqli_stmt_get_result($prepare);

while ($row = mysqli_fetch_assoc($result)) {
    $factory[] = $row;

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
<body class = "container-fluid bg-light">
<div class="container mt-4"">
    <a href="?filter=0" class="btn btn-success btn-sm">Активные</a>
    <a href="?filter=1" class="btn btn-warning btn-sm">Удаленные</a>
    <a href="?filter=2" class="btn btn-secondary btn-sm">Все записи</a>
    <a href="add.php" class="btn btn-primary btn-sm float-end">Добавить заказ</a>
    <a href="../index.php" class="btn btn-outline-secondary btn-sm float-end me-2">На главную</a>
<div>
    <h2> Цеха </h2>
    <table class="table">
        <thead>
        <tr>
            <th> ID </th>
            <th> Адрес</th>
            <th> Название цеха </th>
            <th> Имя начальника </th>
            <th> Фамилия</th>
            <th> Отчество </th>
            <th> Контактные данные </th>
            <th> Удалено </th>
            <th>  </th>
            <th>  </th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($factory as $index => $value): ?>
        <tr>
            <td> <?php echo $index+1  ?> </td>
            <td> <?php echo htmlspecialchars($value['Address'])  ?> </td>
            <td> <?php echo htmlspecialchars($value['Name'])  ?> </td>
            <td> <?php echo htmlspecialchars($value['FactoryHeadName'])  ?> </td>
            <td> <?php echo htmlspecialchars($value['FactoryHeadSName'])  ?> </td>
            <td> <?php echo htmlspecialchars($value['FactoryHeadLName'])  ?> </td>
            <td> <?php echo htmlspecialchars($value['Contacts'])  ?> </td>
            <td> <?php if ($value['is_deleted'] == 0): ?>
           <span class="badge bg-success"> нет</span>
                <?php else: ?>
                <span class="badge bg-success"> Да </span>
                <?php endif; ?>
            </td>
            <td><a href="edit.php?id=<?php echo (int)$value['id']; ?>" class="btn btn-sm btn-primary">Редактировать</a>  </td>
            <td><a href="delete.php?id=<?php echo (int)$value['id']; ?>" class="btn btn-danger btn-sm">Удалить</a>  </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

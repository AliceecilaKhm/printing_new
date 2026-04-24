<?php
require_once __DIR__ . '/../config.php';
$errorMessage = '';
$Factory = [];


$prepareFactory = mysqli_prepare($conn, 'Select id, Name from factory');
if ($prepareFactory) {
    if (mysqli_stmt_execute($prepareFactory)) {
       $result = mysqli_stmt_get_result($prepareFactory);
       if ($result){
           while ($row = mysqli_fetch_assoc($result)) {
               $Factory[] = $row;
           }
       }
       else  {$errorMessage = mysqli_error($conn);}
        mysqli_stmt_close($prepareFactory);
    }

} else {
    $errorMessage = mysqli_error($conn);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Factory_id = (int)($_POST['factory_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $ManufacturingCost = (float)($_POST['ManufacturingCost'] ?? 0);
    $Description = trim($_POST['Description'] ?? '');


    if ($name === '') {
        $errorMessage = 'Введите название продукции.';
    }

    else {
        $check = mysqli_prepare($conn, 'SELECT id FROM product WHERE Name = ? LIMIT 1');
        if ($check) {
            mysqli_stmt_bind_param($check, 's', $name);
            mysqli_stmt_execute($check);
            $result = mysqli_stmt_get_result($check);
            if (mysqli_fetch_assoc($result)) {
                $errorMessage = 'Такое наименование уже существует.';
            }

            else {
                $prepare_products = mysqli_prepare($conn, "INSERT INTO product (Factory_id, Name, ManufacturingCost, Description, is_deleted) VALUES (?, ?, ?, ?, 0)");
                if ($prepare_products) {
                    mysqli_stmt_bind_param($prepare_products, 'isds', $Factory_id, $name, $ManufacturingCost, $Description);
                    if (mysqli_stmt_execute($prepare_products)) {
                        mysqli_stmt_close($prepare_products);
                        header('Location: index.php');
                        exit;
                    } else {
                        $errorMessage = "Ошибка вставки: " . mysqli_error($conn);
                    }
                } else {
                    $errorMessage = "Ошибка подготовки: " . mysqli_error($conn);
                }
            }
            mysqli_stmt_close($check);
        } else {
            $errorMessage = "Ошибка проверки: " . mysqli_error($conn);
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
    <h2> Добавить продукт </h2>
    <?php if ($errorMessage): ?>
        <div class="alert alert-danger">
            <strong>Ошибка!</strong> <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <div class="">
            <div>
            <label for="Factory_Id" class="">Цех</label>
            <select name ='factory_id'>
                <?php foreach ($Factory as $ident): ?>
                <option value="<?php echo (int)$ident['id']; ?>">
                <?php  echo htmlspecialchars($ident['Name']); ?>
                </option>
                <?php endforeach; ?>
            </select></div>
            <label for="name" class="form-label">Название продукта</label>
            <input type="text" class="form-control" id="name" name="name"
                   maxlength="120" required
                   value=" ">
            <label for="ManufacturingCost" class="form-label">Стоимость</label>
            <input type="number" class="form-control" id="ManufacturingCost" name="ManufacturingCost"
                    required
                   value=" ">
            <label for="Description" class="form-label">Описание</label>
            <input type="text" class="form-control" id="Description" name="Description"
                   maxlength="500" required
                   value=" ">
            <button type="submit" class="btn" > Сохранить </button>
        </div>
    </form>
</div>
</body>
</html>
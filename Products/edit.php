<?php
require_once __DIR__ .'/../config.php';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id < 0) {
    header('Location: index.php');
    exit;
}
$Factory_id = '';
$name = '';
$ManufacturingCost = '';
$Description = '';
$del = '0';

$factories = [];
$prep_factories = mysqli_prepare($conn, 'SELECT id, Name FROM factory WHERE is_deleted = 0');
if ($prep_factories) {
    mysqli_stmt_execute($prep_factories);
    $result_factories = mysqli_stmt_get_result($prep_factories);
    while ($row = mysqli_fetch_assoc($result_factories)) {
        $factories[] = $row;
    }
    mysqli_stmt_close($prep_factories);
}
$Product_old=[];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Factory_id = $_POST['Factory_Id'];
    $name = $_POST['name'];
    $ManufacturingCost = $_POST['ManufacturingCost'];
    $Description = $_POST['Description'];
    $del = $_POST['is_deleted'];
    $prepare_Factory = mysqli_prepare($conn, 'Update product set Factory_Id=?, name=?, ManufacturingCost=?, Description=?,  is_deleted=? where id =?');
    if ($prepare_Factory) {
        mysqli_stmt_bind_param($prepare_Factory, 'isdsii',  $Factory_id, $name, $ManufacturingCost, $Description,  $del, $id );
        if (mysqli_stmt_execute($prepare_Factory)) {
            mysqli_stmt_close($prepare_Factory);
            header("Location: index.php");
            exit;
        }
        else { $errorMessage = mysqli_error($conn);}
    }
    else {$errorMessage = mysqli_error($conn);}
}
else {
    $prep_products = mysqli_prepare($conn, 'SELECT Factory_Id, name, ManufacturingCost, Description,  is_deleted FROM product WHERE id = ?');
    if ($prep_products) {
        mysqli_stmt_bind_param($prep_products, 'i', $id);
        mysqli_stmt_execute($prep_products);
        $result = mysqli_stmt_get_result($prep_products);
        if ($result) {

            $Product_old = mysqli_fetch_assoc($result);

            if ($Product_old) {
                $Factory_id = $Product_old['Factory_Id'] ?? '';
                $name = $Product_old['name'] ?? '';
                $ManufacturingCost = $Product_old['ManufacturingCost'] ?? '';
                $Description = $Product_old['Description'] ?? '';
                $del = $Product_old['is_deleted'] ?? 0;
            } else {
                $errorMessage = mysqli_error($conn);
            }
            mysqli_stmt_close($prep_products);
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
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link href="\printing\ctyle\css_factory.css" rel="stylesheet">
    <title>Document</title>
</head>
<body class ="m">
<div class="container mt-5">
    <form method="POST">
        <div class="">
            <h2> Редактировать </h2>
            <div>
                <label for="Factory_Id" class="">Цех</label>
                <select name="Factory_Id" required>
                    <option value="">-- Выберите цех --</option>
                    <?php foreach ($factories as $factory): ?>
                        <option value="<?php echo $factory['id']; ?>"
                                <?php echo ($Factory_id == $factory['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($factory['Name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div>
            <label for="name" class="form-label">Название продукта</label>
            <input type="text" class="form-control" id="name" name="name"
                   maxlength="120" required
                   value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?> ">
                </div>>
            <label for="ManufacturingCost" class="form-label">Стоимость</label>
            <input type="number" class="form-control" id="ManufacturingCost" name="ManufacturingCost"
                   required
                   value="<?php echo htmlspecialchars($ManufacturingCost, ENT_QUOTES, 'UTF-8'); ?>">
            <label for="Description" class="form-label">Описание</label>
            <input type="text" class="form-control" id="Description" name="Description"
                   maxlength="500" required
                   value="<?php echo htmlspecialchars($Description, ENT_QUOTES, 'UTF-8'); ?>">
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

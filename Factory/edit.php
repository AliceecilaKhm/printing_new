<?php
require_once __DIR__ .'/../config.php';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$errorMessage = '';
$Adress ='';
$name ='';
$contact='';
$nameH ='';
$nameS='';
$nameL ='';
$del = '';
if ($id < 0) {
    header('Location: index.php');
    exit;

}
$Factory_old=[];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Adress = $_POST['Address'];
     $name = $_POST['Name'];
     $contact = $_POST['Contacts'];
     $nameH = $_POST['FactoryHeadName'];
     $nameS = $_POST['FactoryHeadSName'];
     $nameL = $_POST['FactoryHeadLName'];
     $del = $_POST['is_deleted'];
     $prepare_Factory = mysqli_prepare($conn, 'Update factory set Address=?, Name=?, Contacts=?, FactoryHeadName=?, FactoryHeadSName=?, FactoryHeadLName=?, is_deleted=? where id =?');
     if ($prepare_Factory) {
         mysqli_stmt_bind_param($prepare_Factory, 'ssssssii',  $Adress, $name, $contact, $nameH, $nameS, $nameL, $del, $id );
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
    $prep_factor = mysqli_prepare($conn, 'SELECT Address, Name, Contacts, FactoryHeadName, FactoryHeadSName, FactoryHeadLName, is_deleted FROM factory WHERE id = ?');
    if ($prep_factor) {
        mysqli_stmt_bind_param($prep_factor, 'i', $id);
        mysqli_stmt_execute($prep_factor);
        $result = mysqli_stmt_get_result($prep_factor);
        if ($result) {

            $Factory_old = mysqli_fetch_assoc($result);

            if ($Factory_old) {
                $Adress = $Factory_old['Address'] ?? '';
                $name = $Factory_old['Name'] ?? '';
                $contact = $Factory_old['Contacts'] ?? '';
                $nameH = $Factory_old['FactoryHeadName'] ?? '';
                $nameS = $Factory_old['FactoryHeadSName'] ?? '';
                $nameL = $Factory_old['FactoryHeadLName'] ?? '';
                $del = $Factory_old['is_deleted'] ?? '';
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
<div class="container mt-5">
<form method="post">
    <div class="">
    <h2> Редактировать </h2>
        <label for="Address" class="">Адрес</label>
        <input
            type="text"
            class="form-control"
            id="Address"
            name="Address"
            maxlength="120"
            required
            value="<?php echo htmlspecialchars($Adress, ENT_QUOTES, 'UTF-8'); ?> "
        >
    </div>
    <div>
        <label for="Name" class="form-label">Название цеха</label>
        <input type="text" class="form-control" id="Name" name="Name"
               maxlength="120" required
               value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?> "
        >
    </div>
    <div>
        <label for="Contacts" class="form-label">Контакты</label>
        <input type="text" class="form-control" id="Contacts" name="Contacts"
               maxlength="11" required
               value="<?php echo htmlspecialchars($contact, ENT_QUOTES, 'UTF-8'); ?> "
        >
    </div>
    <div>
        <label for="FactoryHeadName" class="form-label">Имя</label>
        <input type="text" class="form-control" id="FactoryHeadName" name="FactoryHeadName"
               maxlength="40" required
               value="<?php echo htmlspecialchars($nameH, ENT_QUOTES, 'UTF-8'); ?>"
        >
    </div>
    <div>
        <label for="FactoryHeadSName" class="form-label">Фамилия</label>
        <input type="text" class="form-control" id="FactoryHeadSName" name="FactoryHeadSName"
               maxlength="60" required
               value="<?php echo htmlspecialchars($nameS, ENT_QUOTES, 'UTF-8'); ?> "
        >
    </div>
    <div>
        <label for="FactoryHeadLName" class="form-label">Отчество</label>
        <input type="text" class="form-control" id="FactoryHeadLName" name="FactoryHeadLName"
               maxlength="60" required
               value="<?php echo htmlspecialchars($nameL, ENT_QUOTES, 'UTF-8'); ?> "
        >
    </div>
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
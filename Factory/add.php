<?php
require_once __DIR__ .'/../config.php';

$errorMessage = '';
 if ($_SERVER['REQUEST_METHOD'] === 'POST' and $errorMessage == ''){
     $Adress = $_POST['Address'];
     $Name = $_POST['Name'];
     $Contact = $_POST['Contacts'];
     $NameHead = $_POST['FactoryHeadName'];
     $NameSHead = $_POST['FactoryHeadSName'];
     $NameLHead = $_POST['FactoryHeadLName'];
     $prepare_Factory = mysqli_prepare($conn, 'INSERT INTO factory  (Address, Name, Contacts, FactoryHeadName, FactoryHeadSName, FactoryHeadLName ) values (?, ?, ?, ?, ?, ?)');
     if ($prepare_Factory) {
         mysqli_stmt_bind_param($prepare_Factory, 'ssssss', $Adress, $Name, $Contact, $NameHead, $NameSHead, $NameLHead );
         if (mysqli_stmt_execute($prepare_Factory)) {
             mysqli_stmt_close($prepare_Factory);
             header("Location: index.php");
             exit;
         }
         else { $errorMessage = mysqli_error($conn);}
     }
     else {$errorMessage = mysqli_error($conn);}
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
    <h2> Добавить новый цех </h2>
    <form method="post">
        <div class="">
            <label for="Address" class="">Адрес</label>
            <input
                type="text"
                class="form-control"
                id="Address"
                name="Address"
                maxlength="120"
                required
                value=" "
            >
            <label for="Name" class="form-label">Название цеха</label>
            <input type="text" class="form-control" id="Name" name="Name"
                maxlength="120" required
                value=" "
            >
            <label for="Contacts" class="form-label">Контакты</label>
            <input type="text" class="form-control" id="Contacts" name="Contacts"
                   maxlength="11" required
                   value=" "
            >
            <label for="FactoryHeadName" class="form-label">Имя</label>
            <input type="text" class="form-control" id="FactoryHeadName" name="FactoryHeadName"
                   maxlength="40" required
                   value=" "
            >
            <label for="FactoryHeadSName" class="form-label">Фамилия</label>
            <input type="text" class="form-control" id="FactoryHeadSName" name="FactoryHeadSName"
                   maxlength="60" required
                   value=" "
            >
            <label for="FactoryHeadLName" class="form-label">Отчество</label>
            <input type="text" class="form-control" id="FactoryHeadLName" name="FactoryHeadLName"
                   maxlength="60" required
                   value=" "
            >
            <button type="submit" class="btn" > Сохранить </button>
        </div>
    </form>
</div>
</body>
</html>


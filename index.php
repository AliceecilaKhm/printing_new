<?php
require_once __DIR__ . '/config.php';

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
    <link href="ctyle/css.css" rel="stylesheet">
    <title>Document</title>
</head>
<body class="container-fluid">
<div class="hedder">
    <h1> Типография </h1>
</div>
<div class="container center d-flex justify-content-left">
    <div class="row ">
        <div class="col-6 d-flex justify-content-center align-items-center">
            <a class="btn shadow as border border-4 border-dark custom-hover" href="Factory/index.php">Цех</a>
        </div>
        <div class="col-6 d-flex justify-content-center align-items-center">
            <a class="btn shadow as border border-4 border-dark custom-hover" href="Products/index.php">Продукция</a>
        </div>
        <div class="col-6 d-flex justify-content-center align-items-center">
            <a class="btn shadow as border border-4 border-dark custom-hover" href="Order/index.php">Заказ</a>
        </div>
        <div class="col-6 d-flex justify-content-center align-items-center">
            <a class="btn shadow as border border-4 border-dark custom-hover" href="report.php" >Отчет</a>
        </div>

</div>
</div>
<div class="footer">
    <footer></footer>
</div>
</body>
</html>
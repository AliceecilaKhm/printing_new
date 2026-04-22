<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link href="" rel="stylesheet">
    <title>Factory</title>
</head>
<body class = "container-fluid">
<div class = " ">
    <h2> Добавить продукт </h2>
    <form method="post">
        <div class="">
            <label for="Factory_Id" class="">Цех</label>
            <input
                type="text"
                class="form-control"
                id="Factory_Id"
                name="Factory_Id"
                maxlength="120"
                required
                value=" ">
            <label for="name" class="form-label">Название продукта</label>
            <input type="text" class="form-control" id="name" name="name"
                   maxlength="120" required
                   value=" ">
            <label for="ManufacturingCost" class="form-label">Стоимость</label>
            <input type="text" class="form-control" id="ManufacturingCost" name="ManufacturingCost"
                   maxlength="120" required
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



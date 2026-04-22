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
                   maxlength="120" required
                   value=" "
            >
            <label for="FactoryHeadName" class="form-label">Имя</label>
            <input type="text" class="form-control" id="FactoryHeadName" name="FactoryHeadName"
                   maxlength="120" required
                   value=" "
            >
            <label for="FactoryHeadSName" class="form-label">Фамилия</label>
            <input type="text" class="form-control" id="FactoryHeadSName" name="FactoryHeadSName"
                   maxlength="120" required
                   value=" "
            >
            <label for="FactoryHeadLName" class="form-label">Отчество</label>
            <input type="text" class="form-control" id="FactoryHeadLName" name="FactoryHeadLName"
                   maxlength="120" required
                   value=" "
            >
            <button type="submit" class="btn" > Сохранить </button>
        </div>
    </form>
</div>
</body>
</html>


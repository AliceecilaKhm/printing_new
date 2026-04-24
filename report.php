<?php
require_once __DIR__ . '/config.php';

$errorMessage = '';
$selectedYear = date('Y');
$years = [];
$reportData = [];
$totalAllQuantity = 0;
$totalAllCost = 0;


$prepare_years = mysqli_prepare($conn, "SELECT DISTINCT YEAR(date_time) as year FROM contract where is_deleted = 0 ORDER BY year DESC");
if ($prepare_years) {
    mysqli_stmt_execute($prepare_years);
    $result_years = mysqli_stmt_get_result($prepare_years);
    while ($row = mysqli_fetch_assoc($result_years)) {
        $years[] = $row['year'];
    }
    mysqli_stmt_close($prepare_years);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['year'])) {
    $selectedYear = (int)$_POST['year'];
} elseif (isset($_GET['year'])) {
    $selectedYear = (int)$_GET['year'];
}

if ($selectedYear) {
    $prepare_report = mysqli_prepare($conn, "
        SELECT 
            f.Name as factory_name,
            p.name as product_name,
            c.id as contract_id,
            o.Quantity,
            p.ManufacturingCost as unit_price,
            (o.Quantity * p.ManufacturingCost) as total_cost
        FROM orders o
        LEFT JOIN contract c ON o.contract_id = c.id
        LEFT JOIN product p ON o.product_id = p.id
        LEFT JOIN factory f ON p.Factory_Id = f.id
        WHERE YEAR(c.date_time) = ? AND o.is_deleted = 0 AND p.is_deleted = 0
        ORDER BY f.Name, p.name
    ");

    if ($prepare_report) {
        mysqli_stmt_bind_param($prepare_report, 'i', $selectedYear);
        mysqli_stmt_execute($prepare_report);
        $result_report = mysqli_stmt_get_result($prepare_report);

        while ($row = mysqli_fetch_assoc($result_report)) {
            $reportData[] = $row;
        }
        mysqli_stmt_close($prepare_report);
    }
}

$groupedByFactory = [];
foreach ($reportData as $item) {
    $factoryName = $item['factory_name'] ?? 'Без цеха';
    if (!isset($groupedByFactory[$factoryName])) {
        $groupedByFactory[$factoryName] = [
            'items' => [],
            'total_quantity' => 0,
            'total_cost' => 0
        ];
    }
    $groupedByFactory[$factoryName]['items'][] = $item;
    $groupedByFactory[$factoryName]['total_quantity'] += $item['Quantity'];
    $groupedByFactory[$factoryName]['total_cost'] += $item['total_cost'];
    $totalAllQuantity += $item['Quantity'];
    $totalAllCost += $item['total_cost'];
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отчет за <?php echo $selectedYear; ?> год</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container-fluid py-4">
<div class="container">
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" class="row g-3 align-items-end">
                <div class="col-auto">
                    <label class="form-label">Выберите год:</label>
                    <select name="year" class="form-select">
                        <?php foreach ($years as $year): ?>
                            <option value="<?php echo $year; ?>" <?php echo $selectedYear == $year ? 'selected' : ''; ?>>
                                <?php echo $year; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Показать отчет</button>
                </div>
                <div class="col-auto">
                   <a href="index.php" class="btn btn-primary">На главную </a>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center mb-4">
        <h3>Отчет о выполнении заказов на изготовление полиграфической продукции</h3>
        <h4>за <?php echo $selectedYear; ?> год</h4>
    </div>

    <?php if (empty($groupedByFactory)): ?>
        <div class="alert alert-warning text-center">
            Нет данных за <?php echo $selectedYear; ?> год
        </div>
    <?php else: ?>
        <?php foreach ($groupedByFactory as $factoryName => $factoryData): ?>
            <div class="card mt-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">🏭 <?php echo htmlspecialchars($factoryName); ?></h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-dark">
                        <tr>
                            <th>Название продукции</th>
                            <th>Номер договора</th>
                            <th class="text-end">Количество, шт.</th>
                            <th class="text-end">Стоимость ед., руб.</th>
                            <th class="text-end">Общая стоимость, руб.</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($factoryData['items'] as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product_name'] ?? 'Не указано'); ?></td>
                                <td><?php echo htmlspecialchars($item['contract_id']); ?></td>
                                <td class="text-end"><?php echo number_format($item['Quantity'], 0, '.', ' '); ?></td>
                                <td class="text-end"><?php echo number_format($item['unit_price'], 2, '.', ' '); ?></td>
                                <td class="text-end"><?php echo number_format($item['total_cost'], 2, '.', ' '); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="5" >
                                <strong> Цех: <?php echo htmlspecialchars($factoryName); ?></strong>
                            </td>
                        </tr>
                        <tr class="">
                            <th colspan="2" class="text-end">Итого по цеху:</th>
                            <th class="text-end"><?php echo number_format($factoryData['total_quantity'], 0, '.', ' '); ?></th>
                            <th></th>
                            <th class="text-end"><?php echo number_format($factoryData['total_cost'], 2, '.', ' '); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">🏭 <?php echo htmlspecialchars($factoryName); ?></h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>


        <div class="card mt-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"> Общая статистика по всем цехам</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tbody>
                    <tr class="table-active fw-bold">
                        <td style="width: 50%;">Общее количество продукции (шт.)</td>
                        <td class="text-end"><?php echo number_format($totalAllQuantity, 0, '.', ' '); ?></td>
                    </tr>
                    <tr class="table-active fw-bold">
                        <td>Общая стоимость всей продукции (руб.)</td>
                        <td class="text-end"><?php echo number_format($totalAllCost, 2, '.', ' '); ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

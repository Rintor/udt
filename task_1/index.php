<?php

$data = file_get_contents(__DIR__ . '/mock_data.json');
$items = json_decode($data, true);

$users = isset($items['users']) ? $items['users'] : [];
$deals = isset($items['deals']) ? $items['deals'] : [];

$deals_filtered = array_filter($deals, function ($val) {
    return in_array($val['STATUS'], [
        'WON', 'LOSE'
    ]);
});

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task 1</title>
    <style>
        .table {border: 1px solid #000}
        .table td {border: 1px solid #000; padding: 0.5rem}
    </style>
</head>
<body>
    <h3>Пользователи</h3>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>EMAIL</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['ID'] ?></td>
                <td><?php echo $user['NAME'] ?></td>
                <td><?php echo $user['EMAIL'] ?></td>
            </tr>
        <?php endforeach ?>
    </table>

    <h3>Сделки</h3>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>TITLE</th>
            <th>STATUS</th>
            <th>AMOUNT</th>
        </tr>
        <?php foreach ($deals_filtered as $deal_filtered): ?>
            <tr>
                <td><?php echo $deal_filtered['ID'] ?></td>
                <td><?php echo $deal_filtered['TITLE'] ?></td>
                <td><?php echo $deal_filtered['STATUS'] ?></td>
                <td><?php echo $deal_filtered['AMOUNT'] ?></td>
            </tr>
        <?php endforeach ?>
    </table>
</body>
</html>
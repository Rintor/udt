<?php

$data = file_get_contents(__DIR__ . '/mock_deals.json');
$items = json_decode($data, true);

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task 4</title>
    <style>
        .table {border: 1px solid #000}
        .table td {border: 1px solid #000; padding: 0.5rem}
        .hidden {display: none}
    </style>
</head>
<body>
    <h2>Список сделок</h2>

    <div style="margin-bottom: 1rem;">
        <label for="statusFilter">Фильтр по статусу:</label>
        <select id="statusFilter">
            <option value="">Все</option>
            <option value="WON">WON</option>
            <option value="LOSE">LOSE</option>
        </select>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Статус</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            <?php if (! empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr data-status="<?php echo htmlspecialchars($item['status']) ?>">
                        <td><?php echo htmlspecialchars($item['id']) ?></td>
                        <td><?php echo htmlspecialchars($item['title']) ?></td>
                        <td><?php echo htmlspecialchars($item['status']) ?></td>
                        <td><?php echo htmlspecialchars($item['amount']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Нет данных</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        const filter = document.querySelector('#statusFilter');
        const rows = document.querySelectorAll('.table tbody tr');

        filter.addEventListener('change', () => {
            const value = filter.value;

            rows.forEach(row => {
                const status = row.dataset.status;

                if (! value || status === value) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        });
    </script>

</body>
</html>
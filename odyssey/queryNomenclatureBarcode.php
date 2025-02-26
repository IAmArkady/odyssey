<?php

require_once __DIR__ . '/PostgresDB.php';
$result = \DB\PostgresDB::query(
    'SELECT n.id, n.name, b.code FROM barcode AS b 
JOIN nomenclature AS n ON b.nomenclature_id = n.id ORDER BY n.name, b.code');

print("Сортировка по имени и штрихкоду:\n");
foreach ($result as $index => $line)
    print(sprintf("%d) [%d] %s: %s\n", $index + 1, $line['id'], $line['name'], $line['code']));
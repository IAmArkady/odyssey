<?php

require_once __DIR__ . '/PostgresDB.php';
$id = 10;
$result = \DB\PostgresDB::query(
    'SELECT n.id, n.name, b.code FROM barcode AS b 
        JOIN nomenclature AS n ON b.nomenclature_id = n.id  
        WHERE b.nomenclature_id = :id', ['id' => $id]);
print("Поиск id:\n");
foreach ($result as $index => $line)
    print(sprintf("%d) [%d] %s: %s\n", $index + 1, $line['id'], $line['name'], $line['code']));

$name = 'Молоко';
print("\nПоиск по name:\n");
$result = \DB\PostgresDB::query(
    "SELECT n.id, n.name, b.code FROM barcode AS b 
        JOIN nomenclature AS n ON b.nomenclature_id = n.id  
        WHERE n.name = :name", ['name' => $name]);
foreach ($result as $index => $line)
    print(sprintf("%d) [%d] %s: %s\n", $index + 1, $line['id'], $line['name'], $line['code']));
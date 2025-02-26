<?php

require_once __DIR__ . '/PostgresDB.php';
$result = \DB\PostgresDB::query(
    "SELECT n.name, STRING_AGG(b.code, '\t') AS code FROM barcode AS b 
JOIN nomenclature AS n ON b.nomenclature_id = n.id GROUP BY n.name");

$outFile = fopen('array.txt', 'w');
if (!$outFile)
    print('Error create file for write data');

foreach ($result as $line) {
    $text = sprintf("\"%s\"\t%s\n", $line['name'], $line['code']);
    print($text);
    if ($outFile)
        fwrite($outFile, $text);
}
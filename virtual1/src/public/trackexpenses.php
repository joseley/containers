<?php

include __DIR__ . "/../tools/prettyoutput.php";

$scanDir = __DIR__ . "/../sources";

$sources = array_filter(scandir($scanDir), fn($dirName) => ($dirName!="." && $dirName!=".." && !is_dir("$scanDir/$dirName")));
$sources = array_map(fn($element) => "$scanDir/$element", $sources);

$transactions = [];
$headers = [];

foreach($sources as $sourceFile) {
    if (!file_exists($sourceFile)) continue;

    $file = fopen($sourceFile, 'r');

    $headers = fgetcsv($file);
    $body = [];
    while(($line = fgetcsv($file)) !== false) {
        $body[] = $line;
    }

    $transactions = array_merge($transactions, $body);

    echo "<h2>" . date("F", strtotime(end($body)[0])) . "</h2>";
    echo buildHTMLTable($headers, $body, 'transactions');
}

$totalIncome = 0;
$totalExpense = 0;
$transactions = array_map(function($item) use (&$totalIncome, &$totalExpense) {
    $item[0] = date("M j, Y", strtotime($item[0]));
    if ($item[2]>0) {
        $totalIncome += $item[2];
    } else {
        $totalExpense -= $item[2];
    }

    return $item;
}, $transactions);

$transactions[] = [null, "Income:", $totalIncome];
$transactions[] = [null, "Expense:", $totalExpense];
$transactions[] = [null, "Net Total:", ($totalIncome-$totalExpense)];

echo buildHTMLTable($headers, $transactions, 'transactions tracking');

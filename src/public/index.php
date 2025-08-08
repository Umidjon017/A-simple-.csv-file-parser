<?php

declare(strict_types=1);

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;

define('APP_PATH', $root . 'app' . DIRECTORY_SEPARATOR);
define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);

require_once APP_PATH . 'helper.php';
require_once APP_PATH . 'transaction.php';

$app = new Transaction();

$files = $app->getTransactionFiles(FILES_PATH);

$transactions = [];

foreach ($files as $file) {
    $transactions = $app->getTransactions($file, function (array $transaction) use($app) {
        return $app->extractTransactions($transaction);
    });
}

$totals = $app->calculateAmount($transactions);

require_once VIEWS_PATH . 'transactions.php';








//require_once '../lesson_transaction.php';
//
//$transaction = (new Lesson_Transaction(100, 'Transaction 1'))
//    ->addTax(2.5)
//    ->applyDiscount(10)
//    ->getAmount();
//
//var_dump($transaction);
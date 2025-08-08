<?php

declare(strict_types=1);

class Transaction
{
    public function __construct(
        private array $files = [],
        private array $transactions = []
    ) {}

    public function getTransactionFiles(string $dirPath): array
    {
        foreach (scandir($dirPath) as $file) {
            if (is_dir($file)) {
                continue;
            }

            $this->files[] = $dirPath . $file;
        }

        return $this->files;
    }

    public function getTransactions(string $fileName, ?callable $transactionHandler = null): array
    {
        if (! file_exists($fileName)) {
            trigger_error('File not found: ' . $fileName, E_USER_ERROR);
        }

        $file = fopen($fileName, "r");

        fgetcsv($file);

        while (($transaction = fgetcsv($file)) !== false) {
            if ($transactionHandler !== null) {
                $transaction = $transactionHandler($transaction);
            }

            $this->transactions[] = $transaction;
        }

        return $this->transactions;
    }

    public function extractTransactions(array $transactions): array
    {
        [$date, $checkNumber, $description, $amount] = $transactions;

        $amount = (float) str_replace(['$', ','], '', $amount);

        return [
            'date' => $date,
            'checkNumber' => $checkNumber,
            'description' => $description,
            'amount' => $amount
        ];
    }

    public function calculateAmount(array $transactions): array
    {
        $totals = ['income' => 0.0, 'expense' => 0.0, 'netTotal' => 0.0];

        foreach ($transactions as $transaction) {
            $totals['netTotal'] += $transaction['amount'];

            if ($transaction['amount'] >= 0) {
                $totals['income'] += $transaction['amount'];
            } else {
                $totals['expense'] += $transaction['amount'];
            }
        }

        return $totals;
    }

    public function getFilesProperty(): array
    {
        return $this->files;
    }

    public function getTransactionsProperty(): array
    {
        return $this->transactions;
    }
}
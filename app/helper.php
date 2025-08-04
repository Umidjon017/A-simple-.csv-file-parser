<?php

declare(strict_types=1);

function formatDollarAmount(float $amount): string
{
    $isNegative = $amount < 0;

    return ($isNegative ? '-' : '') . '$' . number_format(abs($amount), 2);
}

function formatDate($date): string
{
    return date('M j, Y', strtotime($date));
}

function prettyPrint(array $data): void
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

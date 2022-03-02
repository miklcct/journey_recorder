<?php
declare(strict_types=1);

namespace Miklcct\JourneyRecorder;

use InvalidArgumentException;
use mysqli;
use mysqli_stmt;
use function abs;
use function intdiv;
use function is_int;
use function Safe\file_get_contents;
use function Safe\json_decode;
use function sprintf;

function format_currency(string $currency_code, int|float $amount, bool $omit_currency = false) : string {
    $digits = get_currency_digits($currency_code);
    if ($digits === null) {
        return "$currency_code $amount";
    }
    if (!is_int($amount)) {
        throw new InvalidArgumentException('Amount must be an integer for currencies with fixed digits.');
    }
    if ($digits === 0) {
        return "$currency_code $amount";
    }
    $factor = 10 ** $digits;
    return ($omit_currency ? '' : "$currency_code ") . sprintf("%d.%0{$digits}d", intdiv($amount, $factor), abs($amount) % $factor);
}

function get_currency_digits(string $currency_code) : ?int {
    return json_decode(
            file_get_contents(__DIR__ . '/../resource/currencies.json')
            , true
        )[$currency_code]['digits'] ?? null;
}

function query_database(mysqli $connection, string $query, ?string $types = null, mixed ...$arguments) : mysqli_stmt {
    $query_statement = $connection->prepare($query);
    assert($query_statement instanceof mysqli_stmt);
    if ($types !== null) {
        $query_statement->bind_param($types, ...$arguments);
    }
    $query_statement->execute();
    return $query_statement;
}

function nullif(mixed $value, mixed $test, bool $strict = true) : mixed {
    /** @noinspection TypeUnsafeComparisonInspection */
    return ($strict ? $value === $test : $value == $test) ? null : $value;
}
<?php
declare(strict_types=1);

namespace Miklcct\JourneyRecorder;

use mysqli;
use mysqli_stmt;
use function abs;
use function intdiv;
use function Safe\file_get_contents;
use function Safe\json_decode;
use function sprintf;

const DEFAULT_CURRENCY_DIGITS = 10;

function format_currency(string $currency_code, int $amount, bool $omit_currency = false) : string {
    $digits = get_currency_digits($currency_code);
    $currency_prefix = $omit_currency ? '' : "$currency_code ";
    if ($digits === 0) {
        return $currency_prefix . $amount;
    }
    $factor = 10 ** $digits;
    return $currency_prefix . sprintf("%d.%0{$digits}d", intdiv($amount, $factor), abs($amount) % $factor);
}

function get_currency_digits(string $currency_code) : int {
    return json_decode(
            file_get_contents(__DIR__ . '/../resource/currencies.json')
            , true
        )[$currency_code]['digits'] ?? DEFAULT_CURRENCY_DIGITS;
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
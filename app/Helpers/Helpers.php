<?php

function logPS($message)
{
    echo now() . ' - ' . $message . PHP_EOL;
}

function roundValue($value, $decimals = 2, $currency = false)
{
    $append = $currency ? ' ' . config('settings.currency.short_name') : '';
    return number_format($value, $decimals, ".", "") . $append;
}

function ___($key, $fallback_value)
{
    if (Lang::has($key, app()->getLocale(), false)) {
        return __($key);
    }

    return $fallback_value;
}

function setConnection($dbInfo, $conn = 'mysql')
{
    if (count($dbInfo) > 0) {
        foreach ($dbInfo as $key => $value) {
            \Config::set('database.connections.' . $conn . '.' . $key, $value);
        }
    }

    \DB::purge($conn);
    \DB::connection($conn)->reconnect();
}

function restoreConnection()
{
    setConnection([
        'host' => env('DB_HOST'),
        'database' => env('DB_DATABASE'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
    ]);
}

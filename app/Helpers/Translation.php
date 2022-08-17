<?php
use Illuminate\Support\Facades\Auth;

function ___($key, $fallback_value = '')
{
    if (Lang::has($key, app()->getLocale(), false)) {
        return __($key);
    }

    return $fallback_value;
}
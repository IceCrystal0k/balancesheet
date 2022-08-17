<?php
namespace App\Helpers;

class DateUtils
{
    /**
     * transform a date from user settings format to mysql format
     * @param {string} $date date to transform
     * @param {string} $dateFormat user date format
     * @param {object} $option additional options { startDay, endDay }
     */
    public static function userToMysqlDate($date, $dateFormat, $options = null)
    {
        $carbonDate = \Carbon\Carbon::createFromFormat($dateFormat, $date);
        if ($options) {
            $carbonDate = self::applyOptions($carbonDate, $options);
        }
        return $carbonDate->format(config('settings.mysql_date_format'));
    }

    /**
     * transform a date from mysql format to user settings format
     * @param {string} $date date to transform
     * @param {object} $dateFormat user date format
     * @param {object} $option additional options { startDay, endDay }
     */
    public static function mysqlToUserDate($date, $dateFormat, $options = null)
    {
        $carbonDate = \Carbon\Carbon::parse($date);
        if ($options) {
            $carbonDate = self::applyOptions($carbonDate, $options);
        }
        return $carbonDate->format($dateFormat);
    }

    private static function applyOptions($carbonDate, $options)
    {
        if (isset($options['startDay'])) {
            $carbonDate = $carbonDate->startOfDay();
        } else if (isset($options['endDay'])) {
            $carbonDate = $carbonDate->endOfDay();
        }
        return $carbonDate;
    }
}

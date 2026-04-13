<?php

use Qadir\PashtoCalendar\PashtoCalendar;
use Qadir\PashtoCalendar\PashtoDate;
use Qadir\PashtoCalendar\Support\Months;
use Qadir\PashtoCalendar\Support\Holidays;

if (!function_exists('pashto_date')) {
    /**
     * Convert any date to PashtoDate
     *
     * Usage:
     * pashto_date()                // today
     * pashto_date('2024-03-20')    // from string
     * pashto_date(now())           // from Carbon
     */
    function pashto_date($date = null): PashtoDate
    {
        if ($date === null) {
            return PashtoCalendar::now();
        }

        return PashtoCalendar::parse($date);
    }
}

if (!function_exists('to_shamsi')) {
    /**
     * Convert Gregorian date to Shamsi formatted string
     *
     * Usage:
     * to_shamsi('2024-03-20')         // '1403/01/01'
     * to_shamsi('2024-03-20', 'Y/m/d')
     */
    function to_shamsi($date, string $format = 'Y/m/d'): string
    {
        return PashtoCalendar::parse($date)->format($format);
    }
}

if (!function_exists('to_shamsi_pashto')) {
    /**
     * Convert Gregorian date to Pashto readable string
     *
     * Usage:
     * to_shamsi_pashto('2024-03-20')  // '۱ وری ۱۴۰۳'
     */
    function to_shamsi_pashto($date): string
    {
        $pashtoDate = PashtoCalendar::parse($date);

        $day   = PashtoDate::toPashtoNumber($pashtoDate->day);
        $month = $pashtoDate->monthName();
        $year  = PashtoDate::toPashtoNumber($pashtoDate->year);

        return "$day $month $year";
    }
}

if (!function_exists('to_gregorian')) {
    /**
     * Convert Shamsi date to Gregorian formatted string
     *
     * Usage:
     * to_gregorian(1403, 1, 1)         // '2024-03-20'
     * to_gregorian(1403, 1, 1, 'Y/m/d')
     */
    function to_gregorian(int $year, int $month, int $day, string $format = 'Y-m-d'): string
    {
        [$gy, $gm, $gd] = \Qadir\PashtoCalendar\Support\JalaliConverter::toGregorian(
            $year, $month, $day
        );

        return \Carbon\Carbon::create($gy, $gm, $gd)->format($format);
    }
}

if (!function_exists('pashto_now')) {
    /**
     * Get current Pashto date as formatted string
     *
     * Usage:
     * pashto_now()           // '۱ وری ۱۴۰۳'
     * pashto_now('Y/m/d')    // '1403/01/01'
     */
    function pashto_now(string $format = null): string
    {
        $today = PashtoCalendar::now();

        if ($format) {
            return $today->format($format);
        }

        return $today->formatPashto();
    }
}

if (!function_exists('pashto_month_name')) {
    /**
     * Get Pashto month name by number
     *
     * Usage:
     * pashto_month_name(1)              // 'وری'
     * pashto_month_name(1, 'dari')      // 'حمل'
     */
    function pashto_month_name(int $month, string $language = null): string
    {
        return Months::name($month, $language);
    }
}

if (!function_exists('pashto_number')) {
    /**
     * Convert number to Pashto numerals
     *
     * Usage:
     * pashto_number(1403)   // '۱۴۰۳'
     * pashto_number(25)     // '۲۵'
     */
    function pashto_number($value): string
    {
        return PashtoDate::toPashtoNumber($value);
    }
}

if (!function_exists('is_pashto_holiday')) {
    /**
     * Check if a Shamsi date is a holiday
     *
     * Usage:
     * is_pashto_holiday(1, 1)   // true — Nowroz
     * is_pashto_holiday(3, 15)  // false
     */
    function is_pashto_holiday(int $month, int $day): bool
    {
        return Holidays::isHoliday($month, $day);
    }
}
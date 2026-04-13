<?php

namespace Qadir\PashtoCalendar\Support;

use Carbon\Carbon;
use Qadir\PashtoCalendar\PashtoCalendar;
use Qadir\PashtoCalendar\PashtoDate;

class CarbonMacros
{
    public static function register(): void
    {
        // ============================================================
        // ✅ $carbon->toPashto()
        // Returns PashtoDate object
        //
        // Usage:
        // now()->toPashto()
        // Carbon::parse('2024-03-20')->toPashto()
        // ============================================================

        Carbon::macro('toPashto', function () {
            return PashtoCalendar::fromGregorian($this);
        });

        // ============================================================
        // ✅ $carbon->toPashtoString()
        // Returns Pashto readable string
        //
        // Usage:
        // now()->toPashtoString()   // '۱ وری ۱۴۰۳'
        // ============================================================

        Carbon::macro('toPashtoString', function () {
            return PashtoCalendar::fromGregorian($this)->formatPashto();
        });

        // ============================================================
        // ✅ $carbon->toPashtoFormat($format)
        // Returns formatted Shamsi string
        //
        // Usage:
        // now()->toPashtoFormat('Y/m/d')  // '1403/01/01'
        // ============================================================

        Carbon::macro('toPashtoFormat', function (string $format = 'Y/m/d') {
            return PashtoCalendar::fromGregorian($this)->format($format);
        });

        // ============================================================
        // ✅ $carbon->isPashtoHoliday()
        // Checks if the Carbon date is an Afghan holiday
        //
        // Usage:
        // now()->isPashtoHoliday()
        // ============================================================

        Carbon::macro('isPashtoHoliday', function () {
            $pashto = PashtoCalendar::fromGregorian($this);
            return Holidays::isHoliday($pashto->month, $pashto->day);
        });

        // ============================================================
        // ✅ $carbon->pashtoHolidayName()
        // Returns holiday name if it is a holiday
        //
        // Usage:
        // now()->pashtoHolidayName()  // 'نوروز' or null
        // ============================================================

        Carbon::macro('pashtoHolidayName', function () {
            $pashto = PashtoCalendar::fromGregorian($this);
            return Holidays::getHoliday($pashto->month, $pashto->day);
        });

        // ============================================================
        // ✅ Carbon::parsePashto($year, $month, $day)
        // Build a Carbon from a Shamsi date
        //
        // Usage:
        // Carbon::parsePashto(1403, 1, 1)  // returns Carbon
        // ============================================================

        Carbon::macro('parsePashto', function (int $year, int $month, int $day) {
            [$gy, $gm, $gd] = \Qadir\PashtoCalendar\Support\JalaliConverter::toGregorian(
                $year, $month, $day
            );

            return Carbon::create($gy, $gm, $gd, 0, 0, 0,
                config('pashto-calendar.timezone', 'Asia/Kabul')
            );
        });
    }
}
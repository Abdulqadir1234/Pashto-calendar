<?php

namespace Qadir\PashtoCalendar\Support;

class Holidays
{
    /*
    |--------------------------------------------------------------------------
    | AFGHAN NATIONAL HOLIDAYS (Solar Hijri / Shamsi)
    |--------------------------------------------------------------------------
    | Format: 'month-day' => ['name_ps' => '...', 'name_en' => '...']
    |
    | NOTE: Islamic holidays (Eid, Ramadan, Ashura etc.) are lunar-based
    | and change every year — they are handled separately in IslamicHolidays.php
    | which we will build in Phase 4.
    |--------------------------------------------------------------------------
    */

    protected static array $holidays = [

        // ============================================================
        // 🌸 MONTH 1 — HAMAL (وری) — March/April
        // ============================================================

        '01-01' => [
            'name_ps' => 'نوروز - د افغانستان د نوي کال لومړۍ ورځ',
            'name_en' => 'Nowroz - Afghan New Year Day 1',
        ],
        '01-02' => [
            'name_ps' => 'نوروز - د نوي کال دوهمه ورځ',
            'name_en' => 'Nowroz - Afghan New Year Day 2',
        ],
        '01-03' => [
            'name_ps' => 'نوروز - د نوي کال دریمه ورځ',
            'name_en' => 'Nowroz - Afghan New Year Day 3',
        ],

        // ============================================================
        // 🌼 MONTH 2 — SAUR (غویی) — April/May
        // ============================================================

        '02-08' => [
            'name_ps' => 'د مجاهدینو د بریا ورځ',
            'name_en' => 'Mujahideen Victory Day (April 28)',
        ],
        '02-11' => [
            'name_ps' => 'د نړیوالو کارګرانو ورځ',
            'name_en' => 'International Workers Day (May 1)',
        ],

        // ============================================================
        // ☀️ MONTH 3 — JAWZA (غبرګولی) — May/June
        // ============================================================

        '03-15' => [
            'name_ps' => 'د ماشومانو نړیواله ورځ',
            'name_en' => 'International Children\'s Day',
        ],

        // ============================================================
        // 🌞 MONTH 5 — ASAD (زمری) — July/August
        // ============================================================

        '05-28' => [
            'name_ps' => 'د افغانستان د خپلواکۍ ورځ',
            'name_en' => 'Afghan Independence Day (August 19)',
        ],

        // ============================================================
        // 🍂 MONTH 6 — SUNBULA (وږی) — August/September
        // ============================================================

        '06-06' => [
            'name_ps' => 'د شهیدانو او معیوبینو ورځ',
            'name_en' => 'Martyrs and Disabled People Day',
        ],

        // ============================================================
        // 🍁 MONTH 7 — MIZAN (تله) — September/October
        // ============================================================

        '07-07' => [
            'name_ps' => 'د ملګرو ملتونو ورځ',
            'name_en' => 'United Nations Day',
        ],

        // ============================================================
        // ❄️ MONTH 9 — QAWS (لیندۍ) — November/December
        // ============================================================

        '09-10' => [
            'name_ps' => 'د بشري حقونو نړیواله ورځ',
            'name_en' => 'International Human Rights Day (December 10)',
        ],

        // ============================================================
        // 🌨️ MONTH 10 — JADI (مرغومی) — December/January
        // ============================================================

        '10-04' => [
            'name_ps' => 'د افغانستان د بریا ورځ',
            'name_en' => 'Liberation Day',
        ],

        // ============================================================
        // 🌬️ MONTH 11 — DALW (سلواغه) — January/February
        // ============================================================

        '11-26' => [
            'name_ps' => 'د سویت اتحاد د وتلو ورځ',
            'name_en' => 'Soviet Withdrawal Day (February 15)',
        ],

        // ============================================================
        // 🌱 MONTH 12 — HUT (کب) — February/March
        // ============================================================

        '12-08' => [
            'name_ps' => 'د شهیدانو ورځ',
            'name_en' => 'Martyrs Day',
        ],
        '12-29' => [
            'name_ps' => 'د نوي کال د راتګ هرکلی',
            'name_en' => 'New Year\'s Eve',
        ],

    ];

    // ============================================================
    // ✅ CHECK IF A DATE IS A HOLIDAY
    // ============================================================

    public static function isHoliday(int $month, int $day): bool
    {
        $key = self::makeKey($month, $day);
        return isset(self::$holidays[$key]);
    }

    // ============================================================
    // ✅ GET HOLIDAY NAME
    // Returns name based on config language (pashto or dari/english)
    // ============================================================

    public static function getHoliday(int $month, int $day): ?string
    {
        $key = self::makeKey($month, $day);

        if (!isset(self::$holidays[$key])) {
            return null;
        }

        $language = config('pashto-calendar.language', 'pashto');

        return $language === 'en'
            ? self::$holidays[$key]['name_en']
            : self::$holidays[$key]['name_ps'];
    }

    // ============================================================
    // ✅ GET FULL HOLIDAY DATA (both languages)
    // ============================================================

    public static function getHolidayFull(int $month, int $day): ?array
    {
        $key = self::makeKey($month, $day);
        return self::$holidays[$key] ?? null;
    }

    // ============================================================
    // ✅ GET ALL HOLIDAYS FOR A MONTH
    // ============================================================

    public static function forMonth(int $month): array
    {
        $result = [];
        $prefix = str_pad($month, 2, '0', STR_PAD_LEFT) . '-';

        foreach (self::$holidays as $key => $holiday) {
            if (str_starts_with($key, $prefix)) {
                $day = (int) explode('-', $key)[1];
                $result[$day] = $holiday;
            }
        }

        return $result;
    }

    // ============================================================
    // ✅ GET ALL HOLIDAYS
    // ============================================================

    public static function all(): array
    {
        return self::$holidays;
    }

    // ============================================================
    // ✅ GET ALL HOLIDAYS WITH PARSED MONTH/DAY
    // Useful for displaying in a list
    // ============================================================

    public static function allParsed(): array
    {
        $result = [];

        foreach (self::$holidays as $key => $holiday) {
            [$month, $day] = explode('-', $key);
            $result[] = [
                'month'      => (int) $month,
                'day'        => (int) $day,
                'month_name' => Months::name((int) $month),
                'name_ps'    => $holiday['name_ps'],
                'name_en'    => $holiday['name_en'],
            ];
        }

        return $result;
    }

    // ============================================================
    // ✅ PRIVATE HELPER — build key string
    // ============================================================

    private static function makeKey(int $month, int $day): string
    {
        return str_pad($month, 2, '0', STR_PAD_LEFT)
             . '-'
             . str_pad($day,   2, '0', STR_PAD_LEFT);
    }
}
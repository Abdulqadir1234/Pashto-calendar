<?php

namespace Qadir\PashtoCalendar\Support;

class Months
{
    // ✅ NATIVE PASHTO
    protected static array $pashto = [
        1  => 'وری',      2  => 'غویی',
        3  => 'غبرګولی',  4  => 'چنګاښ',
        5  => 'زمری',     6  => 'وږی',
        7  => 'تله',      8  => 'لړم',
        9  => 'لیندۍ',   10  => 'مرغومی',
        11 => 'سلواغه',  12  => 'کب',
    ];

    // ✅ DARI / ARABIC
    protected static array $dari = [
        1  => 'حمل',    2  => 'ثور',
        3  => 'جوزا',   4  => 'سرطان',
        5  => 'اسد',    6  => 'سنبله',
        7  => 'میزان',  8  => 'عقرب',
        9  => 'قوس',   10  => 'جدی',
        11 => 'دلو',   12  => 'حوت',
    ];

    // ✅ ENGLISH
    protected static array $english = [
        1  => 'Hamal',    2  => 'Saur',
        3  => 'Jawza',    4  => 'Saratan',
        5  => 'Asad',     6  => 'Sunbula',
        7  => 'Mizan',    8  => 'Aqrab',
        9  => 'Qaws',    10  => 'Jadi',
        11 => 'Dalw',    12  => 'Hut',
    ];

    // ============================================================
    // ✅ GET MONTH NAME — reads language from config
    // ============================================================

    public static function name(int $month, string $language = null): string
    {
        $language = $language ?? config('pashto-calendar.language', 'pashto');

        $list = match($language) {
            'dari'    => self::$dari,
            'en'      => self::$english,
            default   => self::$pashto,
        };

        return $list[$month] ?? '';
    }

    // ============================================================
    // ✅ GET ALL MONTH NAMES
    // ============================================================

    public static function all(string $language = null): array
    {
        $language = $language ?? config('pashto-calendar.language', 'pashto');

        return match($language) {
            'dari'  => self::$dari,
            'en'    => self::$english,
            default => self::$pashto,
        };
    }

    // ✅ Direct access
    public static function pashto():  array { return self::$pashto;  }
    public static function dari():    array { return self::$dari;    }
    public static function english(): array { return self::$english; }
}
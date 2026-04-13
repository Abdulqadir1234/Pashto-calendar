<?php

namespace Qadir\PashtoCalendar;

use Qadir\PashtoCalendar\Events\EventManager;
use Qadir\PashtoCalendar\Converter\GregorianToPashto;
use Carbon\Carbon;
use DateTime;

class PashtoCalendar
{
    /**
     * Convert Gregorian input to PashtoDate
     */
    public static function fromGregorian($date): PashtoDate
    {
        $converter = new GregorianToPashto();
        return $converter->convert($date);
    }

    /**
     * Get current date
     */
   public static function now(): PashtoDate
{
    return self::fromGregorian(
        \Carbon\Carbon::now('Asia/Kabul')
    );
}

    /**
     * Parse different formats
     */
    public static function parse($date): PashtoDate
    {
        if ($date instanceof Carbon || $date instanceof DateTime) {
            return self::fromGregorian($date);
        }

        if (is_int($date)) {
            return self::fromGregorian($date);
        }

        if (is_string($date)) {
            return self::fromGregorian(Carbon::parse($date));
        }

        return self::fromGregorian(Carbon::now());
    }

    /**
     * 🚀 MAIN CALENDAR ENGINE (FIXED)
     */
 public static function make(int $year, int $month): array
{
    $engine = new \Qadir\PashtoCalendar\PashtoDate($year, $month, 1);
    $days = $engine->generateDays();

    foreach ($days as $index => $day) {

        if (!empty($day['empty'])) {
            continue;
        }

        $events = EventManager::forDate($year, $month, $day['day']);

        $days[$index]['events'] = $events;
        $days[$index]['event_count'] = $events->count();
    }

    return $days;
}
}
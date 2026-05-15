<?php

namespace Qadir\PashtoCalendar\Support;

use Qadir\PashtoCalendar\Services\HolidayService;

class Holidays
{
    protected static ?HolidayService $service = null;

    protected static function service(): HolidayService
    {
        if (!self::$service) {
            self::$service = app(HolidayService::class);
        }
        return self::$service;
    }

    public static function isHoliday(int $year, int $month, int $day): bool
    {
        // Ensure data for this year is cached
        self::service()->fetchAndCacheYear($year);
        return self::service()->isHoliday($year, $month, $day);
    }

    public static function getName(int $year, int $month, int $day, string $locale = 'ps'): ?string
    {
        self::service()->fetchAndCacheYear($year);
        return self::service()->getHolidayName($year, $month, $day, $locale);
    }

    public static function getForMonth(int $year, int $month): array
    {
        self::service()->fetchAndCacheYear($year);
        return \Qadir\PashtoCalendar\Models\PashtoHoliday::where('year', $year)
            ->where('month', $month)
            ->get()
            ->keyBy('day')
            ->map(fn($h) => $h->name)
            ->toArray();
    }
/**
 * Get all holidays for a given Pashto year (default: current Pashto year).
 */
/**
 * Get all holidays for a given Pashto year (default: current Pashto year).
 */
public static function all(?int $year = null): array
{
    $year = $year ?? \Qadir\PashtoCalendar\PashtoCalendar::now()->year;

    $holidays = \Qadir\PashtoCalendar\Models\PashtoHoliday::where('year', $year)
        ->orderBy('month')
        ->orderBy('day')
        ->get();

    // If no holidays for requested year, try the most recent year available
    if ($holidays->isEmpty()) {
        $latestYear = \Qadir\PashtoCalendar\Models\PashtoHoliday::max('year');
        if ($latestYear) {
            $holidays = \Qadir\PashtoCalendar\Models\PashtoHoliday::where('year', $latestYear)
                ->orderBy('month')
                ->orderBy('day')
                ->get();
        }
    }

    return $holidays->toArray();
}
 /**
 * Get all holidays formatted for the demo view.
 * Returns an array with keys: month, day, name_ps, name_en.
 */
public static function allParsed(?int $year = null): array
{
    $holidays = self::all($year);

    return array_map(function ($holiday) {
        return [
            'month'   => $holiday['month'],
            'day'     => $holiday['day'],
            'name_ps' => $holiday['name'] ?? '',
            'name_en' => $holiday['name_en'] ?? $holiday['name'] ?? '',
        ];
    }, $holidays);
}
}

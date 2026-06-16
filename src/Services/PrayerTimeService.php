<?php

namespace Qadir\PashtoCalendar\Services;

use IslamicNetwork\PrayerTimes\PrayerTimes;

class PrayerTimeService
{
    protected string $city;
    protected array $cityConfig;

    public function __construct(string $city = 'kabul')
    {
        $this->city = $city;
        $cities = config('pashto-prayer-cities', []);
        $this->cityConfig = $cities[$city] ?? $cities['kabul'];
    }

    public function getTimes($date = null): array
    {
        $date = $date ?: now()->toDateString();

        // Create with method and school
        // MWL = Muslim World League, 1 = Hanafi Asr
        $pt = new PrayerTimes('MWL', 1);

        $times = $pt->getTimes(
            new \DateTime($date),
            (float) $this->cityConfig['lat'],
            (float) $this->cityConfig['lng'],
            null,    // timezone (use default)
            null     // elevation
        );

        // Convert 24-hour to 12-hour AM/PM
        $to12Hour = function ($time24) {
            if (empty($time24)) return '';
            $parts = explode(':', $time24);
            $h = (int) $parts[0];
            $m = $parts[1] ?? '00';
            $ampm = $h >= 12 ? 'PM' : 'AM';
            $h12 = $h % 12;
            if ($h12 == 0) $h12 = 12;
            return sprintf('%d:%02d %s', $h12, $m, $ampm);
        };

        return [
            'city'      => $this->city,
            'city_name' => $this->cityConfig['name'],
            'date'      => $date,
            'fajr'      => $to12Hour($times['Fajr']),
            'sunrise'   => $to12Hour($times['Sunrise']),
            'dhuhr'     => $to12Hour($times['Dhuhr']),
            'asr'       => $to12Hour($times['Asr']),
            'maghrib'   => $to12Hour($times['Maghrib']),
            'isha'      => $to12Hour($times['Isha']),
        ];
    }
}
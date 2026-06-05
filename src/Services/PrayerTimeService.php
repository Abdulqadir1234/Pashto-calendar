<?php

namespace Qadir\PashtoCalendar\Services;

use IslamicNetwork\PrayerTimes\PrayerTimes;

class PrayerTimeService
{
    protected string $city;
    protected array  $cityConfig;
    protected string $method    = 'MWL';      // Muslim World League
    protected string $asrMethod = 'Hanafi';    // Hanafi school

    public function __construct(string $city = 'kabul')
    {
        $this->city = $city;
        $cities = config('pashto-prayer-cities', []);
        $this->cityConfig = $cities[$city] ?? $cities['kabul'];
    }

    public function getTimes($date = null): array
    {
        $date = $date ?: now()->toDateString();

        $lat = (float) $this->cityConfig['lat'];
        $lng = (float) $this->cityConfig['lng'];

        // Calculate UTC offset for the given date and timezone
        $tzOffset = $this->getUtcOffset($date, $this->cityConfig['timezone']);

        // Create the prayer times calculator
        $pt = new PrayerTimes($this->method, $this->asrMethod);

        // ✅ Pass the offset as a FLOAT (not a timezone string)
        $times = $pt->getTimes(
            new \DateTime($date),
            $lat,
            $lng,
            $tzOffset   // e.g. 4.5 for Kabul
        );

        return [
            'city'      => $this->city,
            'city_name' => $this->cityConfig['name'],
            'date'      => $date,
            'fajr'      => $this->formatTime($times['Fajr']),
            'sunrise'   => $this->formatTime($times['Sunrise']),
            'dhuhr'     => $this->formatTime($times['Dhuhr']),
            'asr'       => $this->formatTime($times['Asr']),
            'maghrib'   => $this->formatTime($times['Maghrib']),
            'isha'      => $this->formatTime($times['Isha']),
        ];
    }

    /**
     * Calculate the UTC offset in hours for a specific date and timezone.
     */
    private function getUtcOffset(string $date, string $timezone): float
    {
        $dt = new \DateTime($date . ' 12:00:00', new \DateTimeZone('UTC'));
        $dt->setTimezone(new \DateTimeZone($timezone));
        return $dt->getOffset() / 3600.0;
    }

    /**
     * Convert 24‑hour "HH:MM" to 12‑hour "H:MM AM/PM".
     */
    protected function formatTime(string $time24): string
    {
        [$h, $m] = explode(':', $time24);
        $h = (int) $h;
        $ampm = $h >= 12 ? 'PM' : 'AM';
        $h12 = $h % 12;
        if ($h12 == 0) $h12 = 12;
        return sprintf('%d:%02d %s', $h12, $m, $ampm);
    }
}
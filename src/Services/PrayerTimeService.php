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

        // MWL = Muslim World League, 1 = Hanafi Asr
        $pt = new PrayerTimes('MWL', 1);

        // ── FIX: the previous code passed `null` for the timezone
        // parameter, which made the underlying library fall back to
        // the SERVER's PHP/system timezone (often UTC) instead of the
        // selected city's actual timezone. That mismatch is exactly
        // why "current prayer" looked wrong by several hours.
        //
        // The library expects a UTC OFFSET IN HOURS (float), not a
        // timezone name string — so we resolve the city's IANA
        // timezone (e.g. "Asia/Kabul") into its numeric offset for
        // the given date (this also correctly handles any DST shift
        // automatically, even though Afghanistan does not use DST).
        $timezoneName   = $this->cityConfig['timezone'] ?? 'Asia/Kabul';
        $utcOffsetHours = $this->resolveUtcOffsetHours($timezoneName, $date);

        $times = $pt->getTimes(
            new \DateTime($date, new \DateTimeZone($timezoneName)),
            (float) $this->cityConfig['lat'],
            (float) $this->cityConfig['lng'],
            $utcOffsetHours,   // ← was `null` — now the correct numeric offset
            null               // elevation
        );

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
            'timezone'  => $timezoneName,
            'date'      => $date,
            'fajr'      => $to12Hour($times['Fajr']),
            'sunrise'   => $to12Hour($times['Sunrise']),
            'dhuhr'     => $to12Hour($times['Dhuhr']),
            'asr'       => $to12Hour($times['Asr']),
            'maghrib'   => $to12Hour($times['Maghrib']),
            'isha'      => $to12Hour($times['Isha']),
            // raw 24-hour values too — useful for the front-end countdown
            'fajr_24'      => $times['Fajr']    ?? null,
            'sunrise_24'   => $times['Sunrise'] ?? null,
            'dhuhr_24'     => $times['Dhuhr']   ?? null,
            'asr_24'       => $times['Asr']     ?? null,
            'maghrib_24'   => $times['Maghrib'] ?? null,
            'isha_24'      => $times['Isha']    ?? null,
        ];
    }

    /**
     * Resolve an IANA timezone name (e.g. "Asia/Kabul") to its
     * current UTC offset in hours (e.g. 4.5), for the given date.
     * Using DateTimeZone::getOffset() automatically accounts for
     * DST where applicable, so this works correctly for any city,
     * not just Afghanistan.
     */
    protected function resolveUtcOffsetHours(string $timezoneName, string $date): float
    {
        try {
            $tz  = new \DateTimeZone($timezoneName);
            $dt  = new \DateTime($date, $tz);
            return $tz->getOffset($dt) / 3600;
        } catch (\Exception $e) {
            // Fallback: Afghanistan standard time
            return 4.5;
        }
    }
}
<?php

namespace Qadir\PashtoCalendar\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Qadir\PashtoCalendar\Models\PashtoHoliday;

class HolidayService
{
    /**
     * Configurable remote source (can be set in config).
     */
    public function getRemoteUrl(): ?string
    {
        return config('pashto-calendar.holiday_source_url');
    }

    /**
     * Path to the bundled fallback JSON file.
     */
    public function getFallbackPath(): string
    {
        return __DIR__ . '/../../resources/holidays/default.json';
    }

    /**
     * Fetch holidays for a Shamsi year and cache in DB.
     */
    public function fetchAndCacheYear(int $year): array
    {
        $cacheKey = "pashto_holidays_{$year}";

        return Cache::remember($cacheKey, now()->addWeeks(2), function () use ($year) {
            $holidays = [];

            // 1. Try remote URL if configured
            if ($url = $this->getRemoteUrl()) {
                $holidays = $this->fetchFromUrl($url, $year);
            }

            // 2. Fallback to bundled JSON
            if (empty($holidays)) {
                $holidays = $this->fetchFromFile($this->getFallbackPath(), $year);
            }

            // 3. Store in database for fast future access
            if (!empty($holidays)) {
                $this->storeHolidaysInDatabase($year, $holidays);
            }

            return $holidays;
        });
    }

    /**
     * Fetch holidays from a remote URL for a specific year.
     */
    public function fetchFromUrl(string $url, int $year): array
    {
        try {
            $response = Http::timeout(15)->get($url);
            if (!$response->successful()) {
                return [];
            }

            $all = $response->json();
            if (!is_array($all)) {
                return [];
            }

            return array_values(array_filter($all, fn($h) => ($h['year'] ?? null) == $year));
        } catch (\Exception $e) {
            \Log::warning("Holiday fetch from {$url} failed: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Fetch holidays from a local JSON file for a specific year.
     */
    public function fetchFromFile(string $path, int $year): array
    {
        if (!File::exists($path)) {
            return [];
        }

        try {
            $all = json_decode(File::get($path), true);
            if (!is_array($all)) {
                return [];
            }

            return array_values(array_filter($all, fn($h) => ($h['year'] ?? null) == $year));
        } catch (\Exception $e) {
            \Log::warning("Holiday file read failed: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Store holiday records in the database.
     */
    public function storeHolidaysInDatabase(int $year, array $holidays): void
    {
        foreach ($holidays as $h) {
            if (!isset($h['month'], $h['day'])) {
                continue;
            }

            PashtoHoliday::updateOrCreate(
                [
                    'year'  => $year,
                    'month' => $h['month'],
                    'day'   => $h['day'],
                ],
                [
                    'name'        => $h['name_ps'] ?? $h['name'] ?? 'نامعلوم',
                    'name_en'     => $h['name_en'] ?? null,
                    'description' => $h['description_ps'] ?? $h['description'] ?? null,
                    'type'        => $h['type'] ?? null,
                    'raw_data'    => json_encode($h),
                ]
            );
        }
    }

    /**
     * Check if a specific Shamsi date is a holiday.
     */
    public function isHoliday(int $year, int $month, int $day): bool
    {
        return PashtoHoliday::where('year', $year)
            ->where('month', $month)
            ->where('day', $day)
            ->exists();
    }

    /**
     * Get the holiday name for a specific Shamsi date.
     */
    public function getHolidayName(int $year, int $month, int $day, string $locale = 'ps'): ?string
    {
        $holiday = PashtoHoliday::where('year', $year)
            ->where('month', $month)
            ->where('day', $day)
            ->first();

        if (!$holiday) {
            return null;
        }

        return $locale === 'ps' ? $holiday->name : ($holiday->name_en ?? $holiday->name);
    }
}
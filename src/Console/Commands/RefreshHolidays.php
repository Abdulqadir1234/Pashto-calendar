<?php

namespace Qadir\PashtoCalendar\Console\Commands;

use Illuminate\Console\Command;
use Qadir\PashtoCalendar\Services\HolidayService;

class RefreshHolidays extends Command
{
    protected $signature = 'pashto:refresh-holidays
                            {year? : Shamsi year to refresh (default: current year)}
                            {--url= : Remote URL to fetch holidays from}';

    protected $description = 'Refresh Afghan holidays from bundled JSON or a remote source';

    public function handle(HolidayService $service): int
    {
        $year = $this->argument('year') ?? \Qadir\PashtoCalendar\PashtoCalendar::now()->year;
        $url  = $this->option('url');

        if ($url) {
            $this->info("Fetching holidays from {$url} for year {$year}...");
            $holidays = $service->fetchFromUrl($url, $year);
        } else {
            $this->info("Loading holidays from bundled JSON for year {$year}...");
            $holidays = $service->fetchFromFile($service->getFallbackPath(), $year);
        }

        if (empty($holidays)) {
            $this->error("No holidays found for year {$year}.");
            return self::FAILURE;
        }

        $service->storeHolidaysInDatabase($year, $holidays);
        $this->info("Successfully refreshed " . count($holidays) . " holidays for year {$year}.");
        return self::SUCCESS;
    }
}
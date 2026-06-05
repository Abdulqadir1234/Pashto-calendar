<?php

namespace Qadir\PashtoCalendar;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Qadir\PashtoCalendar\Models\PashtoEvent;
use Qadir\PashtoCalendar\Models\PashtoHoliday;
use Qadir\PashtoCalendar\View\Components\Calendar;
use Qadir\PashtoCalendar\View\Components\MiniCalendar;
use Qadir\PashtoCalendar\Support\Holidays;
use Qadir\PashtoCalendar\Services\HolidayService;
use Qadir\PashtoCalendar\Console\Commands\RefreshHolidays;
use Illuminate\Support\Facades\App;
class PashtoCalendarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $helpersFile = __DIR__ . '/Support/helpers.php';
        if (file_exists($helpersFile)) {
            require_once $helpersFile;
        }

        $this->mergeConfigFrom(
            __DIR__.'/../config/pashto-calendar.php',
            'pashto-calendar'
        );

        $this->app->singleton('pashto-calendar', function () {
            return new PashtoCalendar();
        });
    }

public function boot(): void
{
    $timezone = config('pashto-calendar.timezone', 'Asia/Kabul');
    date_default_timezone_set($timezone);

    $this->loadViewsFrom(__DIR__.'/../resources/views', 'pashto-calendar');
    $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

    // ✅ Load translations
    $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'pashto-calendar');

    Blade::component('pashto-mini-calendar', MiniCalendar::class);

    $this->publishes([
        __DIR__.'/../config/pashto-calendar.php' => config_path('pashto-calendar.php'),
    ], 'pashto-calendar-config');

    $this->publishes([
        __DIR__.'/../resources/views' => resource_path('views/vendor/pashto-calendar'),
    ], 'pashto-calendar-views');

    $this->publishes([
        __DIR__.'/../resources/lang' => lang_path('vendor/pashto-calendar'),
    ], 'pashto-calendar-lang');

    Blade::component('pashto-calendar', Calendar::class);

    \Qadir\PashtoCalendar\Support\BladeDirectives::register();
    \Qadir\PashtoCalendar\Support\CarbonMacros::register();

    $this->registerValidationRules();
    $this->registerRoutes();

    Blade::if('Holiday', function ($month, $day) {
        return Holidays::isHoliday($month, $day);
    });

    $this->autoSeedHolidays();

    if ($this->app->runningInConsole()) {
        $this->commands([
            RefreshHolidays::class,
        ]);
    }

    //prayer time component
    // Merge prayer cities config (so it's always available)
$this->mergeConfigFrom(__DIR__.'/../config/pashto-prayer-cities.php', 'pashto-prayer-cities');

// Publish the cities config
$this->publishes([
    __DIR__.'/../config/pashto-prayer-cities.php' => config_path('pashto-prayer-cities.php'),
], 'pashto-calendar-config');

// Register the prayer times component
Blade::component('pashto-prayer-times', \Qadir\PashtoCalendar\View\Components\PrayerTimes::class);
}

    protected function registerRoutes(): void
    {
        // Update event
        Route::put('/pashto-calendar/event/{id}', function (\Illuminate\Http\Request $request, $id) {
            $data = $request->validate([
                'title'                => 'required|string|max:255',
                'description'          => 'nullable|string',
                'time'                 => 'nullable|string|max:50',
                'year'                 => 'required|integer|min:1300|max:1500',
                'month'                => 'required|integer|min:1|max:12',
                'day'                  => 'required|integer|min:1|max:31',
                'color'                => 'nullable|string|max:20',
                'recurrence'           => 'nullable|in:none,daily,weekly,monthly,yearly',
                'recurrence_end_date'  => 'nullable|date',
            ]);

            $event = \Qadir\PashtoCalendar\Models\PashtoEvent::findOrFail($id);
            $event->update($data);
            return response()->json($event);
        })->middleware('web');

        // Create event
        Route::post('/pashto-calendar/event', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'title'                => 'required|string|max:255',
                'description'          => 'nullable|string',
                'time'                 => 'nullable|string|max:50',
                'year'                 => 'required|integer|min:1300|max:1500',
                'month'                => 'required|integer|min:1|max:12',
                'day'                  => 'required|integer|min:1|max:31',
                'color'                => 'nullable|string|max:20',
                'recurrence'           => 'nullable|in:none,daily,weekly,monthly,yearly',
                'recurrence_end_date'  => 'nullable|date',
            ]);

            try {
                $event = \Qadir\PashtoCalendar\Models\PashtoEvent::create($validated);
                return response()->json($event, 201);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Could not create event',
                    'message' => $e->getMessage()
                ], 500);
            }
        })->middleware('web');

        // Delete event
        Route::delete('/pashto-calendar/event/{id}', function ($id) {
            try {
                $deleted = \Qadir\PashtoCalendar\Events\EventManager::delete((int) $id);
                if ($deleted) {
                    return response()->json(['success' => true]);
                }
                return response()->json(['error' => 'Event not found'], 404);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Deletion failed'], 500);
            }
        })->middleware('web');

        if (!config('pashto-calendar.demo_route', true)) {
            return;
        }

        // Main calendar page (initial load)
        Route::get('/pashto-calendar', function () {
            $now   = \Qadir\PashtoCalendar\PashtoCalendar::now();
            $year  = (int) request('year', $now->year);
            $month = (int) request('month', $now->month);

            if ($month > 12) { $month = 1;  $year++; }
            if ($month < 1)  { $month = 12; $year--; }

            $rawDays = \Qadir\PashtoCalendar\PashtoCalendar::make($year, $month);

            // ✅ Inject recurrence‑aware occurrences
            $allEvents = \Qadir\PashtoCalendar\Models\PashtoEvent::getOccurrencesForMonth($year, $month);
            $eventsByDay = [];
            foreach ($allEvents as $event) {
                $eventsByDay[$event->day][] = $event;
            }
            foreach ($rawDays as &$day) {
                if (!empty($day['empty'])) continue;
                $dayNum = $day['day'];
                $day['events'] = $eventsByDay[$dayNum] ?? [];
                $day['event_count'] = count($day['events']);
            }
            unset($day);

            // Convert to clean Alpine array
            $alpineDays = [];
            foreach ($rawDays as $day) {
                if (!empty($day['empty'])) {
                    $alpineDays[] = ['empty' => true];
                    continue;
                }
                $alpineDays[] = [
                    'day'          => $day['day'],
                    'empty'        => false,
                    'is_today'     => $day['is_today'] ?? false,
                    'is_friday'    => $day['is_friday'] ?? false,
                    'is_holiday'   => $day['is_holiday'] ?? false,
                    'holiday_name' => $day['holiday_name'] ?? '',
                    'event_count'  => $day['event_count'] ?? 0,
                    'events'       => isset($day['events']) ? collect($day['events'])->map(fn($e) => $e->toArray())->values()->all() : [],
                ];
            }

            return view('pashto-calendar::calendar', [
                'year'       => $year,
                'month'      => $month,
                'days'       => $rawDays,
                'alpineDays' => $alpineDays,
                'rtl'        => config('pashto-calendar.rtl', true),
                'font'       => config('pashto-calendar.font', 'Noto Naskh Arabic, serif'),
            ]);
        })->middleware('web');

        // Converter page
      Route::get('/pashto-calendar/converter', function (\Illuminate\Http\Request $request) {
    // Temporary testing code – set locale from query parameter
    if ($request->has('lang') && in_array($request->query('lang'), ['ps', 'fa', 'en'])) {
        App::setLocale($request->query('lang'));
    }

    return view('pashto-calendar::converter', [
        'rtl' => config('pashto-calendar.rtl', true),
    ]);
})->middleware('web');

    // prayer time route
    Route::get('/pashto-calendar/prayer-times/{city?}', function ($city = 'kabul') {
    $service = new \Qadir\PashtoCalendar\Services\PrayerTimeService($city);
    $times = $service->getTimes();
    return response()->json([
        'times'     => [
            'fajr'    => $times['fajr'],
            'sunrise' => $times['sunrise'],
            'dhuhr'   => $times['dhuhr'],
            'asr'     => $times['asr'],
            'maghrib' => $times['maghrib'],
            'isha'    => $times['isha'],
        ],
        'city_name' => $times['city_name'],
    ]);
})->middleware('web');
        // Demo page
        Route::get('/pashto-calendar/demo', function () {
            $now    = \Qadir\PashtoCalendar\PashtoCalendar::now();
            $sample = \Qadir\PashtoCalendar\PashtoCalendar::parse('2024-03-20');
            return view('pashto-calendar::demo', [
                'now'    => $now,
                'sample' => $sample,
            ]);
        })->middleware('web');

        // Gregorian → Pashto converter API
        Route::get('/pashto-calendar/convert/gregorian', function (\Illuminate\Http\Request $request) {
            $date = $request->query('date');
            if (!$date) {
                return response()->json(['error' => 'Missing date parameter'], 422);
            }
            try {
                $pashto = \Qadir\PashtoCalendar\PashtoCalendar::fromGregorian($date);
                return response()->json([
                    'year'      => $pashto->year,
                    'month'     => $pashto->month,
                    'day'       => $pashto->day,
                    'formatted' => $pashto->format('Y/m/d'),
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid Gregorian date'], 422);
            }
        })->middleware('web');

        // Pashto → Gregorian converter API
        Route::get('/pashto-calendar/convert/pashto', function (\Illuminate\Http\Request $request) {
            $year  = $request->query('year');
            $month = $request->query('month');
            $day   = $request->query('day');
            if (!$year || !$month || !$day) {
                return response()->json(['error' => 'Missing year/month/day parameters'], 422);
            }
            try {
                $gregorian = to_gregorian((int)$year, (int)$month, (int)$day);
                return response()->json(['gregorian' => $gregorian]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid Pashto date'], 422);
            }
        })->middleware('web');

        // Year data API
        Route::get('/pashto-calendar/year-data/{year}', function ($year) {
            $year = (int) $year;
            $months = [];
            $monthNames = ['', 'وری', 'غویی', 'غبرګولی', 'چنګاښ', 'زمری', 'وږی', 'تله', 'لړم', 'لیندۍ', 'مرغومی', 'سلواغه', 'کب'];
            $today = \Qadir\PashtoCalendar\PashtoCalendar::now();

            for ($m = 1; $m <= 12; $m++) {
                $days = \Qadir\PashtoCalendar\PashtoCalendar::make($year, $m);
                $daysArray = [];
                foreach ($days as $day) {
                    if (!empty($day['empty'])) {
                        $daysArray[] = ['day' => null];
                        continue;
                    }
                    $daysArray[] = [
                        'day'       => $day['day'],
                        'isToday'   => ($year == $today->year && $m == $today->month && $day['day'] == $today->day),
                        'isHoliday' => $day['is_holiday'] ?? false,
                    ];
                }
                $months[] = [
                    'number' => $m,
                    'name'   => $monthNames[$m],
                    'days'   => $daysArray,
                ];
            }

            return response()->json([
                'year'   => $year,
                'months' => $months,
            ]);
        })->middleware('web');

        // Year page
        Route::get('/pashto-calendar/year', function (\Illuminate\Http\Request $request) {
            $year = (int) $request->query('year', \Qadir\PashtoCalendar\PashtoCalendar::now()->year);
            return view('pashto-calendar::year', [
                'year' => $year,
                'rtl'  => config('pashto-calendar.rtl', true),
            ]);
        })->middleware('web');

        // AJAX month data (with recurrence)
        Route::get('/pashto-calendar/data/{year}/{month}', function ($year, $month) {
            $year  = (int) $year;
            $month = (int) $month;
            if ($month > 12) { $month = 1;  $year++; }
            if ($month < 1)  { $month = 12; $year--; }

            $rawDays = \Qadir\PashtoCalendar\PashtoCalendar::make($year, $month);

            // Recurrence injection
            $allEvents = \Qadir\PashtoCalendar\Models\PashtoEvent::getOccurrencesForMonth($year, $month);
            $eventsByDay = [];
            foreach ($allEvents as $event) {
                $eventsByDay[$event->day][] = $event;
            }
            foreach ($rawDays as &$day) {
                if (!empty($day['empty'])) continue;
                $dayNum = $day['day'];
                $day['events'] = $eventsByDay[$dayNum] ?? [];
                $day['event_count'] = count($day['events']);
            }
            unset($day);

            // Convert to Alpine array
            $alpineDays = [];
            foreach ($rawDays as $day) {
                if (!empty($day['empty'])) {
                    $alpineDays[] = ['empty' => true];
                    continue;
                }
                $alpineDays[] = [
                    'day'          => $day['day'],
                    'empty'        => false,
                    'is_today'     => $day['is_today'] ?? false,
                    'is_friday'    => $day['is_friday'] ?? false,
                    'is_holiday'   => $day['is_holiday'] ?? false,
                    'holiday_name' => $day['holiday_name'] ?? '',
                    'event_count'  => $day['event_count'] ?? 0,
                    'events'       => isset($day['events']) ? collect($day['events'])->map(fn($e) => $e->toArray())->values()->all() : [],
                ];
            }

            if (request()->has('json')) {
                return response()->json(['days' => $alpineDays]);
            }

            return view('pashto-calendar::_calendar_content', [
                'year'  => $year,
                'month' => $month,
                'days'  => $alpineDays,
            ]);
        })->middleware('web');
    }

    protected function autoSeedHolidays(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        if (!Schema::hasTable('pashto_holidays')) {
            return;
        }

        if (PashtoHoliday::count() === 0) {
            try {
                $service = app(HolidayService::class);
                $currentYear = \Qadir\PashtoCalendar\PashtoCalendar::now()->year;
                $service->fetchAndCacheYear($currentYear);
                \Log::info('Pashto Calendar: Auto-seeded holidays for year ' . $currentYear);
            } catch (\Exception $e) {
                \Log::warning('Pashto Calendar: Could not auto-seed holidays - ' . $e->getMessage());
            }
        }
    }

    protected function registerValidationRules(): void
    {
        Validator::extend('pashto_date', function ($attribute, $value, $parameters, $validator) {
            return (new \Qadir\PashtoCalendar\Validation\PashtoDateRule())->passes($attribute, $value);
        }, 'نیټه سمه نه ده');

        Validator::extend('pashto_date_format', function ($attribute, $value, $parameters, $validator) {
            $format = $parameters[0] ?? 'Y/m/d';
            return (new \Qadir\PashtoCalendar\Validation\PashtoDateFormatRule($format))->passes($attribute, $value);
        }, 'د نیټې بڼه سمه نه ده');

        Validator::extend('before_pashto_date', function ($attribute, $value, $parameters, $validator) {
            $date = $parameters[0] ?? null;
            if (!$date) return false;
            return (new \Qadir\PashtoCalendar\Validation\BeforePashtoDateRule($date))->passes($attribute, $value);
        }, 'نیټه باید مخکې وي');

        Validator::extend('after_pashto_date', function ($attribute, $value, $parameters, $validator) {
            $date = $parameters[0] ?? null;
            if (!$date) return false;
            return (new \Qadir\PashtoCalendar\Validation\AfterPashtoDateRule($date))->passes($attribute, $value);
        }, 'نیټه باید وروسته وي');
    }
}
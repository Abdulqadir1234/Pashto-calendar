<?php

namespace Qadir\PashtoCalendar;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Qadir\PashtoCalendar\Models\PashtoEvent;
use Qadir\PashtoCalendar\View\Components\Calendar;
use Qadir\PashtoCalendar\Support\Holidays;

class PashtoCalendarServiceProvider extends ServiceProvider
{
    // ============================================================
    // ✅ REGISTER — only bind things into the container
    // ============================================================

    public function register(): void
{
    // ✅ Load helpers file manually — most reliable approach
    $helpersFile = __DIR__ . '/Support/helpers.php';
    if (file_exists($helpersFile)) {
        require_once $helpersFile;
    }

    // ✅ Merge default config
    $this->mergeConfigFrom(
        __DIR__.'/../config/pashto-calendar.php',
        'pashto-calendar'
    );

    // ✅ Register main class as singleton
    $this->app->singleton('pashto-calendar', function () {
        return new PashtoCalendar();
    });
}

    // ============================================================
    // ✅ BOOT — views, routes, directives, macros, rules
    // ============================================================

    public function boot(): void
    {
        // ✅ Timezone from config
        $timezone = config('pashto-calendar.timezone', 'Asia/Kabul');
        date_default_timezone_set($timezone);

        // ✅ Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'pashto-calendar');

        // ✅ Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // ✅ Publishable config
        $this->publishes([
            __DIR__.'/../config/pashto-calendar.php' => config_path('pashto-calendar.php'),
        ], 'pashto-calendar-config');

        // ✅ Publishable views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/pashto-calendar'),
        ], 'pashto-calendar-views');

        // ✅ Register Blade component
        Blade::component('pashto-calendar', Calendar::class);

        // ✅ Register Blade directives (Phase 3)
        \Qadir\PashtoCalendar\Support\BladeDirectives::register();

        // ✅ Register Carbon macros (Phase 3)
        \Qadir\PashtoCalendar\Support\CarbonMacros::register();

        // ✅ Register validation rules (Phase 4)
        $this->registerValidationRules();

        // ✅ Register package routes
        $this->registerRoutes();
        Blade::if('Holiday', function ($month, $day) {
        return Holidays::isHoliday($month, $day);
    });
    }

    // ============================================================
    // ✅ ROUTES
    // ============================================================

  protected function registerRoutes(): void
{
    // ✅ Always register event API routes
    // These are needed by the calendar UI to save/delete events
    Route::post('/pashto-calendar/event', function () {
        return \Qadir\PashtoCalendar\Models\PashtoEvent::create(
            request()->only([
                'title', 'description',
                'year', 'month', 'day',
                'time', 'color',
            ])
        );
    })->middleware('web');

    Route::delete('/pashto-calendar/event/{id}', function ($id) {
        \Qadir\PashtoCalendar\Events\EventManager::delete((int) $id);
        return response()->json(['deleted' => true]);
    })->middleware('web');

    // ✅ Only register UI routes if demo_route is enabled in config
    if (!config('pashto-calendar.demo_route', true)) {
        return;
    }

    // ✅ Main calendar UI
    Route::get('/pashto-calendar', function () {
        $now   = \Qadir\PashtoCalendar\PashtoCalendar::now();
        $year  = (int) request('year',  $now->year);
        $month = (int) request('month', $now->month);

        if ($month > 12) { $month = 1;  $year++; }
        if ($month < 1)  { $month = 12; $year--; }

        return view('pashto-calendar::calendar', [
            'year'  => $year,
            'month' => $month,
            'days'  => \Qadir\PashtoCalendar\PashtoCalendar::make($year, $month),
            'rtl'   => config('pashto-calendar.rtl', true),
            'font'  => config('pashto-calendar.font', 'Noto Naskh Arabic, serif'),
        ]);
    })->middleware('web');

    // ✅ Feature demo page
    Route::get('/pashto-calendar/demo', function () {
        $now     = \Qadir\PashtoCalendar\PashtoCalendar::now();
        $sample  = \Qadir\PashtoCalendar\PashtoCalendar::parse('2024-03-20');

        return view('pashto-calendar::demo', [
            'now'    => $now,
            'sample' => $sample,
        ]);
    })->middleware('web');

    // ✅ API — returns calendar JSON for a specific month (no page reload)
Route::get('/pashto-calendar/data/{year}/{month}', function ($year, $month) {
    $year  = (int) $year;
    $month = (int) $month;

    if ($month > 12) { $month = 1;  $year++; }
    if ($month < 1)  { $month = 12; $year--; }

    $days = \Qadir\PashtoCalendar\PashtoCalendar::make($year, $month);

    // Convert events to array so JSON works properly
    $days = array_map(function ($day) {
        if (!empty($day['empty'])) return $day;
        $day['events'] = $day['events']->map(fn($e) => $e->toArray())->values()->toArray();
        return $day;
    }, $days);

    return response()->json([
        'year'       => $year,
        'month'      => $month,
        'month_name' => \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month),
        'days'       => $days,
    ]);
})->middleware('web');
}
    // ============================================================
    // ✅ VALIDATION RULES (Phase 4)
    // ============================================================

    protected function registerValidationRules(): void
    {
        // pashto_date — validates a valid Shamsi date string
        Validator::extend(
            'pashto_date',
            function ($attribute, $value, $parameters, $validator) {
                return (new \Qadir\PashtoCalendar\Validation\PashtoDateRule())
                    ->passes($attribute, $value);
            },
            'نیټه سمه نه ده'
        );

        // pashto_date_format:Y/m/d — validates format
        Validator::extend(
            'pashto_date_format',
            function ($attribute, $value, $parameters, $validator) {
                $format = $parameters[0] ?? 'Y/m/d';
                return (new \Qadir\PashtoCalendar\Validation\PashtoDateFormatRule($format))
                    ->passes($attribute, $value);
            },
            'د نیټې بڼه سمه نه ده'
        );

        // before_pashto_date:1403/12/29 — date must be before
        Validator::extend(
            'before_pashto_date',
            function ($attribute, $value, $parameters, $validator) {
                $date = $parameters[0] ?? null;
                if (!$date) return false;
                return (new \Qadir\PashtoCalendar\Validation\BeforePashtoDateRule($date))
                    ->passes($attribute, $value);
            },
            'نیټه باید مخکې وي'
        );

        // after_pashto_date:1400/01/01 — date must be after
        Validator::extend(
            'after_pashto_date',
            function ($attribute, $value, $parameters, $validator) {
                $date = $parameters[0] ?? null;
                if (!$date) return false;
                return (new \Qadir\PashtoCalendar\Validation\AfterPashtoDateRule($date))
                    ->passes($attribute, $value);
            },
            'نیټه باید وروسته وي'
        );
    }
}
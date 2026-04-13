<?php

use Illuminate\Support\Facades\Route;
use Qadir\PashtoCalendar\PashtoCalendar;

// Test calendar with events - Simple JSON output
Route::get('/test-calendar', function () {
    // Use current Pashto date
    $current = \Qadir\PashtoCalendar\PashtoCalendar::now();
    $year = $current->year;
    $month = $current->month;
    
    $calendar = PashtoCalendar::make($year, $month);
    
    return response()->json([
        'year' => $year,
        'month' => $month,
        'month_name' => \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month),
        'calendar' => $calendar,
        'events_found' => collect($calendar)->sum('event_count'),
        'total_days' => count($calendar)
    ]);
});

// Test calendar with HTML view
Route::get('/calendar-demo', function () {
    $current = \Qadir\PashtoCalendar\PashtoCalendar::now();
    $year = $current->year;
    $month = $current->month;
    
    $calendar = PashtoCalendar::make($year, $month);
    
    return view('pashto-calendar-demo', [
        'year' => $year,
        'month' => $month,
        'month_name' => \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month),
        'calendar' => $calendar
    ]);
});

// Test specific date events
Route::get('/test-events/{year}/{month}/{day}', function ($year, $month, $day) {
    $events = \Qadir\PashtoCalendar\Events\EventManager::forDate($year, $month, $day);
    
    return response()->json([
        'date' => "$year-$month-$day",
        'pashto_date' => "$day " . \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) . " $year",
        'events' => array_values($events),
        'event_count' => count($events)
    ]);
});

// Test all events
Route::get('/all-events', function () {
    $events = \Qadir\PashtoCalendar\Events\EventManager::all();
    
    return response()->json([
        'total_events' => count($events),
        'events' => $events
    ]);
});

// Test current date
Route::get('/current-date', function () {
    $current = \Qadir\PashtoCalendar\PashtoCalendar::now();
    
    return response()->json([
        'gregorian' => now()->format('Y-m-d'),
        'pashto' => $current->toArray(),
        'pashto_formatted' => $current->formatPashto(),
        'is_holiday' => $current->isHoliday(),
        'holiday_name' => $current->holidayName()
    ]);
});

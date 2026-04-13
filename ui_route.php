<?php

use Illuminate\Support\Facades\Route;

// UI Calendar Route with Events
Route::get('/calendar', function () {
    // Use current date as default, but allow URL parameters
    $current = \Qadir\PashtoCalendar\PashtoCalendar::now();
    $year = request('year', $current->year);
    $month = request('month', $current->month);
    
    // Handle month boundaries
    if ($month < 1) {
        $month = 12;
        $year--;
    } elseif ($month > 12) {
        $month = 1;
        $year++;
    }
    
    $calendar = \Qadir\PashtoCalendar\PashtoCalendar::make($year, $month);
    
    return view('pashto-calendar::calendar', [
        'year' => $year,
        'month' => $month,
        'days' => $calendar
    ]);
});

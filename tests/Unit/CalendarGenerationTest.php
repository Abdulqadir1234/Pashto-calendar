<?php

use Qadir\PashtoCalendar\Facades\PashtoCalendar;
use Qadir\PashtoCalendar\Models\PashtoEvent;

beforeEach(function () {
    $this->requireDatabase();
    PashtoEvent::truncate();
});

test('calendar grid returns array', function () {
    $days = PashtoCalendar::make(1403, 1);   // already returns an array
    expect($days)->toBeArray();
    expect(count($days))->toBeGreaterThanOrEqual(35);
});

test('today is marked in calendar', function () {
    $today = PashtoCalendar::now();
    $days  = PashtoCalendar::make($today->year, $today->month);
    $todayCell = collect($days)->first(fn($d) => !($d['empty'] ?? false) && $d['day'] == $today->day);
    expect($todayCell['is_today'] ?? false)->toBeTrue();
});

test('calendar includes events', function () {
    PashtoEvent::create([
        'title' => 'Test',
        'year'  => 1403,
        'month' => 1,
        'day'   => 5,
        'color' => '#ff0000',
    ]);
    $days = PashtoCalendar::make(1403, 1);
    $day5 = collect($days)->first(fn($d) => !($d['empty'] ?? false) && $d['day'] == 5);
    expect($day5['event_count'])->toBe(1);
});
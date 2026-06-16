<?php

use Qadir\PashtoCalendar\Models\PashtoEvent;
use Qadir\PashtoCalendar\Facades\PashtoCalendar;
use Qadir\PashtoCalendar\Events\EventManager;

beforeEach(function () {
    $this->requireDatabase();
    PashtoEvent::truncate();
});

test('daily recurring event appears on multiple days', function () {
    EventManager::add([
        'title'      => 'Daily Standup',
        'year'       => 1403,
        'month'      => 1,
        'day'        => 5,
        'recurrence' => 'daily',
    ]);

    $occurrences = PashtoEvent::getOccurrencesForMonth(1403, 1);
    expect(count($occurrences))->toBeGreaterThan(5); // at least days 5 through end of month
});

test('weekly recurring event appears on correct days', function () {
    EventManager::add([
        'title'      => 'Weekly Meeting',
        'year'       => 1403,
        'month'      => 1,
        'day'        => 1,
        'recurrence' => 'weekly',
    ]);

    $occurrences = PashtoEvent::getOccurrencesForMonth(1403, 1);
    expect(count($occurrences))->toBeGreaterThan(3); // 1st, 8th, 15th, 22nd, 29th
});

test('monthly recurring event appears once per month', function () {
    EventManager::add([
        'title'      => 'Monthly Review',
        'year'       => 1403,
        'month'      => 1,
        'day'        => 10,
        'recurrence' => 'monthly',
    ]);

    $occurrences = PashtoEvent::getOccurrencesForMonth(1403, 2);
    expect(count($occurrences))->toBe(1);
});

test('yearly recurring event appears in the same month next year', function () {
    EventManager::add([
        'title'      => 'Annual Event',
        'year'       => 1403,
        'month'      => 5,
        'day'        => 20,
        'recurrence' => 'yearly',
    ]);

    $occurrences = PashtoEvent::getOccurrencesForMonth(1404, 5);
    expect(count($occurrences))->toBe(1);
});

test('recurring event with end date stops after end date', function () {
    EventManager::add([
        'title'               => 'Limited Run',
        'year'                => 1403,
        'month'               => 1,
        'day'                 => 1,
        'recurrence'          => 'daily',
        'recurrence_end_date' => '2024-03-25', // 5 days after Nowroz
    ]);

    $occurrences = PashtoEvent::getOccurrencesForMonth(1403, 1);
    expect(count($occurrences))->toBe(5); // days 1–5
});
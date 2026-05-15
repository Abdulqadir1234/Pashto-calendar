<?php

use Qadir\PashtoCalendar\Models\PashtoEvent;
use Qadir\PashtoCalendar\EventManager;

beforeEach(function () {
    $this->requireDatabase();   // Only runs if SQLite is loaded
    PashtoEvent::truncate();
});

test('can add an event', function () {
    $event = EventManager::add([
        'title' => 'Meeting',
        'year'  => 1403,
        'month' => 5,
        'day'   => 15,
    ]);
    expect($event)->toBeInstanceOf(PashtoEvent::class);
    expect($event->title)->toBe('Meeting');
});

test('can retrieve events for a date', function () {
    EventManager::add(['title' => 'A', 'year' => 1403, 'month' => 6, 'day' => 10]);
    EventManager::add(['title' => 'B', 'year' => 1403, 'month' => 6, 'day' => 10]);
    expect(EventManager::forDate(1403, 6, 10))->toHaveCount(2);
});

test('can update an event', function () {
    $event = EventManager::add(['title' => 'Old', 'year' => 1403, 'month' => 9, 'day' => 20]);
    EventManager::update($event->id, ['title' => 'New']);
    expect(PashtoEvent::find($event->id)->title)->toBe('New');
});

test('can delete an event', function () {
    $event = EventManager::add(['title' => 'Delete me', 'year' => 1403, 'month' => 10, 'day' => 5]);
    EventManager::delete($event->id);
    expect(PashtoEvent::find($event->id))->toBeNull();
});

test('can clear a month', function () {
    EventManager::add(['title' => 'One', 'year' => 1403, 'month' => 11, 'day' => 1]);
    EventManager::add(['title' => 'Two', 'year' => 1403, 'month' => 11, 'day' => 2]);
    EventManager::clearMonth(1403, 11);
    expect(EventManager::forMonth(1403, 11))->toHaveCount(0);
});
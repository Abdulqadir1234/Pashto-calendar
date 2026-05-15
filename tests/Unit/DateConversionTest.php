<?php

use Qadir\PashtoCalendar\Facades\PashtoCalendar;
use Qadir\PashtoCalendar\PashtoDate;

test('gregorian to pashto conversion works', function () {
    $pashto = PashtoCalendar::fromGregorian('2024-03-20');
    expect($pashto->year)->toBe(1403);
    expect($pashto->month)->toBe(1);
    expect($pashto->day)->toBe(1);
});

test('pashto to gregorian conversion works', function () {
    // to_gregorian returns a string like "2024-03-20"
    $gregorian = to_gregorian(1403, 1, 1);
    expect($gregorian)->toBeString();
    expect($gregorian)->toBe('2024-03-20');
});

test('leap year detection', function () {
    $date = new PashtoDate(1403, 1, 1);
    expect($date->isLeapYear())->toBeTrue();

    $date = new PashtoDate(1404, 1, 1);
    expect($date->isLeapYear())->toBeFalse();
});

test('month length', function () {
    $date = new PashtoDate(1404, 1, 1);
    expect($date->daysInMonth())->toBe(31);  // matches your data

    $date = new PashtoDate(1403, 12, 1);
    expect($date->daysInMonth())->toBe(30);  // leap year Kab has 30
});

test('now returns valid pashto date', function () {
    $now = PashtoCalendar::now();
    expect($now)->toBeInstanceOf(PashtoDate::class);
    expect($now->year)->toBeGreaterThan(1300);
    expect($now->month)->toBeBetween(1, 12);
    expect($now->day)->toBeBetween(1, 31);
});
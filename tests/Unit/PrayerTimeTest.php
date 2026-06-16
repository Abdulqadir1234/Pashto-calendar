<?php

use Qadir\PashtoCalendar\Services\PrayerTimeService;

test('prayer times returns expected structure', function () {
    $service = new PrayerTimeService('kabul');
    $times = $service->getTimes('2024-03-20');

    expect($times)->toHaveKeys([
        'city', 'city_name', 'date',
        'fajr', 'sunrise', 'dhuhr', 'asr', 'maghrib', 'isha'
    ]);
    expect($times['city'])->toBe('kabul');
    expect($times['city_name'])->toBe('کابل');
});

test('prayer times are in 12‑hour format with AM/PM', function () {
    $service = new PrayerTimeService('kabul');
    $times = $service->getTimes('2024-06-15');

    foreach (['fajr', 'sunrise', 'dhuhr', 'asr', 'maghrib', 'isha'] as $prayer) {
        expect($times[$prayer])->toMatch('/^\d{1,2}:\d{2} (AM|PM)$/');
    }
});

test('prayer times differ between cities', function () {
    $kabul = (new PrayerTimeService('kabul'))->getTimes('2024-06-15');
    $herat = (new PrayerTimeService('herat'))->getTimes('2024-06-15');

    // At least one prayer time should differ due to longitude
    $diffFound = false;
    foreach (['fajr', 'dhuhr', 'asr', 'maghrib', 'isha'] as $prayer) {
        if ($kabul[$prayer] !== $herat[$prayer]) {
            $diffFound = true;
            break;
        }
    }
    expect($diffFound)->toBeTrue();
});

test('prayer times change throughout the year', function () {
    $service = new PrayerTimeService('kabul');
    $summer = $service->getTimes('2024-06-15'); // June (longer days)
    $winter = $service->getTimes('2024-12-15'); // December (shorter days)

    // Fajr should be earlier in summer
    $summerFajr = strtotime($summer['fajr']);
    $winterFajr = strtotime($winter['fajr']);
    // In winter, fajr is later (absolute time)
    // We just check they are different
    expect($summerFajr)->not->toBe($winterFajr);
});

test('prayer times works for all 34 provinces', function () {
    $cities = config('pashto-prayer-cities');
    expect(count($cities))->toBeGreaterThanOrEqual(34);

    foreach ($cities as $key => $city) {
        $service = new PrayerTimeService($key);
        $times = $service->getTimes();
        expect($times['fajr'])->not->toBeEmpty();
        expect($times['city_name'])->toBe($city['name']);
    }
});
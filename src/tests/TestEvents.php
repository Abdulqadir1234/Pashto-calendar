<?php

require __DIR__ . '/../vendor/autoload.php';

use Qadir\PashtoCalendar\Events\EventManager;
use Qadir\PashtoCalendar\Models\PashtoEvent;
use Qadir\PashtoCalendar\PashtoCalendar;

// 1. Clear old events
EventManager::clear();

// 2. Add test events
EventManager::add(new PashtoEvent(
    "Meeting",
    1405,
    1,
    10,
    "Test meeting",
    null,
    "red"
));

EventManager::add(new PashtoEvent(
    "Exam",
    1405,
    1,
    15,
    "School exam",
    null,
    "blue"
));

// 3. Generate calendar
$days = PashtoCalendar::make(1405, 1);

// 4. Print result (DEBUG)
foreach ($days as $day) {
    if (!empty($day['empty'])) continue;

    echo $day['day'];

    if ($day['event_count'] > 0) {
        echo " 👉 EVENTS: " . $day['event_count'];
    }

    echo PHP_EOL;
}
<?php

require_once 'src/Support/JalaliConverter.php';
use Qadir\PashtoCalendar\Support\JalaliConverter;

$today = new DateTime();
echo 'Gregorian Today: ' . $today->format('Y-m-d') . PHP_EOL;
echo 'Timestamp: ' . $today->getTimestamp() . PHP_EOL;

// Test the conversion
[$jy, $jm, $jd] = JalaliConverter::toJalali((int)$today->format('Y'), (int)$today->format('m'), (int)$today->format('d'));
echo 'Converted Jalali: ' . $jy . '-' . $jm . '-' . $jd . PHP_EOL;

// Also test with Carbon if available
if (class_exists('Carbon\Carbon')) {
    $carbon = \Carbon\Carbon::now();
    echo 'Carbon Today: ' . $carbon->format('Y-m-d') . PHP_EOL;
}

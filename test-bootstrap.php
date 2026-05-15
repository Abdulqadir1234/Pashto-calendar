<?php

require __DIR__ . '/vendor/autoload.php';

echo "Autoload OK\n";

if (class_exists('Qadir\PashtoCalendar\Tests\TestCase')) {
    echo "TestCase class exists\n";
} else {
    echo "TestCase NOT FOUND\n";
}
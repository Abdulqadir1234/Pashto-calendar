# Pashto Calendar for Laravel

[![Tests](https://github.com/Abdulqadir1234/Pashto-calendar/actions/workflows/tests.yml/badge.svg)](https://github.com/Abdulqadir1234/Pashto-calendar/actions/workflows/tests.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/qadir/pashto-calendar.svg?style=flat-square)](https://packagist.org/packages/qadir/pashto-calendar)
[![Total Downloads](https://img.shields.io/packagist/dt/qadir/pashto-calendar.svg?style=flat-square)](https://packagist.org/packages/qadir/pashto-calendar)
[![License](https://img.shields.io/github/license/Abdulqadir1234/Pashto-calendar?style=flat-square)](LICENSE.md)
[![Laravel 10+](https://img.shields.io/badge/Laravel-10%2B-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
![Calendar Demo](https://raw.githubusercontent.com/Abdulqadir1234/Pashto-calendar/main/docs/screenshot.png)

A comprehensive Laravel package for the **Pashto Solar Hijri (Shamsi) calendar** system used throughout Afghanistan. Includes bidirectional date conversion, event management, prayer times, interactive calendar UI, and full RTL/Pashto localization support.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Basic Date Operations](#basic-date-operations)
  - [Event Management](#event-management)
  - [Prayer Times](#prayer-times)
  - [Blade Components](#blade-components)
  - [Blade Directives](#blade-directives)
  - [Carbon Macros](#carbon-macros)
  - [Helper Functions](#helper-functions)
  - [Validation Rules](#validation-rules)
  - [Eloquent Cast](#eloquent-cast)
  - [iCal Export](#ical-export)
- [Demo](#demo)
- [Multi-Language Support](#multi-language-support)
- [Artisan Commands](#artisan-commands)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)
- [Credits](#credits)

## Features

- **🔄 Bidirectional Date Conversion** — Convert seamlessly between Gregorian and Pashto (Jalali) calendars
- **📅 Event Management** — Create, update, and delete events with full recurrence support (daily, weekly, monthly, yearly)
- **🕌 Prayer Times** — Accurate prayer times for all 34 Afghan provinces using Muslim World League method with Hanafi Asr
- **🏆 Holiday System** — 30+ built-in Afghan national holidays with Artisan refresh command
- **📥 iCal Export** — Export events to Google Calendar, Outlook, and Apple Calendar formats
- **⚙️ Interactive Calendar UI** — AJAX-powered calendar with Alpine.js and Tailwind CSS
- **🌍 Multi-Language Support** — Pashto, Dari, and English with automatic RTL layout
- **🌙 Dark/Light Mode** — Theme persistence with localStorage
- **🎨 Blade Components** — Pre-built components: `<x-pashto-calendar>`, `<x-pashto-mini-calendar>`, `<x-pashto-prayer-times>`
- **✨ Blade Directives** — Convenient directives: `@pashtoDate`, `@pashtoNow`, `@pashtoNumber`, `@ifHoliday`
- **🔗 Carbon Macros** — Fluent API: `now()->toPashto()`, `Carbon::parsePashto()`
- **🛠️ Helper Functions** — `pashto_now()`, `pashto_number()`, `to_shamsi_pashto()`, and more
- **✅ Validation Rules** — `pashto_date`, `pashto_date_format`, `before_pashto_date`, `after_pashto_date`
- **💾 Eloquent Cast** — `PashtoDateCast` for automatic model attribute conversion
- **⚙️ Fully Configurable** — Language, numerals, RTL direction, week start, and more
- **🧪 Comprehensive Test Suite** — Pest tests with GitHub Actions CI/CD

## Requirements

- PHP 8.1 or higher
- Laravel 10.0 or higher
- SQLite, MySQL, PostgreSQL, or other supported database

## Installation

### 1. Install via Composer

```bash
composer require qadirnasrat/pashto-calendar
```

Publish Configuration & Assets:

```bash
php artisan vendor:publish --provider="Qadir\PashtoCalendar\PashtoCalendarServiceProvider"
php artisan migrate
```

This publishes:
- Configuration file: `config/pashto-calendar.php`
- Database migrations
- Language files: `lang/ps/`, `lang/fa/`, `lang/en/`
- View components

Run Migrations:

```bash
php artisan migrate
```

(Optional) Seed Holidays:

```bash
php artisan pashto:refresh-holidays
```

## Configuration

```php
return [
    // Default language: 'ps' (Pashto), 'fa' (Dari), 'en' (English)
    'language' => env('PASHTO_CALENDAR_LANGUAGE', 'ps'),

    // Display RTL: auto-detected based on language, or force true/false
    'rtl' => true,

    // Use Pashto numerals (۰۱۲۳...) or Western (0123...)
    'use_pashto_numbers' => true,

    // First day of week: 0 = Sunday, 6 = Saturday
    'first_day_of_week' => 6, // Saturday (Afghanistan standard)

    // Demo route path
    'demo_route' => '/pashto-calendar',

    // Enable demo route
    'enable_demo_route' => true,

    // Database table prefix
    'table_prefix' => 'pashto_',
];
```
## Usage

### Basic Date Operations
#### Get Current Pashto Date

```php
use Qadir\PashtoCalendar\Facades\PashtoCalendar;

$today = PashtoCalendar::now();

echo $today->year;        // 1405
echo $today->month;       // 3
echo $today->day;         // 23
echo $today->monthName(); // "غویی" (Pashto month name)
echo $today->dayName();   // "یکشنبه" (Sunday in Pashto)
```


#### Convert Gregorian to Pashto

```php
$pashto = PashtoCalendar::fromGregorian('2024-03-20');

echo $pashto->format('Y/m/d');     // "1403/01/01"
echo $pashto->format('Y-m-d H:i'); // "1403-01-01 00:00"
```

#### Convert Pashto to Gregorian

```php
$gregorian = PashtoCalendar::toGregorian(1403, 1, 1);
// Returns: Carbon instance for 2024-03-20

echo to_gregorian(1403, 1, 1); // "2024-03-20"
```
#### Generate Calendar Grid

```php
$days = PashtoCalendar::make(1405, 3); // Year 1405, Month 3

foreach ($days as $day) {
    if ($day['empty']) continue; // Skip empty cells
    
    echo $day['day'];           // Day number
    echo $day['is_today'];      // Is today?
    echo $day['is_friday'];     // Is Friday?
    echo $day['is_holiday'];    // Is holiday?
    echo $day['holiday_name'];  // Holiday name if applicable
    echo $day['events'];        // Associated events array
}
```
### Event Management
#### Create Event

```php
use Qadir\PashtoCalendar\Events\EventManager;

$event = EventManager::add([
    'title'                => 'Team Meeting',
    'description'          => 'Quarterly planning',
    'year'                 => 1405,
    'month'                => 3,
    'day'                  => 15,
    'time'                 => '10:00',
    'color'                => '#3b82f6',
    'recurrence'           => 'weekly', // 'none', 'daily', 'weekly', 'monthly', 'yearly'
    'recurrence_end_date'  => '1405-06-15',
]);
```

#### Retrieve Events
```php
// Events for specific date
$events = EventManager::forDate(1405, 3, 15);

// Events for entire month
$events = EventManager::forMonth(1405, 3);

// Today's events
$events = EventManager::today();

// All events
$events = EventManager::all();

```
#### Update Event
```php
EventManager::update($eventId, [
    'title'  => 'Updated Meeting',
    'time'   => '14:00',
    'color'  => '#ef4444',
]);
```
#### Delete Event

```php
EventManager::delete($eventId);

// Clear all events for a month
EventManager::clearMonth(1405, 3);
```
### Prayer Times

#### Prayer Times Component

```blade
<x-pashto-prayer-times city="kabul" />

```

Available cities: Kabul, Kandahar, Herat, Mazar-i-Sharif, Jalalabad, Kunduz, Bamyan, Badakhshan, Baghlan, Balkh, Daykundi, Faryab, Farah, Ghazni, Ghor, Hilmand, Jawzjan, Kapisa, Kunar, Lagman, Logar, Nangarhar, Nimruz, Nurestan, Paktia, Paktika, Panshir, Parwan, Samangan, Saripul, Takhar, Urozgan, Wardak, Zabul. API Endpoint

GET /pashto-calendar/prayer-times/{city}

**Response:**
```json
{
  "city": "kabul",
  "city_name": "کابل",
  "latitude": 34.5553,
  "longitude": 69.2075,
  "fajr": "02:47 AM",
  "sunrise": "04:37 AM",
  "dhuhr": "11:55 AM",
  "asr": "03:45 PM",
  "maghrib": "07:14 PM",
  "isha": "09:04 PM"
}
```
### Blade Components

#### Full Calendar

```blade
<x-pashto-calendar 
    :year="1405" 
    :month="3"
    :show-header="true"
/>
```
#### Mini Calendar

```blade
<x-pashto-mini-calendar />
```
#### Prayer Times

```blade
<x-pashto-prayer-times city="herat" />
```
### Blade Directives

#### Display Pashto Date
```blade
{{-- Display Pashto Date --}}
@pashtoDate($post->created_at)
<!-- Output: ۲۳ غویی ۱۴۰۵ -->

{{-- Display Current Pashto Date --}}
@pashtoNow
<!-- Output: ۲۳ غویی ۱۴۰۵ -->

{{-- Convert Number to Pashto --}}
@pashtoNumber(1405)
<!-- Output: ۱۴۰۵ -->

{{-- Holiday Conditional --}}
@ifHoliday(1, 1)
    <p>🎉 Happy Nowroz! (Afghan New Year)</p>
@endIfHoliday
```
### Carbon Macros

```php
use Illuminate\Support\Carbon;

// Convert Carbon to Pashto
$pashtoDate = now()->toPashto();
echo $pashtoDate->year; // 1405

// Parse Pashto date to Carbon
$carbon = Carbon::parsePashto(1405, 3, 23);
echo $carbon->format('Y-m-d'); // 2026-06-14

// Check if Pashto holiday
if (now()->isPashtoHoliday()) {
    echo "Today is a holiday!";
}
```
### Helper Functions

```php
// Get current Pashto date
$today = pashto_now();
echo $today->year; // 1405

// Convert number to Pashto numerals
$pashtoNum = pashto_number(1405);
echo $pashtoNum; // ۱۴۰۵

// Convert Gregorian to Pashto
$shamsi = to_shamsi_pashto(2024, 3, 20);
echo $shamsi; // "۱۴۰۳/۰۱/۰۱"

// Convert Pashto to Gregorian
$gregorian = to_gregorian(1403, 1, 1);
echo $gregorian; // "2024-03-20"

// Get Pashto month name
$month = pashto_month_name(3);
echo $month; // "غویی"

// Get Pashto day name
$day = pashto_day_name(0); // 0 = Sunday
echo $day; // "یکشنبه"
```
### Validation Rules

```php
$request->validate([
    'birth_date' => 'required|pashto_date',
    'event_date' => 'required|pashto_date_format:Y/m/d',
    'start_date' => 'required|before_pashto_date:1406/12/29',
    'end_date'   => 'required|after_pashto_date:1403/01/01',
]);
```
### Eloquent Cast

```php
use Qadir\PashtoCalendar\Casts\PashtoDateCast;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $casts = [
        'event_date' => PashtoDateCast::class,
    ];
}

// Usage
$event = Event::find(1);
echo $event->event_date->year;  // 1405
echo $event->event_date->month; // 3
```

### iCal Export
 Export events to .ics format for Google Calendar, Outlook, and Apple Calendar
<!-- Download button in calendar UI -->
```blade
<a href="/pashto-calendar/export/1405/3.ics" class="btn">
    📥 Export to Calendar
</a>
```

Or via API:
GET /pashto-calendar/export/{year}/{month}.ics


## Demo
Visit the interactive calendar demo at:

http://your-app.local/pashto-calendar

The demo includes:
✨ Full interactive calendar with month navigation
📅 Event creation, editing, and deletion
🕌 Real-time prayer times
🌙 Dark/Light mode toggle
🌍 Multi-language support
📱 Fully responsive design
Disable demo route in config/pashto-calendar.php:

'enable_demo_route' => false,

## Multi-Language Support
The package supports Pashto (ps), Dari (fa), and English (en).
Set Application Language
In config/app.php:

```php
'locale' => 'ps', // or 'fa', 'en'
```

Or dynamically:

```php
App::setLocale('ps');
```


### Publish Language Files:

```bash
php artisan vendor:publish --tag=pashto-calendar-lang
```
## Artisan Commands

Refresh Holidays
Update holiday database from configuration:
Refresh for current year
Refresh for specific year

```bash

php artisan pashto:refresh-holidays

php artisan pashto:refresh-holidays 1406

php artisan pashto:refresh-holidays --year=1405-1410
```
## Testing

```bash
composer test
composer test tests/Unit/PashtoCalendarTest.php
composer test -- --coverage
```
## Contributing
We welcome contributions! Please read CONTRIBUTING.md for guidelines.

## License
This package is licensed under the MIT License. See LICENSE.md for details.

## Credits
- Author: Abdul Qadir Nasrat
- Contributors: See CONTRIBUTORS.md
- Inspired by: Laravel ecosystem best practices and Hijri Calendar implementations

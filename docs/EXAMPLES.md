# Usage Examples

## Date Conversion

// Gregorian to Pashto
$pashto = PashtoCalendar::fromGregorian('2024-03-20');
echo $pashto->format('Y/m/d'); // "1403/01/01"

// Pashto to Gregorian
echo to_gregorian(1403, 1, 1); // "2024-03-20"

## Using PashtoDate

$date = pashto_date();
echo $date->addDays(10)->monthName(); // "غویی"

## Creating Events

EventManager::add([
    'title' => 'Meeting',
    'year'  => 1405,
    'month' => 3,
    'day'   => 15,
    'recurrence' => 'weekly',
]);

## Blade Components

<x-pashto-calendar :year="1405" :month="3" />
<x-pashto-mini-calendar />
<x-pashto-prayer-times city="kabul" />

## Blade Directives

@pashtoNow
@pashtoDate($user->created_at)
@pashtoNumber(1405)

## Validation

'birth_date' => 'required|pashto_date'

## iCal Export

/pashto-calendar/export/1405/3.ics
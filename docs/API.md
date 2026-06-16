# API Reference – Pashto Calendar for Laravel

## PashtoCalendar Facade

### PashtoCalendar::now(): PashtoDate
Returns the current date as a PashtoDate object.

$today = PashtoCalendar::now();
echo $today->year;  // 1405
echo $today->month; // 3
echo $today->day;   // 23
echo $today->monthName(); // "غویی"

### PashtoCalendar::fromGregorian($date): PashtoDate
Converts a Gregorian date to a Pashto date.

$pashto = PashtoCalendar::fromGregorian('2024-03-20');
echo $pashto->format('Y/m/d'); // "1403/01/01"

### PashtoCalendar::parse($date): PashtoDate
Parses a date string or Carbon instance into a PashtoDate.

$pashto = PashtoCalendar::parse('1403-01-01');
$pashto = PashtoCalendar::parse(now());

### PashtoCalendar::make($year, $month): array
Generates a calendar grid for the specified month.

$days = PashtoCalendar::make(1405, 3);

---

## PashtoDate Class

### Properties
$year, $month, $day

### format($format): string
$date->format('Y/m/d');   // "1405/03/23"

### monthName(): string
$date->monthName(); // "غویی"

### formatPashto(): string
$date->formatPashto(); // "۲۳ غویی ۱۴۰۵"

### Arithmetic Methods
$date->addDays(10); $date->subDays(5);
$date->addMonths(2); $date->subMonths(1);
$date->addYears(1); $date->subYears(1);

### Utility Methods
$date->isLeapYear();
$date->daysInMonth();
$date->dayOfYear();
$date->weekOfYear();
$date->diffForHumans();

---

## Global Helper Functions

pashto_date($date = null): PashtoDate
to_shamsi($date, $format = 'Y/m/d'): string
to_shamsi_pashto($date): string
to_gregorian($year, $month, $day, $format = 'Y-m-d'): string
pashto_now($format = null): string
pashto_month_name($month, $language = null): string
pashto_number($value): string
is_pashto_holiday($month, $day, $year = null): bool

---

## Blade Components

<x-pashto-calendar :year="1405" :month="3" />
<x-pashto-mini-calendar />
<x-pashto-prayer-times city="kabul" />

---

## Blade Directives

@pashtoDate($post->created_at)
@pashtoNow
@pashtoNumber($year)
@pashtoFormat('2024-03-20', 'Y/m/d')
@ifHoliday(1, 1) Nowroz Mubarak! @endIfHoliday

---

## Carbon Macros

now()->toPashto();
now()->toPashtoString();
now()->toPashtoFormat('Y/m/d');
Carbon::parsePashto(1403, 1, 1);

---

## Validation Rules

'date' => 'required|pashto_date'
'date' => 'required|pashto_date_format:Y/m/d'
'date' => 'required|after_pashto_date:1403/01/01'
'date' => 'required|before_pashto_date:1403/12/29'

---

## Event Management

EventManager::add([...]);
EventManager::forDate($year, $month, $day);
EventManager::forMonth($year, $month);
EventManager::update($id, [...]);
EventManager::delete($id);
EventManager::clearMonth($year, $month);

---

## Prayer Times

$service = new PrayerTimeService('kabul');
$times = $service->getTimes();

---

## Artisan Commands

php artisan pashto:refresh-holidays

---

## iCal Export

/pashto-calendar/export/{year}/{month}.ics
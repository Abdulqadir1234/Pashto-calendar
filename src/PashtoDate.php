<?php

namespace Qadir\PashtoCalendar;

use Carbon\Carbon;
use Qadir\PashtoCalendar\Support\JalaliConverter;
use Qadir\PashtoCalendar\Support\Holidays;
use Qadir\PashtoCalendar\Support\Months;

class PashtoDate
{
    public int $year;
    public int $month;
    public int $day;

    public function __construct($year, $month, $day)
    {
        $this->year  = (int) $year;
        $this->month = (int) $month;
        $this->day   = (int) $day;
    }

    // ============================================================
    // ✅ TIMEZONE — reads from config (Bug #7 fix)
    // ============================================================
    protected static function timezone(): string
    {
        return config('pashto-calendar.timezone', 'Asia/Kabul');
    }

    // ============================================================
    // ✅ CORE HELPER — convert self to Carbon
    // Now uses config timezone instead of hardcoded 'Asia/Kabul'
    // ============================================================
    protected function toCarbon(): Carbon
    {
        [$gy, $gm, $gd] = JalaliConverter::toGregorian(
            $this->year,
            $this->month,
            $this->day
        );

        return Carbon::create($gy, $gm, $gd, 0, 0, 0, self::timezone());
    }

    // ============================================================
    // ✅ CORE HELPER — build PashtoDate from Carbon
    // ============================================================
    protected static function fromCarbon(Carbon $carbon): self
    {
        [$jy, $jm, $jd] = JalaliConverter::toJalali(
            (int) $carbon->format('Y'),
            (int) $carbon->format('m'),
            (int) $carbon->format('d')
        );

        return new self($jy, $jm, $jd);
    }

    // ============================================================
    // ✅ ADD / SUBTRACT DAYS
    // ============================================================

    public function addDays(int $days): self
    {
        return self::fromCarbon($this->toCarbon()->addDays($days));
    }

    public function subDays(int $days): self
    {
        return self::fromCarbon($this->toCarbon()->subDays($days));
    }

    // ============================================================
    // ✅ ADD / SUBTRACT MONTHS
    // ============================================================

    public function addMonths(int $months): self
    {
        return self::fromCarbon($this->toCarbon()->addMonths($months));
    }

    public function subMonths(int $months): self
    {
        return self::fromCarbon($this->toCarbon()->subMonths($months));
    }

    // ============================================================
    // ✅ ADD / SUBTRACT YEARS
    // ============================================================

    public function addYears(int $years): self
    {
        return self::fromCarbon($this->toCarbon()->addYears($years));
    }

    public function subYears(int $years): self
    {
        return self::fromCarbon($this->toCarbon()->subYears($years));
    }

    // ============================================================
    // ✅ DATE DIFFERENCE
    // ============================================================

    public function diffInDays(PashtoDate $other): int
    {
        return (int) abs($this->toCarbon()->diffInDays($other->toCarbon()));
    }

    public function diffInMonths(PashtoDate $other): int
    {
        return (int) abs($this->toCarbon()->diffInMonths($other->toCarbon()));
    }

    public function diffInYears(PashtoDate $other): int
    {
        return (int) abs($this->toCarbon()->diffInYears($other->toCarbon()));
    }

    // ============================================================
    // ✅ COMPARE DATES
    // ============================================================

    public function isBefore(PashtoDate $other): bool
    {
        return $this->toCarbon()->lt($other->toCarbon());
    }

    public function isAfter(PashtoDate $other): bool
    {
        return $this->toCarbon()->gt($other->toCarbon());
    }

    public function isSameDay(PashtoDate $other): bool
    {
        return $this->year  === $other->year
            && $this->month === $other->month
            && $this->day   === $other->day;
    }

    // ============================================================
    // ✅ START / END OF MONTH
    // ============================================================

    public function startOfMonth(): self
    {
        return new self($this->year, $this->month, 1);
    }

    public function endOfMonth(): self
    {
        return new self($this->year, $this->month, $this->daysInMonth());
    }

    // ============================================================
    // ✅ DAY OF YEAR
    // ============================================================

    public function dayOfYear(): int
    {
        $total = 0;

        for ($m = 1; $m < $this->month; $m++) {
            $temp = new self($this->year, $m, 1);
            $total += $temp->daysInMonth();
        }

        return $total + $this->day;
    }

    // ============================================================
    // ✅ WEEK NUMBER
    // ============================================================

    public function weekOfYear(): int
    {
        return (int) ceil($this->dayOfYear() / 7);
    }

    // ============================================================
    // ✅ LEAP YEAR — proper Shamsi algorithm
    // ============================================================

    public function isLeapYear(): bool
    {
        $leapYears = [1, 5, 9, 13, 17, 22, 26, 30];
        return in_array($this->year % 33, $leapYears);
    }

    // ============================================================
    // ✅ DAYS IN MONTH — uses real leap year
    // ============================================================

    public function daysInMonth(): int
    {
        if ($this->month <= 6) return 31;
        if ($this->month <= 11) return 30;
        return $this->isLeapYear() ? 30 : 29;
    }

    // ============================================================
    // ✅ WEEKDAY INDEX
    // Now uses config timezone + config first_day_of_week (Bug #7)
    // ============================================================

    public function weekDayIndex(): int
    {
        [$gy, $gm, $gd] = JalaliConverter::toGregorian(
            $this->year,
            $this->month,
            $this->day
        );

        $dayOfWeek = Carbon::create($gy, $gm, $gd, 0, 0, 0, self::timezone())->dayOfWeek;

        // ✅ first_day_of_week from config (default Saturday = 6)
        $firstDay = config('pashto-calendar.first_day_of_week', 6);

        return ($dayOfWeek - $firstDay + 7) % 7;
    }

    // ============================================================
    // ✅ GENERATE DAYS
    // ============================================================

    public function generateDays(): array
    {
        $days        = [];
        $today       = PashtoCalendar::now();
        $firstDay    = new self($this->year, $this->month, 1);
        $offset      = $firstDay->weekDayIndex();
        $daysInMonth = $firstDay->daysInMonth();

        for ($i = 0; $i < $offset; $i++) {
            $days[] = ['empty' => true, 'events' => [], 'event_count' => 0];
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {

            $date = new self($this->year, $this->month, $day);

            [$gy, $gm, $gd] = JalaliConverter::toGregorian($this->year, $this->month, $day);
            $weekday = Carbon::create($gy, $gm, $gd, 0, 0, 0, self::timezone())->dayOfWeek;

            $days[] = [
                'empty'        => false,
                'day'          => $day,
                'events'       => [],
                'event_count'  => 0,
                'is_holiday'   => $date->isHoliday(),
                'holiday_name' => $date->holidayName(),
                'is_today'     => (
                    $day === $today->day &&
                    $this->month === $today->month &&
                    $this->year  === $today->year
                ),
                'is_friday' => ($weekday === 5),
            ];
        }

        return $days;
    }

    // ============================================================
    // ✅ TO ARRAY
    // ============================================================

    public function toArray(): array
    {
        return [
            'year'       => $this->year,
            'month'      => $this->month,
            'day'        => $this->day,
            'month_name' => $this->monthName(),
        ];
    }

    // ============================================================
    // ✅ FORMAT
    // ============================================================

    public function format(string $pattern): string
    {
        return str_replace(
            ['Y', 'm', 'd'],
            [
                $this->year,
                str_pad($this->month, 2, '0', STR_PAD_LEFT),
                str_pad($this->day,   2, '0', STR_PAD_LEFT),
            ],
            $pattern
        );
    }

    // ============================================================
    // ✅ MONTH NAME — now uses Months.php (Bug #6 fix)
    // ============================================================

    public function monthName(string $language = null): string
    {
        return Months::name($this->month, $language);
    }

    public static function monthNameStatic(int $month, string $language = null): string
    {
        return Months::name($month, $language);
    }

    // ============================================================
    // ✅ PASHTO FORMAT
    // ============================================================

    public function formatPashto(): string
    {
        return $this->day . ' ' . $this->monthName() . ' ' . $this->year;
    }

    // ============================================================
    // ✅ HOLIDAYS
    // ============================================================

    public function isHoliday(): bool
    {
        return Holidays::isHoliday($this->year, $this->month, $this->day);
    }

    public function holidayName(): ?string
    {
        // return Holidays::getHoliday($this->year,$this->month, $this->day);
        return Holidays::getName($this->year, $this->month, $this->day);
    }

    public function isWorkingDay(): bool
    {
        return !$this->isHoliday();
    }

    // ============================================================
    // ✅ PASHTO NUMBER CONVERSION
    // Now respects config('pashto-calendar.pashto_numbers') (Bug #7)
    // ============================================================

    public static function toPashtoNumber($value): string
    {
        // ✅ If config says false — return plain number
        if (!config('pashto-calendar.pashto_numbers', true)) {
            return (string) $value;
        }

        $en = ['0','1','2','3','4','5','6','7','8','9'];
        $ps = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];

        return str_replace($en, $ps, (string) $value);
    }
    // ============================================================
// ✅ RELATIVE DATE — "دوه ورځې وړاندې", "سبا" etc.
// ============================================================

public function diffForHumans(PashtoDate $other = null): string
{
    $compare = $other ? $other->toCarbon() : \Carbon\Carbon::now(self::timezone());
    $current = $this->toCarbon();

    $diffDays    = (int) $current->diffInDays($compare);
    $diffMonths  = (int) $current->diffInMonths($compare);
    $diffYears   = (int) $current->diffInYears($compare);
    $isFuture    = $current->greaterThan($compare);

    // ---- YEARS ----
    if ($diffYears >= 1) {
        $num = PashtoDate::toPashtoNumber($diffYears);
        return $isFuture
            ? "$num کاله وروسته"
            : "$num کاله وړاندې";
    }

    // ---- MONTHS ----
    if ($diffMonths >= 1) {
        $num = PashtoDate::toPashtoNumber($diffMonths);
        return $isFuture
            ? "$num میاشتې وروسته"
            : "$num میاشتې وړاندې";
    }

    // ---- DAYS ----
    if ($diffDays === 0) return 'نن';
    if ($diffDays === 1) return $isFuture ? 'سبا'     : 'پرون';
    if ($diffDays === 2) return $isFuture ? 'بلسبا'   : 'پرون پرون';

    $num = PashtoDate::toPashtoNumber($diffDays);
    return $isFuture
        ? "$num ورځې وروسته"
        : "$num ورځې وړاندې";
}
}
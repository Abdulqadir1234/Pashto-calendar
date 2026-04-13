<?php

namespace Qadir\PashtoCalendar\Validation;

use Illuminate\Contracts\Validation\Rule;
use Qadir\PashtoCalendar\Support\JalaliConverter;

class PashtoDateRule implements Rule
{
    protected string $message = '';

    // ============================================================
    // ✅ VALIDATE — checks if value is a valid Shamsi date
    //
    // Accepts formats:
    // 1403/01/01
    // 1403-01-01
    // 1403/1/1
    // ============================================================

    public function passes($attribute, $value): bool
    {
        // Must be a string
        if (!is_string($value)) {
            $this->message = 'نیټه باید د متن په بڼه وي';
            return false;
        }

        // Parse the date string
        $parsed = $this->parseDate($value);

        if ($parsed === null) {
            $this->message = 'د نیټې بڼه سمه نه ده — سمه بڼه: 1403/01/01';
            return false;
        }

        [$year, $month, $day] = $parsed;

        // Validate year range
        if ($year < 1300 || $year > 1500) {
            $this->message = 'کال باید د ۱۳۰۰ او ۱۵۰۰ تر منځ وي';
            return false;
        }

        // Validate month
        if ($month < 1 || $month > 12) {
            $this->message = 'میاشت باید د ۱ او ۱۲ تر منځ وي';
            return false;
        }

        // Validate day
        $maxDay = $this->daysInMonth($year, $month);

        if ($day < 1 || $day > $maxDay) {
            $this->message = "ورځ باید د ۱ او $maxDay تر منځ وي";
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->message ?: 'نیټه سمه نه ده';
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private function parseDate(string $value): ?array
    {
        // Support both / and - separators
        $value = str_replace('/', '-', $value);
        $parts = explode('-', $value);

        if (count($parts) !== 3) {
            return null;
        }

        $year  = (int) $parts[0];
        $month = (int) $parts[1];
        $day   = (int) $parts[2];

        if ($year === 0 || $month === 0 || $day === 0) {
            return null;
        }

        return [$year, $month, $day];
    }

    private function daysInMonth(int $year, int $month): int
    {
        if ($month <= 6)  return 31;
        if ($month <= 11) return 30;

        // Leap year check
        $leapYears = [1, 5, 9, 13, 17, 22, 26, 30];
        return in_array($year % 33, $leapYears) ? 30 : 29;
    }
}
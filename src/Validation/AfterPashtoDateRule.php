<?php

namespace Qadir\PashtoCalendar\Validation;

use Illuminate\Contracts\Validation\Rule;
use Qadir\PashtoCalendar\PashtoCalendar;

class AfterPashtoDateRule implements Rule
{
    protected string $compareDate;

    // ============================================================
    // ✅ VALIDATE — date must be AFTER the given Shamsi date
    //
    // Usage:
    // new AfterPashtoDateRule('1400/01/01')
    // ============================================================

    public function __construct(string $date)
    {
        $this->compareDate = $date;
    }

    public function passes($attribute, $value): bool
    {
        try {
            $input   = PashtoCalendar::parse($this->shamsiToGregorian($value));
            $compare = PashtoCalendar::parse($this->shamsiToGregorian($this->compareDate));
            return $input->isAfter($compare);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function message(): string
    {
        return "نیټه باید د {$this->compareDate} نه وروسته وي";
    }

    private function shamsiToGregorian(string $date): string
    {
        $date  = str_replace('/', '-', $date);
        $parts = explode('-', $date);

        [$gy, $gm, $gd] = \Qadir\PashtoCalendar\Support\JalaliConverter::toGregorian(
            (int) $parts[0],
            (int) $parts[1],
            (int) $parts[2]
        );

        return "$gy-$gm-$gd";
    }
}
<?php

namespace Qadir\PashtoCalendar\Validation;

use Illuminate\Contracts\Validation\Rule;
use Qadir\PashtoCalendar\PashtoCalendar;

class BeforePashtoDateRule implements Rule
{
    protected string $compareDate;

    // ============================================================
    // ✅ VALIDATE — date must be BEFORE the given Shamsi date
    //
    // Usage:
    // new BeforePashtoDateRule('1403/12/29')
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
            return $input->isBefore($compare);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function message(): string
    {
        return "نیټه باید د {$this->compareDate} نه مخکې وي";
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
<?php

namespace Qadir\PashtoCalendar\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Qadir\PashtoCalendar\PashtoCalendar;
use Qadir\PashtoCalendar\PashtoDate;

class Calendar extends Component
{
    public int $month;
    public int $year;
    public array $days;
    public string $monthName;

    public bool $rtl;
    public string $font;

    public function __construct(int $year = 0, int $month = 0)
    {
        $now = PashtoCalendar::now();

        $this->year = $year ?: $now->year;
        $this->month = $month ?: $now->month;

        if ($this->month > 12) {
            $this->month = 1;
            $this->year++;
        }

        if ($this->month < 1) {
            $this->month = 12;
            $this->year--;
        }

        $this->days = PashtoCalendar::make(
            $this->year,
            $this->month
        );

        $this->monthName = PashtoDate::monthNameStatic(
            $this->month
        );

        $this->rtl = config('pashto-calendar.rtl', true);
        $this->font = config(
            'pashto-calendar.font',
            'Noto Naskh Arabic, serif'
        );
    }

    public function render(): View
    {
        return view('pashto-calendar::calendar', [
            'year' => $this->year,
            'month' => $this->month,
            'days' => $this->days,
            'alpineDays' => $this->days,
            'monthName' => $this->monthName,
            'rtl' => $this->rtl,
            'font' => $this->font,
        ]);
    }
}
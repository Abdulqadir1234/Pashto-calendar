<?php

namespace Qadir\PashtoCalendar\View\Components;

use Illuminate\View\Component;
use Qadir\PashtoCalendar\PashtoCalendar;

class MiniCalendar extends Component
{
    public int $year;
    public int $month;
    public array $days;

   public function __construct(?int $year = null, ?int $month = null)
{
    $this->year = $year ?? PashtoCalendar::now()->year;
    $this->month = $month ?? PashtoCalendar::now()->month;
    $this->days = PashtoCalendar::make($this->year, $this->month); // ← removed ->generate()
}

   public function render()
{
    return view('pashto-calendar::mini-calendar');
}
}
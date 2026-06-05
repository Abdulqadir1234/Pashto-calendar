<?php

namespace Qadir\PashtoCalendar\View\Components;

use Illuminate\View\Component;
use Qadir\PashtoCalendar\Services\PrayerTimeService;

class PrayerTimes extends Component
{
    public array $times;

    public function __construct(?string $city = 'kabul', ?string $date = null)
    {
        $service = new PrayerTimeService($city ?? 'kabul');
        $this->times = $service->getTimes($date);
    }

   public function render()
{
    return view('pashto-calendar::prayer-times', ['times' => $this->times]);
}
}
<?php

namespace Qadir\PashtoCalendar\Converter;

use Carbon\Carbon;
use DateTime;
use Qadir\PashtoCalendar\Support\JalaliConverter;
use Qadir\PashtoCalendar\PashtoDate;

class GregorianToPashto
{
    public function convert($date): PashtoDate
    {
        // ✅ Read timezone from config (Bug #7 fix)
        $timezone = config('pashto-calendar.timezone', 'Asia/Kabul');

        if ($date instanceof Carbon) {
            $carbon = $date->copy()->timezone($timezone);
        } elseif ($date instanceof DateTime) {
            $carbon = Carbon::instance($date)->timezone($timezone);
        } elseif (is_int($date)) {
            $carbon = Carbon::createFromTimestamp($date, $timezone);
        } else {
            $carbon = Carbon::parse($date, $timezone);
        }

        $gy = (int) $carbon->format('Y');
        $gm = (int) $carbon->format('m');
        $gd = (int) $carbon->format('d');

        [$jy, $jm, $jd] = JalaliConverter::toJalali($gy, $gm, $gd);

        return new PashtoDate($jy, $jm, $jd);
    }
}
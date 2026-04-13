<?php

namespace Qadir\PashtoCalendar\Support;

class JalaliConverter
{
    public static function toJalali($gy, $gm, $gd): array
    {
        $g_days_in_month = [31,28,31,30,31,30,31,31,30,31,30,31];
        $j_days_in_month = [31,31,31,31,31,31,30,30,30,30,30,29];

        $gy -= 1600;
        $gm -= 1;
        $gd -= 1;

        $g_day_no = 365*$gy + intdiv($gy+3,4) - intdiv($gy+99,100) + intdiv($gy+399,400);

        for ($i=0; $i < $gm; ++$i)
            $g_day_no += $g_days_in_month[$i];

        if ($gm > 1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
            $g_day_no++;

        $g_day_no += $gd;

        $j_day_no = $g_day_no - 79;

        $j_np = intdiv($j_day_no, 12053);
        $j_day_no %= 12053;

        $jy = 979 + 33*$j_np + 4*intdiv($j_day_no,1461);

        $j_day_no %= 1461;

        if ($j_day_no >= 366) {
            $jy += intdiv($j_day_no-1, 365);
            $j_day_no = ($j_day_no-1)%365;
        }

        for ($i=0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
            $j_day_no -= $j_days_in_month[$i];

        $jm = $i + 1;
        $jd = $j_day_no + 1;

        return [$jy, $jm, $jd];
    }
    public static function toGregorian($jy, $jm, $jd): array
{
    $jy += 1595;
    $days = -355668 + (365 * $jy) + ((int)($jy / 33) * 8) + ((int)(($jy % 33 + 3) / 4))
        + $jd + ($jm < 7 ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);

    $gy = 400 * (int)($days / 146097);
    $days %= 146097;

    if ($days > 36524) {
        $gy += 100 * (int)(--$days / 36524);
        $days %= 36524;

        if ($days >= 365) $days++;
    }

    $gy += 4 * (int)($days / 1461);
    $days %= 1461;

    if ($days > 365) {
        $gy += (int)(($days - 1) / 365);
        $days = ($days - 1) % 365;
    }

    $gd = $days + 1;

    $months = [0,31, ($gy%4==0 && $gy%100!=0)||($gy%400==0)?29:28,31,30,31,30,31,31,30,31,30,31];

    for ($gm = 1; $gm <= 12; $gm++) {
        if ($gd <= $months[$gm]) break;
        $gd -= $months[$gm];
    }

    return [$gy, $gm, $gd];
}
}
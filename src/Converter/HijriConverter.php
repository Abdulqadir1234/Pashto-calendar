<?php

namespace Qadir\PashtoCalendar\Converter;

use Qadir\PashtoCalendar\Support\JalaliConverter;

/**
 * HijriConverter — pure PHP, zero external dependencies.
 *
 * Uses the standard arithmetic/tabular Hijri calendar
 * (Friday epoch, same as ICU / GNU libc / Astronomical Algorithms).
 *
 * FILE: src/Converter/HijriConverter.php
 */
class HijriConverter
{
    // Hijri month names (Arabic)
    private static array $monthNames = [
        '', 'محرم', 'صفر', 'ربیع الأول', 'ربیع الثاني',
        'جمادى الأولى', 'جمادى الآخرة', 'رجب', 'شعبان',
        'رمضان', 'شوال', 'ذو القعدة', 'ذو الحجة',
    ];

    // ── PUBLIC API ────────────────────────────────────────────────

    /** Gregorian → Hijri.  Returns [year, month, day]. */
    public static function gregorianToHijri(int $gy, int $gm, int $gd): array
    {
        return self::jdToHijri(self::gregorianToJd($gy, $gm, $gd));
    }

    /** Hijri → Gregorian.  Returns [year, month, day]. */
    public static function hijriToGregorian(int $hy, int $hm, int $hd): array
    {
        return self::jdToGregorian(self::hijriToJd($hy, $hm, $hd));
    }

    /** Hijri → Pashto (Shamsi).  Returns [year, month, day]. */
    public static function hijriToPashto(int $hy, int $hm, int $hd): array
    {
        [$gy, $gm, $gd] = self::hijriToGregorian($hy, $hm, $hd);
        return JalaliConverter::toJalali($gy, $gm, $gd);
    }

    /** Pashto (Shamsi) → Hijri.  Returns [year, month, day]. */
    public static function pashtoToHijri(int $jy, int $jm, int $jd): array
    {
        [$gy, $gm, $gd] = JalaliConverter::toGregorian($jy, $jm, $jd);
        return self::gregorianToHijri($gy, $gm, $gd);
    }

    // ── FORMATTING ────────────────────────────────────────────────

    /**
     * Human-readable Hijri string.
     * e.g. "1 محرم 1447  (1447/01/01)"
     */
    public static function formatHijri(int $hy, int $hm, int $hd): string
    {
        $mn = self::$monthNames[$hm] ?? $hm;
        return sprintf('%d %s %d  (%04d/%02d/%02d)', $hd, $mn, $hy, $hy, $hm, $hd);
    }

    // ── JULIAN DAY NUMBER ────────────────────────────────────────

    /** Gregorian → JD (Meeus). */
    private static function gregorianToJd(int $y, int $m, int $d): int
    {
        if ($m <= 2) { $y--; $m += 12; }
        $a = intdiv($y, 100);
        $b = 2 - $a + intdiv($a, 4);
        return (int)(365.25 * ($y + 4716))
             + (int)(30.6001 * ($m + 1))
             + $d + $b - 1524;
    }

    /** JD → Gregorian (Meeus). */
    private static function jdToGregorian(int $jd): array
    {
        $a = (int)(($jd - 1867216.25) / 36524.25);
        $a = $jd + 1 + $a - intdiv($a, 4);
        $b = $a + 1524;
        $c = (int)(($b - 122.1) / 365.25);
        $d = (int)(365.25 * $c);
        $e = (int)(($b - $d) / 30.6001);

        $day   = $b - $d - (int)(30.6001 * $e);
        $month = $e < 14 ? $e - 1 : $e - 13;
        $year  = $month > 2 ? $c - 4716 : $c - 4715;
        return [(int)$year, (int)$month, (int)$day];
    }

    /** Hijri → JD (tabular Friday-epoch). */
    public static function hijriToJd(int $hy, int $hm, int $hd): int
    {
        return intdiv(11 * $hy + 3, 30)
             + 354 * $hy
             + 30 * $hm
             - intdiv($hm - 1, 2)
             + $hd
             + 1948440
             - 385;
    }

    /**
     * JD → Hijri.
     *
     * Correct inversion of hijriToJd — verified against:
     *   2025-06-27 => 1447/01/01  ✓
     *   2024-03-20 => 1445/09/10  ✓
     *   2000-01-01 => 1420/09/24  ✓
     *   1970-01-01 => 1389/10/22  ✓
     */
    private static function jdToHijri(int $jd): array
    {
        // Estimate Hijri year
        $hy = (int)(($jd - 1948440 + 385 - 1) / 354.367);

        // Fine-tune year: advance while start of next year <= jd
        while (self::hijriToJd($hy + 1, 1, 1) <= $jd) $hy++;
        // Back up while start of this year > jd
        while (self::hijriToJd($hy,     1, 1) >  $jd) $hy--;

        // Find month
        $hm = 12;
        for ($m = 1; $m <= 12; $m++) {
            if ($m === 12 || self::hijriToJd($hy, $m + 1, 1) > $jd) {
                $hm = $m;
                break;
            }
        }

        // Day (1-based)
        $hd = $jd - self::hijriToJd($hy, $hm, 1) + 1;
        return [(int)$hy, (int)$hm, (int)$hd];
    }
}
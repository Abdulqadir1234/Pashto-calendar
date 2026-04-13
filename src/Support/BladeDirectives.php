<?php

namespace Qadir\PashtoCalendar\Support;

use Illuminate\Support\Facades\Blade;
use Qadir\PashtoCalendar\PashtoCalendar;
use Qadir\PashtoCalendar\PashtoDate;

class BladeDirectives
{
    public static function register(): void
    {
        // ============================================================
        // ✅ @pashtoDate($date)
        // Converts any date to Pashto readable string
        //
        // Usage in blade:
        // @pashtoDate($user->created_at)
        // @pashtoDate('2024-03-20')
        // ============================================================

        Blade::directive('pashtoDate', function ($expression) {
            return "<?php echo to_shamsi_pashto($expression); ?>";
        });

        // ============================================================
        // ✅ @pashtoNow
        // Outputs current Pashto date
        //
        // Usage in blade:
        // @pashtoNow
        // ============================================================

        Blade::directive('pashtoNow', function () {
            return "<?php echo pashto_now(); ?>";
        });

        // ============================================================
        // ✅ @pashtoFormat($date, $format)
        // Converts date with custom format
        //
        // Usage in blade:
        // @pashtoFormat($user->created_at, 'Y/m/d')
        // ============================================================

        Blade::directive('pashtoFormat', function ($expression) {
            [$date, $format] = array_pad(
                explode(',', $expression, 2),
                2,
                "'Y/m/d'"
            );

            return "<?php echo to_shamsi(trim($date), trim($format)); ?>";
        });

        // ============================================================
        // ✅ @pashtoNumber($value)
        // Converts number to Pashto numerals
        //
        // Usage in blade:
        // @pashtoNumber(1403)
        // @pashtoNumber($year)
        // ============================================================

        Blade::directive('pashtoNumber', function ($expression) {
            return "<?php echo pashto_number($expression); ?>";
        });

        // ============================================================
        // ✅ @ifHoliday($month, $day) ... @endIfHoliday
        // Renders content only if the date is a holiday
        //
        // Usage in blade:
        // @ifHoliday(1, 1)
        //     <span>نوروز مبارک!</span>
        // @endIfHoliday
        // ============================================================

        Blade::directive('ifHoliday', function ($expression) {
            return "<?php if (is_pashto_holiday($expression)): ?>";
        });

        Blade::directive('endIfHoliday', function () {
            return "<?php endif; ?>";
        });

        // ============================================================
        // ✅ @pashtoCalendar($year, $month)
        // Renders full calendar inline
        //
        // Usage in blade:
        // @pashtoCalendar(1403, 1)
        // @pashtoCalendar($year, $month)
        // ============================================================

        Blade::directive('pashtoCalendar', function ($expression) {
            return "<?php
                \$_pcParts = explode(',', \"$expression\");
                \$_pcYear  = isset(\$_pcParts[0]) ? (int) trim(\$_pcParts[0]) : \\Qadir\\PashtoCalendar\\PashtoCalendar::now()->year;
                \$_pcMonth = isset(\$_pcParts[1]) ? (int) trim(\$_pcParts[1]) : \\Qadir\\PashtoCalendar\\PashtoCalendar::now()->month;
                echo view('pashto-calendar::calendar', [
                    'year'  => \$_pcYear,
                    'month' => \$_pcMonth,
                    'days'  => \\Qadir\\PashtoCalendar\\PashtoCalendar::make(\$_pcYear, \$_pcMonth),
                    'rtl'   => config('pashto-calendar.rtl', true),
                    'font'  => config('pashto-calendar.font', 'Noto Naskh Arabic, serif'),
                ])->render();
            ?>";
        });
    }
}
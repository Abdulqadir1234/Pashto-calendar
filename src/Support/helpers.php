<?php

use Qadir\PashtoCalendar\PashtoCalendar;
use Qadir\PashtoCalendar\PashtoDate;
use Qadir\PashtoCalendar\Support\Months;
use Qadir\PashtoCalendar\Support\Holidays;

if (!function_exists('pashto_date')) {
    /**
     * Convert any date to PashtoDate
     *
     * Usage:
     * pashto_date()                // today
     * pashto_date('2024-03-20')    // from string
     * pashto_date(now())           // from Carbon
     */
    function pashto_date($date = null): PashtoDate
    {
        if ($date === null) {
            return PashtoCalendar::now();
        }

        return PashtoCalendar::parse($date);
    }
}

if (!function_exists('to_shamsi')) {
    /**
     * Convert Gregorian date to Shamsi formatted string
     *
     * Usage:
     * to_shamsi('2024-03-20')         // '1403/01/01'
     * to_shamsi('2024-03-20', 'Y/m/d')
     */
    function to_shamsi($date, string $format = 'Y/m/d'): string
    {
        return PashtoCalendar::parse($date)->format($format);
    }
}

if (!function_exists('to_shamsi_pashto')) {
    /**
     * Convert Gregorian date to Pashto readable string
     *
     * Usage:
     * to_shamsi_pashto('2024-03-20')  // '۱ وری ۱۴۰۳'
     */
    function to_shamsi_pashto($date): string
    {
        $pashtoDate = PashtoCalendar::parse($date);

        $day   = PashtoDate::toPashtoNumber($pashtoDate->day);
        $month = $pashtoDate->monthName();
        $year  = PashtoDate::toPashtoNumber($pashtoDate->year);

        return "$day $month $year";
    }
}

if (!function_exists('to_gregorian')) {
    /**
     * Convert Shamsi date to Gregorian formatted string
     *
     * Usage:
     * to_gregorian(1403, 1, 1)         // '2024-03-20'
     * to_gregorian(1403, 1, 1, 'Y/m/d')
     */
    function to_gregorian(int $year, int $month, int $day, string $format = 'Y-m-d'): string
    {
        [$gy, $gm, $gd] = \Qadir\PashtoCalendar\Support\JalaliConverter::toGregorian(
            $year, $month, $day
        );

        return \Carbon\Carbon::create($gy, $gm, $gd)->format($format);
    }
}

if (!function_exists('pashto_now')) {
    /**
     * Get current Pashto date as formatted string
     *
     * Usage:
     * pashto_now()           // '۱ وری ۱۴۰۳'
     * pashto_now('Y/m/d')    // '1403/01/01'
     */
    function pashto_now(?string $format = null): string
    {
        $today = PashtoCalendar::now();

        if ($format) {
            return $today->format($format);
        }

        return $today->formatPashto();
    }
}

if (!function_exists('pashto_month_name')) {
    /**
     * Get Pashto month name by number
     *
     * Usage:
     * pashto_month_name(1)              // 'وری'
     * pashto_month_name(1, 'dari')      // 'حمل'
     */
    function pashto_month_name(int $month, ?string $language = null): string
    {
        return Months::name($month, $language);
    }
}

if (!function_exists('pashto_number')) {
    /**
     * Convert number to Pashto numerals
     *
     * Usage:
     * pashto_number(1403)   // '۱۴۰۳'
     * pashto_number(25)     // '۲۵'
     */
    function pashto_number($value): string
    {
        return PashtoDate::toPashtoNumber($value);
    }
}

if (!function_exists('is_pashto_holiday')) {
    /**
     * Check if a Shamsi date is a holiday
     *
     * Usage:
     * is_pashto_holiday(1, 1)   // true — Nowroz
     * is_pashto_holiday(3, 15)  // false
     */
   function is_pashto_holiday($month, $day, $year = null) {
    $year = $year ?? \Qadir\PashtoCalendar\PashtoCalendar::now()->year;
    return \Qadir\PashtoCalendar\Support\Holidays::isHoliday($year, $month, $day);
}
}
if (!function_exists('pcal_trans')) {
    /**
     * Get a translated string from the package’s JSON files.
     * Falls back to hardcoded Pashto if the key or locale file is missing.
     */
    function pcal_trans(string $key, string $locale = null): mixed
    {
        static $lines = [];

        $locale = $locale ?: app()->getLocale();

        // Load JSON for the current locale only once
        if (!isset($lines[$locale])) {
            $path = __DIR__ . '/../../resources/lang/' . $locale . '.json';
            if (file_exists($path)) {
                $decoded = json_decode(file_get_contents($path), true);
                $lines[$locale] = is_array($decoded) ? $decoded : [];
            } else {
                $lines[$locale] = [];
            }
        }

        // Hardcoded Pashto fallbacks
        $pashtoFallbacks = [
            'current_date' => 'اوسنۍ نیټه',
            'gregorian' => 'میلادي',
            'dark_mode' => 'تیاره',
            'light_mode' => 'رڼا',
            'converter' => 'Converter',
            'year' => 'Year',
            'today' => 'Today',
            'week_days' => ['ش','ی','د','س','چ','پ','ج'],
            'events_panel_title' => 'د دې میاشتې پیښې',
            'no_events' => 'هیڅ پیښه نشته — لاندې یوه زیاته کړه',
            'holidays_title' => 'رخصتۍ',
            'no_holidays' => 'په دې میاشت کې کومه رخصتي نشته',
            'day_info' => 'د ورځې معلومات',
            'event_title' => 'د پیښې سرلیک...',
            'event_description' => 'توضیحات (اختیاري)',
            'event_time' => 'وخت — لکه ۱۰:۰۰ (اختیاري)',
            'color_label' => 'رنګ:',
            'add_event' => '✦ پیښه زیاتول',
            'save' => '✓ خوندي کول',
            'update' => 'تازه کول',
            'saving' => 'خوندي کیږي...',
            'cancel' => 'بندول',
            'edit' => 'تغیرول',
            'delete' => 'حذف',
            'confirm_delete' => 'ایا ډاډه یاست؟',
            'demo_link' => '🎨 Demo',
            'package_name' => 'qadir/pashto-calendar',
            'failed_to_change_month' => 'د میاشت بدلول ناکام شول',
            'failed_to_save_event' => 'د پیښې خوندي کول ناکام شول',
            'failed_to_delete' => 'حذف ناکام شو',
            'optional' => 'اختیاري',
            'recurrence' => 'تکرار',
            'recurrence_none' => 'هیڅ',
            'recurrence_daily' => 'ورځنی',
            'recurrence_weekly' => 'اونیز',
            'recurrence_monthly' => 'میاشتنی',
            'recurrence_yearly' => 'کلنی',
            'recurrence_end_date' => 'د پای نیټه',
            'no_user_events' => 'پیښې نشته',
            'converter_title' => '📅 Date Converter',
'gregorian_to_pashto' => '🇪🇺 Gregorian → Pashto',
'pashto_to_gregorian' => '🇦🇫 Pashto → Gregorian',
'gregorian_date_label' => 'Gregorian Date (YYYY-MM-DD)',
'pashto_date_label' => 'Pashto Date (YYYY/MM/DD)',
'convert' => 'Convert',
'back_to_calendar' => '← Back to Calendar',
'pick_date_first' => 'Please pick a date first',
'invalid_format' => 'Invalid format. Use YYYY/MM/DD',
'conversion_failed' => 'Conversion failed',
'open_calendar' => 'Open calendar',
'eg_date' => 'e.g. 1404/02/15',
'converter_title'          => '📅 د نیټې بدلونکی',
'gregorian_to_pashto'     => '🇪🇺 میلادي → پښتو',
'pashto_to_gregorian'     => '🇦🇫 پښتو → میلادي',
'gregorian_date_label'    => 'میلادي نیټه (YYYY-MM-DD)',
'pashto_date_label'       => 'پښتو نیټه (YYYY/MM/DD)',
'convert'                 => 'بدلول',
'back_to_calendar'        => '← کلیندر ته واپس',
'pick_date_first'         => 'مهرباني وکړئ لومړی نیټه وټاکئ',
'invalid_format'          => 'ناسمه بڼه. YYYY/MM/DD وکاروئ',
'conversion_failed'       => 'بدلون ناکام شو',
'open_calendar'           => 'کلیندر پرانیزئ',
'eg_date'                 => 'بېلګه ۱۴۰۴/۰۲/۱۵',

// Demo page
'demo_features'           => 'ځانګړتیاوې',
'demo_months'             => 'میاشتې',
'demo_mini_cal'           => 'کوچنی کلیندر',
'demo_year_view'          => 'کلنی لید',
'demo_holidays'           => 'رخصتۍ',
'demo_install'            => 'نصبول',
'demo_calendar_link'      => 'کلیندر ➜',
'demo_hero_title'         => 'Afghan Solar Hijri Calendar',
'demo_hero_title_ps'      => 'د پښتو کلیندر',
'demo_hero_sub'           => 'د لاراویل لپاره بشپړ پښتو کلیندر پاکیج — RTL، پښتو شمیرې، رسمي رخصتۍ',
'demo_current_date'       => '✦ اوسنۍ نیټه ✦',
'demo_day'                => 'ورځ',
'demo_month'              => 'میاشت',
'demo_year'               => 'کال',
'demo_open_calendar'      => '📅 کلیندر خلاص کړه',
'demo_go_install'         => 'نصبول ←',
'demo_go_features'        => 'ځانګړتیاوې ←',
'demo_stats_months'       => 'میاشتې / Months',
'demo_stats_languages'    => 'ژبې / Languages',
'demo_stats_holidays'     => 'رخصتۍ / Holidays',
'demo_stats_events'       => 'پیښې / Events',
'demo_section_features'   => '✦ ځانګړتیاوې',
'demo_features_title'     => 'هر هغه شی چې ته ورته اړتیا لرې',
'demo_feat_convert_title' => 'نیټه بدلول',
'demo_feat_convert_desc'  => 'د میلادي نیټې د شمسي هجري کلیندر سره بشپړ تبدیلي — د کاربن سره بشپړ مدغم',
'demo_feat_lang_title'    => 'درې ژبې',
'demo_feat_lang_desc'     => 'پښتو، دري، او انګلیسي — د یوې config بدلون سره ټوله اپ بدلیږي',
'demo_feat_events_title'  => 'پیښې (Events)',
'demo_feat_events_desc'   => 'د ډیټابیس پر بنسټ پیښې — د Alpine.js سره د سمدستي UI اپدیت',
'demo_feat_validation_title' => 'د Validation قوانین',
'demo_feat_validation_desc'  => 'pashto_date، pashto_date_format، before_pashto_date، after_pashto_date',
'demo_feat_directives_title' => 'Blade Directives',
'demo_feat_directives_desc'  => '&#64;pashtoDate، &#64;pashtoNow، &#64;pashtoNumber، &#64;ifHoliday — مستقیم view کې وکاروه',
'demo_feat_carbon_title' => 'Carbon Macros',
'demo_feat_carbon_desc'  => 'now()->toPashto()، now()->toPashtoString()، Carbon::parsePashto(1403,1,1)',
'demo_section_months'    => '✦ میاشتې',
'demo_months_title'      => 'د کال ۱۲ میاشتې — درې ژبو کې',
'demo_current_label'     => '← اوس',
'demo_section_manip'     => '✦ نیټه اداره کول',
'demo_manip_title'       => 'د نیټې مینیپولیشن — Carbon-Style',
'demo_yes'               => 'هو ✓',
'demo_no'                => 'نه ✗',
'demo_section_mini'      => '✦ Mini Calendar Widget',
'demo_mini_title'        => 'کوچنی کلیندر — هر ځای کې وکاروه',
'demo_mini_howto_title'  => '🚀 د استعمال طریقه',
'demo_mini_howto_desc'   => 'د <code style="background: rgba(255,255,255,0.08); padding:2px 8px; border-radius:6px;">&lt;x-pashto-mini-calendar /&gt;</code> کامپونینټ په خپل هر Blade view کې وکاروئ.<br>د کال او میاشت د مشخصولو لپاره: <code style="background: rgba(255,255,255,0.08); padding:2px 8px; border-radius:6px;">&lt;x-pashto-mini-calendar :year="1405" :month="3" /&gt;</code><br>دا وېجیټ نن ورځ او رخصتۍ هایلایټ کوي او د میاشتې بشپړ کالندر ته لېږدوي.',
'demo_section_year'      => '✦ Year View',
'demo_year_title'        => 'د ټول کال عمومي کتنه',
'demo_year_button'       => 'کال کلنی لید وګورئ',
'demo_year_about_title'  => '📖 د کلني لید په اړه',
'demo_year_about_desc'   => 'د <strong>Year View</strong> تاسو ته د یوه کال ټولې ۱۲ میاشتې په یوه ځای کې ښیي.<br>هره میاشت په یوه کارډ کې د ورځو سره ښودل کیږي، نن ورځ او رخصتۍ هایلایټ شوي دي.<br>د هرې میاشتې په کلیک کولو سره د هماغې میاشتې بشپړ کالندر خلاصیږي.<br>د کلونو ترمینځ د تګ لپاره د <strong>Previous</strong> او <strong>Next</strong> تڼۍ وکاروئ.',
'demo_section_holidays'  => '✦ رسمي رخصتۍ',
'demo_holidays_title'    => 'د افغانستان ملي رخصتۍ',
'demo_section_directives' => '✦ Blade Directives',
'demo_directives_title'  => 'مستقیم د View کې وکاروه',
'demo_section_install'   => '✦ نصبول',
'demo_install_title'     => 'د یوې کمانډ سره نصب کړه',
'demo_copy'              => 'کاپي',
'demo_copied'            => '✓ کاپي شو',
'demo_footer_built'      => 'Built by Abdul Qadir Nasrat — Laravel :laravel — PHP :php',
'demo_footer_calendar'   => '📅 د کلیندر لیدل',
'demo_footer_demo'       => '🔄 Demo بیا لوده کړه',
// year view page
'year_view_page_title' => 'Year View – Pashto Calendar',
'year_view_heading'    => 'Year',
'previous'             => 'Previous',
'next'                 => 'Next',
'view_month'           => 'View Month',
'load_year_failed'     => 'Failed to load year data',

'view_full_calendar' => 'بشپړ کلیندر وګورئ',
        ];

        return $lines[$locale][$key]
               ?? $pashtoFallbacks[$key]
               ?? $key;
    }
}
<?php

return [
        /*
    |--------------------------------------------------------------------------
    | Language
    |--------------------------------------------------------------------------
    | 'pashto' = native Pashto month names  (وری، غویی ...)
    | 'dari'   = Dari/Arabic month names    (حمل، ثور ...)
    | 'en'     = English month names        (Hamal, Saur ...)
    */
    'language' => 'pashto',

    /*
    |--------------------------------------------------------------------------
    | Language
    |--------------------------------------------------------------------------
    | Controls which month names are used across the whole package
    |
    | 'pashto' = native Pashto month names  (وری، غویی، غبرګولی ...)
    | 'dari'   = Dari/Arabic month names    (حمل، ثور، جوزا ...)
    */
    'language' => 'pashto',

    /*
    |--------------------------------------------------------------------------
    | Pashto Numbers
    |--------------------------------------------------------------------------
    | Controls how numbers are displayed across the whole package
    |
    | true  = Pashto/Eastern Arabic numerals  ۱، ۲، ۳
    | false = Western Arabic numerals         1, 2, 3
    */
    'pashto_numbers' => true,

    /*
    |--------------------------------------------------------------------------
    | RTL Support
    |--------------------------------------------------------------------------
    | Controls text direction across the whole package
    |
    | true  = Right to Left (Pashto / Dari)
    | false = Left to Right
    */
    'rtl' => true,

    /*
    |--------------------------------------------------------------------------
    | Font Family
    |--------------------------------------------------------------------------
    | Default font used in calendar views
    */
    'font' => 'Noto Naskh Arabic, serif',

    /*
    |--------------------------------------------------------------------------
    | First Day of Week
    |--------------------------------------------------------------------------
    | Controls which day the calendar week starts from
    |
    | 6 = Saturday  (Afghan standard)
    | 0 = Sunday
    | 1 = Monday
    */
    'first_day_of_week' => 6,

    /*
    |--------------------------------------------------------------------------
    | Timezone
    |--------------------------------------------------------------------------
    | Default timezone for date calculations
    */
    'timezone' => 'Asia/Kabul',

    /*
    |--------------------------------------------------------------------------
    | Demo Route
    |--------------------------------------------------------------------------
    | true  = /pashto-calendar route is active (good for development)
    | false = disable in production for security
    |
    | Routes available when true:
    | GET  /pashto-calendar         → calendar UI
    | GET  /pashto-calendar/demo    → feature demo page
    */
    'demo_route' => true,

];
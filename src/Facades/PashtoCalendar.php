<?php

namespace Qadir\PashtoCalendar\Facades;

use Illuminate\Support\Facades\Facade;

class PashtoCalendar extends Facade
{
    /**
     * Get the registered name of the component
     */
    protected static function getFacadeAccessor(): string
    {
        return 'pashto-calendar';
    }
}
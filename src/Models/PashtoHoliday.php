<?php

namespace Qadir\PashtoCalendar\Models;

use Illuminate\Database\Eloquent\Model;

class PashtoHoliday extends Model
{
    protected $fillable = [
        'year', 'month', 'day', 'name', 'name_en',
        'gregorian_date', 'is_recurring', 'type', 'raw_data'
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
        'raw_data' => 'array',
    ];
}
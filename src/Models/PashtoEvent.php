<?php

namespace Qadir\PashtoCalendar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PashtoEvent extends Model
{
    protected $table = 'pashto_events';

    protected $fillable = [
        'title',
        'description',
        'year',
        'month',
        'day',
        'time',
        'color',
    ];

    // ============================================================
    // ✅ SCOPE — filter by Pashto year
    //
    // Usage:
    // PashtoEvent::inPashtoYear(1403)->get()
    // ============================================================

    public function scopeInPashtoYear(Builder $query, int $year): Builder
    {
        return $query->where('year', $year);
    }

    // ============================================================
    // ✅ SCOPE — filter by Pashto month
    //
    // Usage:
    // PashtoEvent::inPashtoMonth(1403, 1)->get()
    // ============================================================

    public function scopeInPashtoMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->where('year', $year)->where('month', $month);
    }

    // ============================================================
    // ✅ SCOPE — filter by exact Pashto date
    //
    // Usage:
    // PashtoEvent::onPashtoDate(1403, 1, 1)->get()
    // ============================================================

    public function scopeOnPashtoDate(Builder $query, int $year, int $month, int $day): Builder
    {
        return $query->where('year', $year)
                     ->where('month', $month)
                     ->where('day', $day);
    }

    // ============================================================
    // ✅ SCOPE — filter by color
    //
    // Usage:
    // PashtoEvent::ofColor('red')->get()
    // ============================================================

    public function scopeOfColor(Builder $query, string $color): Builder
    {
        return $query->where('color', $color);
    }

    // ============================================================
    // ✅ SCOPE — today's events
    //
    // Usage:
    // PashtoEvent::today()->get()
    // ============================================================

    public function scopeToday(Builder $query): Builder
    {
        $today = \Qadir\PashtoCalendar\PashtoCalendar::now();

        return $query->where('year',  $today->year)
                     ->where('month', $today->month)
                     ->where('day',   $today->day);
    }
}
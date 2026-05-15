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
        'recurrence',
        'recurrence_end_date',
    ];

    protected $casts = [
        'recurrence_end_date' => 'date',
    ];

    // ============================================================
    // ✅ SCOPES
    // ============================================================

    public function scopeInPashtoYear(Builder $query, int $year): Builder
    {
        return $query->where('year', $year);
    }

    public function scopeInPashtoMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopeOnPashtoDate(Builder $query, int $year, int $month, int $day): Builder
    {
        return $query->where('year', $year)
                     ->where('month', $month)
                     ->where('day', $day);
    }

    public function scopeOfColor(Builder $query, string $color): Builder
    {
        return $query->where('color', $color);
    }

    public function scopeToday(Builder $query): Builder
    {
        $today = \Qadir\PashtoCalendar\PashtoCalendar::now();

        return $query->where('year',  $today->year)
                     ->where('month', $today->month)
                     ->where('day',   $today->day);
    }

    // ============================================================
    // ✅ RECURRENCE LOGIC
    // ============================================================

    public static function getOccurrencesForMonth(int $year, int $month): array
    {
        $occurrences = [];

        $events = self::where(function ($query) use ($year, $month) {
            // Non‑recurring events for the exact month
            $query->where(function ($q) use ($year, $month) {
                $q->where('year', $year)
                  ->where('month', $month)
                  ->where('recurrence', 'none');
            });
            // Recurring events that started in this month or earlier
            $query->orWhere('recurrence', '!=', 'none');
        })->get();

        foreach ($events as $event) {
            if ($event->recurrence === 'none') {
                $occurrences[] = $event;
                continue;
            }

            // Recurring: generate occurrences within the requested month
            $currentYear = $event->year;
            $currentMonth = $event->month;
            $currentDay = $event->day;

            while (true) {
                if ($currentYear > $year || ($currentYear == $year && $currentMonth > $month)) {
                    break;
                }

                if ($currentYear == $year && $currentMonth == $month) {
                    // Check recurrence end date if set
                    if ($event->recurrence_end_date) {
                        $gregorianEnd = to_gregorian($year, $month, $currentDay);
                        if ($gregorianEnd > $event->recurrence_end_date) {
                            break;
                        }
                    }
                    // Clone the event and preserve the original ID
                    $occurrence = $event->replicate();
                    $occurrence->id = $event->id;   // ← critical for edit/delete buttons
                    $occurrence->year = $year;
                    $occurrence->month = $month;
                    $occurrence->day = $currentDay;
                    $occurrences[] = $occurrence;
                }

                // Advance to next occurrence
                switch ($event->recurrence) {
                    case 'daily':
                        $pashtoDate = new \Qadir\PashtoCalendar\PashtoDate($currentYear, $currentMonth, $currentDay);
                        $next = $pashtoDate->addDays(1);
                        $currentYear = $next->year;
                        $currentMonth = $next->month;
                        $currentDay = $next->day;
                        break;

                    case 'weekly':
                        $pashtoDate = new \Qadir\PashtoCalendar\PashtoDate($currentYear, $currentMonth, $currentDay);
                        $next = $pashtoDate->addDays(7);
                        $currentYear = $next->year;
                        $currentMonth = $next->month;
                        $currentDay = $next->day;
                        break;

                    case 'monthly':
                        $pashtoDate = new \Qadir\PashtoCalendar\PashtoDate($currentYear, $currentMonth, $currentDay);
                        $next = $pashtoDate->addMonths(1);
                        $currentYear = $next->year;
                        $currentMonth = $next->month;
                        $currentDay = min($currentDay, $pashtoDate->daysInMonth($next->year, $next->month));
                        break;

                    case 'yearly':
                        $pashtoDate = new \Qadir\PashtoCalendar\PashtoDate($currentYear, $currentMonth, $currentDay);
                        $next = $pashtoDate->addYears(1);
                        $currentYear = $next->year;
                        break;
                }
            }
        }

        return $occurrences;
    }
}
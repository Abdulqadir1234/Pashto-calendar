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
    // SCOPES
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

    public function scopeToday(Builder $query): Builder
    {
        $today = \Qadir\PashtoCalendar\PashtoCalendar::now();

        return $query->where('year', $today->year)
            ->where('month', $today->month)
            ->where('day', $today->day);
    }

    // ============================================================
    // RECURRENCE FIXED
    // ============================================================

    public static function getOccurrencesForMonth(int $year, int $month): array
    {
        $occurrences = [];

        $events = self::all();

        foreach ($events as $event) {

            // NON-RECURRING
            if ($event->recurrence === 'none') {
                if ($event->year == $year && $event->month == $month) {
                    $occurrences[] = $event;
                }
                continue;
            }

            // START DATE
            $current = new \Qadir\PashtoCalendar\PashtoDate(
                $event->year,
                $event->month,
                $event->day
            );

            $targetMonthDays = \Qadir\PashtoCalendar\PashtoCalendar::monthLength($year, $month);

            // LOOP SAFE LIMIT (prevents infinite loop)
            for ($i = 0; $i < 500; $i++) {

                // STOP if passed target month
                if (
                    $current->year > $year ||
                    ($current->year == $year && $current->month > $month)
                ) {
                    break;
                }

                // END DATE CHECK
                if ($event->recurrence_end_date) {
                    [$endY, $endM, $endD] = explode('-', $event->recurrence_end_date);

                    if (
                        $current->year > $endY ||
                        ($current->year == $endY && $current->month > $endM) ||
                        ($current->year == $endY && $current->month == $endM && $current->day > $endD)
                    ) {
                        break;
                    }
                }

                // MATCH TARGET MONTH
                if ($current->year == $year && $current->month == $month) {

                    $occurrence = $event->replicate();
                    $occurrence->id = $event->id;
                    $occurrence->year = $year;
                    $occurrence->month = $month;
                    $occurrence->day = $current->day;

                    $occurrences[] = $occurrence;
                }

                // MOVE NEXT
                switch ($event->recurrence) {
                    case 'daily':
                        $current = $current->addDays(1);
                        break;

                    case 'weekly':
                        $current = $current->addDays(7);
                        break;

                    case 'monthly':
                        $current = $current->addMonths(1);
                        break;

                    case 'yearly':
                        $current = $current->addYears(1);
                        break;

                    default:
                        break 2;
                }
            }
        }

        return $occurrences;
    }
}
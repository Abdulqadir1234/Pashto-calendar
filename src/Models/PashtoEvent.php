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

        // Get total days in this month
        $daysInMonth = (new \Qadir\PashtoCalendar\PashtoDate($year, $month, 1))
            ->daysInMonth($year, $month);

        for ($day = 1; $day <= $daysInMonth; $day++) {

            // Skip days before event start
            if (
                $year < $event->year ||
                ($year == $event->year && $month < $event->month) ||
                ($year == $event->year && $month == $event->month && $day < $event->day)
            ) {
                continue;
            }

            // Check end date
            if ($event->recurrence_end_date) {
                $currentGregorian = to_gregorian($year, $month, $day);
                if ($currentGregorian > $event->recurrence_end_date) {
                    continue;
                }
            }

            $include = false;

            switch ($event->recurrence) {

                case 'none':
                    $include = (
                        $year == $event->year &&
                        $month == $event->month &&
                        $day == $event->day
                    );
                    break;

                case 'daily':
                    $include = true;
                    break;

                case 'weekly':
                    $start = new \Qadir\PashtoCalendar\PashtoDate(
                        $event->year,
                        $event->month,
                        $event->day
                    );

                    $current = new \Qadir\PashtoCalendar\PashtoDate(
                        $year,
                        $month,
                        $day
                    );

                    $diff = $start->diffInDays($current);
                    $include = $diff % 7 === 0;
                    break;

                case 'monthly':
                    $include = ($day == $event->day);
                    break;

                case 'yearly':
                    $include = (
                        $month == $event->month &&
                        $day == $event->day
                    );
                    break;
            }

            if ($include) {
                $occurrence = $event->replicate();
                $occurrence->id = $event->id;
                $occurrence->year = $year;
                $occurrence->month = $month;
                $occurrence->day = $day;

                $occurrences[] = $occurrence;
            }
        }
    }

    return $occurrences;
}
}
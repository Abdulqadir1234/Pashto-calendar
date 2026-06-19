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

    // ✅ IMPORTANT: only fetch relevant events
    $events = self::query()->get();

    foreach ($events as $event) {

        $eventStart = new \Qadir\PashtoCalendar\PashtoDate(
            $event->year,
            $event->month,
            $event->day
        );

        $daysInMonth = (new \Qadir\PashtoCalendar\PashtoDate($year, $month, 1))
            ->daysInMonth();

        for ($day = 1; $day <= $daysInMonth; $day++) {

            $currentDate = new \Qadir\PashtoCalendar\PashtoDate($year, $month, $day);

            // ✅ skip before start
            if ($currentDate->isBefore($eventStart)) {
                continue;
            }

            // ✅ check recurrence end date
            if ($event->recurrence_end_date) {
                [$gy, $gm, $gd] = \Qadir\PashtoCalendar\Support\JalaliConverter::toGregorian(
                    $year, $month, $day
                );

                $currentCarbon = \Carbon\Carbon::create($gy, $gm, $gd);
                if ($currentCarbon->gt($event->recurrence_end_date)) {
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
                    $diff = $eventStart->diffInDays($currentDate);
                    $include = ($diff % 7 === 0);
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
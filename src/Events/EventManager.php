<?php

namespace Qadir\PashtoCalendar\Events;

use Qadir\PashtoCalendar\Models\PashtoEvent;

class EventManager
{
    // ============================================================
    // ✅ GET EVENTS FOR A SPECIFIC DATE
    // ============================================================

    public static function forDate(int $year, int $month, int $day)
    {
        return PashtoEvent::where('year', $year)
            ->where('month', $month)
            ->where('day', $day)
            ->get();
    }

    // ============================================================
    // ✅ GET ALL EVENTS FOR A MONTH
    // ============================================================

    public static function forMonth(int $year, int $month)
    {
        return PashtoEvent::where('year', $year)
            ->where('month', $month)
            ->orderBy('day')
            ->get();
    }

    // ============================================================
    // ✅ GET ALL EVENTS FOR A YEAR
    // ============================================================

    public static function forYear(int $year)
    {
        return PashtoEvent::where('year', $year)
            ->orderBy('month')
            ->orderBy('day')
            ->get();
    }

    // ============================================================
    // ✅ CREATE EVENT
    // ============================================================

    public static function add(array $data): PashtoEvent
    {
        return PashtoEvent::create([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'year'        => $data['year'],
            'month'       => $data['month'],
            'day'         => $data['day'],
            'time'        => $data['time'] ?? null,
            'color'       => $data['color'] ?? 'blue',
        ]);
    }

    // ============================================================
    // ✅ UPDATE EVENT
    // ============================================================

    public static function update(int $id, array $data): bool
    {
        $event = PashtoEvent::find($id);

        if (!$event) {
            return false;
        }

        return $event->update([
            'title'       => $data['title']       ?? $event->title,
            'description' => $data['description'] ?? $event->description,
            'year'        => $data['year']         ?? $event->year,
            'month'       => $data['month']        ?? $event->month,
            'day'         => $data['day']          ?? $event->day,
            'time'        => $data['time']         ?? $event->time,
            'color'       => $data['color']        ?? $event->color,
        ]);
    }

    // ============================================================
    // ✅ DELETE A SINGLE EVENT BY ID
    // ============================================================

    public static function delete(int $id): bool
    {
        $event = PashtoEvent::find($id);

        if (!$event) {
            return false;
        }

        return $event->delete();
    }

    // ============================================================
    // ✅ CLEAR — DELETE ALL EVENTS (BUG FIX — was missing)
    // ============================================================

    public static function clear(): void
    {
        PashtoEvent::truncate();
    }

    // ============================================================
    // ✅ CLEAR EVENTS FOR A SPECIFIC DATE ONLY
    // ============================================================

    public static function clearDate(int $year, int $month, int $day): void
    {
        PashtoEvent::where('year', $year)
            ->where('month', $month)
            ->where('day', $day)
            ->delete();
    }

    // ============================================================
    // ✅ CLEAR EVENTS FOR A SPECIFIC MONTH ONLY
    // ============================================================

    public static function clearMonth(int $year, int $month): void
    {
        PashtoEvent::where('year', $year)
            ->where('month', $month)
            ->delete();
    }

    // ============================================================
    // ✅ CHECK IF A DATE HAS EVENTS
    // ============================================================

    public static function hasEvents(int $year, int $month, int $day): bool
    {
        return PashtoEvent::where('year', $year)
            ->where('month', $month)
            ->where('day', $day)
            ->exists();
    }

    // ============================================================
    // ✅ COUNT EVENTS FOR A DATE
    // ============================================================

    public static function countForDate(int $year, int $month, int $day): int
    {
        return PashtoEvent::where('year', $year)
            ->where('month', $month)
            ->where('day', $day)
            ->count();
    }

    // ============================================================
    // ✅ GET ALL EVENTS
    // ============================================================

    public static function all()
    {
        return PashtoEvent::orderBy('year')
            ->orderBy('month')
            ->orderBy('day')
            ->get();
    }
}
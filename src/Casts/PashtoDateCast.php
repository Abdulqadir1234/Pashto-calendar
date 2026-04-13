<?php

namespace Qadir\PashtoCalendar\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Qadir\PashtoCalendar\PashtoCalendar;
use Qadir\PashtoCalendar\PashtoDate;

class PashtoDateCast implements CastsAttributes
{
    // ============================================================
    // ✅ GET — called when reading from model
    // Converts Gregorian DB value → PashtoDate
    // ============================================================

    public function get(Model $model, string $key, mixed $value, array $attributes): ?PashtoDate
    {
        if ($value === null) {
            return null;
        }

        return PashtoCalendar::parse($value);
    }

    // ============================================================
    // ✅ SET — called when saving to model
    // Converts PashtoDate → Gregorian string for DB storage
    // ============================================================

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        // If already a PashtoDate — convert to Gregorian
        if ($value instanceof PashtoDate) {
            return to_gregorian($value->year, $value->month, $value->day);
        }

        // If string or Carbon — store as-is
        return $value;
    }
}
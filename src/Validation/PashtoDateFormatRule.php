<?php

namespace Qadir\PashtoCalendar\Validation;

use Illuminate\Contracts\Validation\Rule;

class PashtoDateFormatRule implements Rule
{
    protected string $format;
    protected string $message = '';

    public function __construct(string $format = 'Y/m/d')
    {
        $this->format = $format;
    }

    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) {
            $this->message = 'نیټه باید د متن په بڼه وي';
            return false;
        }

        $pattern = $this->buildPattern($this->format);

        if (!preg_match($pattern, $value)) {
            $this->message = "د نیټې بڼه باید {$this->format} وي";
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->message ?: 'د نیټې بڼه سمه نه ده';
    }

    private function buildPattern(string $format): string
    {
        // ✅ Step 1 — replace tokens with unique placeholders
        // that do NOT contain Y, m, or d letters
        $pattern = $format;
        $pattern = str_replace('Y', '__YEAR__',  $pattern);
        $pattern = str_replace('m', '__MONTH__', $pattern);
        $pattern = str_replace('d', '__DAY__',   $pattern);

        // ✅ Step 2 — escape any remaining special regex characters
        // like / - .
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = str_replace('-', '\-', $pattern);
        $pattern = str_replace('.', '\.', $pattern);

        // ✅ Step 3 — replace placeholders with actual regex
        // No risk of double-replacement now
        $pattern = str_replace('__YEAR__',  '[0-9]{4}',   $pattern);
        $pattern = str_replace('__MONTH__', '[0-9]{1,2}', $pattern);
        $pattern = str_replace('__DAY__',   '[0-9]{1,2}', $pattern);

        return '/^' . $pattern . '$/';
    }
}
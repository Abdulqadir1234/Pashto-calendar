<!DOCTYPE html>
<html lang="ps" dir="{{ ($rtl ?? true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>د پښتو کلیندر</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;600;700&display=swap" rel="stylesheet">

   <style>
    /* ============================================================
       DARK MODE (DEFAULT)
    ============================================================ */
    :root {
        --accent-soft: #93c5fd;
        --night:  #04080f;
        --navy:   #0d1b35;
        --gold:   #f0a500;
        --gold2:  #ffd166;
        --teal:   #06d6a0;
        --rose:   #ef476f;
        --glass:  rgba(255,255,255,0.04);
        --border: rgba(240,165,0,0.18);
        --text:   #e8e0d0;
        --muted:  rgba(232,224,208,0.5);
        --white:  #ffffff;
        --black:  #000000;
        --btn-save-bg: linear-gradient(135deg, var(--gold), #c87800);
        --btn-save-color: #000;
    }

    /* ============================================================
       LIGHT MODE OVERRIDES
    ============================================================ */
    body.light {
        --accent-soft: #60a5fa;
        --night:  #f1f5f9;
        --navy:   #ffffff;
        --gold:   #b45309;
        --gold2:  #d97706;
        --teal:   #0d9488;
        --rose:   #e11d48;
        --glass:  rgba(0,0,0,0.03);
        --border: rgba(180,83,9,0.25);
        --text:   #0f172a;
        --muted:  #475569;
        --white:  #0f172a;
        --black:  #ffffff;
        --btn-save-bg: linear-gradient(135deg, #d97706, #b45309);
        --btn-save-color: #ffffff;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        background: var(--night);
        color: var(--text);
        font-family: '{{ $font ?? "Noto Naskh Arabic, serif" }}';
        min-height: 100vh;
    }

    /* ============================================================
       CLOCK BAR
    ============================================================ */
    .clock-bar {
        background: rgba(8,13,26,0.95);
        border-bottom: 1px solid var(--border);
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        backdrop-filter: blur(20px);
        position: sticky;
        top: 0;
        z-index: 50;
    }

    body.light .clock-bar {
        background: rgba(255,255,255,0.9);
    }

    .clock-time {
        font-size: 28px;
        font-weight: 700;
        background: linear-gradient(135deg, var(--gold), var(--gold2));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: 2px;
        direction: ltr;
    }

    .clock-date-label {
        font-size: 13px;
        color: var(--muted);
    }

    .clock-pashto-date {
        font-size: 16px;
        font-weight: 700;
        color: var(--gold2);
    }

    .clock-seconds {
        font-size: 13px;
        color: var(--teal);
        direction: ltr;
        opacity: 0.7;
    }

    /* Theme toggle button */
    .theme-toggle {
        background: var(--glass);
        border: 1px solid var(--border);
        color: var(--text);
        border-radius: 30px;
        padding: 6px 14px;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .theme-toggle:hover {
        background: var(--border);
    }

    /* Gregorian date */
    .gregorian-date {
        font-size: 14px;
        color: var(--text);
        direction: ltr;
    }

    /* ============================================================
       CALENDAR WRAPPER
    ============================================================ */
    .cal-wrapper {
        max-width: 900px;
        margin: 0 auto;
        padding: 24px 16px;
    }

    /* ============================================================
       HEADER
    ============================================================ */
    .cal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--glass);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 16px 24px;
        margin-bottom: 20px;
    }

    .nav-btn {
        background: rgba(240,165,0,0.1);
        border: 1px solid rgba(240,165,0,0.25);
        color: var(--gold);
        border-radius: 10px;
        padding: 8px 18px;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-weight: bold;
    }

    .nav-btn:hover {
        background: rgba(240,165,0,0.2);
        transform: scale(1.05);
    }

    .cal-month-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
    }

    /* ============================================================
       WEEK HEADERS
    ============================================================ */
    .week-headers {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
        margin-bottom: 8px;
    }

    .week-header {
        text-align: center;
        font-size: 12px;
        font-weight: 700;
        color: var(--muted);
        padding: 8px 4px;
        letter-spacing: 1px;
    }

    .week-header.friday { color: var(--gold); }

    /* ============================================================
       CALENDAR GRID
    ============================================================ */
    .cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
        margin-bottom: 28px;
    }

    .cal-empty {
        height: 80px;
        border-radius: 12px;
    }

    .cal-day {
        height: 80px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.06);
        background: rgba(255,255,255,0.03);
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 8px 6px;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .cal-day:hover {
        background: rgba(240,165,0,0.08);
        border-color: rgba(240,165,0,0.35);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    }

    .cal-day.is-today {
        background: rgba(240,165,0,0.12);
        border-color: rgba(240,165,0,0.5);
        box-shadow: 0 0 20px rgba(240,165,0,0.15);
    }

    .cal-day.is-friday {
        background: rgba(6,214,160,0.05);
        border-color: rgba(6,214,160,0.2);
    }

    .cal-day.is-holiday {
        background: rgba(239,71,111,0.06);
        border-color: rgba(239,71,111,0.25);
    }

    .cal-day.has-events {
        border-color: rgba(240,165,0,0.3);
    }

    .day-number {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        line-height: 1;
        margin-bottom: 4px;
    }

    .cal-day.is-today .day-number { color: var(--gold2); }
    .cal-day.is-friday .day-number { color: var(--teal); }
    .cal-day.is-holiday .day-number { color: var(--rose); }

    /* Today dot */
    .today-dot {
        position: absolute;
        top: 5px;
        left: 5px;
        width: 6px;
        height: 6px;
        background: var(--gold);
        border-radius: 50%;
        animation: blink 2s ease-in-out infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    /* Holiday label */
    .holiday-label {
        font-size: 8px;
        color: var(--rose);
        text-align: center;
        line-height: 1.1;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        padding: 0 2px;
    }

    /* Event dots */
    .event-dots {
        display: flex;
        gap: 3px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 2px;
    }

    .event-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    /* ============================================================
       LIGHT MODE OVERRIDES FOR GRID & PANELS
    ============================================================ */
    body.light .cal-day {
        background: #ffffff;
        border-color: rgba(0,0,0,0.08);
    }
    body.light .cal-day:hover {
        background: rgba(217,119,6,0.08);
        border-color: rgba(217,119,6,0.3);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }
    body.light .cal-day.is-today {
        background: rgba(217,119,6,0.12);
        border-color: rgba(217,119,6,0.5);
        box-shadow: 0 0 20px rgba(217,119,6,0.15);
    }
    body.light .cal-day.is-friday {
        background: rgba(13,148,136,0.05);
        border-color: rgba(13,148,136,0.2);
    }
    body.light .cal-day.is-holiday {
        background: rgba(225,29,72,0.04);
        border-color: rgba(225,29,72,0.2);
    }
    body.light .day-number { color: #0f172a; }
    body.light .cal-day.is-today .day-number { color: #b45309; }
    body.light .cal-day.is-friday .day-number { color: #0d9488; }
    body.light .cal-day.is-holiday .day-number { color: #e11d48; }

    /* ============================================================
       EVENTS PANEL
    ============================================================ */
    .events-panel {
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 28px;
    }
    body.light .events-panel {
        background: #ffffff;
        border-color: rgba(0,0,0,0.08);
    }

    .events-panel-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--gold);
        letter-spacing: 2px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .events-panel-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 12px;
    }

    .event-info-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        transition: all 0.2s;
    }
    body.light .event-info-card {
        background: #f8fafc;
        border-color: rgba(0,0,0,0.06);
    }

    .event-info-card:hover {
        background: rgba(255,255,255,0.07);
        transform: translateY(-1px);
    }
    body.light .event-info-card:hover {
        background: rgba(217,119,6,0.05);
    }

    .event-color-bar {
        width: 4px;
        height: 100%;
        min-height: 36px;
        border-radius: 4px;
        flex-shrink: 0;
    }

    .event-info-day {
        font-size: 11px;
        color: var(--muted);
        margin-bottom: 3px;
    }

    .event-info-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
    }

    .event-info-desc {
        font-size: 12px;
        color: var(--muted);
        margin-top: 2px;
    }

    /* ============================================================
       MODAL
    ============================================================ */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(8px);
        z-index: 40;
    }

    .modal-container {
        position: fixed;
        inset: 0;
        z-index: 50;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-box {
        background: var(--navy);
        border: 1px solid var(--border);
        border-radius: 20px;
        width: 100%;
        max-width: 400px;
        overflow: hidden;
        box-shadow: 0 30px 80px rgba(0,0,0,0.6),
                    0 0 40px rgba(240,165,0,0.08);
    }

    body.light .modal-box {
        background: #ffffff;
        box-shadow: 0 30px 80px rgba(0,0,0,0.15);
    }

    .modal-header {
        background: linear-gradient(135deg, rgba(240,165,0,0.15), rgba(6,214,160,0.08));
        border-bottom: 1px solid var(--border);
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    body.light .modal-header {
        background: linear-gradient(135deg, rgba(180,83,9,0.1), rgba(13,148,136,0.08));
    }

    .modal-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
    }

    .modal-day-badge {
        background: rgba(240,165,0,0.2);
        border: 1px solid rgba(240,165,0,0.4);
        border-radius: 8px;
        padding: 4px 12px;
        color: var(--gold2);
        font-size: 14px;
        font-weight: 700;
    }

    body.light .modal-day-badge {
        background: rgba(180,83,9,0.1);
        border-color: rgba(180,83,9,0.3);
        color: #b45309;
    }

    .modal-close {
        background: rgba(255,255,255,0.08);
        border: none;
        color: var(--muted);
        width: 28px;
        height: 28px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .modal-close:hover { background: rgba(255,255,255,0.15); color: #fff; }

    body.light .modal-close {
        background: rgba(0,0,0,0.08);
        color: var(--text);
    }
    body.light .modal-close:hover {
        background: rgba(0,0,0,0.15);
        color: #0f172a;
    }

    .modal-body { padding: 20px 24px; }

    /* Events list in modal */
    .modal-event-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        border-radius: 10px;
        margin-bottom: 8px;
        color: var(--text);
        font-size: 14px;
    }

    .modal-event-delete {
        background: rgba(255,255,255,0.15);
        border: none;
        color: rgba(255,255,255,0.7);
        border-radius: 50%;
        width: 22px;
        height: 22px;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .modal-event-delete:hover { background: rgba(239,71,111,0.4); color: #fff; }

    body.light .modal-event-delete {
        background: rgba(0,0,0,0.1);
        color: var(--text);
    }
    body.light .modal-event-delete:hover {
        background: rgba(225,29,72,0.3);
        color: #ffffff;
    }

    .modal-empty {
        text-align: center;
        color: var(--muted);
        font-size: 13px;
        padding: 16px 0;
    }

    /* Form */
    .modal-form { border-top: 1px solid var(--border); padding-top: 16px; margin-top: 4px; }

    .modal-input {
        width: 100%;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 10px 14px;
        color: var(--text);
        font-size: 14px;
        margin-bottom: 10px;
        font-family: 'Noto Naskh Arabic', serif;
        outline: none;
        transition: border-color 0.2s;
    }

    body.light .modal-input {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .modal-input:focus { border-color: var(--gold); }
    .modal-input::placeholder { color: var(--muted); }

    .modal-color-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .modal-color-label { font-size: 13px; color: var(--muted); }

    .color-swatches {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .color-swatch {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
    }

    .color-swatch:hover, .color-swatch.active {
        transform: scale(1.2);
        border-color: #fff;
    }

    .modal-btns { display: flex; gap: 10px; }

    .btn-save {
        flex: 1;
        background: var(--btn-save-bg);
        color: var(--btn-save-color);
        font-weight: 700;
        padding: 10px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-family: 'Noto Naskh Arabic', serif;
        transition: all 0.2s;
    }

    .btn-save:hover:not(:disabled) { box-shadow: 0 4px 16px rgba(240,165,0,0.4); }
    body.light .btn-save:hover:not(:disabled) { box-shadow: 0 4px 16px rgba(180,83,9,0.4); }

    .btn-save:disabled { opacity: 0.5; cursor: not-allowed; }

    .btn-cancel {
        flex: 1;
        background: rgba(255,255,255,0.06);
        color: var(--text);
        font-weight: 600;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.1);
        cursor: pointer;
        font-size: 14px;
        font-family: 'Noto Naskh Arabic', serif;
        transition: all 0.2s;
    }

    body.light .btn-cancel {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .btn-cancel:hover { background: rgba(255,255,255,0.1); }
    body.light .btn-cancel:hover { background: #e2e8f0; }

    /* Holiday badge in modal */
    .modal-holiday-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(239,71,111,0.12);
        border: 1px solid rgba(239,71,111,0.3);
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 14px;
        font-size: 13px;
        color: var(--rose);
    }

    body.light .modal-holiday-badge {
        background: rgba(225,29,72,0.1);
        border-color: rgba(225,29,72,0.3);
    }
    /* ============================================================
   EVENT ACTION BUTTONS
   ============================================================ */
.event-actions {
    display: flex;
    gap: 6px;
    align-items: center;
    margin-top: 8px;
    justify-content: flex-end;
}
@media (max-width: 480px) {
    .event-actions {
        justify-content: center;
        margin-top: 6px;
    }
}

.event-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    border: 1px solid transparent;
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    background: var(--glass);
    color: var(--text);
    white-space: nowrap;
    font-family: inherit;
}
.event-action-btn:hover {
    background: var(--hover-bg, rgba(255,255,255,0.1));
    border-color: var(--border);
}

.event-action-btn.edit {
    color: var(--accent-soft, #60a5fa);
}
.event-action-btn.edit:hover {
    background: rgba(59,130,246,0.1);
    border-color: var(--accent-soft);
}

.event-action-btn.delete {
    color: var(--rose, #ef476f);
}
.event-action-btn.delete:hover {
    background: rgba(239,71,111,0.1);
    border-color: var(--rose);
}

/* Make icons/text scale nicely */
.event-action-btn .icon {
    font-size: 14px;
    line-height: 1;
}
.event-action-btn .label {
    display: inline;
}
@media (max-width: 480px) {
    .event-action-btn .label {
        display: none;   /* show only icon on very small screens */
    }
    .event-action-btn {
        padding: 4px 8px;
    }
}

.mini-calendar {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 12px;
    max-width: 220px;
    font-family: inherit;
}
.mini-cal-header {
    text-align: center;
    margin-bottom: 8px;
    font-weight: 700;
    color: var(--gold);
}
.mini-cal-month {
    display: block;
    font-size: 14px;
}
.mini-cal-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
}
.mini-cal-day-name {
    font-size: 10px;
    color: var(--muted);
    text-align: center;
    padding: 2px;
}
.mini-cal-day-name.friday { color: var(--gold); }
.mini-cal-cell {
    font-size: 12px;
    text-align: center;
    padding: 3px;
    border-radius: 4px;
    color: var(--text);
}
.mini-cal-cell.empty { background: transparent; }
.mini-cal-cell.today { background: var(--gold); color: #000; font-weight: 700; }
.mini-cal-cell.holiday { color: var(--rose); }
.mini-cal-link {
    display: block;
    text-align: center;
    margin-top: 8px;
    font-size: 11px;
    color: var(--muted);
    text-decoration: none;
}
.mini-cal-link:hover { color: var(--gold); }

</style>
</head>
<body x-data="clockApp()" :class="{ 'light': !darkMode }">
{{-- ============================================================ --}}
{{-- REAL CLOCK BAR                                               --}}
{{-- ============================================================ --}}
<div class="clock-bar">
    
    <div>
        <div class="clock-date-label">{{ pcal_trans('current_date') }}</div>
        <div class="clock-pashto-date">
            {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber(\Qadir\PashtoCalendar\PashtoCalendar::now()->day) }}
            {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->monthName() }}
            {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber(\Qadir\PashtoCalendar\PashtoCalendar::now()->year) }}
        </div>
    </div>

    <div style="text-align:center;">
        <div class="clock-time" x-text="time"></div>
        <div class="clock-seconds" x-text="dateLabel"></div>
    </div>

    <div style="display:flex; align-items:center; gap:16px;">
        <a href="/pashto-calendar/converter" class="nav-btn" style="font-size:14px; padding:6px 16px; gap:6px;">🔄 {{ pcal_trans('converter') }}</a>
        <a href="/pashto-calendar/year" class="nav-btn" style="font-size:14px; padding:6px 16px; gap:6px;">📆 {{ pcal_trans('year') }}</a>

        <button class="theme-toggle" @click="darkMode = !darkMode">
            <span x-show="darkMode">🌙</span>
            <span x-show="!darkMode">☀️</span>
            <span x-text="darkMode ? '{{ pcal_trans('dark_mode') }}' : '{{ pcal_trans('light_mode') }}'"></span>
        </button>
        <div style="text-align:left; direction:ltr;">
            <div class="clock-date-label">{{ pcal_trans('gregorian') }}</div>
            <div class="gregorian-date">{{ now()->format('d M Y') }}</div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- CALENDAR + CONVERTER (shared wrapper)                        --}}
{{-- ============================================================ --}}
<div class="cal-wrapper"
     x-data='calendarApp({{ $year }}, {{ $month }}, @json($alpineDays, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT))'
     x-init="init">

    {{-- HEADER --}}
   <div class="cal-header">
    <button class="nav-btn" @click="changeMonth(-1)" :disabled="loading">
        <span x-show="!loading">&#8594;</span>
        <span x-show="loading">⏳</span>
    </button>

    <div style="text-align:center;">
        <div class="cal-month-title">
            <span x-text="toPashto(year)"></span>
            &mdash;
            <span x-text="monthName"></span>
        </div>
        <div style="font-size:12px; color:var(--muted); margin-top:2px;" x-text="gregorianLabel"></div>
    </div>

    <div style="display:flex; gap:8px;">
        <button class="nav-btn" style="font-size:14px; padding:6px 16px;"
                @click="goToToday()">
            📍 {{ pcal_trans('today') }}
        </button>

        <button class="nav-btn" @click="changeMonth(1)" :disabled="loading">
            <span x-show="!loading">&#8592;</span>
            <span x-show="loading">⏳</span>
        </button>
    </div>
</div>

    {{-- WEEK HEADERS --}}
    @php
        $weekDaysTrans = pcal_trans('week_days');
        if (is_string($weekDaysTrans)) {
            $decoded = json_decode($weekDaysTrans, true);
            $weekDays = is_array($decoded) ? $decoded : ['ش','ی','د','س','چ','پ','ج'];
        } else {
            $weekDays = is_array($weekDaysTrans) ? $weekDaysTrans : ['ش','ی','د','س','چ','پ','ج'];
        }
    @endphp
    <div class="week-headers">
        @foreach($weekDays as $index => $day)
            <div class="week-header {{ $index == 6 ? 'friday' : '' }}">{{ $day }}</div>
        @endforeach
    </div>

    {{-- CALENDAR CONTENT --}}
    <div id="calendar-content">
        @include('pashto-calendar::_calendar_content', [
            'days'   => $alpineDays,
            'month'  => $month,
            'year'   => $year
        ])
    </div>

    {{-- FOOTER BAR --}}
    <div style="display:flex; justify-content:center; gap:12px; padding-bottom:30px;">
        <a href="/pashto-calendar/demo"
           style="background:rgba(240,165,0,0.1); border:1px solid rgba(240,165,0,0.25); color:var(--gold); padding:8px 20px; border-radius:10px; text-decoration:none; font-size:13px; transition:all 0.2s;"
           onmouseover="this.style.background='rgba(240,165,0,0.2)'"
           onmouseout="this.style.background='rgba(240,165,0,0.1)'">
            {{ pcal_trans('demo_link') }}
        </a>
        <span style="color:var(--muted); font-size:12px; display:flex; align-items:center;">
            {{ pcal_trans('package_name') }}
        </span>
    </div>

    {{-- MODAL OVERLAY --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="modal-overlay"
        @click="open = false"
        style="display:none;"
    ></div>

    {{-- MODAL BOX --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="modal-container"
        style="display:none;"
    >
        <div class="modal-box" @click.stop>

            {{-- Modal Header --}}
            <div class="modal-header">
                <div>
                    <div style="font-size:11px; color:var(--muted); margin-bottom:4px;" x-text="selectedMonthName + ' ' + toPashto(year)"></div>
                    <div class="modal-title">{{ pcal_trans('day_info') }}</div>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <div class="modal-day-badge" x-text="toPashto(selectedDay)"></div>
                    <button class="modal-close" @click="open = false">&times;</button>
                </div>
            </div>

            <div class="modal-body">

                {{-- Holiday badge --}}
                <div x-show="isHoliday" class="modal-holiday-badge">
                    🎉 <span x-text="holidayName"></span>
                </div>

                {{-- Events list --}}
                <div style="max-height:160px; overflow-y:auto; margin-bottom:4px;">
                    <template x-if="events.length === 0">
                        <div class="modal-empty">{{ pcal_trans('no_events') }}</div>
                    </template>

                    <template x-for="event in events" :key="event.id">
                        <div
                            class="modal-event-item"
                            :style="'background-color:' + (event.color || '#f0a500') + '22; border:1px solid ' + (event.color || '#f0a500') + '44'"
                        >
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span
                                    style="width:10px; height:10px; border-radius:50%; flex-shrink:0;"
                                    :style="'background:' + (event.color || '#f0a500')"
                                ></span>
                                <span x-text="event.title"></span>
                            </div>
                            <div style="display:flex; gap:4px;">
                                <button
                                    type="button"
                                    @click.stop="editEvent(event)"
                                    style="background:none; border:none; color:var(--accent-soft, #60a5fa); cursor:pointer; font-size:14px; padding:0 4px;"
                                    title="{{ pcal_trans('edit') }}"
                                >✏️</button>

                                <button
                                    class="modal-event-delete"
                                    @click.stop="deleteEvent(event.id)"
                                    title="{{ pcal_trans('delete') }}"
                                >&times;</button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Add Event Form --}}
                <div class="modal-form">
                    <div style="font-size:12px; color:var(--gold); letter-spacing:1px; margin-bottom:10px;">{{ pcal_trans('add_event') }}</div>

                    <input type="text" class="modal-input" placeholder="{{ pcal_trans('event_title') }}" x-model="form.title" @keydown.enter="saveEvent">
                    <input type="text" class="modal-input" placeholder="{{ pcal_trans('event_description') }}" x-model="form.description">
                    <input type="text" class="modal-input" placeholder="{{ pcal_trans('event_time') }}" x-model="form.time" style="margin-bottom:14px;">

                    <div class="modal-color-row" style="margin-bottom:14px;">
                        <span class="modal-color-label">{{ pcal_trans('recurrence') }}:</span>
                        <select x-model="form.recurrence" class="modal-input" style="margin-bottom:0;">
                            <option value="none">{{ pcal_trans('recurrence_none') }}</option>
                            <option value="daily">{{ pcal_trans('recurrence_daily') }}</option>
                            <option value="weekly">{{ pcal_trans('recurrence_weekly') }}</option>
                            <option value="monthly">{{ pcal_trans('recurrence_monthly') }}</option>
                            <option value="yearly">{{ pcal_trans('recurrence_yearly') }}</option>
                        </select>
                    </div>

                    <div class="modal-color-row" style="margin-bottom:14px;" x-show="form.recurrence !== 'none'">
                        <span class="modal-color-label">{{ pcal_trans('recurrence_end_date') }} ({{ pcal_trans('optional') }}):</span>
                        <input type="date" x-model="form.recurrence_end_date" class="modal-input" style="margin-bottom:0;">
                    </div>

                    <div class="modal-color-row">
                        <span class="modal-color-label">{{ pcal_trans('color_label') }}</span>
                        <div class="color-swatches">
                            @foreach(['#ef4444','#f0a500','#06d6a0','#3b82f6','#8b5cf6','#ec4899','#f97316','#ffffff'] as $color)
                                <div class="color-swatch"
                                     style="background:{{ $color }}"
                                     :class="form.color === '{{ $color }}' ? 'active' : ''"
                                     @click="form.color = '{{ $color }}'"></div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-btns">
                        <button class="btn-save" @click="saveEvent" :disabled="saving || !form.title.trim()">
                            <span x-show="!saving">
                                <span x-text="editingEvent ? '{{ pcal_trans('update') }}' : '{{ pcal_trans('save') }}'"></span>
                            </span>
                            <span x-show="saving">{{ pcal_trans('saving') }}</span>
                        </button>
                        <button class="btn-cancel" @click="open = false">{{ pcal_trans('cancel') }}</button>
                    </div>
                </div>

            </div>{{-- modal-body --}}
        </div>{{-- modal-box --}}
    </div>{{-- modal-container --}}

    {{-- Hidden translation messages for JavaScript alerts --}}
    <div id="trans-messages" style="display:none;"
         data-failed-change-month="{{ pcal_trans('failed_to_change_month') }}"
         data-failed-save-event="{{ pcal_trans('failed_to_save_event') }}"
         data-failed-delete="{{ pcal_trans('failed_to_delete') }}"
         data-confirm-delete="{{ pcal_trans('confirm_delete') }}"
         data-saving="{{ pcal_trans('saving') }}">
    </div>
</div>{{-- end cal-wrapper --}}
<script>
// ============================================================
// CLOCK APP
// ============================================================
function clockApp() {
    return {
        time: '',
        dateLabel: '',
        darkMode: localStorage.getItem('theme') !== 'light',
        init() {
            this.tick();
            setInterval(() => this.tick(), 1000);
        },
        tick() {
            const now   = new Date();
            const h     = String(now.getHours()).padStart(2, '0');
            const m     = String(now.getMinutes()).padStart(2, '0');
            const s     = String(now.getSeconds()).padStart(2, '0');
            this.time   = h + ':' + m + ':' + s;
            const days  = ['یکشنبه','دوشنبه','سه‌شنبه','چهارشنبه','پنجشنبه','جمعه','شنبه'];
            this.dateLabel = days[now.getDay()];
        }
    };
}

const currentPashtoYear = {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->year }};
const currentPashtoMonth = {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->month }};

// ============================================================
// CALENDAR APP – complete, error‑free
// ============================================================
function calendarApp(initialYear, initialMonth, initialDays) {
    return {
        year: initialYear,
        month: initialMonth,
        days: initialDays || [],
        monthName: '',
        gregorianLabel: '',
        loading: false,

        open: false,
        saving: false,
        selectedDay: null,
        selectedDayPs: '',
        selectedMonthName: '',
        isHoliday: false,
        holidayName: '',
        events: [],
        editingEvent: null,

        form: {
            title: '',
            description: '',
            time: '',
            color: '#f0a500',
            recurrence: 'none',
            recurrence_end_date: '',
        },

        toPashto(n) {
            if (n === undefined || n === null) return '';
            const en = ['0','1','2','3','4','5','6','7','8','9'];
            const ps = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
            return String(n).replace(/[0-9]/g, d => ps[en.indexOf(d)]);
        },

        init() {
            const monthNames = ['', 'وری', 'غویی', 'غبرګولی', 'چنګاښ', 'زمری', 'وږی', 'تله', 'لړم', 'لیندۍ', 'مرغومی', 'سلواغه', 'کب'];
            this.monthName = monthNames[this.month] || '';
            const d = new Date(this.year, this.month - 1, 1);
            this.gregorianLabel = d.toLocaleString('en', { month: 'long', year: 'numeric' });
        },

        async changeMonth(delta, targetYear = null, targetMonth = null) {
            if (this.loading) return;
            this.loading = true;

            let newMonth, newYear;
            if (targetYear && targetMonth) {
                newYear = targetYear;
                newMonth = targetMonth;
            } else {
                newMonth = this.month + delta;
                newYear = this.year;
                if (newMonth < 1) { newMonth = 12; newYear--; }
                if (newMonth > 12) { newMonth = 1; newYear++; }
            }

            const trans = document.getElementById('trans-messages')?.dataset || {};

            try {
                const respHtml = await fetch(`/pashto-calendar/data/${newYear}/${newMonth}?ajax=1`);
                if (!respHtml.ok) throw new Error('Network error');
                const html = await respHtml.text();

                const container = document.getElementById('calendar-content');
                if (container) {
                    container.innerHTML = html;
                    if (typeof Alpine !== 'undefined') Alpine.initTree(container);
                }

                const respJson = await fetch(`/pashto-calendar/data/${newYear}/${newMonth}?json=1`);
                if (respJson.ok) {
                    const data = await respJson.json();
                    this.days = data.days;
                }

                this.year = newYear;
                this.month = newMonth;

                const monthNames = ['', 'وری', 'غویی', 'غبرګولی', 'چنګاښ', 'زمری', 'وږی', 'تله', 'لړم', 'لیندۍ', 'مرغومی', 'سلواغه', 'کب'];
                this.monthName = monthNames[this.month] || '';
                const d = new Date(this.year, this.month - 1, 1);
                this.gregorianLabel = d.toLocaleString('en', { month: 'long', year: 'numeric' });

                const url = new URL(window.location);
                url.searchParams.set('year', this.year);
                url.searchParams.set('month', this.month);
                window.history.pushState({}, '', url);
            } catch (e) {
                console.error(e);
                alert(trans.failedChangeMonth || 'د میاشت بدلول ناکام شول');
            } finally {
                this.loading = false;
            }
        },

        goToToday() {
            if (this.year === currentPashtoYear && this.month === currentPashtoMonth) return;
            this.changeMonth(0, currentPashtoYear, currentPashtoMonth);
        },

        openModal(day) {
            const dayObj = this.days.find(d => !d.empty && d.day == day);
            this.selectedDay = day;
            this.selectedDayPs = this.toPashto(day);
            this.selectedMonthName = this.monthName;
            this.isHoliday = dayObj ? (dayObj.is_holiday || false) : false;
            this.holidayName = dayObj ? (dayObj.holiday_name || '') : '';
            this.events = (dayObj && dayObj.events) ? dayObj.events : [];
            this.editingEvent = null;
            this.form.title = '';
            this.form.description = '';
            this.form.time = '';
            this.form.color = '#f0a500';
            this.form.recurrence = 'none';
            this.form.recurrence_end_date = '';
            this.open = true;
        },

        editEventFromPanel(eventId, day) {
            const dayObj = this.days.find(d => !d.empty && d.day == day);
            if (!dayObj || !dayObj.events) return;
            const event = dayObj.events.find(e => e.id == eventId);
            if (!event) return;

            this.selectedDay = day;
            this.selectedDayPs = this.toPashto(day);
            this.selectedMonthName = this.monthName;
            this.isHoliday = dayObj.is_holiday || false;
            this.holidayName = dayObj.holiday_name || '';
            this.events = dayObj.events;
            this.editingEvent = event;
            this.form.title = event.title || '';
            this.form.description = event.description || '';
            this.form.time = event.time || '';
            this.form.color = event.color || '#f0a500';
            this.form.recurrence = event.recurrence || 'none';
            this.form.recurrence_end_date = event.recurrence_end_date || '';
            this.open = true;
        },

        // ✅ This was missing – needed by the modal's ✏️ button
        editEvent(event) {
            this.editingEvent = event;
            this.form.title = event.title || '';
            this.form.description = event.description || '';
            this.form.time = event.time || '';
            this.form.color = event.color || '#f0a500';
            this.form.recurrence = event.recurrence || 'none';
            this.form.recurrence_end_date = event.recurrence_end_date || '';
        },

        async saveEvent() {
            if (!this.form.title.trim()) return;
            this.saving = true;

            const payload = {
                title: this.form.title,
                description: this.form.description,
                time: this.form.time,
                year: this.year,
                month: this.month,
                day: this.selectedDay,
                color: this.form.color,
                recurrence: this.form.recurrence,
                recurrence_end_date: this.form.recurrence_end_date,
            };

            const trans = document.getElementById('trans-messages')?.dataset || {};

            try {
                let resp;
                if (this.editingEvent) {
                    resp = await fetch(`/pashto-calendar/event/${this.editingEvent.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify(payload)
                    });
                } else {
                    resp = await fetch('/pashto-calendar/event', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify(payload)
                    });
                }

                if (!resp.ok) throw new Error('Server error');
                const savedEvent = await resp.json();

                if (this.editingEvent) {
                    const idx = this.events.findIndex(e => e.id === savedEvent.id);
                    if (idx !== -1) this.events.splice(idx, 1, savedEvent);
                } else {
                    this.events.push(savedEvent);
                }

                const dayObj = this.days.find(d => !d.empty && d.day === this.selectedDay);
                if (dayObj) {
                    if (!dayObj.events) dayObj.events = [];
                    if (this.editingEvent) {
                        const dIdx = dayObj.events.findIndex(e => e.id === savedEvent.id);
                        if (dIdx !== -1) dayObj.events.splice(dIdx, 1, savedEvent);
                    } else {
                        dayObj.events.push(savedEvent);
                    }
                }

                this.form.title = '';
                this.form.description = '';
                this.form.time = '';
                this.form.color = '#f0a500';
                this.form.recurrence = 'none';
                this.form.recurrence_end_date = '';
                this.editingEvent = null;
                this.open = false;

                const container = document.getElementById('calendar-content');
                if (container) {
                    const res = await fetch(`/pashto-calendar/data/${this.year}/${this.month}?ajax=1`);
                    if (res.ok) {
                        const html = await res.text();
                        container.innerHTML = html;
                        if (typeof Alpine !== 'undefined') Alpine.initTree(container);
                    }
                    const jsonRes = await fetch(`/pashto-calendar/data/${this.year}/${this.month}?json=1`);
                    if (jsonRes.ok) {
                        const data = await jsonRes.json();
                        this.days = data.days;
                    }
                }
            } catch (e) {
                console.error(e);
                alert(trans.failedSaveEvent || 'د پیښې خوندي کول ناکام شول');
            } finally {
                this.saving = false;
            }
        },

        async deleteEvent(id) {
            const trans = document.getElementById('trans-messages')?.dataset || {};

            if (!confirm(trans.confirmDelete || 'ایا ډاډه یاست؟')) return;

            try {
                const resp = await fetch(`/pashto-calendar/event/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }
                });

                if (!resp.ok) throw new Error('Server error');

                this.events = this.events.filter(e => e.id !== id);

                const dayObj = this.days.find(d => !d.empty && d.day === this.selectedDay);
                if (dayObj && dayObj.events) {
                    dayObj.events = dayObj.events.filter(e => e.id !== id);
                }

                const container = document.getElementById('calendar-content');
                if (container) {
                    const res = await fetch(`/pashto-calendar/data/${this.year}/${this.month}?ajax=1`);
                    if (res.ok) {
                        const html = await res.text();
                        container.innerHTML = html;
                        if (typeof Alpine !== 'undefined') Alpine.initTree(container);
                    }
                    const jsonRes = await fetch(`/pashto-calendar/data/${this.year}/${this.month}?json=1`);
                    if (jsonRes.ok) {
                        const data = await jsonRes.json();
                        this.days = data.days;
                    }
                }
            } catch (e) {
                console.error(e);
                alert(trans.failedDelete || 'حذف ناکام شو');
            }
        }
    };
}

// Initialize the Alpine store for view switching
document.addEventListener('alpine:init', () => {
    Alpine.store('view', 'calendar');
});
</script>
</body>
</html>
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
        :root {
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
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--night);
            color: var(--text);
            font-family: '{{ $font ?? "Noto Naskh Arabic, serif" }}';
            min-height: 100vh;
        }

        /* ============================================================
           CLOCK
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
            background: rgba(255,255,255,0.04);
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
        }

        .nav-btn:hover {
            background: rgba(240,165,0,0.2);
            transform: scale(1.05);
        }

        .cal-month-title {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
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
            color: #fff;
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
           EVENTS PANEL — shown below calendar for days with events
        ============================================================ */
        .events-panel {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 28px;
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

        .event-info-card:hover {
            background: rgba(255,255,255,0.07);
            transform: translateY(-1px);
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
            color: #fff;
        }

        .event-info-desc {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
        }

        /* ============================================================
           MODAL OVERLAY
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
            background: #0d1b35;
            border: 1px solid var(--border);
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.6),
                        0 0 40px rgba(240,165,0,0.08);
        }

        .modal-header {
            background: linear-gradient(135deg, rgba(240,165,0,0.15), rgba(6,214,160,0.08));
            border-bottom: 1px solid var(--border);
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
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

        .modal-body { padding: 20px 24px; }

        /* Events list in modal */
        .modal-event-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 8px;
            color: #fff;
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
            color: #fff;
            font-size: 14px;
            margin-bottom: 10px;
            font-family: 'Noto Naskh Arabic', serif;
            outline: none;
            transition: border-color 0.2s;
        }

        .modal-input:focus { border-color: rgba(240,165,0,0.5); }
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
            background: linear-gradient(135deg, var(--gold), #c87800);
            color: #000;
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

        .btn-cancel:hover { background: rgba(255,255,255,0.1); }

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
    </style>
</head>
<body>

{{-- ============================================================ --}}
{{-- REAL CLOCK BAR                                               --}}
{{-- ============================================================ --}}
<div class="clock-bar" x-data="clockApp()">

    <div>
        <div class="clock-date-label">اوسنۍ نیټه</div>
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

    <div style="text-align:left; direction:ltr;">
        <div class="clock-date-label">Gregorian</div>
        <div style="font-size:14px; color:#fff; direction:ltr;">{{ now()->format('d M Y') }}</div>
    </div>

</div>

{{-- ============================================================ --}}
{{-- CALENDAR                                                     --}}
{{-- ============================================================ --}}
<div class="cal-wrapper" x-data="calendarApp()">

    {{-- HEADER --}}
    <div class="cal-header">
        <a href="?month={{ $month - 1 }}&year={{ $year }}" class="nav-btn">&#8594;</a>

        <div style="text-align:center;">
            <div class="cal-month-title">
                {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($year) }}
                &mdash;
                {{ \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) }}
            </div>
            <div style="font-size:12px; color:var(--muted); margin-top:2px;">
                {{ now()->format('F Y') }}
            </div>
        </div>

        <a href="?month={{ $month + 1 }}&year={{ $year }}" class="nav-btn">&#8592;</a>
    </div>

    {{-- WEEK HEADERS --}}
    <div class="week-headers">
        @foreach([
            'ش'  => false,
            'ی'  => false,
            'د'  => false,
            'س'  => false,
            'چ'  => false,
            'پ'  => false,
            'ج'  => true,
        ] as $label => $isFriday)
            <div class="week-header {{ $isFriday ? 'friday' : '' }}">{{ $label }}</div>
        @endforeach
    </div>

    {{-- CALENDAR GRID --}}
    <div class="cal-grid">

        @foreach($days as $day)

            @if(isset($day['empty']) && $day['empty'])
                <div class="cal-empty"></div>
                @continue
            @endif

            @php
                $classes  = 'cal-day';
                $classes .= ($day['is_today']   ?? false) ? ' is-today'   : '';
                $classes .= ($day['is_friday']  ?? false) ? ' is-friday'  : '';
                $classes .= ($day['is_holiday'] ?? false) ? ' is-holiday' : '';
                $classes .= ($day['event_count'] > 0)     ? ' has-events' : '';
            @endphp

            <div
                class="{{ $classes }}"
                @click="openModal(
                    {{ $day['day'] }},
                    '{{ \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) }}',
                    {{ json_encode($day['is_holiday'] ?? false) }},
                    '{{ addslashes($day['holiday_name'] ?? '') }}',
                    @json($day['events'])
                )"
            >
                {{-- Today pulse dot --}}
                @if($day['is_today'] ?? false)
                    <div class="today-dot"></div>
                @endif

                {{-- Day number --}}
                <div class="day-number">
                    {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($day['day']) }}
                </div>

                {{-- Holiday name --}}
                @if(($day['is_holiday'] ?? false) && $day['holiday_name'])
                    <div class="holiday-label">{{ $day['holiday_name'] }}</div>
                @endif

                {{-- Event dots --}}
                @if($day['event_count'] > 0)
                    <div class="event-dots">
                        @foreach($day['events'] as $event)
                            <span
                                class="event-dot"
                                style="background-color: {{ $event->color ?? '#f0a500' }}"
                            ></span>
                        @endforeach
                    </div>
                @endif

            </div>

        @endforeach

    </div>

    {{-- ============================================================ --}}
    {{-- EVENTS PANEL — shows all events in this month below calendar --}}
    {{-- ============================================================ --}}
    @php
        $daysWithEvents = collect($days)->filter(fn($d) => !($d['empty'] ?? false) && $d['event_count'] > 0);
    @endphp

    @if($daysWithEvents->count() > 0)
        <div class="events-panel">
            <div class="events-panel-title">
                <span>📋</span>
                <span>د دې میاشتې پیښې</span>
                <span style="background:rgba(240,165,0,0.15); border:1px solid rgba(240,165,0,0.3); border-radius:20px; padding:2px 10px; font-size:11px;">
                    {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($daysWithEvents->sum('event_count')) }}
                </span>
            </div>

            <div class="events-panel-grid">
                @foreach($daysWithEvents as $day)
                    @foreach($day['events'] as $event)
                        <div class="event-info-card">
                            <div
                                class="event-color-bar"
                                style="background-color: {{ $event->color ?? '#f0a500' }}"
                            ></div>
                            <div style="flex:1; min-width:0;">
                                <div class="event-info-day">
                                    {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($day['day']) }}
                                    {{ \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) }}
                                </div>
                                <div class="event-info-title">{{ $event->title }}</div>
                                @if($event->description)
                                    <div class="event-info-desc">{{ $event->description }}</div>
                                @endif
                                @if($event->time)
                                    <div class="event-info-desc" style="color:var(--teal);">⏰ {{ $event->time }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- FOOTER BAR                                                   --}}
    {{-- ============================================================ --}}
    <div style="display:flex; justify-content:center; gap:12px; padding-bottom:30px;">
        <a href="/pashto-calendar/demo"
           style="background:rgba(240,165,0,0.1); border:1px solid rgba(240,165,0,0.25); color:var(--gold); padding:8px 20px; border-radius:10px; text-decoration:none; font-size:13px; transition:all 0.2s;"
           onmouseover="this.style.background='rgba(240,165,0,0.2)'"
           onmouseout="this.style.background='rgba(240,165,0,0.1)'">
            🎨 Demo
        </a>
        <span style="color:var(--muted); font-size:12px; display:flex; align-items:center;">
            qadir/pashto-calendar
        </span>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL OVERLAY                                                --}}
    {{-- ============================================================ --}}
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
                    <div style="font-size:11px; color:var(--muted); margin-bottom:4px;" x-text="selectedMonthName + ' ' + '{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($year) }}'"></div>
                    <div class="modal-title">د ورځې معلومات</div>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <div class="modal-day-badge" x-text="selectedDayPs"></div>
                    <button class="modal-close" @click="open = false">&times;</button>
                </div>
            </div>

            <div class="modal-body">

                {{-- Holiday badge --}}
                <div
                    x-show="isHoliday"
                    class="modal-holiday-badge"
                >
                    🎉 <span x-text="holidayName"></span>
                </div>

                {{-- Events list --}}
                <div style="max-height:160px; overflow-y:auto; margin-bottom:4px;">
                    <template x-if="events.length === 0">
                        <div class="modal-empty">هیڅ پیښه نشته — لاندې یوه زیاته کړه</div>
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
                            <button
                                class="modal-event-delete"
                                @click.stop="deleteEvent(event.id)"
                            >&times;</button>
                        </div>
                    </template>
                </div>

                {{-- Add Event Form --}}
                <div class="modal-form">
                    <div style="font-size:12px; color:var(--gold); letter-spacing:1px; margin-bottom:10px;">✦ پیښه زیاتول</div>

                    <input
                        type="text"
                        class="modal-input"
                        placeholder="د پیښې سرلیک..."
                        x-model="form.title"
                        @keydown.enter="saveEvent"
                    >

                    <input
                        type="text"
                        class="modal-input"
                        placeholder="توضیحات (اختیاري)"
                        x-model="form.description"
                    >

                    <input
                        type="text"
                        class="modal-input"
                        placeholder="وخت — لکه ۱۰:۰۰ (اختیاري)"
                        x-model="form.time"
                        style="margin-bottom:14px;"
                    >

                    <div class="modal-color-row">
                        <span class="modal-color-label">رنګ:</span>
                        <div class="color-swatches">
                            @foreach(['#ef4444','#f0a500','#06d6a0','#3b82f6','#8b5cf6','#ec4899','#f97316','#ffffff'] as $color)
                                <div
                                    class="color-swatch"
                                    style="background:{{ $color }}"
                                    :class="form.color === '{{ $color }}' ? 'active' : ''"
                                    @click="form.color = '{{ $color }}'"
                                ></div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-btns">
                        <button
                            class="btn-save"
                            @click="saveEvent"
                            :disabled="saving || !form.title.trim()"
                        >
                            <span x-show="!saving">✓ خوندي کول</span>
                            <span x-show="saving">خوندي کیږي...</span>
                        </button>
                        <button class="btn-cancel" @click="open = false">بندول</button>
                    </div>
                </div>

            </div>
        </div>
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
    }
}

// ============================================================
// CALENDAR APP
// ============================================================
function calendarApp() {
    return {
        open:              false,
        saving:            false,
        selectedDay:       null,
        selectedDayPs:     '',
        selectedMonthName: '',
        isHoliday:         false,
        holidayName:       '',
        events:            [],

        form: {
            title:       '',
            description: '',
            time:        '',
            color:       '#f0a500'
        },

        // ✅ Convert number to Pashto
        toPashto(n) {
            const en = ['0','1','2','3','4','5','6','7','8','9'];
            const ps = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
            return String(n).replace(/[0-9]/g, d => ps[en.indexOf(d)]);
        },

        // ✅ Open modal
        openModal(day, monthName, isHoliday, holidayName, events) {
            this.selectedDay       = day;
            this.selectedDayPs     = this.toPashto(day);
            this.selectedMonthName = monthName;
            this.isHoliday         = isHoliday;
            this.holidayName       = holidayName;
            this.events            = events || [];
            this.form.title        = '';
            this.form.description  = '';
            this.form.time         = '';
            this.form.color        = '#f0a500';
            this.open              = true;
        },

        // ✅ Save event
        async saveEvent() {
            if (!this.form.title.trim()) return;
            this.saving = true;

            try {
                const response = await fetch('/pashto-calendar/event', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify({
                        title:       this.form.title,
                        description: this.form.description,
                        time:        this.form.time,
                        year:        {{ $year }},
                        month:       {{ $month }},
                        day:         this.selectedDay,
                        color:       this.form.color
                    })
                });

                if (!response.ok) throw new Error('Server error');

                const data = await response.json();
                this.events.push(data);

                // ✅ Also refresh the page to update events panel
                setTimeout(() => window.location.reload(), 600);

                this.form.title       = '';
                this.form.description = '';
                this.form.time        = '';

            } catch (error) {
                console.error('Failed to save event:', error);
                alert('د پیښې خوندي کول ناکام شول');
            } finally {
                this.saving = false;
            }
        },

        // ✅ Delete event
        async deleteEvent(id) {
            if (!confirm('ایا ډاډه یاست؟')) return;

            try {
                const response = await fetch('/pashto-calendar/event/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                if (!response.ok) throw new Error('Server error');

                this.events = this.events.filter(e => e.id !== id);

                // ✅ Refresh events panel
                setTimeout(() => window.location.reload(), 400);

            } catch (error) {
                console.error('Failed to delete:', error);
            }
        }
    }
}
</script>

</body>
</html>
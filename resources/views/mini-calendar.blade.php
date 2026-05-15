<div class="mini-calendar-card">
    <div class="mini-cal-header">
        <span class="mini-cal-year">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($year) }}</span>
        <span class="mini-cal-month">{{ \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) }}</span>
    </div>

    <div class="mini-cal-grid">
        {{-- Day names — now use the translated array --}}
        @foreach(pcal_trans('week_days') as $index => $day)
            <div class="mini-cal-day-name {{ $index == 6 ? 'friday' : '' }}">{{ $day }}</div>
        @endforeach

        {{-- Day cells --}}
        @foreach($days as $day)
            @if(isset($day['empty']) && $day['empty'])
                <div class="mini-cal-cell empty"></div>
            @else
                @php
                    $class = 'mini-cal-cell';
                    if ($day['is_today'] ?? false) $class .= ' today';
                    if ($day['is_holiday'] ?? false) $class .= ' holiday';
                    if (($day['event_count'] ?? 0) > 0) $class .= ' has-event';
                @endphp
                <div class="{{ $class }}" title="{{ $day['holiday_name'] ?? '' }}">
                    {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($day['day']) }}
                </div>
            @endif
        @endforeach
    </div>

    <a href="/pashto-calendar?year={{ $year }}&month={{ $month }}" class="mini-cal-link">
        <span>📅</span> {{ pcal_trans('view_full_calendar') }}
    </a>
</div>

<style>
    /* … your existing CSS remains unchanged … */
</style>

<style>
    /* Mini Calendar – self contained, works in dark & light mode */
    .mini-calendar-card {
        max-width: 230px;
        width: 100%;
        margin: 0 auto;
        font-family: 'Noto Naskh Arabic', serif;
        background: rgba(15, 23, 42, 0.95); /* dark slate fallback */
        background: var(--bg-secondary, #1e293b);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(240,165,0,0.15);
        border-radius: 20px;
        padding: 16px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .mini-calendar-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    }

    .mini-cal-header {
        text-align: center;
        margin-bottom: 14px;
    }
    .mini-cal-year {
        font-size: 18px;
        font-weight: 700;
        background: linear-gradient(135deg, #f0a500, #ffd166);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: block;
    }
    .mini-cal-month {
        font-size: 15px;
        font-weight: 600;
        color: #e8e0d0; /* soft gold text */
        margin-top: 2px;
        display: block;
    }

    .mini-cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 3px;
        margin-bottom: 12px;
    }

    .mini-cal-day-name {
        font-size: 11px;
        color: rgba(232,224,208,0.45); /* muted */
        text-align: center;
        padding: 4px 0;
    }
    .mini-cal-day-name.friday {
        color: #f0a500; /* gold */
    }

    .mini-cal-cell {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s;
        color: #e8e0d0; /* light text */
        position: relative;
    }
    .mini-cal-cell.empty {
        background: transparent;
        pointer-events: none;
    }
    .mini-cal-cell:hover {
        background: rgba(240,165,0,0.15);
    }
    .mini-cal-cell.today {
        background: #f0a500;
        color: #000; /* black text on gold */
        font-weight: 700;
        box-shadow: 0 0 10px rgba(240,165,0,0.3);
    }
    .mini-cal-cell.holiday {
        color: #ef476f; /* rose */
        font-weight: 600;
    }
    .mini-cal-cell.holiday:hover {
        background: rgba(239,71,111,0.15);
    }
    .mini-cal-cell.has-event::after {
        content: '';
        width: 5px;
        height: 5px;
        background: #f0a500;
        border-radius: 50%;
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%);
    }

    .mini-cal-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        font-size: 12px;
        color: rgba(232,224,208,0.6);
        text-decoration: none;
        transition: color 0.2s;
        padding: 6px 0 0;
        border-top: 1px solid rgba(240,165,0,0.1);
    }
    .mini-cal-link:hover {
        color: #f0a500;
    }

    /* Light mode overrides */
    body.light .mini-calendar-card {
        background: #ffffff;
        border-color: rgba(37,99,235,0.15);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    body.light .mini-cal-cal-year {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    body.light .mini-cal-month {
        color: #0f172a;
    }
    body.light .mini-cal-day-name {
        color: #64748b;
    }
    body.light .mini-cal-day-name.friday {
        color: #2563eb;
    }
    body.light .mini-cal-cell {
        color: #0f172a;
    }
    body.light .mini-cal-cell.today {
        background: #2563eb;
        color: #ffffff;
    }
    body.light .mini-cal-cell.holiday {
        color: #dc2626;
    }
    body.light .mini-cal-cell:hover {
        background: rgba(37,99,235,0.1);
    }
    body.light .mini-cal-link {
        color: #64748b;
    }
    body.light .mini-cal-link:hover {
        color: #2563eb;
    }
</style>
{{-- Hidden metadata for Alpine --}}
<span data-month-name="{{ \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) }}"
      data-gregorian-label="{{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}"
      style="display:none;"></span>

<div id="calendar-content">

    {{-- CALENDAR GRID --}}
    <div class="cal-grid">
        @foreach($days as $day)
            @if(!empty($day['empty']))
                <div class="cal-empty"></div>
                @continue
            @endif

            @php
                $classes  = 'cal-day';
                $classes .= ($day['is_today']   ?? false) ? ' is-today'   : '';
                $classes .= ($day['is_friday']  ?? false) ? ' is-friday'  : '';
                $classes .= ($day['is_holiday'] ?? false) ? ' is-holiday' : '';
                $classes .= (($day['event_count'] ?? 0) > 0) ? ' has-events' : '';
            @endphp

         <div class="{{ $classes }}"
     @click="openModal({{ $day['day'] }})"
>
                @if($day['is_today'] ?? false)
                    <div class="today-dot"></div>
                @endif
                <div class="day-number">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($day['day']) }}</div>
                @if(($day['is_holiday'] ?? false) && ($day['holiday_name'] ?? ''))
                    <div class="holiday-label">{{ $day['holiday_name'] }}</div>
                @endif
                @if(($day['event_count'] ?? 0) > 0)
                    <div class="event-dots">
                        @foreach($day['events'] as $event)
                            <span class="event-dot" style="background-color: {{ $event['color'] ?? '#f0a500' }}"></span>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- EVENTS + HOLIDAYS PANEL --}}
    @php
        // User events per day
        $daysWithEvents = collect($days)->filter(fn($d) => empty($d['empty']) && ($d['event_count'] ?? 0) > 0);
        // Holidays for this month (from database)
        $holidaysThisMonth = \Qadir\PashtoCalendar\Models\PashtoHoliday::where('year', $year)
            ->where('month', $month)
            ->orderBy('day')
            ->get();
        $totalEvents = $daysWithEvents->sum('event_count');
    @endphp

    <div class="events-panel">
        <div class="events-panel-title">
            <span>📋</span>
            <span>{{ pcal_trans('events_panel_title') }}</span>
            <span style="background:rgba(240,165,0,0.15); border:1px solid rgba(240,165,0,0.3); border-radius:20px; padding:2px 10px; font-size:11px;">
                {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($totalEvents + $holidaysThisMonth->count()) }}
            </span>
        </div>

        {{-- User Events --}}
        @if($daysWithEvents->isNotEmpty())
      <div class="events-panel-grid">
    @foreach($daysWithEvents as $day)
        @foreach($day['events'] as $event)
            <div class="event-info-card">
                <div class="event-color-bar" style="background-color: {{ $event['color'] ?? '#f0a500' }}"></div>
                <div style="flex:1; min-width:0; display:flex; flex-direction:column; gap:4px;">
                    <div class="event-info-day">
                        {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($day['day']) }}
                        {{ \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) }}
                    </div>
                    <div class="event-info-title">{{ $event['title'] }}</div>
                    @if($event['description'] ?? false)
                        <div class="event-info-desc">{{ $event['description'] }}</div>
                    @endif
                   @if($event['time'] ?? false)
    <div class="event-info-desc" style="color:var(--teal);">⏰ {{ $event['time'] }}</div>
@endif

@php
    // Build an object that includes the day so editEventFromPanel can work
    $editPayload = array_merge($event, ['day' => $day['day']]);
@endphp

{{-- Action Buttons --}}
<div class="event-actions">
    <button type="button"
        class="event-action-btn edit"
        @click="editEventFromPanel({{ $event['id'] }}, {{ $day['day'] }})"
        title="{{ pcal_trans('edit') }}">
    <span class="icon">✏️</span>
    <span class="label">{{ pcal_trans('edit') }}</span>
</button>

    <button type="button"
            class="event-action-btn delete"
            @click="deleteEvent({{ $event['id'] }})"
            title="{{ pcal_trans('delete') }}">
        <span class="icon">🗑️</span>
        <span class="label">{{ pcal_trans('delete') }}</span>
    </button>
</div>
                </div>
            </div>
        @endforeach
    @endforeach
</div>
        @else
            <div style="text-align:center; color:var(--muted); padding:8px 0;">{{ pcal_trans('no_user_events') }}</div>
        @endif

        {{-- Holidays section --}}
        <div style="margin-top: 20px;">
            <div class="events-panel-title" style="margin-bottom:10px;">
                <span>🎉</span>
                <span>{{ pcal_trans('holidays_title') }}</span>
                <span style="background:rgba(245,158,11,0.2); border:1px solid rgba(245,158,11,0.4); border-radius:20px; padding:2px 10px; font-size:11px;">
                    {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($holidaysThisMonth->count()) }}
                </span>
            </div>
            @if($holidaysThisMonth->isNotEmpty())
                <div class="events-panel-grid">
                    @foreach($holidaysThisMonth as $holiday)
                        <div class="event-info-card" style="border-left: 4px solid var(--accent-warning);">
                            <div style="flex:1; min-width:0;">
                                <div class="event-info-day">
                                    {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($holiday->day) }}
                                    {{ \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) }}
                                </div>
                                <div class="event-info-title">{{ $holiday->name }}</div>
                                @if($holiday->name_en)
                                    <div class="event-info-desc">{{ $holiday->name_en }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center; color:var(--muted); padding:8px 0;">{{ pcal_trans('no_holidays') }}</div>
            @endif
        </div>
    </div>

    {{-- FOOTER --}}
    <div style="display:flex; justify-content:center; gap:12px; padding-bottom:30px;">
        <a href="/pashto-calendar/demo"
           style="background:rgba(240,165,0,0.1); border:1px solid rgba(240,165,0,0.25); color:var(--gold); padding:8px 20px; border-radius:10px; text-decoration:none; font-size:13px; transition:all 0.2s;">
            {{ pcal_trans('demo_link') }}
        </a>
        <span style="color:var(--muted); font-size:12px;">{{ pcal_trans('package_name') }}</span>
    </div>
</div>
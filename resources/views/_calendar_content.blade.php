{{-- ============================================================
     _calendar_content.blade.php  —  EVENT PANEL UPGRADE ONLY
     Calendar grid code is UNCHANGED. Only panel CSS + markup improved.
============================================================ --}}

{{-- ─── PANEL-ONLY STYLES ──────────────────────────────────────── --}}
<style>
/* ================================================================
   EVENTS PANEL — complete restyle
================================================================ */

/* Panel shell */
.events-panel {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 0;
    margin-bottom: 28px;
    overflow: hidden;
}
body.light .events-panel {
    background: #ffffff;
    border-color: rgba(0,0,0,0.08);
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
}

/* ── Section header bar ── */
.ep-section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    background: rgba(240,165,0,0.04);
}
body.light .ep-section-header {
    background: rgba(180,83,9,0.04);
    border-bottom-color: rgba(0,0,0,0.07);
}
.ep-section-header.holiday-header {
    background: rgba(239,71,111,0.04);
    border-top: 1px solid var(--border);
}
body.light .ep-section-header.holiday-header {
    background: rgba(225,29,72,0.04);
}

.ep-section-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.ep-section-icon.events-icon {
    background: rgba(240,165,0,0.15);
    color: var(--gold);
}
.ep-section-icon.holiday-icon {
    background: rgba(239,71,111,0.15);
    color: var(--rose);
}
body.light .ep-section-icon.events-icon { background: rgba(180,83,9,0.12); }
body.light .ep-section-icon.holiday-icon { background: rgba(225,29,72,0.1); }

.ep-section-label {
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
    flex: 1;
    min-width: 0;
}

.ep-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 24px;
    height: 22px;
    padding: 0 8px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    line-height: 1;
    flex-shrink: 0;
}
.ep-count-badge.events-badge {
    background: rgba(240,165,0,0.18);
    border: 1px solid rgba(240,165,0,0.35);
    color: var(--gold2);
}
.ep-count-badge.holiday-badge {
    background: rgba(239,71,111,0.15);
    border: 1px solid rgba(239,71,111,0.35);
    color: var(--rose);
}
body.light .ep-count-badge.events-badge { color: #b45309; }
body.light .ep-count-badge.holiday-badge { color: #e11d48; }

/* Export button */
.ep-export-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(6,214,160,0.08);
    border: 1px solid rgba(6,214,160,0.28);
    color: var(--teal);
    padding: 5px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.2s;
    white-space: nowrap;
    flex-shrink: 0;
}
.ep-export-btn:hover {
    background: rgba(6,214,160,0.16);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(6,214,160,0.15);
}
body.light .ep-export-btn {
    background: rgba(13,148,136,0.07);
    border-color: rgba(13,148,136,0.25);
    color: #0d9488;
}

/* ── Section body ── */
.ep-section-body {
    padding: 16px 20px;
}

/* Empty state */
.ep-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 28px 20px;
    color: var(--muted);
    font-size: 13px;
    text-align: center;
}
.ep-empty-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
    border: 1px dashed var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 4px;
}
body.light .ep-empty-icon {
    background: rgba(0,0,0,0.03);
}

/* ── Events grid ── */
.events-panel-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 10px;
}
@media (max-width: 600px) {
    .events-panel-grid {
        grid-template-columns: 1fr;
    }
}

/* ── Event card ── */
.event-info-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px;
    padding: 0;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    position: relative;
}
body.light .event-info-card {
    background: #f8fafc;
    border-color: rgba(0,0,0,0.07);
}
.event-info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(0,0,0,0.22);
    border-color: rgba(255,255,255,0.13);
}
body.light .event-info-card:hover {
    box-shadow: 0 8px 28px rgba(0,0,0,0.1);
    border-color: rgba(0,0,0,0.12);
}

/* Top accent strip */
.event-card-strip {
    height: 3px;
    width: 100%;
    flex-shrink: 0;
}

/* Card body */
.event-card-body {
    padding: 12px 14px 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    flex: 1;
}

/* Date chip */
.event-info-day {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 6px;
    padding: 2px 8px;
    width: fit-content;
}
body.light .event-info-day {
    background: rgba(0,0,0,0.04);
    border-color: rgba(0,0,0,0.07);
    color: #64748b;
}

.event-info-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
    line-height: 1.3;
    word-break: break-word;
}
body.light .event-info-title { color: #0f172a; }

.event-info-desc {
    font-size: 12px;
    color: var(--muted);
    line-height: 1.4;
    word-break: break-word;
}

.event-time-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    color: var(--teal);
    font-weight: 600;
    background: rgba(6,214,160,0.08);
    border: 1px solid rgba(6,214,160,0.2);
    border-radius: 6px;
    padding: 2px 8px;
    width: fit-content;
}
body.light .event-time-chip {
    color: #0d9488;
    background: rgba(13,148,136,0.07);
    border-color: rgba(13,148,136,0.2);
}

/* Card footer */
.event-card-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
    padding: 8px 14px;
    border-top: 1px solid rgba(255,255,255,0.05);
    background: rgba(255,255,255,0.02);
}
body.light .event-card-footer {
    border-top-color: rgba(0,0,0,0.06);
    background: rgba(0,0,0,0.02);
}

/* Action buttons inside card */
.ep-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid transparent;
    cursor: pointer;
    background: transparent;
    transition: all 0.18s ease;
    font-family: inherit;
    white-space: nowrap;
}
.ep-action-btn.ep-edit {
    color: var(--accent-soft);
    border-color: rgba(96,165,250,0.2);
    background: rgba(96,165,250,0.06);
}
.ep-action-btn.ep-edit:hover {
    background: rgba(96,165,250,0.14);
    border-color: rgba(96,165,250,0.4);
    transform: translateY(-1px);
}
.ep-action-btn.ep-delete {
    color: var(--rose);
    border-color: rgba(239,71,111,0.2);
    background: rgba(239,71,111,0.06);
}
.ep-action-btn.ep-delete:hover {
    background: rgba(239,71,111,0.14);
    border-color: rgba(239,71,111,0.4);
    transform: translateY(-1px);
}
body.light .ep-action-btn.ep-edit { color: #3b82f6; }
body.light .ep-action-btn.ep-delete { color: #e11d48; }

@media (max-width: 480px) {
    .ep-action-btn .ep-btn-label { display: none; }
    .ep-action-btn { padding: 5px 8px; }
}

/* ── Holiday card variant ── */
.holiday-info-card {
    background: rgba(239,71,111,0.04);
    border: 1px solid rgba(239,71,111,0.15);
    border-radius: 14px;
    padding: 12px 14px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    transition: transform 0.2s, box-shadow 0.2s;
}
body.light .holiday-info-card {
    background: rgba(225,29,72,0.03);
    border-color: rgba(225,29,72,0.12);
}
.holiday-info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(239,71,111,0.1);
}

.holiday-day-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(239,71,111,0.12);
    border: 1px solid rgba(239,71,111,0.25);
    flex-shrink: 0;
    gap: 0;
}
body.light .holiday-day-badge {
    background: rgba(225,29,72,0.08);
    border-color: rgba(225,29,72,0.2);
}
.holiday-day-num {
    font-size: 16px;
    font-weight: 800;
    color: var(--rose);
    line-height: 1.1;
}
body.light .holiday-day-num { color: #e11d48; }
.holiday-day-unit {
    font-size: 8px;
    color: var(--rose);
    opacity: 0.7;
    line-height: 1;
}

.holiday-card-content {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.holiday-card-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
    line-height: 1.3;
}
body.light .holiday-card-title { color: #0f172a; }
.holiday-card-subtitle {
    font-size: 11px;
    color: var(--muted);
    direction: ltr;
    text-align: right;
}
body.light .holiday-card-subtitle { color: #64748b; }

/* ── Footer ── */
.ep-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 18px 20px 24px;
    flex-wrap: wrap;
}
.ep-footer-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(240,165,0,0.08);
    border: 1px solid rgba(240,165,0,0.22);
    color: var(--gold);
    padding: 8px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}
.ep-footer-link:hover {
    background: rgba(240,165,0,0.16);
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(240,165,0,0.15);
}
body.light .ep-footer-link {
    background: rgba(180,83,9,0.07);
    border-color: rgba(180,83,9,0.2);
    color: #b45309;
}
.ep-footer-label {
    font-size: 11px;
    color: var(--muted);
    display: flex;
    align-items: center;
    gap: 5px;
}

/* ── Responsive tweaks ── */
@media (max-width: 480px) {
    .ep-section-header { padding: 12px 14px; gap: 8px; }
    .ep-section-body   { padding: 12px 14px; }
    .ep-section-label  { font-size: 13px; }
    .ep-export-btn span.ep-export-label { display: none; }
    .holiday-info-card { padding: 10px 12px; }
}
</style>

{{-- Hidden metadata for Alpine --}}
<span data-month-name="{{ \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) }}"
      data-gregorian-label="{{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}"
      style="display:none;"></span>

<div id="calendar-content">

    {{-- ═══════════════════════════════════════════════════════════
         CALENDAR GRID — UNCHANGED
    ═══════════════════════════════════════════════════════════ --}}
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

            <div class="{{ $classes }}" @click="openModal({{ $day['day'] }})">
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

    {{-- ═══════════════════════════════════════════════════════════
         EVENTS + HOLIDAYS PANEL — UPGRADED
    ═══════════════════════════════════════════════════════════ --}}
    @php
        $daysWithEvents     = collect($days)->filter(fn($d) => empty($d['empty']) && ($d['event_count'] ?? 0) > 0);
        $holidaysThisMonth  = \Qadir\PashtoCalendar\Models\PashtoHoliday::where('year', $year)
                                ->where('month', $month)->orderBy('day')->get();
        $totalEvents        = $daysWithEvents->sum('event_count');
    @endphp

    <div class="events-panel">

        {{-- ── USER EVENTS SECTION ─────────────────────────────── --}}
        <div class="ep-section-header">

            {{-- Icon --}}
            <div class="ep-section-icon events-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8"  y1="2" x2="8"  y2="6"/>
                    <line x1="3"  y1="10" x2="21" y2="10"/>
                </svg>
            </div>

            <span class="ep-section-label">{{ pcal_trans('events_panel_title') }}</span>

            <span class="ep-count-badge events-badge">
                {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($totalEvents) }}
            </span>

            {{-- iCal Export --}}
            <a href="/pashto-calendar/export/{{ $year }}/{{ $month }}.ics"
               class="ep-export-btn"
               title="{{ pcal_trans('export_ical') }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                <span class="ep-export-label">{{ pcal_trans('export') }}</span>
            </a>
        </div>

        <div class="ep-section-body">
            @if($daysWithEvents->isNotEmpty())
                <div class="events-panel-grid">
                    @foreach($daysWithEvents as $day)
                        @foreach($day['events'] as $event)
                            <div class="event-info-card">

                                {{-- Colored top strip --}}
                                <div class="event-card-strip"
                                     style="background: {{ $event['color'] ?? '#f0a500' }};
                                            box-shadow: 0 2px 8px {{ $event['color'] ?? '#f0a500' }}55;">
                                </div>

                                {{-- Card body --}}
                                <div class="event-card-body">

                                    {{-- Date chip --}}
                                    <div class="event-info-day">
                                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2.5"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($day['day']) }}
                                        {{ \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) }}
                                    </div>

                                    <div class="event-info-title">{{ $event['title'] }}</div>

                                    @if($event['description'] ?? false)
                                        <div class="event-info-desc">{{ $event['description'] }}</div>
                                    @endif

                                    @if($event['time'] ?? false)
                                        <div class="event-time-chip">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2.5"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"/>
                                                <polyline points="12 6 12 12 16 14"/>
                                            </svg>
                                            {{ $event['time'] }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Card footer with actions --}}
                                <div class="event-card-footer">
                                    <button type="button"
                                            class="ep-action-btn ep-edit"
                                            @click="editEventFromPanel({{ $event['id'] }}, {{ $day['day'] }})"
                                            title="{{ pcal_trans('edit') }}">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2.2"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        <span class="ep-btn-label">{{ pcal_trans('edit') }}</span>
                                    </button>

                                    <button type="button"
                                            class="ep-action-btn ep-delete"
                                            @click="deleteEvent({{ $event['id'] }})"
                                            title="{{ pcal_trans('delete') }}">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2.2"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14H6L5 6"/>
                                            <path d="M10 11v6M14 11v6"/>
                                            <path d="M9 6V4h6v2"/>
                                        </svg>
                                        <span class="ep-btn-label">{{ pcal_trans('delete') }}</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            @else
                <div class="ep-empty">
                    <div class="ep-empty-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted)">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                        </svg>
                    </div>
                    {{ pcal_trans('no_user_events') }}
                </div>
            @endif
        </div>

        {{-- ── HOLIDAYS SECTION ─────────────────────────────────── --}}
        <div class="ep-section-header holiday-header">
            <div class="ep-section-icon holiday-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
            </div>
            <span class="ep-section-label">{{ pcal_trans('holidays_title') }}</span>
            <span class="ep-count-badge holiday-badge">
                {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($holidaysThisMonth->count()) }}
            </span>
        </div>

        <div class="ep-section-body">
            @if($holidaysThisMonth->isNotEmpty())
                <div class="events-panel-grid">
                    @foreach($holidaysThisMonth as $holiday)
                        <div class="holiday-info-card">

                            {{-- Day badge --}}
                            <div class="holiday-day-badge">
                                <span class="holiday-day-num">
                                    {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($holiday->day) }}
                                </span>
                                <span class="holiday-day-unit">{{ \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) }}</span>
                            </div>

                            {{-- Content --}}
                            <div class="holiday-card-content">
                                <div class="holiday-card-title">{{ $holiday->name }}</div>
                                @if($holiday->name_en)
                                    <div class="holiday-card-subtitle">{{ $holiday->name_en }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="ep-empty">
                    <div class="ep-empty-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted)">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                    {{ pcal_trans('no_holidays') }}
                </div>
            @endif
        </div>

    </div>{{-- /events-panel --}}

    {{-- ═══════════════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════════════ --}}
    <div class="ep-footer">
        <a href="/pashto-calendar/demo" class="ep-footer-link">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                <polyline points="15 3 21 3 21 9"/>
                <line x1="10" y1="14" x2="21" y2="3"/>
            </svg>
            {{ pcal_trans('demo_link') }}
        </a>
        <span class="ep-footer-label">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ pcal_trans('package_name') }}
        </span>
    </div>

</div>{{-- /calendar-content --}}
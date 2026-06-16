{{-- ═══════════════════════════════════════════════════
     MINI CALENDAR COMPONENT — Blade + CSS only
     All PHP/Blade logic preserved exactly.
     Added: previous/next month navigation buttons
════════════════════════════════════════════════════ --}}

@php
    // ── Calculate previous & next month/year ──
    $prevMonth = $month - 1;
    $prevYear  = $year;
    if ($prevMonth < 1) {
        $prevMonth = 12;
        $prevYear--;
    }

    $nextMonth = $month + 1;
    $nextYear  = $year;
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear++;
    }

    // Build query strings preserving all current parameters
    $prevUrl = url()->current() . '?' . http_build_query(array_merge(request()->query(), [
        'month' => $prevMonth,
        'year'  => $prevYear,
    ]));
    $nextUrl = url()->current() . '?' . http_build_query(array_merge(request()->query(), [
        'month' => $nextMonth,
        'year'  => $nextYear,
    ]));
@endphp

<div class="mc-shell">

  {{-- Ambient glow orb --}}
  <div class="mc-orb" aria-hidden="true"></div>

  {{-- ── HEADER ── --}}
  <div class="mc-header">

    <div class="mc-header-inner">
      {{-- 👈 Previous month button --}}
      <a href="{{ $prevUrl }}" class="mc-nav-btn" aria-label="{{ pcal_trans('previous_month') }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
      </a>

      {{-- Month block + dot (centred) --}}
      <div class="mc-month-center">
        <div class="mc-month-block">
          <span class="mc-month-name">{{ \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) }}</span>
          <span class="mc-year-chip">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($year) }}</span>
        </div>
        <div class="mc-header-dot" aria-hidden="true"></div>
      </div>

      {{-- 👉 Next month button --}}
      <a href="{{ $nextUrl }}" class="mc-nav-btn" aria-label="{{ pcal_trans('next_month') }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </a>
    </div>

    {{-- Decorative gradient rule --}}
    <div class="mc-rule" aria-hidden="true"></div>
  </div>

  {{-- ── GRID ── --}}
  {{-- (unchanged) --}}
  <div class="mc-grid" role="grid">
    @foreach(pcal_trans('week_days') as $index => $day)
      <div class="mc-dn {{ $index == 6 ? 'fri' : '' }}" role="columnheader">{{ $day }}</div>
    @endforeach

    @foreach($days as $day)
      @if(isset($day['empty']) && $day['empty'])
        <div class="mc-cell mc-empty" aria-hidden="true"></div>
      @else
        @php
          $cls = 'mc-cell';
          if ($day['is_today']   ?? false) $cls .= ' mc-today';
          if ($day['is_holiday'] ?? false) $cls .= ' mc-holiday';
          if (($day['event_count'] ?? 0) > 0) $cls .= ' mc-has-event';
        @endphp
        <div class="{{ $cls }}"
             title="{{ $day['holiday_name'] ?? '' }}"
             role="gridcell"
             tabindex="0">
          <span class="mc-num">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($day['day']) }}</span>
          @if(($day['event_count'] ?? 0) > 0)
            <span class="mc-event-pip" aria-hidden="true"></span>
          @endif
        </div>
      @endif
    @endforeach
  </div>

  {{-- ── FOOTER LINK ── --}}
  <a href="/pashto-calendar?year={{ $year }}&month={{ $month }}" class="mc-link">
    {{-- (unchanged) --}}
    <span class="mc-link-icon" aria-hidden="true">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="4" width="18" height="18" rx="2"/>
        <line x1="16" y1="2" x2="16" y2="6"/>
        <line x1="8"  y1="2" x2="8"  y2="6"/>
        <line x1="3"  y1="10" x2="21" y2="10"/>
      </svg>
    </span>
    <span class="mc-link-text">{{ pcal_trans('view_full_calendar') }}</span>
    <span class="mc-link-arrow" aria-hidden="true">
      <svg width="11" height="11" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2.5">
        <polyline points="15 18 9 12 15 6"/>
      </svg>
    </span>
  </a>

</div>

<style>
/* ═══════════════════════════════════════════════════════
   MINI CALENDAR — DESIGN TOKENS
   (Tokens unchanged, shown for completeness)
═══════════════════════════════════════════════════════ */
.mc-shell {
  /* Dark defaults */
  --mc-bg:          #0f1829;
  --mc-bg-inner:    rgba(255,255,255,0.028);
  --mc-border:      rgba(245,158,11,0.18);
  --mc-border-rule: rgba(245,158,11,0.1);
  --mc-glow:        rgba(245,158,11,0.07);
  --mc-orb-color:   rgba(245,158,11,0.08);

  --mc-gold:        #f59e0b;
  --mc-gold-lt:     #fcd34d;
  --mc-gold-dim:    rgba(245,158,11,0.12);
  --mc-teal:        #14b8a6;
  --mc-teal-dim:    rgba(20,184,166,0.12);
  --mc-rose:        #f43f5e;
  --mc-rose-dim:    rgba(244,63,94,0.11);

  --mc-text:        #e2e8f0;
  --mc-text-sub:    #94a3b8;
  --mc-text-muted:  rgba(148,163,184,0.45);
  --mc-shadow:      0 24px 48px rgba(0,0,0,0.55), 0 0 0 1px rgba(255,255,255,0.04) inset;
  --mc-shadow-hov:  0 32px 64px rgba(0,0,0,0.65), 0 0 40px rgba(245,158,11,0.06);
  --mc-radius:      22px;
  --mc-cell-radius: 9px;
  --mc-tr:          all 0.2s cubic-bezier(0.4,0,0.2,1);
}

/* Light mode (body.light parent) */
body.light .mc-shell {
  --mc-bg:          #ffffff;
  --mc-bg-inner:    rgba(0,0,0,0.018);
  --mc-border:      rgba(59,130,246,0.18);
  --mc-border-rule: rgba(59,130,246,0.1);
  --mc-glow:        rgba(59,130,246,0.04);
  --mc-orb-color:   rgba(59,130,246,0.06);

  --mc-gold:        #2563eb;
  --mc-gold-lt:     #3b82f6;
  --mc-gold-dim:    rgba(37,99,235,0.08);
  --mc-teal:        #0d9488;
  --mc-teal-dim:    rgba(13,148,136,0.08);
  --mc-rose:        #e11d48;
  --mc-rose-dim:    rgba(225,29,72,0.07);

  --mc-text:        #0f172a;
  --mc-text-sub:    #334155;
  --mc-text-muted:  #94a3b8;
  --mc-shadow:      0 16px 40px rgba(0,0,0,0.1), 0 0 0 1px rgba(0,0,0,0.05);
  --mc-shadow-hov:  0 24px 56px rgba(0,0,0,0.14);
}

/* ═══════════════════════════════════════════════════════
   SHELL / CARD
═══════════════════════════════════════════════════════ */
.mc-shell {
  position: relative;
  width: 100%;
  max-width: 260px;
  min-width: 200px;
  margin: 0 auto;
  background: var(--mc-bg);
  border: 1px solid var(--mc-border);
  border-radius: var(--mc-radius);
  padding: 18px 16px 14px;
  box-shadow: var(--mc-shadow);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  font-family: 'Noto Naskh Arabic', serif;
  overflow: hidden;
  transition: var(--mc-tr), box-shadow 0.3s ease;
  animation: mc-entry 0.45s cubic-bezier(0.34,1.56,0.64,1) both;
}

.mc-shell:hover {
  transform: translateY(-3px);
  box-shadow: var(--mc-shadow-hov);
}

@keyframes mc-entry {
  from { opacity: 0; transform: translateY(14px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

.mc-shell::before {
  content: '';
  position: absolute;
  top: 0; left: 10%; right: 10%;
  height: 1px;
  background: linear-gradient(90deg,
    transparent,
    rgba(255,255,255,0.18) 40%,
    rgba(255,255,255,0.08) 60%,
    transparent);
  border-radius: 0 0 4px 4px;
}

.mc-orb {
  position: absolute;
  top: -30px; right: -30px;
  width: 120px; height: 120px;
  border-radius: 50%;
  background: var(--mc-orb-color);
  filter: blur(30px);
  pointer-events: none;
  animation: mc-orb-breathe 5s ease-in-out infinite alternate;
}

@keyframes mc-orb-breathe {
  from { opacity: 0.7; transform: scale(1); }
  to   { opacity: 1;   transform: scale(1.15); }
}

/* ═══════════════════════════════════════════════════════
   HEADER — Updated for navigation
═══════════════════════════════════════════════════════ */
.mc-header { margin-bottom: 12px; }

.mc-header-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}

/* New: centre block for month + dot */
.mc-month-center {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  flex: 1;
}

/* Month block remains column */
.mc-month-block {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.mc-month-name {
  font-size: 16px;
  font-weight: 700;
  color: var(--mc-text);
  letter-spacing: 0.3px;
  line-height: 1;
}

.mc-year-chip {
  display: inline-flex;
  align-items: center;
  font-size: 11px;
  font-weight: 600;
  color: var(--mc-gold);
  background: var(--mc-gold-dim);
  border: 1px solid rgba(245,158,11,0.2);
  border-radius: 20px;
  padding: 1px 8px;
  letter-spacing: 0.5px;
  width: fit-content;
}

body.light .mc-year-chip {
  border-color: rgba(37,99,235,0.2);
}

/* Pulsing live dot */
.mc-header-dot {
  width: 8px; height: 8px;
  border-radius: 50%;
  background: var(--mc-teal);
  box-shadow: 0 0 8px var(--mc-teal);
  flex-shrink: 0;
  animation: mc-dot-pulse 2.4s ease-in-out infinite;
}

@keyframes mc-dot-pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50%       { opacity: 0.4; transform: scale(0.7); }
}

/* Gradient rule */
.mc-rule {
  height: 1px;
  background: linear-gradient(90deg,
    var(--mc-gold) 0%,
    var(--mc-teal) 50%,
    transparent 100%);
  opacity: 0.25;
  border-radius: 1px;
}

/* ── Navigation buttons ── */
.mc-nav-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  color: var(--mc-text-muted);
  background: transparent;
  border: 1px solid transparent;
  transition: var(--mc-tr);
  text-decoration: none;
  flex-shrink: 0;
}

.mc-nav-btn:hover {
  color: var(--mc-gold);
  background: var(--mc-gold-dim);
  border-color: rgba(245,158,11,0.25);
}

body.light .mc-nav-btn:hover {
  color: var(--mc-gold);
  background: rgba(37,99,235,0.08);
  border-color: rgba(37,99,235,0.2);
}

/* RTL: flip arrow icons automatically */
[dir="rtl"] .mc-nav-btn svg {
  transform: scaleX(-1);
}

/* ═══════════════════════════════════════════════════════
   GRID (unchanged)
═══════════════════════════════════════════════════════ */
.mc-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 2px;
  margin-bottom: 12px;
}

.mc-dn {
  text-align: center;
  font-size: 10px;
  font-weight: 700;
  color: var(--mc-text-muted);
  padding: 5px 0 6px;
  letter-spacing: 0.3px;
  text-transform: uppercase;
}

.mc-dn.fri {
  color: var(--mc-gold);
}

/* ═══════════════════════════════════════════════════════
   CELLS (unchanged)
═══════════════════════════════════════════════════════ */
.mc-cell {
  position: relative;
  aspect-ratio: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  font-size: 12.5px;
  font-weight: 500;
  border-radius: var(--mc-cell-radius);
  cursor: pointer;
  color: var(--mc-text);
  background: var(--mc-bg-inner);
  border: 1px solid transparent;
  transition: var(--mc-tr);
  outline: none;
  user-select: none;
  gap: 1px;
}

.mc-cell:focus-visible {
  border-color: var(--mc-gold);
  box-shadow: 0 0 0 2px var(--mc-gold-dim);
}

.mc-num {
  line-height: 1;
  display: block;
}

.mc-cell:not(.mc-empty):hover {
  background: var(--mc-gold-dim);
  border-color: rgba(245,158,11,0.22);
  transform: scale(1.08);
  z-index: 2;
}

body.light .mc-cell:not(.mc-empty):hover {
  background: rgba(37,99,235,0.08);
  border-color: rgba(37,99,235,0.2);
}

.mc-empty {
  background: transparent;
  border-color: transparent;
  cursor: default;
  pointer-events: none;
}

.mc-today {
  background: linear-gradient(135deg, var(--mc-gold), #c97a00) !important;
  color: #000 !important;
  font-weight: 800 !important;
  border-color: transparent !important;
  box-shadow:
    0 0 0 2px rgba(245,158,11,0.25),
    0 4px 14px rgba(245,158,11,0.35);
}

body.light .mc-today {
  background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
  color: #fff !important;
  box-shadow: 0 0 0 2px rgba(37,99,235,0.25), 0 4px 14px rgba(37,99,235,0.3);
}

.mc-today:hover {
  transform: scale(1.1) !important;
  box-shadow:
    0 0 0 2px rgba(245,158,11,0.4),
    0 6px 20px rgba(245,158,11,0.45) !important;
}

.mc-holiday .mc-num {
  color: var(--mc-rose);
}

.mc-cell.mc-holiday {
  background: var(--mc-rose-dim);
  border-color: rgba(244,63,94,0.15);
}

.mc-cell.mc-holiday:hover {
  background: rgba(244,63,94,0.18) !important;
  border-color: rgba(244,63,94,0.3) !important;
}

.mc-today.mc-holiday .mc-num {
  color: #000;
}
body.light .mc-today.mc-holiday .mc-num { color: #fff; }

.mc-event-pip {
  display: block;
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background: var(--mc-teal);
  box-shadow: 0 0 5px var(--mc-teal);
  flex-shrink: 0;
}

.mc-today .mc-event-pip {
  background: rgba(0,0,0,0.5);
  box-shadow: none;
}

body.light .mc-today .mc-event-pip {
  background: rgba(255,255,255,0.6);
}

/* ═══════════════════════════════════════════════════════
   FOOTER LINK (unchanged)
═══════════════════════════════════════════════════════ */
.mc-link {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 8px 0 2px;
  border-top: 1px solid var(--mc-border-rule);
  text-decoration: none;
  font-size: 11.5px;
  font-weight: 600;
  color: var(--mc-text-muted);
  letter-spacing: 0.3px;
  transition: var(--mc-tr);
  border-radius: 0 0 calc(var(--mc-radius) - 2px) calc(var(--mc-radius) - 2px);
}

.mc-link-icon {
  display: flex;
  align-items: center;
  opacity: 0.6;
  transition: var(--mc-tr);
}

.mc-link-arrow {
  display: flex;
  align-items: center;
  opacity: 0;
  transform: translateX(4px);
  transition: var(--mc-tr);
}

[dir="rtl"] .mc-link-arrow svg {
  transform: scaleX(-1);
}

.mc-link:hover {
  color: var(--mc-gold);
}

.mc-link:hover .mc-link-icon {
  opacity: 1;
  color: var(--mc-gold);
}

.mc-link:hover .mc-link-arrow {
  opacity: 1;
  transform: translateX(0);
}

body.light .mc-link:hover {
  color: var(--mc-gold);
}

/* ═══════════════════════════════════════════════════════
   RESPONSIVE BREAKPOINTS (unchanged)
═══════════════════════════════════════════════════════ */
@media (min-width: 480px) and (max-width: 768px) {
  .mc-shell {
    max-width: 300px;
  }
  .mc-cell { font-size: 13px; border-radius: 10px; }
  .mc-month-name { font-size: 17px; }
  .mc-year-chip  { font-size: 11.5px; }
}

@media (min-width: 1024px) {
  .mc-shell {
    max-width: 280px;
  }
  .mc-cell { font-size: 13px; }
}

@media (max-width: 320px) {
  .mc-shell { padding: 14px 12px 11px; border-radius: 16px; }
  .mc-month-name { font-size: 14px; }
  .mc-year-chip  { font-size: 10px; padding: 1px 6px; }
  .mc-cell { font-size: 11px; border-radius: 7px; }
  .mc-dn   { font-size: 9px; }
  .mc-link { font-size: 10.5px; }
  .mc-grid { gap: 1px; }
}

@container (max-width: 220px) {
  .mc-cell { font-size: 11px; border-radius: 7px; }
}
</style>
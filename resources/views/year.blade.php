<!DOCTYPE html>
<html lang="ps" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ pcal_trans('year_view_page_title') }}</title>
<link rel="stylesheet" href="{{ asset('vendor/pashto-calendar/css/pashto-calendar.css') }}">
<script defer src="{{ asset('vendor/pashto-calendar/js/pashto-calendar.js') }}"></script>
<link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ── TOKENS ── */
:root {
    --gold:      #e8a020;
    --gold-l:    #f5c55a;
    --gold-dim:  rgba(232,160,32,.10);
    --gold-bdr:  rgba(232,160,32,.22);
    --teal:      #10c9b2;
    --teal-dim:  rgba(16,201,178,.10);
    --rose:      #e8445a;
    --rose-dim:  rgba(232,68,90,.09);
    --navy:      #02060f;
    --navy2:     #07111e;
    --navy3:     #0b1528;
    --navy4:     #0f1d34;
    --sub:       rgba(255,255,255,.055);
    --sub2:      rgba(255,255,255,.035);
    --t1:        #edeae1;
    --t2:        #7e90ad;
    --t3:        #3e506a;
    --ease:      cubic-bezier(.4,0,.2,1);
}

*,*::before,*::after { margin:0; padding:0; box-sizing:border-box; }
html { scroll-behavior:smooth; }

body {
    background: var(--navy);
    font-family: 'Noto Naskh Arabic', serif;
    color: var(--t1);
    min-height: 100vh;
    padding: 36px 16px 64px;
    position: relative;
    overflow-x: hidden;
}

/* ── AURORA BG ── */
.aurora { position:fixed; inset:0; pointer-events:none; z-index:0; overflow:hidden; }
.blob   { position:absolute; border-radius:50%; filter:blur(120px); opacity:.12; animation:db 20s ease-in-out infinite alternate; }
.b1 { width:600px;height:600px;background:#e8a020;top:-200px;left:-200px; }
.b2 { width:500px;height:500px;background:#10c9b2;bottom:-150px;right:-150px;animation-delay:-8s; }
.b3 { width:360px;height:360px;background:#e8445a;top:40%;left:52%;translate:-50% -50%;animation-delay:-16s;opacity:.07; }
@keyframes db { from{transform:translate(0,0);} to{transform:translate(30px,22px);} }
body::before {
    content:''; position:fixed; inset:0; z-index:0; pointer-events:none;
    background-image:
        linear-gradient(rgba(255,255,255,.018) 1px,transparent 1px),
        linear-gradient(90deg,rgba(255,255,255,.018) 1px,transparent 1px);
    background-size:52px 52px;
}

/* ── SHELL ── */
.shell { position:relative; z-index:10; max-width:1280px; margin:0 auto; }

/* ── HEADER ── */
.page-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    margin-bottom: 40px;
    animation: up .5s var(--ease) both;
}
@keyframes up { from{opacity:0;transform:translateY(20px);} to{opacity:1;transform:none;} }

.eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--gold-dim); border: 1px solid var(--gold-bdr);
    border-radius: 99px; padding: 4px 14px;
    font-size: 10px; letter-spacing: 2.5px; color: var(--gold-l); text-transform: uppercase;
}
.edot {
    width: 5px; height: 5px; background: var(--gold-l); border-radius: 50%;
    box-shadow: 0 0 8px var(--gold-l); animation: pd 2s ease-in-out infinite;
}
@keyframes pd { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:.35;transform:scale(1.7);} }

.year-display {
    font-size: clamp(52px, 10vw, 96px);
    font-weight: 700;
    line-height: 1;
    letter-spacing: -3px;
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-l) 45%, var(--teal) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    font-family: 'Inter', sans-serif;
}

/* Year navigation */
.year-nav {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--navy3);
    border: 1px solid var(--sub);
    border-radius: 99px;
    padding: 6px 6px 6px 18px;
}
.year-nav-label {
    font-size: 13px; color: var(--t2); font-family: 'Inter', sans-serif; font-weight: 500;
}
.year-nav-btn {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--navy4); border: 1px solid var(--sub);
    color: var(--t2); display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all .2s var(--ease); font-family: inherit;
}
.year-nav-btn:hover { background: var(--gold-dim); border-color: var(--gold-bdr); color: var(--gold-l); }

/* Legend */
.legend {
    display: flex; gap: 16px; flex-wrap: wrap; justify-content: center;
}
.legend-item {
    display: flex; align-items: center; gap: 6px;
    font-size: 11.5px; color: var(--t3); font-family: 'Inter', sans-serif;
}
.legend-dot {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
}
.ld-today   { background: var(--gold); box-shadow: 0 0 6px rgba(232,160,32,.6); }
.ld-holiday { background: var(--rose); box-shadow: 0 0 6px rgba(232,68,90,.5); }
.ld-friday  { background: var(--teal); }

/* ── LOADING STATE ── */
.loading-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
    gap: 16px;
}
.skeleton {
    height: 200px; border-radius: 20px;
    background: linear-gradient(90deg, var(--navy3) 25%, var(--navy4) 50%, var(--navy3) 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}
@keyframes shimmer { from{background-position:200% 0;} to{background-position:-200% 0;} }

/* ── YEAR GRID ── */
.year-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
    gap: 16px;
    animation: up .5s var(--ease) .15s both;
}

/* ── MONTH CARD ── */
.month-card {
    background: var(--navy3);
    border: 1px solid var(--sub);
    border-radius: 20px;
    padding: 18px 16px 14px;
    cursor: pointer;
    transition: transform .22s var(--ease), box-shadow .22s var(--ease), border-color .22s;
    position: relative;
    overflow: hidden;
    text-decoration: none;
    display: block;
    color: inherit;
}
.month-card::before {
    content: ''; position: absolute; top: 0; left: 10%; right: 10%; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.07), transparent);
}
.month-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 48px rgba(0,0,0,.55), 0 0 0 1px var(--gold-bdr);
    border-color: var(--gold-bdr);
}
.month-card.has-today {
    border-color: var(--gold-bdr);
    box-shadow: 0 0 0 1px var(--gold-bdr), 0 0 32px rgba(232,160,32,.08);
}

/* top accent bar per card */
.month-accent {
    position: absolute; top: 0; left: 0; right: 0; height: 2px; border-radius: 20px 20px 0 0;
    opacity: 0; transition: opacity .22s;
    background: linear-gradient(90deg, var(--gold), var(--teal));
}
.month-card:hover .month-accent,
.month-card.has-today .month-accent { opacity: 1; }

/* month header */
.month-hdr {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 12px;
}
.month-name-text {
    font-size: 17px; font-weight: 700; color: var(--t1); line-height: 1;
}
.month-num-badge {
    font-size: 10px; font-weight: 600; color: var(--t3);
    background: var(--sub2); border: 1px solid var(--sub);
    border-radius: 99px; padding: 2px 8px;
    font-family: 'Inter', sans-serif;
}
.month-card.has-today .month-name-text { color: var(--gold-l); }
.month-card.has-today .month-num-badge { background: var(--gold-dim); border-color: var(--gold-bdr); color: var(--gold); }

/* divider */
.month-rule {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--sub), transparent);
    margin-bottom: 10px;
}

/* mini grid */
.mini-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
}
.day-name {
    text-align: center; font-size: 9px; font-weight: 700;
    color: var(--t3); padding: 3px 0;
    text-transform: uppercase; letter-spacing: .5px;
    font-family: 'Inter', sans-serif;
}
.day-name.fri { color: var(--gold); }

.day-cell {
    aspect-ratio: 1;
    display: flex; align-items: center; justify-content: center;
    font-size: 11.5px; border-radius: 6px;
    color: var(--t2); position: relative;
    transition: background .15s;
}
.day-cell:not(.empty):hover { background: var(--sub2); color: var(--t1); }
.day-cell.empty { opacity: 0; pointer-events: none; }

.day-cell.today {
    background: linear-gradient(135deg, var(--gold), #a16207) !important;
    color: #000 !important; font-weight: 700;
    box-shadow: 0 0 12px rgba(232,160,32,.4);
}
.day-cell.holiday { color: var(--rose); }
.day-cell.holiday.today { color: #000; }
.day-cell.friday { color: var(--teal); }

/* event pip */
.day-cell.has-event::after {
    content: ''; position: absolute;
    bottom: 2px; left: 50%; translate: -50% 0;
    width: 3px; height: 3px; border-radius: 50%;
    background: var(--teal);
}
.day-cell.today::after { background: rgba(0,0,0,.5); }

/* month footer link */
.month-footer {
    display: flex; align-items: center; justify-content: center; gap: 5px;
    margin-top: 12px; padding-top: 10px;
    border-top: 1px solid var(--sub);
    font-size: 11px; color: var(--t3);
    font-family: 'Inter', sans-serif; font-weight: 500;
    transition: color .18s;
}
.month-card:hover .month-footer { color: var(--gold-l); }

/* ── FOOTER ── */
.page-footer { text-align: center; margin-top: 36px; animation: up .5s var(--ease) .3s both; }
.back-link {
    display: inline-flex; align-items: center; gap: 7px;
    color: var(--t3); text-decoration: none; font-size: 13px;
    padding: 9px 22px; border: 1px solid var(--sub);
    border-radius: 99px; background: rgba(255,255,255,.025);
    transition: all .2s var(--ease); font-family: 'Inter', sans-serif;
}
.back-link:hover { color: var(--gold-l); border-color: var(--gold-bdr); background: var(--gold-dim); }

/* scrollbar */
::-webkit-scrollbar { width: 4px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--sub); border-radius: 99px; }

/* ── RESPONSIVE ── */
@media (max-width: 600px) {
    .year-display { font-size: 56px; letter-spacing: -2px; }
    .year-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .month-card { padding: 14px 12px 10px; border-radius: 14px; }
    .month-name-text { font-size: 14px; }
    .day-cell { font-size: 10px; }
}
@media (max-width: 380px) {
    body { padding: 24px 10px 48px; }
    .year-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="aurora">
    <div class="blob b1"></div>
    <div class="blob b2"></div>
    <div class="blob b3"></div>
</div>

<div class="shell" x-data="yearApp" x-init="init()">

    <!-- HEADER -->
    <div class="page-header">
        <div class="eyebrow"><span class="edot"></span> {{ pcal_trans('year_view_page_title') }}</div>

        <div class="year-display" x-text="year"></div>

        <div class="year-nav">
            <span class="year-nav-label">{{ pcal_trans('year_view_page_title') }}</span>
            <button class="year-nav-btn" @click="changeYear(-1)" :disabled="loading" title="{{ pcal_trans('previous') }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button class="year-nav-btn" @click="changeYear(1)" :disabled="loading" title="{{ pcal_trans('next') }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>

        <div class="legend">
            <div class="legend-item"><div class="legend-dot ld-today"></div> {{ pcal_trans('today') ?? 'Today' }}</div>
            <div class="legend-item"><div class="legend-dot ld-holiday"></div> {{ pcal_trans('holiday') ?? 'Holiday' }}</div>
            <div class="legend-item"><div class="legend-dot ld-friday"></div> {{ pcal_trans('friday') ?? 'Friday' }}</div>
        </div>
    </div>

    <!-- LOADING SKELETONS -->
    <div x-show="loading" class="loading-grid">
        <template x-for="i in 12" :key="i">
            <div class="skeleton"></div>
        </template>
    </div>

    <!-- YEAR GRID -->
    <div x-show="!loading" class="year-grid">
        <template x-for="(month, mi) in monthsData" :key="mi">
            <a :href="'/pashto-calendar?year=' + year + '&month=' + month.number"
               class="month-card"
               :class="{ 'has-today': month.hasToday }">

                <div class="month-accent"></div>

                <div class="month-hdr">
                    <div class="month-name-text" x-text="month.name"></div>
                    <div class="month-num-badge" x-text="month.number"></div>
                </div>

                <div class="month-rule"></div>

                <div class="mini-grid">
                    <!-- Day name headers -->
                    <template x-for="(dn, di) in weekDays" :key="di">
                        <div class="day-name" :class="{ fri: di === 6 }" x-text="dn"></div>
                    </template>

                    <!-- Day cells -->
                    <template x-for="(cell, ci) in month.days" :key="ci">
                        <div class="day-cell"
                             :class="{
                                 'empty':     !cell.day,
                                 'today':     cell.isToday,
                                 'holiday':   cell.isHoliday && !cell.isToday,
                                 'has-event': cell.hasEvent,
                             }"
                             x-text="cell.day || ''">
                        </div>
                    </template>
                </div>

                <div class="month-footer">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    {{ pcal_trans('view_month') }}
                </div>
            </a>
        </template>
    </div>

    <!-- FOOTER -->
    <div class="page-footer">
        <a href="/pashto-calendar" class="back-link">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            {{ pcal_trans('back_to_calendar') }}
        </a>
    </div>

</div><!-- /shell -->

<script>
const _weekDays    = @json(pcal_trans('week_days'));
const _yearHeading = @json(pcal_trans('year_view_heading'));
const _loadFailed  = @json(pcal_trans('load_year_failed'));

document.addEventListener('alpine:init', () => {
    Alpine.data('yearApp', () => ({
        year:       {{ $year }},
        monthsData: [],
        weekDays:   _weekDays,
        loading:    true,

        async init() {
            await this.loadYear(this.year);
        },

        async loadYear(y) {
            this.loading = true;
            this.monthsData = [];
            try {
                const resp = await fetch(`/pashto-calendar/year-data/${y}`);
                if (!resp.ok) throw new Error('Network error');
                const data = await resp.json();
                // Annotate each month with hasToday flag + hasEvent per cell
                this.monthsData = data.months.map(m => ({
                    ...m,
                    hasToday: m.days.some(d => d.isToday),
                    days: m.days.map(d => ({
                        ...d,
                        hasEvent: d.eventCount > 0,
                    })),
                }));
                this.year = data.year;
            } catch (e) {
                console.error(e);
                alert(_loadFailed);
            } finally {
                this.loading = false;
            }
        },

        async changeYear(delta) {
            await this.loadYear(this.year + delta);
            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('year', this.year);
            window.history.pushState({}, '', url);
        },
    }));
});
</script>
</body>
</html>
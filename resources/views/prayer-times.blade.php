<style>
/* ============================================================
   PRAYER TIMES — CELESTIAL REDESIGN
============================================================ */

/* Keyframes */
@keyframes pt-fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes pt-shimmer {
    0%   { background-position: -200% center; }
    100% { background-position: 200% center; }
}
@keyframes pt-pulse-ring {
    0%   { transform: scale(1);   opacity: 0.6; }
    50%  { transform: scale(1.18); opacity: 0.2; }
    100% { transform: scale(1);   opacity: 0.6; }
}
@keyframes pt-orbit {
    from { transform: rotate(0deg) translateX(90px) rotate(0deg); }
    to   { transform: rotate(360deg) translateX(90px) rotate(-360deg); }
}
@keyframes pt-float {
    0%, 100% { transform: translateY(0px); }
    50%       { transform: translateY(-6px); }
}
@keyframes pt-starTwinkle {
    0%, 100% { opacity: 0.15; transform: scale(1); }
    50%       { opacity: 0.7;  transform: scale(1.4); }
}
@keyframes pt-loading-bar {
    0%   { transform: scaleX(0); opacity: 1; }
    80%  { transform: scaleX(1); opacity: 1; }
    100% { transform: scaleX(1); opacity: 0; }
}
@keyframes pt-glow-pulse {
    0%, 100% { box-shadow: 0 0 14px rgba(251,191,36,0.25), 0 0 40px rgba(251,191,36,0.08); }
    50%       { box-shadow: 0 0 24px rgba(251,191,36,0.5),  0 0 60px rgba(251,191,36,0.18); }
}

/* Root card */
.pt-card {
    position: relative;
    background: linear-gradient(160deg, #05101f 0%, #091524 40%, #0c1a2e 70%, #050d1a 100%);
    border: 1px solid rgba(251,191,36,0.14);
    border-radius: 28px;
    padding: 0;
    width: 100%;
    max-width: 380px;
    margin: 0 auto;
    overflow: hidden;
    box-shadow:
        0 30px 70px rgba(0,0,0,0.65),
        0 0 0 1px rgba(255,255,255,0.04) inset,
        0 1px 0   rgba(255,255,255,0.08) inset;
    font-family: 'Noto Naskh Arabic', 'Amiri', serif;
    animation: pt-fadeUp 0.5s cubic-bezier(0.34,1.26,0.64,1) both;
}

/* Star field background */
.pt-stars {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
}
.pt-star {
    position: absolute;
    background: #fff;
    border-radius: 50%;
    animation: pt-starTwinkle var(--dur, 3s) ease-in-out infinite;
    animation-delay: var(--delay, 0s);
}

/* Ambient orb glows */
.pt-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    pointer-events: none;
    z-index: 0;
}
.pt-orb-gold {
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(251,191,36,0.12), transparent 70%);
    top: -60px; right: -60px;
}
.pt-orb-blue {
    width: 160px; height: 160px;
    background: radial-gradient(circle, rgba(56,189,248,0.09), transparent 70%);
    bottom: 20px; left: -40px;
}
.pt-orb-indigo {
    width: 120px; height: 120px;
    background: radial-gradient(circle, rgba(99,102,241,0.1), transparent 70%);
    top: 50%; left: 50%; transform: translate(-50%,-50%);
}

/* Loading bar */
.pt-loading-bar {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, #f59e0b, #fcd34d, #f59e0b);
    transform-origin: left;
    transform: scaleX(0);
    border-radius: 2px;
    z-index: 20;
}
.pt-loading-bar.active {
    animation: pt-loading-bar 1.2s ease-in-out forwards;
}

/* Header zone */
.pt-header {
    position: relative;
    z-index: 2;
    padding: 26px 24px 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    background: linear-gradient(180deg, rgba(251,191,36,0.05) 0%, transparent 100%);
}

/* Moon SVG icon above city */
.pt-moon-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    animation: pt-float 4s ease-in-out infinite;
}

.pt-city-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(251,191,36,0.1);
    border: 1px solid rgba(251,191,36,0.25);
    border-radius: 99px;
    padding: 5px 16px;
    margin-bottom: 6px;
}
.pt-city-name {
    font-size: 15px;
    font-weight: 700;
    color: #fcd34d;
    letter-spacing: 0.5px;
}
.pt-city-dot {
    width: 6px; height: 6px;
    background: #f59e0b;
    border-radius: 50%;
    animation: pt-pulse-ring 2s ease-in-out infinite;
}
.pt-header-date {
    font-size: 12px;
    color: rgba(148,163,184,0.8);
    margin-top: 4px;
    display: block;
}

/* City selector */
.pt-selector-wrap {
    position: relative;
    z-index: 2;
    padding: 14px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.04);
}
.pt-select-label {
    font-size: 10px;
    color: rgba(148,163,184,0.55);
    letter-spacing: 1.2px;
    text-transform: uppercase;
    margin-bottom: 6px;
    display: block;
}
.pt-select-inner {
    position: relative;
    display: flex;
    align-items: center;
}
.pt-select-icon {
    position: absolute;
    right: 12px;
    top: 50%; transform: translateY(-50%);
    pointer-events: none;
    color: #f59e0b;
    opacity: 0.7;
}
body[dir="ltr"] .pt-select-icon { right: auto; left: 12px; }

.pt-dropdown {
    width: 100%;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(251,191,36,0.18);
    border-radius: 12px;
    padding: 10px 40px 10px 14px;
    color: #e2e8f0;
    font-size: 14px;
    font-family: inherit;
    outline: none;
    cursor: pointer;
    transition: all 0.2s ease;
    appearance: none;
    -webkit-appearance: none;
}
body[dir="ltr"] .pt-dropdown { padding: 10px 14px 10px 40px; }
.pt-dropdown:focus {
    border-color: rgba(251,191,36,0.5);
    box-shadow: 0 0 0 3px rgba(251,191,36,0.08);
    background: rgba(255,255,255,0.08);
}
.pt-dropdown option { background: #091524; color: #e2e8f0; }

/* Prayer grid */
.pt-grid {
    position: relative;
    z-index: 2;
    padding: 16px 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

/* Individual prayer row */
.pt-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px 16px;
    border-radius: 14px;
    border: 1px solid transparent;
    background: rgba(255,255,255,0.025);
    cursor: default;
    transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
    position: relative;
    overflow: hidden;
    animation: pt-fadeUp 0.4s ease both;
}
.pt-row:nth-child(1) { animation-delay: 0.05s; }
.pt-row:nth-child(2) { animation-delay: 0.10s; }
.pt-row:nth-child(3) { animation-delay: 0.15s; }
.pt-row:nth-child(4) { animation-delay: 0.20s; }
.pt-row:nth-child(5) { animation-delay: 0.25s; }
.pt-row:nth-child(6) { animation-delay: 0.30s; }

.pt-row:hover {
    background: rgba(255,255,255,0.055);
    border-color: rgba(251,191,36,0.1);
    transform: translateX(-2px);
}
body[dir="ltr"] .pt-row:hover { transform: translateX(2px); }

/* Active prayer highlight */
.pt-row.is-active {
    background: linear-gradient(120deg, rgba(251,191,36,0.1), rgba(251,191,36,0.04));
    border-color: rgba(251,191,36,0.3);
    animation: pt-fadeUp 0.4s ease both, pt-glow-pulse 2.5s ease-in-out infinite;
}
.pt-row.is-active::before {
    content: '';
    position: absolute;
    right: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #f59e0b, #fcd34d, #f59e0b);
    border-radius: 0 14px 14px 0;
}
body[dir="ltr"] .pt-row.is-active::before { right: auto; left: 0; border-radius: 14px 0 0 14px; }

/* Prayer row shimmer on active */
.pt-row.is-active::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent 0%, rgba(251,191,36,0.05) 50%, transparent 100%);
    background-size: 200% 100%;
    animation: pt-shimmer 3s linear infinite;
}

/* Icon container */
.pt-icon-wrap {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: transform 0.2s ease;
}
.pt-row:hover .pt-icon-wrap { transform: scale(1.1); }

/* Icon color themes per prayer */
.pt-icon-fajr    { background: rgba(99,102,241,0.15);  color: #818cf8; border: 1px solid rgba(99,102,241,0.2);  }
.pt-icon-sunrise { background: rgba(251,146,60,0.15);  color: #fb923c; border: 1px solid rgba(251,146,60,0.2);  }
.pt-icon-dhuhr   { background: rgba(250,204,21,0.15);  color: #facc15; border: 1px solid rgba(250,204,21,0.2);  }
.pt-icon-asr     { background: rgba(56,189,248,0.15);  color: #38bdf8; border: 1px solid rgba(56,189,248,0.2);  }
.pt-icon-maghrib { background: rgba(239,68,68,0.12);   color: #f87171; border: 1px solid rgba(239,68,68,0.2);   }
.pt-icon-isha    { background: rgba(30,41,59,0.8);     color: #94a3b8; border: 1px solid rgba(148,163,184,0.15);}

/* Prayer name */
.pt-name-group {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
    flex: 1;
}
.pt-label {
    font-size: 14px;
    font-weight: 600;
    color: #cbd5e1;
    white-space: nowrap;
    transition: color 0.2s;
}
.pt-row.is-active .pt-label { color: #fcd34d; font-weight: 700; }
.pt-row:hover .pt-label { color: #e2e8f0; }

/* Active badge */
.pt-now-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(251,191,36,0.15);
    border: 1px solid rgba(251,191,36,0.3);
    border-radius: 99px;
    padding: 2px 8px;
    font-size: 9px;
    font-weight: 700;
    color: #f59e0b;
    letter-spacing: 0.8px;
    text-transform: uppercase;
}
.pt-now-dot {
    width: 5px; height: 5px;
    background: #f59e0b;
    border-radius: 50%;
    animation: pt-pulse-ring 1.5s ease-in-out infinite;
}

/* Time display */
.pt-time-group {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    flex-shrink: 0;
}
.pt-time {
    font-size: 16px;
    font-weight: 700;
    color: #fcd34d;
    direction: ltr;
    letter-spacing: 0.5px;
    line-height: 1;
    font-variant-numeric: tabular-nums;
    transition: color 0.2s;
}
.pt-row.is-active .pt-time {
    font-size: 17px;
    color: #fbbf24;
    text-shadow: 0 0 12px rgba(251,191,36,0.4);
}

/* Footer */
.pt-footer {
    position: relative;
    z-index: 2;
    padding: 12px 20px 16px;
    border-top: 1px solid rgba(255,255,255,0.04);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}
.pt-footer-note {
    font-size: 11px;
    color: rgba(148,163,184,0.45);
}
.pt-compass {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    color: rgba(251,191,36,0.5);
}
.pt-compass-icon {
    width: 14px; height: 14px;
    color: #f59e0b;
    opacity: 0.6;
}

/* Divider decoration between header & grid */
.pt-divider {
    position: relative;
    z-index: 2;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(251,191,36,0.2), transparent);
    margin: 0;
}

/* Responsive squeeze */
@media (max-width: 400px) {
    .pt-card { border-radius: 20px; }
    .pt-time  { font-size: 14px; }
    .pt-label { font-size: 13px; }
    .pt-icon-wrap { width: 32px; height: 32px; border-radius: 8px; }
    .pt-icon-wrap svg { width: 14px; height: 14px; }
    .pt-header { padding: 20px 16px 16px; }
    .pt-grid   { padding: 12px 10px 14px; }
}
</style>

{{-- ============================================================
     PRAYER TIMES COMPONENT — BLADE TEMPLATE
============================================================ --}}
<div class="pt-card" x-data="prayerTimesApp('{{ $times['city'] }}')">

    {{-- Loading bar --}}
    <div class="pt-loading-bar" :class="{ active: loading }" x-ref="loadingBar"></div>

    {{-- Ambient stars --}}
    <div class="pt-stars" aria-hidden="true">
        <span class="pt-star" style="width:2px;height:2px;top:12%;left:18%;--dur:2.8s;--delay:0.2s;"></span>
        <span class="pt-star" style="width:1px;height:1px;top:22%;left:72%;--dur:3.5s;--delay:0.8s;"></span>
        <span class="pt-star" style="width:2px;height:2px;top:8%;left:55%;--dur:4.1s;--delay:0.4s;"></span>
        <span class="pt-star" style="width:1px;height:1px;top:35%;left:88%;--dur:2.4s;--delay:1.1s;"></span>
        <span class="pt-star" style="width:2px;height:2px;top:62%;left:8%;--dur:3.2s;--delay:0.6s;"></span>
        <span class="pt-star" style="width:1px;height:1px;top:75%;left:45%;--dur:4.6s;--delay:0.1s;"></span>
        <span class="pt-star" style="width:2px;height:2px;top:88%;left:80%;--dur:2.9s;--delay:1.4s;"></span>
        <span class="pt-star" style="width:1px;height:1px;top:48%;left:30%;--dur:3.8s;--delay:0.9s;"></span>
    </div>

    {{-- Ambient orbs --}}
    <div class="pt-orb pt-orb-gold"  aria-hidden="true"></div>
    <div class="pt-orb pt-orb-blue"  aria-hidden="true"></div>
    <div class="pt-orb pt-orb-indigo" aria-hidden="true"></div>

    {{-- ── HEADER ── --}}
    <div class="pt-header">
        {{-- Animated crescent moon SVG --}}
        <div class="pt-moon-icon">
            <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <radialGradient id="moonGrad" cx="35%" cy="30%" r="65%">
                        <stop offset="0%"   stop-color="#fde68a"/>
                        <stop offset="60%"  stop-color="#f59e0b"/>
                        <stop offset="100%" stop-color="#b45309"/>
                    </radialGradient>
                    <filter id="moonGlow">
                        <feGaussianBlur stdDeviation="2.5" result="coloredBlur"/>
                        <feMerge><feMergeNode in="coloredBlur"/><feMergeNode in="SourceGraphic"/></feMerge>
                    </filter>
                </defs>
                <circle cx="16" cy="18" r="10" fill="url(#moonGrad)" filter="url(#moonGlow)"/>
                <circle cx="21" cy="14" r="8.5" fill="#05101f"/>
                <circle cx="30" cy="8" r="2" fill="#fde68a" opacity="0.6"/>
                <circle cx="28" cy="26" r="1.2" fill="#fcd34d" opacity="0.45"/>
            </svg>
        </div>

        {{-- City badge --}}
        <div style="display:flex; justify-content:center;">
            <div class="pt-city-badge">
                <span class="pt-city-dot"></span>
                <span class="pt-city-name" x-text="cityName"></span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5" style="opacity:0.7">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                    <circle cx="12" cy="9" r="2.5"/>
                </svg>
            </div>
        </div>

        {{-- Pashto date --}}
        <span class="pt-header-date">
            {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber(\Qadir\PashtoCalendar\PashtoCalendar::now()->day) }}
            {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->monthName() }}
            {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber(\Qadir\PashtoCalendar\PashtoCalendar::now()->year) }}
        </span>
    </div>

    {{-- ── CITY SELECTOR ── --}}
    <div class="pt-selector-wrap">
        <span class="pt-select-label">{{ pcal_trans('select_city') ?? 'ښار غوره کړئ' }}</span>
        <div class="pt-select-inner">
            <select x-model="city" @change="loadTimes" class="pt-dropdown">
                @foreach(config('pashto-prayer-cities') as $key => $cityData)
                    <option value="{{ $key }}">{{ $cityData['name'] }}</option>
                @endforeach
            </select>
            <span class="pt-select-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </span>
        </div>
    </div>

    <div class="pt-divider"></div>

    {{-- ── PRAYER GRID ── --}}
    <div class="pt-grid">
        <template x-for="(time, key) in times" :key="key">
            <div class="pt-row" :class="{ 'is-active': isCurrentPrayer(key) }">

                {{-- Icon --}}
                <div class="pt-icon-wrap" :class="'pt-icon-' + key">
                    {{-- Fajr --}}
                    <template x-if="key === 'fajr'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9z"/><path d="M20 3v4M22 5h-4"/></svg>
                    </template>
                    {{-- Sunrise --}}
                    <template x-if="key === 'sunrise'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M4.93 4.93l2.83 2.83M2 12h4M20 12h2M19.07 4.93l-2.83 2.83"/><path d="M5 17H3M21 17h-2M7 17a5 5 0 0 1 10 0"/><line x1="3" y1="21" x2="21" y2="21"/></svg>
                    </template>
                    {{-- Dhuhr --}}
                    <template x-if="key === 'dhuhr'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg>
                    </template>
                    {{-- Asr --}}
                    <template x-if="key === 'asr'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M2 12h2M20 12h2M12 2v2M12 20v2"/><path d="M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/><path d="M8 12h4l2 3"/></svg>
                    </template>
                    {{-- Maghrib --}}
                    <template x-if="key === 'maghrib'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 18a5 5 0 0 0-10 0"/><line x1="12" y1="9" x2="12" y2="2"/><line x1="4.22" y1="10.22" x2="5.64" y2="11.64"/><line x1="1" y1="18" x2="3" y2="18"/><line x1="21" y1="18" x2="23" y2="18"/><line x1="18.36" y1="11.64" x2="19.78" y2="10.22"/><line x1="23" y1="22" x2="1" y2="22"/><polyline points="16 5 12 9 8 5"/></svg>
                    </template>
                    {{-- Isha --}}
                    <template x-if="key === 'isha'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    </template>
                </div>

                {{-- Name + now badge --}}
                <div class="pt-name-group">
                    <span class="pt-label" x-text="labels[key]"></span>
                    <span x-show="isCurrentPrayer(key)" class="pt-now-badge">
                        <span class="pt-now-dot"></span>
                        اوس
                    </span>
                </div>

                {{-- Time --}}
                <div class="pt-time-group">
                    <span class="pt-time" x-text="time"></span>
                </div>
            </div>
        </template>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="pt-footer">
        <span class="pt-footer-note">{{ pcal_trans('prayer_times_source') ?? 'د لمانځه وختونه' }}</span>
        <div class="pt-compass">
            <svg class="pt-compass-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            قبله: {{ number_format(config('pashto-prayer-cities.' . $times['city'] . '.qibla', 257)) }}&deg;
        </div>
    </div>

</div>

<script>
function prayerTimesApp(initialCity) {
    const prayerLabels = {
        fajr:    @json(pcal_trans('prayer_fajr')),
        sunrise: @json(pcal_trans('prayer_sunrise')),
        dhuhr:   @json(pcal_trans('prayer_dhuhr')),
        asr:     @json(pcal_trans('prayer_asr')),
        maghrib: @json(pcal_trans('prayer_maghrib')),
        isha:    @json(pcal_trans('prayer_isha'))
    };

    // Approximate hour ranges for active prayer detection
    const prayerOrder = ['fajr','sunrise','dhuhr','asr','maghrib','isha'];

    return {
        city:     initialCity,
        cityName: @json($times['city_name']),
        loading:  false,
        times: {
            fajr:    @json($times['fajr']),
            sunrise: @json($times['sunrise']),
            dhuhr:   @json($times['dhuhr']),
            asr:     @json($times['asr']),
            maghrib: @json($times['maghrib']),
            isha:    @json($times['isha'])
        },
        labels: prayerLabels,
        currentPrayer: '',

        init() {
            this.detectCurrentPrayer();
            setInterval(() => this.detectCurrentPrayer(), 60000);
        },

        parseTime(str) {
            if (!str) return null;
            const clean = str.trim().replace(/\s*(AM|PM|am|pm)\s*/i, '');
            const [h, m] = clean.split(':').map(Number);
            const suffix = str.toUpperCase().includes('PM') ? 'PM' : (str.toUpperCase().includes('AM') ? 'AM' : '');
            let hour = h;
            if (suffix === 'PM' && hour < 12) hour += 12;
            if (suffix === 'AM' && hour === 12) hour = 0;
            return hour * 60 + (m || 0);
        },

        detectCurrentPrayer() {
            const now = new Date();
            const nowMin = now.getHours() * 60 + now.getMinutes();
            const times = prayerOrder.map(k => ({ key: k, min: this.parseTime(this.times[k]) }))
                                      .filter(t => t.min !== null)
                                      .sort((a, b) => a.min - b.min);
            let current = times[times.length - 1].key;
            for (let i = 0; i < times.length; i++) {
                if (nowMin < times[i].min) {
                    current = i === 0 ? times[times.length - 1].key : times[i - 1].key;
                    break;
                }
                if (i === times.length - 1) current = times[i].key;
            }
            this.currentPrayer = current;
        },

        isCurrentPrayer(key) {
            return this.currentPrayer === key;
        },

        async loadTimes() {
            this.loading = true;
            try {
                const resp = await fetch(`/pashto-calendar/prayer-times/${this.city}`);
                if (!resp.ok) throw new Error('Network error');
                const data = await resp.json();
                this.times    = data.times;
                this.cityName = data.city_name;
                this.detectCurrentPrayer();
            } catch (e) {
                alert('Failed to load prayer times');
            } finally {
                setTimeout(() => { this.loading = false; }, 1200);
            }
        }
    };
}
</script>
```
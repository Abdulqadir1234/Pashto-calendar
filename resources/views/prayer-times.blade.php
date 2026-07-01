<style>
/* ============================================================
   PRAYER TIMES — TOKENS & KEYFRAMES
============================================================ */
@keyframes pt-fadeUp   { from{opacity:0;transform:translateY(14px);} to{opacity:1;transform:none;} }
@keyframes pt-shimmer  { 0%{background-position:-200% center;} 100%{background-position:200% center;} }
@keyframes pt-pulseDot { 0%,100%{transform:scale(1);opacity:.7;} 50%{transform:scale(1.6);opacity:.2;} }
@keyframes pt-float    { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-6px);} }
@keyframes pt-twinkle  { 0%,100%{opacity:.12;transform:scale(1);} 50%{opacity:.65;transform:scale(1.4);} }
@keyframes pt-loadBar  {
    0%  {transform:scaleX(0);opacity:1;}
    80% {transform:scaleX(1);opacity:1;}
    100%{transform:scaleX(1);opacity:0;}
}

.pt-card {
    position:relative;
    background:linear-gradient(160deg,#05101f 0%,#091524 40%,#0c1a2e 70%,#050d1a 100%);
    border:1px solid rgba(251,191,36,.14);
    border-radius:28px;
    width:100%;max-width:380px;
    margin:0 auto;
    overflow:hidden;
    box-shadow:0 30px 70px rgba(0,0,0,.65),0 0 0 1px rgba(255,255,255,.04) inset,0 1px 0 rgba(255,255,255,.08) inset;
    font-family:'Noto Naskh Arabic','Amiri',serif;
    animation:pt-fadeUp .5s cubic-bezier(.34,1.26,.64,1) both;
}

.pt-stars{position:absolute;inset:0;pointer-events:none;z-index:0;overflow:hidden;}
.pt-star {position:absolute;background:#fff;border-radius:50%;animation:pt-twinkle var(--dur,3s) ease-in-out infinite var(--delay,0s);}

.pt-orb        {position:absolute;border-radius:50%;filter:blur(60px);pointer-events:none;z-index:0;}
.pt-orb-gold   {width:200px;height:200px;background:radial-gradient(circle,rgba(251,191,36,.12),transparent 70%);top:-60px;right:-60px;}
.pt-orb-blue   {width:160px;height:160px;background:radial-gradient(circle,rgba(56,189,248,.09),transparent 70%);bottom:20px;left:-40px;}
.pt-orb-indigo {width:120px;height:120px;background:radial-gradient(circle,rgba(99,102,241,.10),transparent 70%);top:50%;left:50%;transform:translate(-50%,-50%);}

.pt-loading-bar{
    position:absolute;top:0;left:0;right:0;height:2px;
    background:linear-gradient(90deg,#f59e0b,#fcd34d,#f59e0b);
    transform-origin:left;border-radius:2px;z-index:20;
    opacity:0;transform:scaleX(0);
}
.pt-loading-bar.active{animation:pt-loadBar 1.2s ease-in-out forwards;}

.pt-header{
    position:relative;z-index:2;
    padding:26px 24px 18px;
    text-align:center;
    border-bottom:1px solid rgba(255,255,255,.05);
    background:linear-gradient(180deg,rgba(251,191,36,.05) 0%,transparent 100%);
}
.pt-moon-icon{display:flex;align-items:center;justify-content:center;margin-bottom:10px;animation:pt-float 4s ease-in-out infinite;}
.pt-city-badge{
    display:inline-flex;align-items:center;gap:6px;
    background:rgba(251,191,36,.10);border:1px solid rgba(251,191,36,.25);
    border-radius:99px;padding:5px 16px;margin-bottom:8px;
}
.pt-city-name{font-size:15px;font-weight:700;color:#fcd34d;letter-spacing:.5px;}
.pt-city-dot{width:6px;height:6px;background:#f59e0b;border-radius:50%;animation:pt-pulseDot 2s ease-in-out infinite;}

.pt-dates{display:flex;flex-direction:column;gap:2px;align-items:center;margin-bottom:14px;}
.pt-date-pashto{font-size:13px;color:rgba(226,232,240,.85);direction:rtl;}
.pt-date-greg  {font-size:11px;color:rgba(148,163,184,.55);direction:ltr;font-family:sans-serif;}

.pt-countdown{
    display:flex;align-items:center;justify-content:center;gap:14px;
    background:rgba(255,255,255,.03);
    border:1px solid rgba(251,191,36,.16);
    border-radius:18px;
    padding:14px 18px;
}
.pt-ring-wrap{position:relative;width:64px;height:64px;flex-shrink:0;}
.pt-ring-svg{transform:rotate(-90deg);width:64px;height:64px;}
.pt-ring-bg{fill:none;stroke:rgba(255,255,255,.08);stroke-width:5;}
.pt-ring-fg{fill:none;stroke:url(#pt-ring-grad);stroke-width:5;stroke-linecap:round;transition:stroke-dashoffset 1s linear;}
.pt-ring-center{
    position:absolute;inset:0;display:flex;align-items:center;justify-content:center;
    font-size:9px;color:#94a3b8;
}
.pt-countdown-info{display:flex;flex-direction:column;gap:3px;text-align:start;}
.pt-countdown-label{font-size:10px;letter-spacing:1.2px;text-transform:uppercase;color:rgba(148,163,184,.6);}
.pt-countdown-time{
    font-size:20px;font-weight:700;color:#fff;
    font-variant-numeric:tabular-nums;direction:ltr;letter-spacing:.5px;
    text-shadow:0 0 16px rgba(251,191,36,.3);
}
.pt-countdown-sub{font-size:10.5px;color:rgba(148,163,184,.5);}

.pt-selector-wrap{position:relative;z-index:2;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.04);}
.pt-select-label{font-size:10px;color:rgba(148,163,184,.55);letter-spacing:1.2px;text-transform:uppercase;margin-bottom:6px;display:block;}
.pt-select-inner{position:relative;display:flex;align-items:center;}
.pt-select-icon{position:absolute;right:12px;top:50%;transform:translateY(-50%);pointer-events:none;color:#f59e0b;opacity:.7;}
body[dir="ltr"] .pt-select-icon{right:auto;left:12px;}
.pt-dropdown{
    width:100%;background:rgba(255,255,255,.05);
    border:1px solid rgba(251,191,36,.18);border-radius:12px;
    padding:10px 40px 10px 14px;color:#e2e8f0;
    font-size:14px;font-family:inherit;outline:none;cursor:pointer;
    transition:all .2s;appearance:none;-webkit-appearance:none;
}
body[dir="ltr"] .pt-dropdown{padding:10px 14px 10px 40px;}
.pt-dropdown:focus{border-color:rgba(251,191,36,.5);box-shadow:0 0 0 3px rgba(251,191,36,.08);background:rgba(255,255,255,.08);}
.pt-dropdown option{background:#091524;color:#e2e8f0;}

.pt-divider{position:relative;z-index:2;height:1px;background:linear-gradient(90deg,transparent,rgba(251,191,36,.2),transparent);}

.pt-grid{position:relative;z-index:2;padding:14px 14px 18px;display:flex;flex-direction:column;gap:7px;}

.pt-row{
    display:flex;align-items:center;justify-content:space-between;
    padding:12px 14px;border-radius:14px;
    border:1px solid transparent;
    background:rgba(255,255,255,.025);
    transition:all .22s cubic-bezier(.4,0,.2,1);
    position:relative;overflow:hidden;
    animation:pt-fadeUp .4s ease both;
}
.pt-row:nth-child(1){animation-delay:.04s;}
.pt-row:nth-child(2){animation-delay:.08s;}
.pt-row:nth-child(3){animation-delay:.12s;}
.pt-row:nth-child(4){animation-delay:.16s;}
.pt-row:nth-child(5){animation-delay:.20s;}
.pt-row:nth-child(6){animation-delay:.24s;}

.pt-row:hover{background:rgba(255,255,255,.05);border-color:rgba(251,191,36,.1);transform:translateX(-2px);}
body[dir="ltr"] .pt-row:hover{transform:translateX(2px);}

.pt-row.is-active{
    background:linear-gradient(120deg,rgba(251,191,36,.10),rgba(251,191,36,.04));
    border-color:rgba(251,191,36,.28);
    box-shadow:0 0 14px rgba(251,191,36,.18),0 0 40px rgba(251,191,36,.06);
}
.pt-row.is-active::before{
    content:'';position:absolute;
    right:0;top:0;bottom:0;width:3px;
    background:linear-gradient(180deg,#f59e0b,#fcd34d,#f59e0b);
    border-radius:0 14px 14px 0;
}
body[dir="ltr"] .pt-row.is-active::before{right:auto;left:0;border-radius:14px 0 0 14px;}
.pt-row.is-active::after{
    content:'';position:absolute;inset:0;
    background:linear-gradient(90deg,transparent,rgba(251,191,36,.05),transparent);
    background-size:200% 100%;
    animation:pt-shimmer 3s linear infinite;
}

.pt-row.is-next{border-color:rgba(56,189,248,.2);}
.pt-row.is-next .pt-label{color:#7dd3fc;}

.pt-icon-wrap{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:transform .2s;}
.pt-row:hover .pt-icon-wrap{transform:scale(1.1);}
.pt-icon-fajr   {background:rgba(99,102,241,.15);color:#818cf8;border:1px solid rgba(99,102,241,.2);}
.pt-icon-sunrise{background:rgba(251,146,60,.15);color:#fb923c;border:1px solid rgba(251,146,60,.2);}
.pt-icon-dhuhr  {background:rgba(250,204,21,.15);color:#facc15;border:1px solid rgba(250,204,21,.2);}
.pt-icon-asr    {background:rgba(56,189,248,.15);color:#38bdf8;border:1px solid rgba(56,189,248,.2);}
.pt-icon-maghrib{background:rgba(239,68,68,.12); color:#f87171;border:1px solid rgba(239,68,68,.2);}
.pt-icon-isha   {background:rgba(30,41,59,.8);   color:#94a3b8;border:1px solid rgba(148,163,184,.15);}

.pt-name-group{display:flex;align-items:center;gap:8px;min-width:0;flex:1;padding:0 10px;}
.pt-label{font-size:14px;font-weight:600;color:#cbd5e1;white-space:nowrap;transition:color .2s;}
.pt-row.is-active .pt-label{color:#fcd34d;font-weight:700;}
.pt-row:hover .pt-label{color:#e2e8f0;}

.pt-now-badge{
    display:inline-flex;align-items:center;gap:4px;
    background:rgba(251,191,36,.15);border:1px solid rgba(251,191,36,.3);
    border-radius:99px;padding:2px 8px;
    font-size:9px;font-weight:700;color:#f59e0b;
    letter-spacing:.8px;text-transform:uppercase;
}
.pt-now-dot{width:5px;height:5px;background:#f59e0b;border-radius:50%;animation:pt-pulseDot 1.5s ease-in-out infinite;}

.pt-next-badge{
    display:inline-flex;align-items:center;
    background:rgba(56,189,248,.12);border:1px solid rgba(56,189,248,.25);
    border-radius:99px;padding:2px 7px;
    font-size:9px;font-weight:700;color:#38bdf8;
    letter-spacing:.6px;text-transform:uppercase;
}

.pt-time{
    font-size:15px;font-weight:700;color:#fcd34d;
    direction:ltr;letter-spacing:.5px;line-height:1;
    font-variant-numeric:tabular-nums;
    transition:all .2s;flex-shrink:0;
}
.pt-row.is-active .pt-time{font-size:16px;color:#fbbf24;text-shadow:0 0 10px rgba(251,191,36,.35);}

.pt-row.is-sunrise{opacity:.65;}
.pt-row.is-sunrise .pt-label{font-style:italic;}
.pt-row.is-sunrise .pt-time{color:#94a3b8;}

.pt-footer{
    position:relative;z-index:2;
    padding:11px 20px 15px;
    border-top:1px solid rgba(255,255,255,.04);
    display:flex;align-items:center;justify-content:space-between;gap:10px;
}
.pt-footer-note{font-size:11px;color:rgba(148,163,184,.4);}
.pt-qibla{display:flex;align-items:center;gap:5px;font-size:11px;color:rgba(251,191,36,.5);}
.pt-qibla svg{width:14px;height:14px;color:#f59e0b;opacity:.6;}

@media(max-width:400px){
    .pt-card{border-radius:20px;}
    .pt-time{font-size:13px;}
    .pt-label{font-size:12.5px;}
    .pt-icon-wrap{width:30px;height:30px;border-radius:8px;}
    .pt-icon-wrap svg{width:13px;height:13px;}
    .pt-header{padding:18px 14px 14px;}
    .pt-grid{padding:10px 10px 14px;gap:6px;}
    .pt-countdown{padding:12px 14px;gap:10px;}
    .pt-ring-wrap,.pt-ring-svg{width:54px;height:54px;}
    .pt-countdown-time{font-size:17px;}
}
</style>

{{-- ============================================================
     PRAYER TIMES COMPONENT
============================================================ --}}
<div class="pt-card" x-data="prayerTimesApp('{{ $times['city'] }}')">

    <div class="pt-loading-bar" :class="{ active: loading }" :key="loadKey"></div>

    <div class="pt-stars" aria-hidden="true">
        <span class="pt-star" style="width:2px;height:2px;top:11%;left:17%;--dur:2.8s;--delay:.2s"></span>
        <span class="pt-star" style="width:1px;height:1px;top:22%;left:73%;--dur:3.5s;--delay:.8s"></span>
        <span class="pt-star" style="width:2px;height:2px;top:8%;left:55%;--dur:4.1s;--delay:.4s"></span>
        <span class="pt-star" style="width:1px;height:1px;top:35%;left:88%;--dur:2.4s;--delay:1.1s"></span>
        <span class="pt-star" style="width:2px;height:2px;top:63%;left:8%;--dur:3.2s;--delay:.6s"></span>
        <span class="pt-star" style="width:1px;height:1px;top:76%;left:45%;--dur:4.6s;--delay:.1s"></span>
        <span class="pt-star" style="width:2px;height:2px;top:88%;left:81%;--dur:2.9s;--delay:1.4s"></span>
        <span class="pt-star" style="width:1px;height:1px;top:48%;left:30%;--dur:3.8s;--delay:.9s"></span>
    </div>

    <div class="pt-orb pt-orb-gold"   aria-hidden="true"></div>
    <div class="pt-orb pt-orb-blue"   aria-hidden="true"></div>
    <div class="pt-orb pt-orb-indigo" aria-hidden="true"></div>

    {{-- ── HEADER ── --}}
    <div class="pt-header">
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
                <circle cx="30" cy="8" r="2"    fill="#fde68a" opacity=".6"/>
                <circle cx="28" cy="26" r="1.2" fill="#fcd34d" opacity=".45"/>
            </svg>
        </div>

        <div style="display:flex;justify-content:center;">
            <div class="pt-city-badge">
                <span class="pt-city-dot"></span>
                <span class="pt-city-name" x-text="cityName"></span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5" style="opacity:.7">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                    <circle cx="12" cy="9" r="2.5"/>
                </svg>
            </div>
        </div>

        <div class="pt-dates">
            <span class="pt-date-pashto">
                {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber(\Qadir\PashtoCalendar\PashtoCalendar::now()->day) }}
                {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->monthName() }}
                {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber(\Qadir\PashtoCalendar\PashtoCalendar::now()->year) }}
            </span>
            <span class="pt-date-greg">{{ now()->format('d F Y, H:i') }} ({{ config('pashto-calendar.timezone', 'Asia/Kabul') }})</span>
        </div>

        {{-- ── LIVE COUNTDOWN RING ── --}}
        <div class="pt-countdown">
            <div class="pt-ring-wrap">
                <svg class="pt-ring-svg" viewBox="0 0 64 64">
                    <defs>
                        <linearGradient id="pt-ring-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%"   stop-color="#f59e0b"/>
                            <stop offset="100%" stop-color="#fcd34d"/>
                        </linearGradient>
                    </defs>
                    <circle class="pt-ring-bg" cx="32" cy="32" r="27"/>
                    <circle class="pt-ring-fg" cx="32" cy="32" r="27"
                            stroke-dasharray="169.6"
                            :stroke-dashoffset="ringOffset"/>
                </svg>
                <div class="pt-ring-center">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fcd34d" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
            </div>
            <div class="pt-countdown-info">
                <span class="pt-countdown-label">{{ pcal_trans('time_remaining') ?? 'پاتې وخت' }}</span>
                <span class="pt-countdown-time" x-text="countdownText"></span>
                <span class="pt-countdown-sub">
                    <span x-text="nextLabel"></span> — <span x-text="nextTimeStr"></span>
                </span>
            </div>
        </div>
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
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </span>
        </div>
    </div>

    <div class="pt-divider"></div>

    {{-- ── PRAYER GRID ── --}}
    <div class="pt-grid">
        <template x-for="(prayer, idx) in prayers" :key="prayer.key">
            <div class="pt-row"
                 :class="{
                     'is-active':  currentPrayer === prayer.key,
                     'is-next':    nextPrayerKey === prayer.key,
                     'is-sunrise': prayer.key === 'sunrise'
                 }">

                <div class="pt-icon-wrap" :class="'pt-icon-' + prayer.key">
                    <template x-if="prayer.key === 'fajr'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9z"/><path d="M20 3v4M22 5h-4"/></svg>
                    </template>
                    <template x-if="prayer.key === 'sunrise'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17H3M21 17h-2M7 17a5 5 0 0 1 10 0"/><line x1="12" y1="9" x2="12" y2="2"/><line x1="4.22" y1="10.22" x2="5.64" y2="11.64"/><line x1="18.36" y1="11.64" x2="19.78" y2="10.22"/><line x1="3" y1="21" x2="21" y2="21"/></svg>
                    </template>
                    <template x-if="prayer.key === 'dhuhr'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg>
                    </template>
                    <template x-if="prayer.key === 'asr'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M2 12h2M20 12h2M12 2v2M12 20v2"/><path d="M8 12h4l2 3"/></svg>
                    </template>
                    <template x-if="prayer.key === 'maghrib'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 18a5 5 0 0 0-10 0"/><line x1="12" y1="9" x2="12" y2="2"/><polyline points="16 5 12 9 8 5"/><line x1="3" y1="22" x2="21" y2="22"/></svg>
                    </template>
                    <template x-if="prayer.key === 'isha'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    </template>
                </div>

                <div class="pt-name-group">
                    <span class="pt-label" x-text="prayer.label"></span>
                    <span x-show="currentPrayer === prayer.key" class="pt-now-badge">
                        <span class="pt-now-dot"></span>
                        اوس
                    </span>
                    <span x-show="nextPrayerKey === prayer.key" class="pt-next-badge">
                        راتلونکی
                    </span>
                </div>

                <span class="pt-time" x-text="prayer.time"></span>
            </div>
        </template>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="pt-footer">
        <span class="pt-footer-note">{{ pcal_trans('prayer_times_source') ?? 'د لمانځه وختونه' }}</span>
        @php
            $qibla = config('pashto-prayer-cities.' . ($times['city'] ?? 'kabul') . '.qibla', 257);
        @endphp
        <div class="pt-qibla">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            قبله: {{ number_format((float)$qibla) }}&deg;
        </div>
    </div>

</div>

<script>
function prayerTimesApp(initialCity) {
    const _labels = {
        fajr:    @json(pcal_trans('prayer_fajr')    ?? 'فجر'),
        sunrise: @json(pcal_trans('prayer_sunrise') ?? 'لمر ختل'),
        dhuhr:   @json(pcal_trans('prayer_dhuhr')   ?? 'غرمه'),
        asr:     @json(pcal_trans('prayer_asr')     ?? 'عصر'),
        maghrib: @json(pcal_trans('prayer_maghrib') ?? 'ماښام'),
        isha:    @json(pcal_trans('prayer_isha')     ?? 'ماسخوتن'),
    };

    /* Chronological order EXCLUDING sunrise — sunrise marks the END
       of Fajr's window, it is not itself a prayer with a "current" state. */
    const PRAYER_ORDER = ['fajr', 'dhuhr', 'asr', 'maghrib', 'isha'];
    const RING_CIRCUMFERENCE = 169.6;

    return {
        city:           initialCity,
        cityName:       @json($times['city_name']),
        loading:        false,
        loadKey:        0,
        currentPrayer:  '',
        nextPrayerKey:  '',
        nextLabel:      '',
        nextTimeStr:    '',
        countdownText:  '--:--:--',
        ringOffset:     RING_CIRCUMFERENCE,
        _timer:         null,
        _badData:       false, // true if _24 fields were missing — surfaced in console

        rawTimes: {
            fajr:    @json($times['fajr']),
            sunrise: @json($times['sunrise']),
            dhuhr:   @json($times['dhuhr']),
            asr:     @json($times['asr']),
            maghrib: @json($times['maghrib']),
            isha:    @json($times['isha']),
        },

        /* 24-hour values — REQUIRED for correct detection/countdown.
           If these come through as null, _badData flags it loudly
           instead of silently mis-highlighting a prayer. */
        rawTimes24: {
            fajr:    @json($times['fajr_24']    ?? null),
            sunrise: @json($times['sunrise_24'] ?? null),
            dhuhr:   @json($times['dhuhr_24']   ?? null),
            asr:     @json($times['asr_24']     ?? null),
            maghrib: @json($times['maghrib_24'] ?? null),
            isha:    @json($times['isha_24']    ?? null),
        },

        get prayers() {
            return ['fajr','sunrise','dhuhr','asr','maghrib','isha'].map(k => ({
                key:   k,
                label: _labels[k] || k,
                time:  this.rawTimes[k] || '—',
            }));
        },

        init() {
            this._checkData();
            this.tick();
            this._timer = setInterval(() => this.tick(), 1000);
        },

        destroy() {
            if (this._timer) clearInterval(this._timer);
        },

        _checkData() {
            const missing = PRAYER_ORDER.filter(k => !this.rawTimes24[k]);
            if (missing.length) {
                this._badData = true;
                console.error(
                    '[PashtoCalendar PrayerTimes] Missing 24-hour time data for:',
                    missing,
                    '— the /pashto-calendar/prayer-times/{city} route must return a `times_24` object. ' +
                    'Check that PrayerTimeService::getTimes() includes the *_24 keys and that the route ' +
                    'forwards them as `times_24` in its JSON response.'
                );
            } else {
                this._badData = false;
            }
        },

        /** Build a real Date object for "HH:MM" on a given base date (+ optional day offset). */
        _buildDate(str, baseDate, dayOffset = 0) {
            if (!str) return null;
            const parts = str.split(':');
            const h = parseInt(parts[0], 10);
            const m = parseInt(parts[1], 10);
            if (isNaN(h) || isNaN(m)) return null;
            const d = new Date(baseDate);
            d.setDate(d.getDate() + dayOffset);
            d.setHours(h, m, 0, 0);
            return d;
        },

        /**
         * Bulletproof current/next detection.
         * Builds actual Date objects for every prayer today, plus
         * tomorrow's Fajr (to handle the overnight/Isha wraparound),
         * sorts them chronologically, and picks:
         *   current = the most recent prayer whose time has passed
         *   next    = the very next prayer time after now
         * No index arithmetic, no off-by-one wraparound bugs.
         */
        _computeSchedule(now) {
            const events = [];

            for (const key of PRAYER_ORDER) {
                const t = this._buildDate(this.rawTimes24[key], now, 0);
                if (t) events.push({ key, time: t, label: this.rawTimes[key] });
            }
            // tomorrow's Fajr — needed so "next" resolves correctly after Isha
            const tomorrowFajr = this._buildDate(this.rawTimes24.fajr, now, 1);
            if (tomorrowFajr) {
                events.push({ key: 'fajr', time: tomorrowFajr, label: this.rawTimes.fajr, isTomorrow: true });
            }

            events.sort((a, b) => a.time - b.time);

            let current = null;
            let next    = null;
            for (let i = 0; i < events.length; i++) {
                if (events[i].time <= now) {
                    current = events[i];
                } else {
                    next = events[i];
                    break;
                }
            }
            // Before today's Fajr has happened yet: we are still in last night's Isha window
            if (!current) {
                current = { key: 'isha', time: null, label: this.rawTimes.isha };
            }
            return { current, next, events };
        },

        tick() {
            if (this._badData) {
                // Can't safely compute anything without real 24h data
                this.countdownText = '--:--:--';
                return;
            }

            const now = new Date();
            const { current, next } = this._computeSchedule(now);

            this.currentPrayer = current.key;
            this.nextPrayerKey = next ? next.key : '';
            this.nextLabel     = next ? (_labels[next.key] || next.key) : '';
            this.nextTimeStr   = next ? next.label : '';

            if (!next) { this.countdownText = '--:--:--'; return; }

            const diffMs = next.time - now;
            const totalSeconds = Math.max(0, Math.floor(diffMs / 1000));
            const hh = Math.floor(totalSeconds / 3600);
            const mm = Math.floor((totalSeconds % 3600) / 60);
            const ss = totalSeconds % 60;
            this.countdownText = `${String(hh).padStart(2,'0')}:${String(mm).padStart(2,'0')}:${String(ss).padStart(2,'0')}`;

            // ring progress: elapsed fraction between current.time and next.time
            if (current.time) {
                const windowMs  = next.time - current.time;
                const elapsedMs = now - current.time;
                const fraction  = windowMs > 0 ? Math.min(1, Math.max(0, elapsedMs / windowMs)) : 0;
                this.ringOffset = RING_CIRCUMFERENCE * (1 - fraction);
            }
        },

        async loadTimes() {
            this.loading = true;
            this.loadKey++;
            try {
                const resp = await fetch(`/pashto-calendar/prayer-times/${this.city}`);
                if (!resp.ok) throw new Error('Network error');
                const data      = await resp.json();
                this.rawTimes   = data.times;
                this.rawTimes24 = data.times_24 || {};
                this.cityName   = data.city_name;
                this._checkData();
                this.tick();
            } catch {
                alert('د وختونو د ترلاسه کولو خطا / Failed to load prayer times');
            } finally {
                setTimeout(() => { this.loading = false; }, 1200);
            }
        },
    };
}
</script>
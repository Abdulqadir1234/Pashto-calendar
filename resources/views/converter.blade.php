<!DOCTYPE html>
<html lang="ps" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ pcal_trans('converter_title') }}</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script> -->
    <link href="{{ asset('vendor/pashto-calendar/css/pashto-calendar.css') }}">
<script src="{{ asset('vendor/pashto-calendar/js/pashto-calendar.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;600;700&family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">

<style>
/* ── TOKENS ── */
:root {
    --gold:        #f0a500;
    --gold-light:  #ffd166;
    --gold-dim:    rgba(240,165,0,0.12);
    --gold-glow:   rgba(240,165,0,0.25);
    --teal:        #0dd9c4;
    --teal-dim:    rgba(13,217,196,0.1);
    --rose:        #ff4e6a;
    --navy-deep:   #03060e;
    --navy-mid:    #080d1c;
    --navy-card:   #0c1528;
    --navy-panel:  #0f1b30;
    --border-sub:  rgba(255,255,255,0.06);
    --border-gold: rgba(240,165,0,0.22);
    --text-1:      #f0ece4;
    --text-2:      #8899b4;
    --text-3:      #4a5a72;
    --r-sm: 12px; --r-md: 18px; --r-lg: 26px; --r-xl: 36px;
    --ease: cubic-bezier(.4,0,.2,1);
}

*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}

/* ── BODY + BACKGROUND ── */
body {
    background: var(--navy-deep);
    font-family: 'Noto Naskh Arabic', serif;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 32px 16px;
    overflow-x: hidden;
    position: relative;
}

/* animated aurora blobs */
.bg-aurora {
    position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden;
}
.blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.18;
    animation: driftBlob 18s ease-in-out infinite alternate;
    will-change: transform;
}
.blob-1 { width: 600px; height: 600px; background: #f0a500; top: -180px; left: -200px; animation-delay: 0s; }
.blob-2 { width: 500px; height: 500px; background: #0dd9c4; bottom: -150px; right: -150px; animation-delay: -6s; }
.blob-3 { width: 350px; height: 350px; background: #6366f1; top: 40%; left: 50%; translate: -50% -50%; animation-delay: -12s; opacity: 0.1; }

@keyframes driftBlob {
    from { transform: translate(0,0) scale(1); }
    to   { transform: translate(40px, 30px) scale(1.1); }
}

/* star-field dots */
.stars { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
.star { position: absolute; background: #fff; border-radius: 50%; animation: twinkle var(--dur, 3s) ease-in-out infinite; opacity: 0; }
@keyframes twinkle {
    0%,100%{opacity:0;} 50%{opacity:var(--op,0.6);}
}

/* grid overlay */
body::after {
    content:'';
    position:fixed; inset:0; z-index:0; pointer-events:none;
    background-image:
        linear-gradient(rgba(240,165,0,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(240,165,0,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}

/* ── MAIN CARD ── */
.converter-shell {
    position: relative; z-index: 10;
    width: 100%; max-width: 900px;
    animation: shellIn 0.6s var(--ease) both;
}
@keyframes shellIn {
    from { opacity:0; transform: translateY(32px) scale(0.97); }
    to   { opacity:1; transform: none; }
}

/* ── HEADER AREA ── */
.header-area { text-align: center; margin-bottom: 36px; }

.header-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--gold-dim);
    border: 1px solid var(--border-gold);
    border-radius: 99px;
    padding: 5px 16px;
    font-size: 11px;
    letter-spacing: 2px;
    color: var(--gold);
    text-transform: uppercase;
    margin-bottom: 16px;
    animation: shellIn 0.5s var(--ease) 0.1s both;
}
.eyebrow-dot { width: 6px; height: 6px; background: var(--gold); border-radius: 50%; box-shadow: 0 0 6px var(--gold); animation: pulse-dot 2s ease-in-out infinite; }
@keyframes pulse-dot { 0%,100%{transform:scale(1);opacity:1;} 50%{transform:scale(1.6);opacity:0.4;} }

.header-title {
    font-family: 'Cinzel', serif;
    font-size: clamp(26px, 5vw, 42px);
    font-weight: 700;
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 50%, var(--teal) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    line-height: 1.2;
    margin-bottom: 10px;
    animation: shellIn 0.5s var(--ease) 0.15s both;
}

.header-sub {
    font-size: 14px; color: var(--text-2);
    animation: shellIn 0.5s var(--ease) 0.2s both;
}

/* ── CONVERTER BODY ── */
.converter-body {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    gap: 0;
    align-items: start;
    background: rgba(8,13,28,0.8);
    border: 1px solid var(--border-sub);
    border-radius: var(--r-xl);
    backdrop-filter: blur(40px) saturate(150%);
    -webkit-backdrop-filter: blur(40px) saturate(150%);
    box-shadow: 0 40px 80px rgba(0,0,0,0.6), 0 0 0 1px rgba(240,165,0,0.07) inset, 0 1px 0 rgba(255,255,255,0.05) inset;
    overflow: visible;
    position: relative;
    animation: shellIn 0.5s var(--ease) 0.25s both;
}

/* corner accent lines */
.converter-body::before {
    content:'';
    position:absolute; inset:0; border-radius: var(--r-xl); pointer-events:none;
    background: linear-gradient(135deg, rgba(240,165,0,0.06) 0%, transparent 50%, rgba(13,217,196,0.04) 100%);
}

/* ── PANEL ── */
.panel {
    padding: 36px 32px 32px;
    position: relative;
}
.panel-left  { border-radius: var(--r-xl) 0 0 var(--r-xl); }
.panel-right { border-radius: 0 var(--r-xl) var(--r-xl) 0; }

.panel-badge {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--gold-dim);
    border: 1px solid var(--border-gold);
    border-radius: 99px;
    padding: 4px 12px 4px 6px;
    margin-bottom: 20px;
}
.panel-badge-icon {
    width: 26px; height: 26px;
    background: linear-gradient(135deg, var(--gold), #c87800);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.panel-badge-icon svg { display:block; }
.panel-badge-text { font-size: 12px; color: var(--gold-light); font-weight: 600; letter-spacing: 0.5px; }

.panel-title {
    font-family: 'Cinzel', serif;
    font-size: 17px; font-weight: 600;
    color: var(--text-1);
    margin-bottom: 24px;
    line-height: 1.4;
}

/* divider between panels */
.panel-divider {
    width: 1px;
    background: linear-gradient(180deg, transparent 0%, var(--border-gold) 30%, var(--border-gold) 70%, transparent 100%);
    position: relative;
    self-align: stretch;
    min-height: 300px;
}

/* swap button in center */
.swap-hub {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 0 0;
    position: relative;
    z-index: 5;
    width: 64px;
    align-self: stretch;
}
.swap-btn {
    width: 48px; height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--navy-card), var(--navy-panel));
    border: 1px solid var(--border-gold);
    color: var(--gold);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.3s var(--ease);
    box-shadow: 0 0 20px rgba(240,165,0,0.1), 0 4px 16px rgba(0,0,0,0.4);
    flex-shrink: 0;
}
.swap-btn:hover {
    background: linear-gradient(135deg, var(--gold), #c87800);
    color: #000;
    box-shadow: 0 0 30px rgba(240,165,0,0.4), 0 8px 24px rgba(0,0,0,0.4);
    transform: rotate(180deg) scale(1.1);
}
.swap-line {
    flex: 1;
    width: 1px;
    background: linear-gradient(180deg, transparent, var(--border-gold), transparent);
}

/* ── LABEL ── */
.field-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--text-3);
    margin-bottom: 8px;
    display: block;
}

/* ── INPUT ── */
.field-wrap { position: relative; margin-bottom: 16px; }
.field-input {
    width: 100%;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: var(--r-sm);
    padding: 14px 18px;
    color: var(--text-1);
    font-size: 15px;
    outline: none;
    transition: all 0.2s var(--ease);
    font-family: 'Noto Naskh Arabic', serif;
    -webkit-appearance: none;
    appearance: none;
}
.field-input:focus {
    border-color: var(--gold);
    background: rgba(240,165,0,0.06);
    box-shadow: 0 0 0 3px rgba(240,165,0,0.12);
}
.field-input::placeholder { color: var(--text-3); }

input[type="date"].field-input::-webkit-calendar-picker-indicator {
    filter: invert(0.6) sepia(1) hue-rotate(5deg) saturate(3);
    cursor: pointer;
    opacity: 0.7;
}
input[type="date"].field-input::-webkit-calendar-picker-indicator:hover { opacity: 1; }

.field-input-icon { padding-inline-end: 48px; }
.field-icon-btn {
    position: absolute;
    inset-inline-end: 12px;
    top: 50%; translate: 0 -50%;
    background: none; border: none;
    color: var(--text-3);
    cursor: pointer; padding: 4px;
    display: flex; align-items: center; justify-content: center;
    transition: color 0.2s;
    border-radius: 6px;
}
.field-icon-btn:hover { color: var(--gold); background: var(--gold-dim); }

/* ── CONVERT BUTTON ── */
.btn-convert {
    width: 100%;
    background: linear-gradient(135deg, var(--gold) 0%, #c87800 100%);
    color: #000;
    font-weight: 700;
    font-size: 15px;
    font-family: 'Noto Naskh Arabic', serif;
    letter-spacing: 0.5px;
    border: none;
    border-radius: var(--r-sm);
    padding: 14px 20px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.25s var(--ease);
    box-shadow: 0 4px 20px rgba(240,165,0,0.25);
}
.btn-convert::before {
    content:'';
    position:absolute; inset:0;
    background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.2) 50%, transparent 100%);
    translate: -100% 0;
    transition: translate 0.5s var(--ease);
}
.btn-convert:hover::before { translate: 100% 0; }
.btn-convert:hover {
    box-shadow: 0 8px 32px rgba(240,165,0,0.45);
    transform: translateY(-2px);
}
.btn-convert:active { transform: scale(0.97); }

/* ── RESULT BOX ── */
.result-box {
    margin-top: 16px;
    padding: 18px 20px;
    background: linear-gradient(135deg, rgba(240,165,0,0.07), rgba(13,217,196,0.04));
    border-radius: var(--r-md);
    border: 1px solid rgba(240,165,0,0.2);
    position: relative;
    overflow: hidden;
    animation: resultIn 0.4s var(--ease) both;
}
@keyframes resultIn {
    from { opacity:0; transform: translateY(8px) scale(0.98); }
    to   { opacity:1; transform: none; }
}
.result-box::before {
    content:'';
    position:absolute; inset-inline-start:0; top:0; bottom:0; width:3px;
    background: linear-gradient(180deg, var(--gold), var(--teal));
    border-radius: 99px;
}
.result-label {
    font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase;
    color: var(--text-3); margin-bottom: 6px; display: block;
}
.result-value {
    font-size: 20px; font-weight: 700; color: var(--gold-light);
    line-height: 1.3; word-break: break-word;
    font-family: 'Cinzel', serif;
}
.result-copy-btn {
    position: absolute; inset-inline-end: 12px; top: 12px;
    background: var(--gold-dim); border: 1px solid var(--border-gold);
    border-radius: 6px; padding: 4px 8px;
    color: var(--gold); font-size: 11px; cursor: pointer;
    display: flex; align-items: center; gap: 4px;
    transition: all 0.2s;
}
.result-copy-btn:hover { background: rgba(240,165,0,0.2); }

/* ── PICKER DROPDOWN ── */
.picker-dropdown {
    position: absolute;
    top: calc(100% + 10px);
    inset-inline-start: 0;
    width: min(320px, 90vw);
    background: #0a1122;
    border: 1px solid rgba(240,165,0,0.25);
    border-radius: var(--r-lg);
    z-index: 80;
    padding: 18px;
    box-shadow: 0 24px 60px rgba(0,0,0,0.6), 0 0 0 1px rgba(240,165,0,0.05) inset;
    animation: dropIn 0.2s var(--ease) both;
}
@keyframes dropIn {
    from { opacity:0; transform: translateY(-6px) scale(0.97); }
    to   { opacity:1; transform: none; }
}
.picker-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 14px; padding-bottom: 12px;
    border-bottom: 1px solid var(--border-sub);
}
.picker-nav {
    width: 30px; height: 30px; border-radius: 8px;
    background: var(--gold-dim); border: 1px solid var(--border-gold);
    color: var(--gold); cursor: pointer; font-size: 14px;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.15s;
}
.picker-nav:hover { background: rgba(240,165,0,0.22); transform: scale(1.1); }
.picker-month-title { font-size: 14px; font-weight: 700; color: var(--gold-light); text-align: center; }
.picker-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 3px; }
.picker-day-name { text-align:center; font-size:11px; color:var(--text-3); padding: 4px 0; font-weight:600; }
.picker-day-name:last-child { color: var(--gold); }
.picker-cell {
    text-align: center; padding: 7px 2px; border-radius: 7px;
    cursor: pointer; color: var(--text-1); font-size: 13px;
    transition: all 0.15s; line-height: 1;
}
.picker-cell:hover { background: rgba(240,165,0,0.15); color: var(--gold-light); }
.picker-cell.today { border: 1px solid rgba(240,165,0,0.4); color: var(--gold); font-weight:700; }
.picker-cell.selected { background: linear-gradient(135deg, var(--gold), #c87800); color:#000; font-weight:700; box-shadow:0 2px 10px rgba(240,165,0,0.3); }
.picker-cell.empty { cursor: default; pointer-events:none; }

/* ── DIVIDER (horizontal on mobile) ── */
.h-divider {
    display: none;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--border-gold), transparent);
    margin: 0 24px;
    position: relative;
}
.h-divider-hub {
    position: absolute; top: 50%; left: 50%; translate: -50% -50%;
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--navy-mid);
    border: 1px solid var(--border-gold);
    color: var(--gold);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 16px rgba(240,165,0,0.15);
}

/* ── FOOTER ── */
.footer-area {
    text-align: center; margin-top: 28px;
    animation: shellIn 0.5s var(--ease) 0.35s both;
}
.back-link {
    display: inline-flex; align-items: center; gap: 8px;
    color: var(--text-2); text-decoration: none; font-size: 13px;
    padding: 9px 20px;
    border: 1px solid var(--border-sub);
    border-radius: 99px;
    transition: all 0.2s var(--ease);
    background: rgba(255,255,255,0.03);
}
.back-link:hover {
    color: var(--gold); border-color: var(--border-gold);
    background: var(--gold-dim);
    transform: translateY(-2px);
}

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width:5px; }
::-webkit-scrollbar-track { background:transparent; }
::-webkit-scrollbar-thumb { background:var(--border-gold); border-radius:99px; }

/* ── RESPONSIVE ── */
@media (max-width: 700px) {
    .converter-body {
        grid-template-columns: 1fr;
    }
    .swap-hub { display: none; }
    .panel-left  { border-radius: var(--r-xl) var(--r-xl) 0 0; }
    .panel-right { border-radius: 0 0 var(--r-xl) var(--r-xl); }
    .h-divider { display: block; }
    .panel { padding: 28px 22px 24px; }
    .result-value { font-size: 17px; }
}

@media (max-width: 420px) {
    .header-title { font-size: 22px; }
    .panel { padding: 22px 16px 20px; }
    .picker-dropdown { width: calc(100vw - 32px); }
}
</style>
</head>

<body>

<!-- ── BACKGROUND ── -->
<div class="bg-aurora">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
</div>
<div class="stars" id="stars"></div>

<!-- ── CONVERTER SHELL ── -->
<div class="converter-shell" x-data="converterApp()">

    <!-- HEADER -->
    <div class="header-area">
        <div>
            <div class="header-eyebrow">
                <span class="eyebrow-dot"></span>
                {{ pcal_trans('converter_title') }}
            </div>
        </div>
        <h1 class="header-title">{{ pcal_trans('converter_title') }}</h1>
        <p class="header-sub">{{ pcal_trans('gregorian_to_pashto') }} &nbsp;&bull;&nbsp; {{ pcal_trans('pashto_to_gregorian') }}</p>
    </div>

    <!-- CONVERTER BODY -->
    <div class="converter-body">

        <!-- ── LEFT PANEL: Gregorian → Pashto ── -->
        <div class="panel panel-left">
            <div class="panel-badge">
                <span class="panel-badge-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                </span>
                <span class="panel-badge-text">Gregorian</span>
            </div>
            <h2 class="panel-title">{{ pcal_trans('gregorian_to_pashto') }}</h2>

            <label class="field-label">{{ pcal_trans('gregorian_date_label') }}</label>
            <div class="field-wrap">
                <input type="date" class="field-input"
                       x-model="gregorianInput"
                       @change="convertGregorian"
                       @keyup.enter="convertGregorian">
            </div>

            <button class="btn-convert" @click="convertGregorian">
                <span style="display:flex;align-items:center;justify-content:center;gap:8px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    {{ pcal_trans('convert') }}
                </span>
            </button>

            <div x-show="pashtoResult" x-transition style="display:none;" class="result-box">
                <span class="result-label">{{ pcal_trans('pashto_to_gregorian') ?? 'Result' }}</span>
                <button class="result-copy-btn" @click="copyText(pashtoResult)" title="Copy">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                    Copy
                </button>
                <div class="result-value" x-text="pashtoResult"></div>
            </div>
        </div>

        <!-- ── CENTER DIVIDER + SWAP ── -->
        <div class="swap-hub">
            <div class="swap-line"></div>
            <button class="swap-btn" title="Swap panels">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M8 3L4 7l4 4"/><path d="M4 7h16"/><path d="M16 21l4-4-4-4"/><path d="M20 17H4"/></svg>
            </button>
            <div class="swap-line"></div>
        </div>

        <!-- ── RIGHT PANEL: Pashto → Gregorian ── -->
        <div class="panel panel-right" x-data="pashtoDatePicker()">

            <!-- horizontal divider for mobile -->
            <div class="h-divider" style="display:none; margin: 0 0 24px; position:relative; height:1px; background: linear-gradient(90deg,transparent,rgba(240,165,0,0.2),transparent);">
                <div class="h-divider-hub">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M8 3L4 7l4 4"/><path d="M4 7h16"/><path d="M16 21l4-4-4-4"/><path d="M20 17H4"/></svg>
                </div>
            </div>

            <div class="panel-badge" style="background:var(--teal-dim); border-color:rgba(13,217,196,0.22);">
                <span class="panel-badge-icon" style="background:linear-gradient(135deg,var(--teal),#0aa898);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                </span>
                <span class="panel-badge-text" style="color:#9decdf;">Pashto</span>
            </div>
            <h2 class="panel-title">{{ pcal_trans('pashto_to_gregorian') }}</h2>

            <label class="field-label">{{ pcal_trans('pashto_date_label') }}</label>
            <div class="field-wrap" style="position:relative;">
                <input type="text"
                       class="field-input field-input-icon"
                       x-model="dateText"
                       placeholder="{{ pcal_trans('eg_date') }}"
                       @keyup.enter="convertFromText"
                       @focus="open = false">
                <button type="button" class="field-icon-btn" @click.stop="open = !open" title="{{ pcal_trans('open_calendar') }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                </button>

                <!-- Calendar Dropdown -->
                <div x-show="open" x-transition class="picker-dropdown" @click.away="open = false" style="display:none;">
                    <div class="picker-header">
                        <button class="picker-nav" @click="changeMonth(-1)">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <div class="picker-month-title" x-text="monthName + ' ' + viewYear"></div>
                        <button class="picker-nav" @click="changeMonth(1)">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                    <div class="picker-grid">
                        <template x-for="n in ['ش','ی','د','س','چ','پ','ج']">
                            <div class="picker-day-name" x-text="n"></div>
                        </template>
                        <template x-for="(d, idx) in days" :key="idx">
                            <div :class="['picker-cell', d.day ? '' : 'empty', d.day && isSelected(d.day) ? 'selected' : '', d.day && isToday(d.day) ? 'today' : '']"
                                 @click="d.day && selectDate(d.day)"
                                 x-text="d.day || ''">
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <button class="btn-convert" style="background:linear-gradient(135deg,var(--teal),#0aa898);" @click="convertFromText">
                <span style="display:flex;align-items:center;justify-content:center;gap:8px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    {{ pcal_trans('convert') }}
                </span>
            </button>

            <div x-show="gregorianResult" x-transition style="display:none;"
                 class="result-box" style="border-color:rgba(13,217,196,0.2); background:linear-gradient(135deg,rgba(13,217,196,0.06),rgba(240,165,0,0.03));">
                <span class="result-label">Gregorian Result</span>
                <button class="result-copy-btn" @click="copyText(gregorianResult)" title="Copy"
                        style="background:var(--teal-dim); border-color:rgba(13,217,196,0.25); color:var(--teal);">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                    Copy
                </button>
                <div class="result-value" style="color:#9decdf;" x-text="gregorianResult"></div>
            </div>
        </div>

    </div><!-- /converter-body -->

    <!-- FOOTER -->
    <div class="footer-area">
        <a href="/pashto-calendar" class="back-link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            {{ pcal_trans('back_to_calendar') }}
        </a>
    </div>

</div><!-- /converter-shell -->

<!-- Translation strings -->
<script>
window.converterTrans = {
    pickDateFirst:    @json(pcal_trans('pick_date_first')),
    invalidFormat:    @json(pcal_trans('invalid_format')),
    conversionFailed: @json(pcal_trans('conversion_failed')),
};
</script>

<script>
/* ── STAR FIELD ── */
(function(){
    const s = document.getElementById('stars');
    for(let i=0;i<120;i++){
        const el = document.createElement('div');
        el.className='star';
        const sz = Math.random()*2+0.5;
        el.style.cssText = `
            width:${sz}px;height:${sz}px;
            top:${Math.random()*100}%;left:${Math.random()*100}%;
            --dur:${2+Math.random()*4}s;
            --op:${0.3+Math.random()*0.6};
            animation-delay:${Math.random()*5}s;
        `;
        s.appendChild(el);
    }
})();

/* ── CONVERTER APP ── */
function converterApp() {
    return {
        gregorianInput: '',
        pashtoResult: '',

        copyText(txt) {
            if(navigator.clipboard) navigator.clipboard.writeText(txt);
        },

        async convertGregorian() {
            if (!this.gregorianInput) return;
            try {
                const r = await fetch(`/pashto-calendar/convert/gregorian?date=${encodeURIComponent(this.gregorianInput)}`);
                const d = await r.json();
                if (d.error) alert(d.error);
                else this.pashtoResult = `${d.formatted}  (${d.year}-${d.month}-${d.day})`;
            } catch(e) { alert(window.converterTrans.conversionFailed); }
        }
    };
}

/* ── PASHTO DATE PICKER ── */
function pashtoDatePicker() {
    return {
        dateText: '',
        open: false,
        viewYear:  {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->year }},
        viewMonth: {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->month }},
        selectedDay: null, selectedMonth: null, selectedYear: null,
        todayYear:  {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->year }},
        todayMonth: {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->month }},
        todayDay:   {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->day }},
        monthNames: ['','وری','غویی','غبرګولی','چنګاښ','زمری','وږی','تله','لړم','لیندۍ','مرغومی','سلواغه','کب'],
        gregorianResult: '',

        get monthName() { return this.monthNames[this.viewMonth]||''; },
        get days() {
            const dim   = this.daysInMonth(this.viewYear, this.viewMonth);
            const first = this.firstDayOfWeek(this.viewYear, this.viewMonth);
            const cells = [];
            for(let i=0;i<first;i++) cells.push({day:null});
            for(let d=1;d<=dim;d++) cells.push({day:d});
            while(cells.length<42) cells.push({day:null});
            return cells;
        },
        daysInMonth(y,m) {
            const l=[31,31,31,31,31,31,30,30,30,30,30,29];
            if(m===12&&[1,5,9,13,17,21,25,29].includes(y%33)) return 30;
            return l[m-1];
        },
        firstDayOfWeek(y,m) {
            let t=0;
            for(let yr=1403;yr<y;yr++) t+=this.isLeapYear(yr)?366:365;
            for(let mo=1;mo<m;mo++) t+=this.daysInMonth(y,mo);
            return (t+4)%7;
        },
        isLeapYear(y){ return [1,5,9,13,17,21,25,29].includes(y%33); },
        isSelected(d){ return this.selectedYear&&this.selectedMonth===this.viewMonth&&this.selectedYear===this.viewYear&&d===this.selectedDay; },
        isToday(d){ return d===this.todayDay&&this.viewMonth===this.todayMonth&&this.viewYear===this.todayYear; },
        changeMonth(delta){
            let m=this.viewMonth+delta, y=this.viewYear;
            if(m>12){m=1;y++;} if(m<1){m=12;y--;}
            this.viewMonth=m; this.viewYear=y;
        },
        selectDate(d){
            this.selectedYear=this.viewYear; this.selectedMonth=this.viewMonth; this.selectedDay=d;
            this.dateText=`${this.selectedYear}/${String(this.selectedMonth).padStart(2,'0')}/${String(d).padStart(2,'0')}`;
            this.open=false;
            this.convertPashto();
        },
        copyText(txt){ if(navigator.clipboard) navigator.clipboard.writeText(txt); },
        async convertPashto(){
            if(!this.selectedYear||!this.selectedMonth||!this.selectedDay){
                alert(window.converterTrans.pickDateFirst); return;
            }
            try{
                const r=await fetch(`/pashto-calendar/convert/pashto?year=${this.selectedYear}&month=${this.selectedMonth}&day=${this.selectedDay}`);
                const d=await r.json();
                if(d.error) alert(d.error);
                else this.gregorianResult=d.gregorian;
            }catch(e){ alert(window.converterTrans.conversionFailed); }
        },
        convertFromText(){
            const p=this.dateText.split('/');
            if(p.length===3){
                const y=parseInt(p[0]),m=parseInt(p[1]),d=parseInt(p[2]);
                if(!isNaN(y)&&!isNaN(m)&&!isNaN(d)){
                    this.selectedYear=y; this.selectedMonth=m; this.selectedDay=d;
                    this.viewYear=y; this.viewMonth=m;
                    this.convertPashto(); return;
                }
            }
            alert(window.converterTrans.invalidFormat);
        }
    };
}
</script>
</body>
</html>
```
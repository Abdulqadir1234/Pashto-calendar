<!DOCTYPE html>
<html lang="ps" dir="{{ ($rtl ?? true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>د پښتو کلیندر</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script> -->
   <link rel="stylesheet" href="{{ asset('vendor/pashto-calendar/css/pashto-calendar.css') }}?v=1">
<script src="{{ asset('vendor/pashto-calendar/js/pashto-calendar.js') }}?v=1" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ============================================================
   CSS CUSTOM PROPERTIES — DARK (DEFAULT)
============================================================ */
:root {
    --bg-base:        #0b0f1a;
    --bg-surface:     #111827;
    --bg-card:        #161d2e;
    --bg-card-hover:  #1c2540;
    --bg-input:       #0f1626;
    --border-subtle:  rgba(255,255,255,0.06);
    --border-accent:  rgba(234,179,8,0.22);
    --border-focus:   rgba(234,179,8,0.6);

    --amber:          #f59e0b;
    --amber-light:    #fcd34d;
    --amber-dim:      rgba(245,158,11,0.12);
    --teal:           #14b8a6;
    --teal-dim:       rgba(20,184,166,0.1);
    --rose:           #f43f5e;
    --rose-dim:       rgba(244,63,94,0.1);
    --blue:           #60a5fa;
    --blue-dim:       rgba(96,165,250,0.1);

    --text-primary:   #f1f5f9;
    --text-secondary: #94a3b8;
    --text-muted:     #475569;

    --shadow-sm:      0 2px 8px rgba(0,0,0,0.4);
    --shadow-md:      0 8px 32px rgba(0,0,0,0.5);
    --shadow-lg:      0 20px 60px rgba(0,0,0,0.6);
    --shadow-glow:    0 0 30px rgba(245,158,11,0.08);

    --radius-sm:      8px;
    --radius-md:      12px;
    --radius-lg:      18px;
    --radius-xl:      24px;

    --transition:     all 0.2s cubic-bezier(0.4,0,0.2,1);

    /* Navbar-specific tokens */
    --nav-bg:         rgba(10,14,24,0.96);
    --nav-border:     rgba(245,158,11,0.1);
    --nav-height:     68px;
    --nav-height-sm:  60px;
}

/* ============================================================
   LIGHT MODE OVERRIDES
============================================================ */
body.light {
    --bg-base:        #eef6f9;
    --bg-surface:     #e3f2f8;
    --bg-card:        #ffffff;
    --bg-card-hover:  #f1fbfd;
    --bg-input:       #eef8fb;
    --border-subtle:  rgba(15,23,42,0.08);
    --border-accent:  rgba(6,182,212,0.28);
    --border-focus:   rgba(6,182,212,0.55);

    --amber:          #0891b2;
    --amber-light:    #06b6d4;
    --amber-dim:      rgba(8,145,178,0.1);
    --teal:           #0e7490;
    --teal-dim:       rgba(14,116,144,0.08);
    --rose:           #e11d48;
    --rose-dim:       rgba(225,29,72,0.08);
    --blue:           #2563eb;
    --blue-dim:       rgba(37,99,235,0.08);

    --text-primary:   #000000;
    --text-secondary: #1f2937;
    --text-muted:     #52606d;

    --shadow-sm:      0 2px 8px rgba(15,23,42,0.07);
    --shadow-md:      0 8px 28px rgba(15,23,42,0.09);
    --shadow-lg:      0 20px 60px rgba(15,23,42,0.14);
    --shadow-glow:    0 0 30px rgba(6,182,212,0.10);

    --nav-bg:         rgba(255,255,255,0.97);
    --nav-border:     rgba(6,182,212,0.16);
}

/* ============================================================
   LIGHT MODE — cyan accent overrides for hardcoded glow colors
============================================================ */
body.light .topbar::before {
    background: linear-gradient(90deg,
        transparent 0%,
        rgba(6,182,212,0.55) 30%,
        rgba(34,211,238,0.9) 50%,
        rgba(6,182,212,0.55) 70%,
        transparent 100%);
}

body.light .topbar-center::before {
    background: radial-gradient(ellipse, rgba(6,182,212,0.14) 0%, transparent 70%);
}

body.light .nav-pill:hover {
    background: rgba(8,145,178,0.16);
    border-color: rgba(8,145,178,0.55);
    box-shadow: 0 4px 16px rgba(8,145,178,0.16), inset 0 1px 0 rgba(255,255,255,0.5);
    color: #0e7490;
}

body.light .nav-arrow:hover:not(:disabled) {
    background: rgba(8,145,178,0.18);
    box-shadow: 0 4px 16px rgba(8,145,178,0.2);
}

/* ============================================================
   RESET & BASE
============================================================ */
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
html { scroll-behavior: smooth; }

body {
    background: var(--bg-base);
    color: var(--text-primary);
    font-family: 'Noto Naskh Arabic', 'Amiri', serif;
    min-height: 100vh;
    line-height: 1.6;
    transition: background 0.3s ease, color 0.3s ease;
}

body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
    pointer-events: none;
    z-index: 0;
    opacity: 0.4;
}
body.light::before { opacity: 0.15; }

::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--border-accent); border-radius: 99px; }
::-webkit-scrollbar-thumb:hover { background: var(--amber); }

/* ============================================================
   ██████████  REDESIGNED TOPBAR  ██████████
============================================================ */

/* Outer shell */
.topbar {
    position: sticky;
    top: 0;
    z-index: 100;
    background: var(--nav-bg);
    border-bottom: 1px solid var(--nav-border);
    backdrop-filter: blur(28px) saturate(200%);
    -webkit-backdrop-filter: blur(28px) saturate(200%);
    box-shadow: 0 1px 0 var(--nav-border), 0 4px 24px rgba(0,0,0,0.25);
    transition: background 0.3s ease, box-shadow 0.3s ease;
}

/* Amber glow accent line at the very top */
.topbar::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg,
        transparent 0%,
        rgba(245,158,11,0.6) 30%,
        rgba(252,211,77,0.9) 50%,
        rgba(245,158,11,0.6) 70%,
        transparent 100%);
    animation: topline-shimmer 4s ease-in-out infinite;
}

@keyframes topline-shimmer {
    0%, 100% { opacity: 0.6; }
    50%       { opacity: 1; }
}

body.light .topbar {
    box-shadow: 0 1px 0 var(--nav-border), 0 4px 20px rgba(0,0,0,0.08);
}

/* ── Three-column grid layout ── */
.topbar-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    height: var(--nav-height);
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 16px;
}

/* ── LEFT ZONE: Pashto date ── */
.topbar-left {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.pashto-date-block {
    display: flex;
    flex-direction: column;
    gap: 1px;
}

.pashto-date-label {
    font-size: 10px;
    color: var(--text-muted);
    letter-spacing: 0.8px;
    text-transform: uppercase;
    line-height: 1;
}

.pashto-date-value {
    font-size: 15px;
    font-weight: 700;
    color: var(--amber-light);
    white-space: nowrap;
    font-family: 'Amiri', serif;
    line-height: 1.2;
}

body.light .pashto-date-value { color: var(--amber); }

/* Small calendar icon beside date */
.date-icon-ring {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: var(--amber-dim);
    border: 1px solid var(--border-accent);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: var(--amber);
}

/* ── CENTER ZONE: Clock ── */
.topbar-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
}

/* Glowing halo behind clock */
.topbar-center::before {
    content: '';
    position: absolute;
    width: 160px;
    height: 56px;
    background: radial-gradient(ellipse, rgba(245,158,11,0.1) 0%, transparent 70%);
    pointer-events: none;
    border-radius: 50%;
}

.clock-display {
    display: flex;
    align-items: baseline;
    gap: 2px;
    direction: ltr;
}

.clock-hms {
    font-size: 28px;
    font-weight: 700;
    background: linear-gradient(135deg, var(--amber) 0%, var(--amber-light) 60%, #fffbe8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: 3px;
    line-height: 1;
    font-variant-numeric: tabular-nums;
    font-feature-settings: "tnum";
}

.clock-ampm {
    font-size: 11px;
    font-weight: 700;
    color: var(--amber);
    letter-spacing: 1px;
    margin-right: 2px;
    margin-bottom: 2px;
    opacity: 0.85;
    -webkit-text-fill-color: var(--amber);
}

.clock-day-label {
    font-size: 11px;
    color: var(--teal);
    letter-spacing: 0.5px;
    margin-top: 1px;
    text-align: center;
    line-height: 1;
}

/* Pulsing dot to show it is live */
.clock-live-dot {
    display: inline-block;
    width: 5px;
    height: 5px;
    background: var(--teal);
    border-radius: 50%;
    margin-inline-start: 5px;
    vertical-align: middle;
    animation: live-pulse 2s ease-in-out infinite;
    box-shadow: 0 0 6px var(--teal);
}

@keyframes live-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.3; transform: scale(0.6); }
}

/* ── RIGHT ZONE: Actions ── */
.topbar-right {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    min-width: 0;
}

/* Gregorian date badge */
.gregorian-badge {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    padding-inline-end: 12px;
    border-inline-end: 1px solid var(--border-subtle);
    gap: 1px;
}

.gregorian-badge .g-label {
    font-size: 9px;
    color: var(--text-muted);
    letter-spacing: 0.8px;
    text-transform: uppercase;
    line-height: 1;
}

.gregorian-badge .g-value {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-secondary);
    direction: ltr;
    white-space: nowrap;
    line-height: 1.3;
}

/* Nav pill buttons */
.nav-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 13px;
    background: var(--amber-dim);
    border: 1px solid var(--border-accent);
    border-radius: 99px;
    color: var(--amber);
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: var(--transition);
    white-space: nowrap;
    font-family: 'Noto Naskh Arabic', serif;
    position: relative;
    overflow: hidden;
}

.nav-pill::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.06), transparent);
    pointer-events: none;
    border-radius: inherit;
}

.nav-pill:hover {
    background: rgba(245,158,11,0.22);
    border-color: rgba(245,158,11,0.6);
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(245,158,11,0.18), inset 0 1px 0 rgba(255,255,255,0.1);
    color: var(--amber-light);
}

.nav-pill:active { transform: scale(0.96); }

/* Prayer pill gets teal tint */
.nav-pill.prayer {
    background: var(--teal-dim);
    border-color: rgba(20,184,166,0.25);
    color: var(--teal);
}

.nav-pill.prayer:hover {
    background: rgba(20,184,166,0.2);
    border-color: rgba(20,184,166,0.6);
    box-shadow: 0 4px 16px rgba(20,184,166,0.18);
    color: var(--teal);
}

/* Theme toggle */
.theme-toggle {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 13px;
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: 99px;
    color: var(--text-secondary);
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
    font-family: 'Noto Naskh Arabic', serif;
}

.theme-toggle:hover {
    border-color: var(--border-accent);
    color: var(--text-primary);
    background: var(--bg-card-hover);
    box-shadow: 0 2px 12px rgba(0,0,0,0.2);
}

.toggle-icon {
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* ── MOBILE HAMBURGER ── */
.mobile-menu-btn {
    display: none;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    cursor: pointer;
    color: var(--text-primary);
    transition: var(--transition);
    flex-shrink: 0;
}

.mobile-menu-btn:hover {
    border-color: var(--border-accent);
    background: var(--bg-card-hover);
    color: var(--amber);
}

/* Hamburger icon lines animate to X */
.ham-line {
    display: block;
    width: 16px;
    height: 1.5px;
    background: currentColor;
    border-radius: 99px;
    transition: transform 0.25s ease, opacity 0.2s ease;
    transform-origin: center;
}

.ham-wrap {
    display: flex;
    flex-direction: column;
    gap: 4px;
    align-items: center;
}

/* ── MOBILE DROPDOWN NAV ── */
.mobile-nav {
    display: none;
    padding: 14px 20px 18px;
    border-top: 1px solid var(--border-subtle);
    background: var(--nav-bg);
    gap: 8px;
    flex-wrap: wrap;
    animation: slideDown 0.2s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}

.mobile-nav-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    gap: 8px;
    flex-wrap: wrap;
}

.mobile-greg-pill {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: 99px;
    padding: 6px 12px;
    font-size: 12px;
    color: var(--text-secondary);
    direction: ltr;
}

/* ============================================================
   PRAYER TIMES PANEL
============================================================ */
.prayer-panel {
    background: var(--bg-surface);
    border-bottom: 1px solid var(--border-subtle);
    overflow: hidden;
}

/* ============================================================
   MAIN WRAPPER & CALENDAR (unchanged)
============================================================ */
.cal-wrapper {
    max-width: 960px;
    margin: 0 auto;
    padding: 28px 20px 40px;
    position: relative;
    z-index: 1;
}

.cal-header-card {
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-xl);
    padding: 20px 24px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    box-shadow: var(--shadow-sm), var(--shadow-glow);
    transition: background 0.3s ease;
}

.cal-month-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
    font-family: 'Amiri', serif;
    letter-spacing: 0.5px;
}

.cal-gregorian-sub {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
    direction: ltr;
    text-align: center;
}

.nav-arrow {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    background: var(--amber-dim);
    border: 1px solid var(--border-accent);
    border-radius: var(--radius-md);
    color: var(--amber);
    font-size: 20px;
    cursor: pointer;
    transition: var(--transition);
    font-weight: 700;
    flex-shrink: 0;
}

.nav-arrow:hover:not(:disabled) {
    background: rgba(245,158,11,0.22);
    border-color: var(--amber);
    box-shadow: 0 4px 16px rgba(245,158,11,0.2);
    transform: scale(1.08);
}

.nav-arrow:disabled { opacity: 0.4; cursor: not-allowed; }

.today-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 16px;
    background: var(--teal-dim);
    border: 1px solid rgba(20,184,166,0.25);
    border-radius: 99px;
    color: var(--teal);
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    font-family: 'Noto Naskh Arabic', serif;
    white-space: nowrap;
}

.today-btn:hover {
    background: rgba(20,184,166,0.18);
    border-color: var(--teal);
    transform: translateY(-1px);
}

.week-headers {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
    margin-bottom: 8px;
    padding: 0 2px;
}

.week-header {
    text-align: center;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    padding: 10px 4px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.week-header.friday {
    color: var(--amber);
    background: var(--amber-dim);
    border-radius: var(--radius-sm);
}

.cal-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
    margin-bottom: 24px;
}

.cal-empty {
    border-radius: var(--radius-md);
    min-height: 82px;
    background: transparent;
}

.cal-day {
    min-height: 82px;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-subtle);
    background: var(--bg-card);
    cursor: pointer;
    transition: var(--transition);
    padding: 10px 8px 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.cal-day::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, transparent 60%, rgba(255,255,255,0.02));
    pointer-events: none;
}

.cal-day:hover {
    background: var(--bg-card-hover);
    border-color: var(--border-accent);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    z-index: 2;
}

.cal-day.is-today {
    background: linear-gradient(135deg, var(--amber-dim), rgba(252,211,77,0.06));
    border-color: rgba(245,158,11,0.45);
    box-shadow: 0 0 0 1px rgba(245,158,11,0.1), var(--shadow-md);
}

.cal-day.is-today:hover { border-color: var(--amber); }

.cal-day.is-friday {
    background: var(--teal-dim);
    border-color: rgba(20,184,166,0.18);
}

.cal-day.is-friday:hover { border-color: var(--teal); }

.cal-day.is-holiday {
    background: var(--rose-dim);
    border-color: rgba(244,63,94,0.2);
}

.cal-day.is-holiday:hover { border-color: var(--rose); }

.cal-day.has-events::before {
    content: '';
    position: absolute;
    top: 0;
    inset-inline-start: 0;
    width: 3px;
    height: 100%;
    background: linear-gradient(180deg, var(--amber), transparent);
    border-radius: 0 0 0 var(--radius-md);
}

.day-number {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 4px;
    font-family: 'Amiri', serif;
    transition: color 0.2s;
}

.cal-day.is-today .day-number { color: var(--amber); }
.cal-day.is-friday .day-number { color: var(--teal); }
.cal-day.is-holiday .day-number { color: var(--rose); }

.today-beacon {
    position: absolute;
    top: 7px;
    inset-inline-end: 7px;
    width: 7px;
    height: 7px;
    background: var(--amber);
    border-radius: 50%;
    animation: beacon 2.5s ease-in-out infinite;
}

@keyframes beacon {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.25; transform: scale(0.7); }
}

.holiday-chip {
    font-size: 9px;
    color: var(--rose);
    background: var(--rose-dim);
    border: 1px solid rgba(244,63,94,0.18);
    border-radius: 4px;
    padding: 1px 5px;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    margin-top: 2px;
}

.event-dots {
    display: flex;
    gap: 3px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: auto;
    padding-top: 4px;
}

.event-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
    box-shadow: 0 0 4px currentColor;
}

body.light .cal-day {
    background: #ffffff;
    border-color: rgba(0,0,0,0.07);
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
}

body.light .cal-day:hover {
    background: #fafcff;
    border-color: var(--border-accent);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

body.light .cal-day.is-today {
    background: linear-gradient(135deg, rgba(6,182,212,0.10), rgba(8,145,178,0.05));
    border-color: rgba(6,182,212,0.4);
    box-shadow: 0 0 0 1px rgba(6,182,212,0.12), var(--shadow-md);
}

.events-section {
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-xl);
    padding: 22px 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
    transition: background 0.3s ease;
}

body.light .events-section { background: #ffffff; }

.events-section-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
}

.events-section-dot {
    width: 8px;
    height: 8px;
    background: var(--amber);
    border-radius: 50%;
    box-shadow: 0 0 8px var(--amber);
}

.events-section-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--amber);
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 10px;
}

.event-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-md);
    padding: 12px 14px;
    display: flex;
    gap: 10px;
    align-items: flex-start;
    transition: var(--transition);
    cursor: pointer;
}

body.light .event-card { background: #f8fafc; }

.event-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--border-accent);
}

.event-card-bar {
    width: 3px;
    min-height: 40px;
    border-radius: 99px;
    flex-shrink: 0;
}

.event-card-day { font-size: 11px; color: var(--text-muted); margin-bottom: 3px; }
.event-card-title { font-size: 14px; font-weight: 600; color: var(--text-primary); line-height: 1.3; }
.event-card-desc { font-size: 12px; color: var(--text-secondary); margin-top: 2px; }

.cal-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    padding: 8px 0 20px;
    flex-wrap: wrap;
}

.footer-badge {
    font-size: 11px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Modal */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.65);
    backdrop-filter: blur(12px);
    z-index: 200;
}

.modal-container {
    position: fixed;
    inset: 0;
    z-index: 201;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
}

.modal-box {
    background: var(--bg-card);
    border: 1px solid var(--border-accent);
    border-radius: var(--radius-xl);
    width: 100%;
    max-width: 440px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow-lg), 0 0 60px rgba(245,158,11,0.06);
    transition: background 0.3s ease;
}

body.light .modal-box {
    background: #ffffff;
    border-color: rgba(6,182,212,0.2);
    box-shadow: 0 30px 80px rgba(15,23,42,0.10), 0 0 50px rgba(6,182,212,0.08);
}

.modal-header {
    padding: 22px 24px 18px;
    border-bottom: 1px solid var(--border-subtle);
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    background: linear-gradient(135deg, var(--amber-dim), var(--teal-dim));
}

.modal-month-label { font-size: 11px; color: var(--text-muted); margin-bottom: 5px; letter-spacing: 0.5px; }

.modal-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
    font-family: 'Amiri', serif;
}

.modal-header-right { display: flex; align-items: center; gap: 10px; }

.modal-day-badge {
    background: var(--amber-dim);
    border: 1px solid var(--border-accent);
    border-radius: var(--radius-md);
    padding: 6px 14px;
    color: var(--amber-light);
    font-size: 18px;
    font-weight: 700;
    font-family: 'Amiri', serif;
    line-height: 1;
}

body.light .modal-day-badge { color: var(--amber); }

.modal-close {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--bg-surface);
    border: 1px solid var(--border-subtle);
    color: var(--text-muted);
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    flex-shrink: 0;
}

.modal-close:hover { background: var(--rose-dim); border-color: var(--rose); color: var(--rose); }

.modal-body { padding: 20px 24px; }

.holiday-banner {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--rose-dim);
    border: 1px solid rgba(244,63,94,0.25);
    border-radius: var(--radius-md);
    padding: 10px 14px;
    margin-bottom: 16px;
    font-size: 13px;
    font-weight: 600;
    color: var(--rose);
}

.holiday-banner-dot {
    width: 8px;
    height: 8px;
    background: var(--rose);
    border-radius: 50%;
    flex-shrink: 0;
    box-shadow: 0 0 8px var(--rose);
}

.modal-events-list { max-height: 170px; overflow-y: auto; margin-bottom: 6px; }

.modal-event-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 12px;
    border-radius: var(--radius-sm);
    margin-bottom: 7px;
    font-size: 14px;
    color: var(--text-primary);
    gap: 8px;
    transition: var(--transition);
}

.modal-event-left { display: flex; align-items: center; gap: 9px; min-width: 0; }
.modal-event-color-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.modal-event-title { font-size: 14px; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.modal-event-actions { display: flex; gap: 4px; flex-shrink: 0; }

.modal-evt-btn {
    width: 26px;
    height: 26px;
    border-radius: var(--radius-sm);
    border: 1px solid transparent;
    background: transparent;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    font-size: 13px;
}

.modal-evt-btn.edit { color: var(--blue); }
.modal-evt-btn.edit:hover { background: var(--blue-dim); border-color: rgba(96,165,250,0.3); }
.modal-evt-btn.delete { color: var(--text-muted); }
.modal-evt-btn.delete:hover { background: var(--rose-dim); border-color: rgba(244,63,94,0.3); color: var(--rose); }

.modal-empty {
    text-align: center;
    color: var(--text-muted);
    font-size: 13px;
    padding: 20px 0 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.modal-empty-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--bg-surface);
    border: 1px solid var(--border-subtle);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: var(--text-muted);
}

.modal-form-section { border-top: 1px solid var(--border-subtle); padding-top: 18px; margin-top: 6px; }

.form-section-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--amber);
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-section-label::after { content: ''; flex: 1; height: 1px; background: var(--border-subtle); }

.modal-input {
    width: 100%;
    background: var(--bg-input);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    padding: 10px 14px;
    color: var(--text-primary);
    font-size: 14px;
    margin-bottom: 10px;
    font-family: 'Noto Naskh Arabic', serif;
    outline: none;
    transition: var(--transition);
}

body.light .modal-input { background: #f8fafc; border-color: #e2e8f0; }
.modal-input:focus { border-color: var(--border-focus); box-shadow: 0 0 0 3px var(--amber-dim); }
.modal-input::placeholder { color: var(--text-muted); }
select.modal-input { cursor: pointer; appearance: auto; }

.recurrence-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; flex-wrap: wrap; }
.recurrence-label { font-size: 12px; color: var(--text-secondary); white-space: nowrap; flex-shrink: 0; }

.color-picker-row { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; flex-wrap: wrap; }
.color-picker-label { font-size: 12px; color: var(--text-secondary); flex-shrink: 0; }
.color-swatches { display: flex; gap: 8px; flex-wrap: wrap; }

.color-swatch {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid transparent;
    transition: var(--transition);
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
}

.color-swatch:hover, .color-swatch.active {
    transform: scale(1.2);
    border-color: rgba(255,255,255,0.6);
    box-shadow: 0 0 12px currentColor;
}

.modal-actions { display: flex; gap: 10px; }

.btn-primary {
    flex: 1;
    background: linear-gradient(135deg, var(--amber), #c87800);
    color: #000;
    font-weight: 700;
    padding: 11px;
    border-radius: var(--radius-sm);
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-family: 'Noto Naskh Arabic', serif;
    transition: var(--transition);
    box-shadow: 0 4px 12px rgba(245,158,11,0.3);
}

body.light .btn-primary {
    color: #fff;
    background: linear-gradient(135deg, #0891b2, #0e7490);
    box-shadow: 0 4px 12px rgba(8,145,178,0.28);
}
body.light .btn-primary:hover:not(:disabled) { box-shadow: 0 6px 20px rgba(8,145,178,0.42); }
.btn-primary:hover:not(:disabled) { box-shadow: 0 6px 20px rgba(245,158,11,0.45); transform: translateY(-1px); }
.btn-primary:active:not(:disabled) { transform: scale(0.97); }
.btn-primary:disabled { opacity: 0.4; cursor: not-allowed; box-shadow: none; }

.btn-secondary {
    flex: 1;
    background: var(--bg-surface);
    color: var(--text-secondary);
    font-weight: 600;
    padding: 11px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-subtle);
    cursor: pointer;
    font-size: 14px;
    font-family: 'Noto Naskh Arabic', serif;
    transition: var(--transition);
}

.btn-secondary:hover { background: var(--bg-card-hover); color: var(--text-primary); border-color: var(--border-accent); }

.spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid var(--amber-dim);
    border-top-color: var(--amber);
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    vertical-align: middle;
}

@keyframes spin { to { transform: rotate(360deg); } }

.mini-calendar { background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 14px; max-width: 220px; font-family: inherit; }
.mini-cal-header { text-align: center; margin-bottom: 10px; font-weight: 700; color: var(--amber); font-size: 14px; font-family: 'Amiri', serif; }
.mini-cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; }
.mini-cal-day-name { font-size: 10px; color: var(--text-muted); text-align: center; padding: 3px 2px; }
.mini-cal-day-name.friday { color: var(--amber); }
.mini-cal-cell { font-size: 11px; text-align: center; padding: 3px 2px; border-radius: 4px; color: var(--text-primary); }
.mini-cal-cell.today { background: var(--amber); color: #000; font-weight: 700; }
.mini-cal-cell.holiday { color: var(--rose); }

.event-actions { display: flex; gap: 6px; align-items: center; margin-top: 8px; justify-content: flex-end; }

.event-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    border: 1px solid transparent;
    border-radius: var(--radius-sm);
    padding: 5px 11px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    background: var(--bg-surface);
    color: var(--text-secondary);
    font-family: inherit;
}

.event-action-btn.edit { color: var(--blue); }
.event-action-btn.edit:hover { background: var(--blue-dim); border-color: rgba(96,165,250,0.3); }
.event-action-btn.delete { color: var(--rose); }
.event-action-btn.delete:hover { background: var(--rose-dim); border-color: rgba(244,63,94,0.3); }

/* ============================================================
   PAGE LOAD ANIMATIONS
============================================================ */
@keyframes fadeSlideIn {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.cal-header-card  { animation: fadeSlideIn 0.4s ease both; }
.week-headers     { animation: fadeSlideIn 0.45s ease both; }
#calendar-content { animation: fadeSlideIn 0.5s ease both; }
.events-section   { animation: fadeSlideIn 0.55s ease both; }

/* ============================================================
   RESPONSIVE BREAKPOINTS
============================================================ */

/* 1024px: hide gregorian text label (keep value), compress pills */
@media (max-width: 1024px) {
    .topbar-inner { padding: 0 16px; }
    .nav-pill span.pill-label { display: none; }
    .nav-pill { padding: 8px 10px; }
}

/* 768px: switch to compact 2-column + hamburger */
@media (max-width: 768px) {
    :root { --nav-height: var(--nav-height-sm); }

    .topbar-inner {
        grid-template-columns: auto 1fr auto;
        height: var(--nav-height-sm);
        padding: 0 14px;
        gap: 10px;
    }

    /* Left: just the icon + compact date */
    .topbar-left { gap: 8px; }
    .pashto-date-label { display: none; }
    .pashto-date-value { font-size: 13px; }

    /* Center clock shrinks */
    .clock-hms { font-size: 20px; letter-spacing: 2px; }

    /* Right: hide everything except hamburger + theme */
    .gregorian-badge,
    .topbar-right .nav-pill { display: none !important; }

    .mobile-menu-btn { display: inline-flex; }
    .mobile-nav { display: flex; }

    /* Calendar below */
    .cal-wrapper { padding: 14px 12px 28px; }
    .cal-header-card { padding: 14px 16px; border-radius: var(--radius-lg); }
    .cal-month-title { font-size: 18px; }
    .nav-arrow { width: 36px; height: 36px; }
    .today-btn { padding: 7px 12px; font-size: 11px; }
    .cal-day { min-height: 66px; padding: 8px 4px 6px; }
    .day-number { font-size: 17px; }
    .holiday-chip { font-size: 8px; padding: 1px 3px; }
    .week-header { font-size: 10px; padding: 8px 2px; }
    .events-grid { grid-template-columns: 1fr; }
    .events-section { padding: 16px; border-radius: var(--radius-lg); }
    .modal-box { max-width: 100%; border-radius: var(--radius-lg); }
    .modal-header { padding: 18px 18px 14px; }
    .modal-body { padding: 16px 18px; }
}

@media (max-width: 480px) {
    .topbar-inner { gap: 6px; padding: 0 12px; }
    .date-icon-ring { display: none; }
    .pashto-date-value { font-size: 12px; }
    .clock-hms { font-size: 17px; letter-spacing: 1.5px; }
    .clock-ampm { font-size: 10px; }
    .theme-toggle .toggle-label { display: none; }
    .theme-toggle { padding: 7px 8px; }

    .cal-grid, .week-headers { gap: 4px; }
    .cal-day { min-height: 56px; padding: 7px 3px 5px; border-radius: var(--radius-sm); }
    .day-number { font-size: 15px; }
    .event-dot { width: 5px; height: 5px; }
    .holiday-chip { display: none; }
    .today-beacon { width: 5px; height: 5px; top: 4px; inset-inline-end: 4px; }
    .cal-month-title { font-size: 14px; }
    .cal-gregorian-sub { font-size: 10px; }
    .nav-arrow { width: 30px; height: 30px; border-radius: var(--radius-sm); }
}

@media (max-width: 360px) {
    .clock-hms { font-size: 15px; letter-spacing: 1px; }
    .pashto-date-value { font-size: 11px; }
    .cal-day { min-height: 48px; padding: 6px 2px 4px; }
    .day-number { font-size: 13px; }
}
</style>
</head>

<body x-data="clockApp()" :class="{ 'light': !darkMode }">

<!-- ============================================================
     TOP NAVIGATION BAR — REDESIGNED
============================================================ -->
<header class="topbar">
    <div class="topbar-inner">

        <!-- ── LEFT: Pashto Date ── -->
        <div class="topbar-left">
            <!-- Calendar icon ring -->
            <div class="date-icon-ring">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
            <div class="pashto-date-block">
                <span class="pashto-date-label">{{ pcal_trans('current_date') }}</span>
                <span class="pashto-date-value">
                    {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber(\Qadir\PashtoCalendar\PashtoCalendar::now()->day) }}
                    {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->monthName() }}
                    {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber(\Qadir\PashtoCalendar\PashtoCalendar::now()->year) }}
                </span>
            </div>
        </div>

        <!-- ── CENTER: Live Clock ── -->
        <div class="topbar-center">
            <div class="clock-display">
                <span class="clock-hms" x-text="timeHMS"></span>
                <span class="clock-ampm" x-text="timeAMPM"></span>
                <span class="clock-live-dot"></span>
            </div>
            <div class="clock-day-label" x-text="dateLabel"></div>
        </div>

        <!-- ── RIGHT: Actions ── -->
        <div class="topbar-right">

            <!-- Gregorian date badge -->
            <div class="gregorian-badge">
                <span class="g-label">{{ pcal_trans('gregorian') }}</span>
                <span class="g-value">{{ now()->format('d M Y') }}</span>
            </div>

            <!-- Nav pills: hidden on mobile, visible in dropdown -->
            <a href="/pashto-calendar/converter" class="nav-pill">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M8 3L4 7l4 4"/><path d="M4 7h16"/>
                    <path d="M16 21l4-4-4-4"/><path d="M20 17H4"/>
                </svg>
                <span class="pill-label">{{ pcal_trans('converter') }}</span>
            </a>

            <a href="/pashto-calendar/year" class="nav-pill">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                <span class="pill-label">{{ pcal_trans('year') }}</span>
            </a>

            <button class="nav-pill prayer" @click="showPrayerTimes = !showPrayerTimes">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 6v6l4 2"/>
                </svg>
                <span class="pill-label">{{ pcal_trans('prayer_times') }}</span>
            </button>

            <!-- Theme toggle -->
            <button class="theme-toggle" @click="darkMode = !darkMode">
                <span class="toggle-icon">
                    <template x-if="darkMode">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                        </svg>
                    </template>
                    <template x-if="!darkMode">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1" x2="12" y2="3"/>
                            <line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            <line x1="1" y1="12" x2="3" y2="12"/>
                            <line x1="21" y1="12" x2="23" y2="12"/>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                        </svg>
                    </template>
                </span>
                <span class="toggle-label" x-text="darkMode ? '{{ pcal_trans('dark_mode') }}' : '{{ pcal_trans('light_mode') }}'"></span>
            </button>

            <!-- Mobile hamburger -->
            <button class="mobile-menu-btn" @click="mobileMenu = !mobileMenu" :aria-expanded="mobileMenu">
                <div class="ham-wrap">
                    <span class="ham-line"
                          :style="mobileMenu ? 'transform:translateY(5.5px) rotate(45deg)' : ''"></span>
                    <span class="ham-line"
                          :style="mobileMenu ? 'opacity:0; transform:scaleX(0)' : ''"></span>
                    <span class="ham-line"
                          :style="mobileMenu ? 'transform:translateY(-5.5px) rotate(-45deg)' : ''"></span>
                </div>
            </button>
        </div>
    </div>

    <!-- ── MOBILE DROPDOWN ── -->
    <div class="mobile-nav" x-show="mobileMenu" x-transition>
        <div class="mobile-nav-row">
            <a href="/pashto-calendar/converter" class="nav-pill">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M8 3L4 7l4 4"/><path d="M4 7h16"/>
                    <path d="M16 21l4-4-4-4"/><path d="M20 17H4"/>
                </svg>
                {{ pcal_trans('converter') }}
            </a>

            <a href="/pashto-calendar/year" class="nav-pill">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                {{ pcal_trans('year') }}
            </a>

            <button class="nav-pill prayer" @click="showPrayerTimes = !showPrayerTimes; mobileMenu = false">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 6v6l4 2"/>
                </svg>
                {{ pcal_trans('prayer_times') }}
            </button>

            <div class="mobile-greg-pill">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                {{ now()->format('d M Y') }}
            </div>
        </div>
    </div>
</header>

<!-- Prayer Times Panel -->
<div class="prayer-panel"
     x-show="showPrayerTimes"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 max-h-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.away="showPrayerTimes = false">
    <div style="max-width:960px; margin:0 auto; padding:16px 20px;">
        <x-pashto-prayer-times city="kabul" />
    </div>
</div>

<!-- ============================================================
     CALENDAR MAIN
============================================================ -->
<div class="cal-wrapper"
     x-data='calendarApp({{ $year }}, {{ $month }}, @json($alpineDays, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT))'
     x-init="init">

    <!-- CALENDAR HEADER -->
    <div class="cal-header-card">
        <button class="nav-arrow" @click="changeMonth(-1)" :disabled="loading">
            <template x-if="!loading">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </template>
            <template x-if="loading"><span class="spinner"></span></template>
        </button>

        <div style="text-align:center; flex:1;">
            <div class="cal-month-title">
                <span x-text="toPashto(year)"></span>
                &nbsp;&mdash;&nbsp;
                <span x-text="monthName"></span>
            </div>
            <div class="cal-gregorian-sub" x-text="gregorianLabel"></div>
        </div>

        <div style="display:flex; align-items:center; gap:8px;">
            <button class="today-btn" @click="goToToday()">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                {{ pcal_trans('today') }}
            </button>

            <button class="nav-arrow" @click="changeMonth(1)" :disabled="loading">
                <template x-if="!loading">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </template>
                <template x-if="loading"><span class="spinner"></span></template>
            </button>
        </div>
    </div>

    <!-- WEEK HEADERS -->
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

    <!-- CALENDAR CONTENT -->
    <div id="calendar-content">
        @include('pashto-calendar::_calendar_content', [
            'days'  => $alpineDays,
            'month' => $month,
            'year'  => $year
        ])
    </div>

    <!-- FOOTER -->
    <div class="cal-footer">
        <a href="/pashto-calendar/demo" class="footer-link">{{ pcal_trans('demo_link') }}</a>
        <span class="footer-badge">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            {{ pcal_trans('package_name') }}
        </span>
    </div>

    <!-- MODAL OVERLAY -->
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

    <!-- MODAL BOX -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="modal-container"
        style="display:none;"
    >
        <div class="modal-box" @click.stop>
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-month-label" x-text="selectedMonthName + ' ' + toPashto(year)"></div>
                    <div class="modal-title">{{ pcal_trans('day_info') }}</div>
                </div>
                <div class="modal-header-right">
                    <div class="modal-day-badge" x-text="toPashto(selectedDay)"></div>
                    <button class="modal-close" @click="open = false">&times;</button>
                </div>
            </div>

            <div class="modal-body">
                <div x-show="isHoliday" class="holiday-banner">
                    <span class="holiday-banner-dot"></span>
                    <span x-text="holidayName"></span>
                </div>

                <div class="modal-events-list">
                    <template x-if="events.length === 0">
                        <div class="modal-empty">
                            <span class="modal-empty-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                            </span>
                            {{ pcal_trans('no_events') }}
                        </div>
                    </template>

                    <template x-for="event in events" :key="event.id">
                        <div class="modal-event-item"
                             :style="'background:' + (event.color || '#f59e0b') + '18; border:1px solid ' + (event.color || '#f59e0b') + '30;'">
                            <div class="modal-event-left">
                                <span class="modal-event-color-dot" :style="'background:' + (event.color || '#f59e0b')"></span>
                                <span class="modal-event-title" x-text="event.title"></span>
                            </div>
                            <div class="modal-event-actions">
                                <button type="button" class="modal-evt-btn edit"
                                        @click.stop="editEvent(event)" title="{{ pcal_trans('edit') }}">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <button type="button" class="modal-evt-btn delete"
                                        @click.stop="deleteEvent(event.id)" title="{{ pcal_trans('delete') }}">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                        <path d="M10 11v6M14 11v6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="modal-form-section">
                    <div class="form-section-label">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        {{ pcal_trans('add_event') }}
                    </div>

                    <input type="text" class="modal-input"
                           placeholder="{{ pcal_trans('event_title') }}"
                           x-model="form.title" @keydown.enter="saveEvent">

                    <input type="text" class="modal-input"
                           placeholder="{{ pcal_trans('event_description') }}"
                           x-model="form.description">

                    <input type="text" class="modal-input"
                           placeholder="{{ pcal_trans('event_time') }}"
                           x-model="form.time" style="margin-bottom:14px;">

                    <div class="recurrence-row">
                        <span class="recurrence-label">{{ pcal_trans('recurrence') }}:</span>
                        <select x-model="form.recurrence" class="modal-input" style="margin-bottom:0; flex:1;">
                            <option value="none">{{ pcal_trans('recurrence_none') }}</option>
                            <option value="daily">{{ pcal_trans('recurrence_daily') }}</option>
                            <option value="weekly">{{ pcal_trans('recurrence_weekly') }}</option>
                            <option value="monthly">{{ pcal_trans('recurrence_monthly') }}</option>
                            <option value="yearly">{{ pcal_trans('recurrence_yearly') }}</option>
                        </select>
                    </div>

                    <div class="recurrence-row" x-show="form.recurrence !== 'none'" style="margin-bottom:14px;">
                        <span class="recurrence-label" style="font-size:11px;">{{ pcal_trans('recurrence_end_date') }}:</span>
                        <input type="date" x-model="form.recurrence_end_date"
                               class="modal-input" style="margin-bottom:0; flex:1;">
                    </div>

                    <div class="color-picker-row">
                        <span class="color-picker-label">{{ pcal_trans('color_label') }}</span>
                        <div class="color-swatches">
                            @foreach(['#ef4444','#f59e0b','#14b8a6','#3b82f6','#8b5cf6','#ec4899','#f97316','#e2e8f0'] as $color)
                                <div class="color-swatch"
                                     style="background:{{ $color }}"
                                     :class="form.color === '{{ $color }}' ? 'active' : ''"
                                     @click="form.color = '{{ $color }}'"></div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-actions">
                        <button class="btn-primary" @click="saveEvent"
                                :disabled="saving || !form.title.trim()">
                            <span x-show="!saving">
                                <span x-text="editingEvent ? '{{ pcal_trans('update') }}' : '{{ pcal_trans('save') }}'"></span>
                            </span>
                            <span x-show="saving" style="display:flex; align-items:center; justify-content:center; gap:6px;">
                                <span class="spinner" style="width:14px;height:14px;border-color:rgba(0,0,0,0.2);border-top-color:#000;"></span>
                                {{ pcal_trans('saving') }}
                            </span>
                        </button>
                        <button class="btn-secondary" @click="open = false">{{ pcal_trans('cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden translation messages -->
    <div id="trans-messages" style="display:none;"
         data-failed-change-month="{{ pcal_trans('failed_to_change_month') }}"
         data-failed-save-event="{{ pcal_trans('failed_to_save_event') }}"
         data-failed-delete="{{ pcal_trans('failed_to_delete') }}"
         data-confirm-delete="{{ pcal_trans('confirm_delete') }}"
         data-saving="{{ pcal_trans('saving') }}">
    </div>

</div>{{-- cal-wrapper --}}

<script>
/* ============================================================
   CLOCK APP
============================================================ */
function clockApp() {
    return {
        time: '',
        timeHMS: '',
        timeAMPM: '',
        dateLabel: '',
        darkMode: localStorage.getItem('theme') !== 'light',
        showPrayerTimes: false,
        mobileMenu: false,

        init() {
            this.tick();
            setInterval(() => this.tick(), 1000);
            this.$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'));
        },

        tick() {
            const now  = new Date();
            const h24  = now.getHours();
            const h12  = h24 % 12 === 0 ? 12 : h24 % 12;
            const ampm = h24 >= 12 ? 'PM' : 'AM';
            const m    = String(now.getMinutes()).padStart(2, '0');
            const s    = String(now.getSeconds()).padStart(2, '0');
            this.timeHMS  = h12 + ':' + m + ':' + s;
            this.timeAMPM = ampm;
            this.time     = this.timeHMS + ' ' + ampm;
            const days    = ['یکشنبه','دوشنبه','سه‌شنبه','چهارشنبه','پنجشنبه','جمعه','شنبه'];
            this.dateLabel = days[now.getDay()];
        }
    };
}

const currentPashtoYear  = {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->year }};
const currentPashtoMonth = {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->month }};

/* ============================================================
   CALENDAR APP
============================================================ */
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
            color: '#f59e0b',
            recurrence: 'none',
            recurrence_end_date: '',
        },

        toPashto(n) {
            if (n === undefined || n === null) return '';
            const ps = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
            return String(n).replace(/[0-9]/g, d => ps[+d]);
        },

        init() {
            const monthNames = ['','وری','غویی','غبرګولی','چنګاښ','زمری','وږی','تله','لړم','لیندۍ','مرغومی','سلواغه','کب'];
            this.monthName = monthNames[this.month] || '';
            const d = new Date(this.year, this.month - 1, 1);
            this.gregorianLabel = d.toLocaleString('en', { month: 'long', year: 'numeric' });
        },

        async changeMonth(delta, targetYear = null, targetMonth = null) {
            if (this.loading) return;
            this.loading = true;

            let newMonth, newYear;
            if (targetYear && targetMonth) {
                newYear = targetYear; newMonth = targetMonth;
            } else {
                newMonth = this.month + delta; newYear = this.year;
                if (newMonth < 1)  { newMonth = 12; newYear--; }
                if (newMonth > 12) { newMonth = 1;  newYear++; }
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
                if (respJson.ok) { const data = await respJson.json(); this.days = data.days; }

                this.year = newYear; this.month = newMonth;
                const monthNames = ['','وری','غویی','غبرګولی','چنګاښ','زمری','وږی','تله','لړم','لیندۍ','مرغومی','سلواغه','کب'];
                this.monthName = monthNames[this.month] || '';
                const d = new Date(this.year, this.month - 1, 1);
                this.gregorianLabel = d.toLocaleString('en', { month: 'long', year: 'numeric' });
                const url = new URL(window.location);
                url.searchParams.set('year', this.year);
                url.searchParams.set('month', this.month);
                window.history.pushState({}, '', url);
            } catch(e) {
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
            this.form = { title:'', description:'', time:'', color:'#f59e0b', recurrence:'none', recurrence_end_date:'' };
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
            this.form.color = event.color || '#f59e0b';
            this.form.recurrence = event.recurrence || 'none';
            this.form.recurrence_end_date = event.recurrence_end_date || '';
            this.open = true;
        },

        editEvent(event) {
            this.editingEvent = event;
            this.form.title = event.title || '';
            this.form.description = event.description || '';
            this.form.time = event.time || '';
            this.form.color = event.color || '#f59e0b';
            this.form.recurrence = event.recurrence || 'none';
            this.form.recurrence_end_date = event.recurrence_end_date || '';
        },

        async saveEvent() {
            if (!this.form.title.trim()) return;
            this.saving = true;
            const payload = {
                title: this.form.title, description: this.form.description,
                time: this.form.time, year: this.year, month: this.month,
                day: this.selectedDay, color: this.form.color,
                recurrence: this.form.recurrence, recurrence_end_date: this.form.recurrence_end_date,
            };
            const trans = document.getElementById('trans-messages')?.dataset || {};
            try {
                const method = this.editingEvent ? 'PUT' : 'POST';
                const url    = this.editingEvent ? `/pashto-calendar/event/${this.editingEvent.id}` : '/pashto-calendar/event';
                const resp   = await fetch(url, {
                    method,
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
                    body: JSON.stringify(payload)
                });
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
                    } else { dayObj.events.push(savedEvent); }
                }
                this.form = { title:'', description:'', time:'', color:'#f59e0b', recurrence:'none', recurrence_end_date:'' };
                this.editingEvent = null;
                this.open = false;
                const container = document.getElementById('calendar-content');
                if (container) {
                    const res = await fetch(`/pashto-calendar/data/${this.year}/${this.month}?ajax=1`);
                    if (res.ok) { container.innerHTML = await res.text(); if (typeof Alpine !== 'undefined') Alpine.initTree(container); }
                    const jsonRes = await fetch(`/pashto-calendar/data/${this.year}/${this.month}?json=1`);
                    if (jsonRes.ok) { const data = await jsonRes.json(); this.days = data.days; }
                }
            } catch(e) {
                console.error(e);
                alert(trans.failedSaveEvent || 'د پیښې خوندي کول ناکام شول');
            } finally { this.saving = false; }
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
                if (dayObj && dayObj.events) dayObj.events = dayObj.events.filter(e => e.id !== id);
                const container = document.getElementById('calendar-content');
                if (container) {
                    const res = await fetch(`/pashto-calendar/data/${this.year}/${this.month}?ajax=1`);
                    if (res.ok) { container.innerHTML = await res.text(); if (typeof Alpine !== 'undefined') Alpine.initTree(container); }
                    const jsonRes = await fetch(`/pashto-calendar/data/${this.year}/${this.month}?json=1`);
                    if (jsonRes.ok) { const data = await jsonRes.json(); this.days = data.days; }
                }
            } catch(e) {
                console.error(e);
                alert(trans.failedDelete || 'حذف ناکام شو');
            }
        }
    };
}

document.addEventListener('alpine:init', () => {
    Alpine.store('view', 'calendar');
});
</script>
</body>
</html>
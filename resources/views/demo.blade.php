<!DOCTYPE html>
<html lang="ps" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>د پښتو کلیندر — Pashto Calendar</title>
    <!-- Modern fonts: Inter for English, Noto Naskh Arabic for Pashto -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Naskh+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-deep:    #0b1121;
            --bg-card:    #111a2e;
            --bg-glass:   rgba(17, 26, 46, 0.7);
            --accent-1:   #3b82f6;   /* Blue */
            --accent-2:   #06b6d4;   /* Cyan */
            --accent-3:   #10b981;   /* Emerald / Teal */
            --accent-4:   #8b5cf6;   /* Violet (for highlights) */
            --danger:     #ef4444;   /* Red */
            --text:       #e2e8f0;
            --muted:      #94a3b8;
            --border:     rgba(59, 130, 246, 0.18);
            --glass:      rgba(255, 255, 255, 0.03);
            --glass-b:    rgba(255, 255, 255, 0.06);
            --radius-sm:  12px;
            --radius-md:  20px;
            --radius-lg:  24px;
            --radius-xl:  32px;
            --shadow:     0 20px 40px rgba(0,0,0,0.4);
            --shadow-hover: 0 25px 50px rgba(0,0,0,0.5);
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--bg-deep);
            color: var(--text);
            font-family: 'Noto Naskh Arabic', serif;
            overflow-x: hidden;
            min-height: 100vh;
        }
        /* Animated background gradient instead of stars – cleaner modern look */
        .bg-gradient-animated {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0; pointer-events: none;
            background: radial-gradient(ellipse at top right, rgba(59, 130, 246, 0.12) 0%, transparent 50%),
                        radial-gradient(ellipse at bottom left, rgba(6, 182, 212, 0.08) 0%, transparent 50%),
                        radial-gradient(ellipse at center, rgba(139, 92, 246, 0.05) 0%, transparent 70%);
            animation: bgPulse 8s ease-in-out infinite alternate;
        }
        @keyframes bgPulse {
            0% { opacity: 0.8; transform: scale(1); }
            100% { opacity: 1; transform: scale(1.05); }
        }
        .content { position: relative; z-index: 1; }
        .hero {
            min-height: 100vh; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center; padding: 40px 20px;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute;
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
            top: 50%; left: 50%; transform: translate(-50%, -50%);
            animation: pulse-glow 4s ease-in-out infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.6; }
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 100px; padding: 8px 24px; font-size: 13px;
            color: var(--accent-1); margin-bottom: 30px;
            animation: fade-down 0.8s ease both; letter-spacing: 1px;
            backdrop-filter: blur(10px);
        }
        .hero-title {
            font-family: 'Inter', sans-serif; font-size: clamp(20px, 5vw, 42px);
            font-weight: 800; color: #fff; margin-bottom: 10px;
            animation: fade-down 0.8s ease 0.1s both; letter-spacing: -0.5px;
        }
        .hero-title-ps {
            font-size: clamp(32px, 8vw, 72px); font-weight: 700;
            background: linear-gradient(135deg, var(--accent-1) 0%, var(--accent-2) 50%, var(--accent-4) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; line-height: 1.2; margin-bottom: 20px;
            animation: fade-down 0.8s ease 0.2s both;
        }
        .hero-sub {
            font-size: 18px; color: var(--muted); margin-bottom: 50px;
            animation: fade-down 0.8s ease 0.3s both;
        }
        .live-date {
            background: var(--bg-glass); backdrop-filter: blur(24px);
            border: 1px solid var(--border); border-radius: var(--radius-lg);
            padding: 30px 50px; margin-bottom: 50px;
            animation: fade-up 0.8s ease 0.4s both; position: relative;
        }
        .live-date::before {
            content: ''; position: absolute; inset: -1px; border-radius: inherit;
            background: linear-gradient(135deg, rgba(59,130,246,0.4), transparent, rgba(6,182,212,0.3));
            z-index: -1; opacity: 0.5;
        }
        .live-date-label { font-size: 12px; letter-spacing: 3px; color: var(--accent-1); margin-bottom: 15px; }
        .live-date-numbers { display: flex; gap: 20px; align-items: center; justify-content: center; }
        .date-unit { text-align: center; }
        .date-unit-num {
            font-size: 52px; font-weight: 700; line-height: 1;
            background: linear-gradient(180deg, #fff 0%, var(--accent-2) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .date-unit-label { font-size: 11px; color: var(--muted); margin-top: 6px; letter-spacing: 1px; }
        .date-sep { font-size: 40px; color: var(--accent-1); opacity: 0.3; margin-bottom: 20px; }
        .hero-btns { display: flex; gap: 16px; flex-wrap: wrap; justify-content: center; animation: fade-up 0.8s ease 0.5s both; }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-1), #2563eb); color: #fff;
            font-weight: 600; padding: 14px 32px; border-radius: var(--radius-sm);
            text-decoration: none; font-size: 15px; transition: var(--transition);
            border: none; cursor: pointer; font-family: 'Inter', sans-serif;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(59, 130, 246, 0.4); }
        .btn-ghost {
            background: var(--glass); color: var(--text); font-weight: 500;
            padding: 14px 32px; border-radius: var(--radius-sm); text-decoration: none;
            font-size: 15px; border: 1px solid var(--border); backdrop-filter: blur(10px);
            transition: var(--transition); font-family: 'Inter', sans-serif;
        }
        .btn-ghost:hover { background: var(--glass-b); transform: translateY(-2px); border-color: rgba(59,130,246,0.4); }
        .scroll-indicator {
            position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%);
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            color: var(--muted); font-size: 12px; animation: fade-in 1s ease 1s both;
        }
        .scroll-line {
            width: 1px; height: 40px;
            background: linear-gradient(to bottom, var(--accent-1), transparent);
            animation: scroll-pulse 2s ease-in-out infinite;
        }
        @keyframes scroll-pulse {
            0%, 100% { transform: scaleY(1); opacity: 1; }
            50% { transform: scaleY(0.5); opacity: 0.4; }
        }
        .section { padding: 100px 20px; max-width: 1100px; margin: 0 auto; }
        .section-label {
            font-size: 11px; letter-spacing: 4px; color: var(--accent-2);
            text-transform: uppercase; margin-bottom: 16px;
            opacity: 0; transform: translateY(20px); transition: all 0.6s ease;
        }
        .section-title {
            font-size: clamp(28px, 4vw, 42px); font-weight: 700; color: #fff;
            margin-bottom: 50px; opacity: 0; transform: translateY(20px); transition: all 0.6s ease 0.1s;
            font-family: 'Inter', sans-serif;
        }
        .section-label.visible, .section-title.visible { opacity: 1; transform: translateY(0); }
        .glass-card {
            background: var(--bg-glass); backdrop-filter: blur(16px);
            border: 1px solid var(--border); border-radius: var(--radius-md);
            padding: 28px; transition: var(--transition);
            opacity: 0; transform: translateY(30px);
        }
        .glass-card.visible { opacity: 1; transform: translateY(0); }
        .glass-card:hover {
            background: rgba(255,255,255,0.05); border-color: rgba(59,130,246,0.35);
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 80px; }
        .stat-card {
            background: var(--bg-glass); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 30px; text-align: center;
            position: relative; overflow: hidden;
            opacity: 0; transform: translateY(30px); transition: all 0.5s ease;
        }
        .stat-card.visible { opacity: 1; transform: translateY(0); }
        .stat-card::before {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle at center, rgba(59,130,246,0.06) 0%, transparent 60%);
        }
        .stat-num {
            font-size: 48px; font-weight: 700;
            background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; line-height: 1; margin-bottom: 10px;
        }
        .stat-label { font-size: 13px; color: var(--muted); letter-spacing: 1px; font-family: 'Inter', sans-serif; }
        .months-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
        @media (max-width: 768px) { .months-grid { grid-template-columns: repeat(2, 1fr); } }
        .month-card {
            background: var(--bg-glass); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 20px 16px;
            text-align: center; transition: var(--transition);
            cursor: default; opacity: 0; transform: scale(0.9);
        }
        .month-card.visible { opacity: 1; transform: scale(1); }
        .month-card:hover { background: rgba(59,130,246,0.08); border-color: rgba(59,130,246,0.4); transform: scale(1.04); }
        .month-card.current {
            background: linear-gradient(135deg, rgba(59,130,246,0.15), rgba(6,182,212,0.08));
            border-color: rgba(59,130,246,0.5);
        }
        .month-num { font-size: 11px; color: var(--accent-2); letter-spacing: 2px; margin-bottom: 8px; }
        .month-ps { font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 6px; }
        .month-dari { font-size: 13px; color: var(--muted); margin-bottom: 4px; }
        .month-en { font-size: 11px; color: rgba(148,163,184,0.4); font-family: 'Inter', sans-serif; direction: ltr; }
        .holidays-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; }
        .holiday-card {
            display: flex; align-items: flex-start; gap: 16px;
            background: var(--bg-glass); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 20px; transition: var(--transition);
            opacity: 0; transform: translateX(20px);
        }
        .holiday-card.visible { opacity: 1; transform: translateX(0); }
        .holiday-card:hover { background: rgba(239,68,68,0.05); border-color: rgba(239,68,68,0.3); transform: translateY(-2px); }
        .holiday-date {
            background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25);
            border-radius: 10px; padding: 8px 14px; font-size: 13px; color: var(--danger);
            font-weight: 700; white-space: nowrap; direction: ltr; font-family: 'Inter', sans-serif;
        }
        .holiday-name-ps { font-size: 15px; font-weight: 600; color: #fff; margin-bottom: 4px; }
        .holiday-name-en { font-size: 12px; color: var(--muted); direction: ltr; font-family: 'Inter', sans-serif; }
        .code-block {
            background: rgba(11,17,33,0.9); border: 1px solid rgba(6,182,212,0.2);
            border-radius: var(--radius-md); padding: 28px; direction: ltr;
            font-family: 'Courier New', monospace; font-size: 14px;
            line-height: 1.8; overflow-x: auto; position: relative;
        }
        .code-block::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(to right, transparent, var(--accent-2), transparent);
        }
        .code-comment { color: #556; }
        .code-fn      { color: var(--accent-2); }
        .code-str     { color: #fbbf24; }
        .code-kw      { color: #c084fc; }
        .code-var     { color: #93c5fd; }
        .code-output  { color: var(--accent-1); }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        .feature-icon { font-size: 36px; margin-bottom: 16px; }
        .feature-title { font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 10px; }
        .feature-desc { font-size: 14px; color: var(--muted); line-height: 1.7; }
        .manip-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 12px; }
        .manip-item {
            display: flex; justify-content: space-between; align-items: center;
            background: var(--bg-glass); border: 1px solid var(--border);
            border-radius: var(--radius-sm); padding: 16px 20px;
            transition: var(--transition);
        }
        .manip-item:hover { background: var(--glass-b); border-color: rgba(59,130,246,0.4); }
        .manip-code { font-family: 'Courier New', monospace; font-size: 12px; color: var(--accent-2); direction: ltr; }
        .manip-value { font-size: 16px; font-weight: 700; color: #fbbf24; }
        .divider {
            width: 100%; height: 1px;
            background: linear-gradient(to right, transparent, var(--accent-1), transparent);
            opacity: 0.15; margin: 0 auto;
        }
        .footer { padding: 60px 20px; text-align: center; position: relative; }
        .footer::before {
            content: ''; position: absolute; top: 0; left: 10%; right: 10%; height: 1px;
            background: linear-gradient(to right, transparent, var(--accent-1), transparent); opacity: 0.2;
        }
        .footer-logo {
            font-size: 32px; font-family: 'Inter', sans-serif; font-weight: 800;
            background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; margin-bottom: 12px;
        }
        .footer-sub { font-size: 14px; color: var(--muted); margin-bottom: 30px; font-family: 'Inter', sans-serif; }
        @keyframes fade-down { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fade-up   { from { opacity: 0; transform: translateY(20px);  } to { opacity: 1; transform: translateY(0); } }
        @keyframes fade-in   { from { opacity: 0; } to { opacity: 1; } }
        .navbar {
            position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
            z-index: 100; background: rgba(11,17,33,0.8); backdrop-filter: blur(20px);
            border: 1px solid var(--border); border-radius: 100px; padding: 12px 32px;
            display: flex; align-items: center; gap: 30px;
            animation: fade-down 0.6s ease both;
        }
        .nav-logo { font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 700; color: var(--accent-1); letter-spacing: 1px; text-decoration: none; }
        .nav-links { display: flex; gap: 24px; list-style: none; }
        .nav-links a { color: var(--muted); text-decoration: none; font-size: 13px; transition: color 0.2s; font-family: 'Inter', sans-serif; }
        .nav-links a:hover { color: var(--accent-1); }
        @media (max-width: 600px) { .nav-links { display: none; } .months-grid { grid-template-columns: repeat(2, 1fr); } }
        .install-box {
            background: rgba(11,17,33,0.9); border: 1px solid rgba(6,182,212,0.3);
            border-radius: var(--radius-md); padding: 20px 30px; direction: ltr;
            font-family: 'Courier New', monospace; font-size: 15px; color: var(--accent-2);
            display: inline-flex; align-items: center; gap: 12px; margin-bottom: 20px;
            position: relative; overflow: hidden;
        }
        .install-box::after {
            content: ''; position: absolute; inset: -1px; border-radius: inherit;
            background: linear-gradient(135deg, rgba(6,182,212,0.2), transparent); z-index: -1;
        }
        .copy-btn {
            background: rgba(6,182,212,0.1); border: 1px solid rgba(6,182,212,0.3);
            border-radius: 8px; color: var(--accent-2); padding: 4px 12px; font-size: 12px;
            cursor: pointer; transition: var(--transition); font-family: 'Inter', sans-serif;
        }
        .copy-btn:hover { background: rgba(6,182,212,0.2); }

        /* ── Mini Calendar Styles ── */
        .mini-calendar-card {
            max-width: 230px; width: 100%; margin: 0 auto;
            font-family: 'Noto Naskh Arabic', serif;
            background: var(--bg-glass); backdrop-filter: blur(16px);
            border: 1px solid var(--border); border-radius: var(--radius-md);
            padding: 16px; box-shadow: var(--shadow);
            transition: var(--transition);
        }
        .mini-calendar-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover); }
        .mini-cal-header { text-align: center; margin-bottom: 14px; }
        .mini-cal-year {
            font-size: 18px; font-weight: 700;
            background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; display: block;
        }
        .mini-cal-month { font-size: 15px; font-weight: 600; color: var(--text); margin-top: 2px; display: block; }
        .mini-cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 3px; margin-bottom: 12px; }
        .mini-cal-day-name { font-size: 11px; color: var(--muted); text-align: center; padding: 4px 0; }
        .mini-cal-day-name.friday { color: var(--accent-1); }
        .mini-cal-cell {
            aspect-ratio: 1; display: flex; align-items: center; justify-content: center;
            font-size: 13px; border-radius: 8px; cursor: pointer; transition: 0.15s;
            color: var(--text); position: relative;
        }
        .mini-cal-cell.empty { background: transparent; pointer-events: none; }
        .mini-cal-cell:hover { background: rgba(59,130,246,0.15); }
        .mini-cal-cell.today { background: var(--accent-1); color: #fff; font-weight: 700; box-shadow: 0 0 10px rgba(59,130,246,0.3); }
        .mini-cal-cell.holiday { color: var(--danger); font-weight: 600; }
        .mini-cal-cell.holiday:hover { background: rgba(239,68,68,0.15); }
        .mini-cal-cell.has-event::after {
            content: ''; width: 5px; height: 5px; background: var(--accent-2); border-radius: 50%;
            position: absolute; bottom: 3px; left: 50%; transform: translateX(-50%);
        }
        .mini-cal-link {
            display: flex; align-items: center; justify-content: center; gap: 4px;
            font-size: 12px; color: var(--muted); text-decoration: none; transition: color 0.2s;
            padding: 6px 0 0; border-top: 1px solid var(--border);
        }
        .mini-cal-link:hover { color: var(--accent-1); }
    </style>
</head>
<body>
<div class="bg-gradient-animated"></div>

<nav class="navbar">
    <a href="#" class="nav-logo">PASHTO CAL</a>
    <ul class="nav-links">
        <li><a href="#features">{{ pcal_trans('demo_features') }}</a></li>
        <li><a href="#months">{{ pcal_trans('demo_months') }}</a></li>
        <li><a href="#mini-calendar">{{ pcal_trans('demo_mini_cal') }}</a></li>
        <li><a href="#year-view">{{ pcal_trans('demo_year_view') }}</a></li>
        <li><a href="#holidays">{{ pcal_trans('demo_holidays') }}</a></li>
        <li><a href="#install">{{ pcal_trans('demo_install') }}</a></li>
        <li><a href="/pashto-calendar" style="color: var(--accent-1)">{{ pcal_trans('demo_calendar_link') }}</a></li>
    </ul>
</nav>

<div class="content">

    {{-- HERO --}}
    <section class="hero">
        <div class="hero-badge">✦ &nbsp; qadir/pashto-calendar &nbsp; ✦</div>
        <div class="hero-title">{{ pcal_trans('demo_hero_title') }}</div>
        <div class="hero-title-ps">{{ pcal_trans('demo_hero_title_ps') }}</div>
        <div class="hero-sub">{{ pcal_trans('demo_hero_sub') }}</div>

        <div class="live-date">
            <div class="live-date-label">{{ pcal_trans('demo_current_date') }}</div>
            <div class="live-date-numbers">
                <div class="date-unit">
                    <div class="date-unit-num">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($now->day) }}</div>
                    <div class="date-unit-label">{{ pcal_trans('demo_day') }}</div>
                </div>
                <div class="date-sep">·</div>
                <div class="date-unit">
                    <div class="date-unit-num" style="font-size: 36px;">{{ $now->monthName() }}</div>
                    <div class="date-unit-label">{{ pcal_trans('demo_month') }}</div>
                </div>
                <div class="date-sep">·</div>
                <div class="date-unit">
                    <div class="date-unit-num">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($now->year) }}</div>
                    <div class="date-unit-label">{{ pcal_trans('demo_year') }}</div>
                </div>
            </div>
        </div>

        <div class="hero-btns">
            <a href="/pashto-calendar" class="btn-primary">{{ pcal_trans('demo_open_calendar') }}</a>
            <a href="#install" class="btn-ghost">{{ pcal_trans('demo_go_install') }}</a>
            <a href="#features" class="btn-ghost">{{ pcal_trans('demo_go_features') }}</a>
        </div>

        <div class="scroll-indicator">
            <span style="font-size:11px; letter-spacing:2px; opacity:0.5">SCROLL</span>
            <div class="scroll-line"></div>
        </div>
    </section>

    <div class="divider"></div>

    {{-- STATS --}}
    <div class="section" style="padding-top: 80px; padding-bottom: 40px;">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-num">۱۲</div>
                <div class="stat-label">{{ pcal_trans('demo_stats_months') }}</div>
            </div>
            <div class="stat-card" style="transition-delay: 0.1s">
                <div class="stat-num">۳</div>
                <div class="stat-label">{{ pcal_trans('demo_stats_languages') }}</div>
            </div>
            <div class="stat-card" style="transition-delay: 0.2s">
                <div class="stat-num">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber(count(\Qadir\PashtoCalendar\Support\Holidays::all())) }}</div>
                <div class="stat-label">{{ pcal_trans('demo_stats_holidays') }}</div>
            </div>
            <div class="stat-card" style="transition-delay: 0.3s">
                <div class="stat-num">∞</div>
                <div class="stat-label">{{ pcal_trans('demo_stats_events') }}</div>
            </div>
        </div>
    </div>

    {{-- FEATURES --}}
    <section class="section" id="features">
        <div class="section-label">{{ pcal_trans('demo_section_features') }}</div>
        <div class="section-title">{{ pcal_trans('demo_features_title') }}</div>

        <div class="features-grid">
            <div class="glass-card">
                <div class="feature-icon">🔄</div>
                <div class="feature-title">{{ pcal_trans('demo_feat_convert_title') }}</div>
                <div class="feature-desc">{{ pcal_trans('demo_feat_convert_desc') }}</div>
            </div>
            <div class="glass-card" style="transition-delay:0.1s">
                <div class="feature-icon">🌐</div>
                <div class="feature-title">{{ pcal_trans('demo_feat_lang_title') }}</div>
                <div class="feature-desc">{{ pcal_trans('demo_feat_lang_desc') }}</div>
            </div>
            <div class="glass-card" style="transition-delay:0.2s">
                <div class="feature-icon">📅</div>
                <div class="feature-title">{{ pcal_trans('demo_feat_events_title') }}</div>
                <div class="feature-desc">{{ pcal_trans('demo_feat_events_desc') }}</div>
            </div>
            <div class="glass-card" style="transition-delay:0.3s">
                <div class="feature-icon">✅</div>
                <div class="feature-title">{{ pcal_trans('demo_feat_validation_title') }}</div>
                <div class="feature-desc">{{ pcal_trans('demo_feat_validation_desc') }}</div>
            </div>
            <div class="glass-card" style="transition-delay:0.4s">
                <div class="feature-icon">🎨</div>
                <div class="feature-title">{{ pcal_trans('demo_feat_directives_title') }}</div>
                <div class="feature-desc">{{ pcal_trans('demo_feat_directives_desc') }}</div>
            </div>
            <div class="glass-card" style="transition-delay:0.5s">
                <div class="feature-icon">⚡</div>
                <div class="feature-title">{{ pcal_trans('demo_feat_carbon_title') }}</div>
                <div class="feature-desc">{{ pcal_trans('demo_feat_carbon_desc') }}</div>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    {{-- MONTHS --}}
    <section class="section" id="months">
        <div class="section-label">{{ pcal_trans('demo_section_months') }}</div>
        <div class="section-title">{{ pcal_trans('demo_months_title') }}</div>

        <div class="months-grid">
            @for($i = 1; $i <= 12; $i++)
                <div class="month-card {{ $i === $now->month ? 'current' : '' }}"
                     style="transition-delay: {{ ($i-1) * 0.05 }}s">
                    <div class="month-num">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="month-ps">{{ \Qadir\PashtoCalendar\Support\Months::name($i, 'pashto') }}</div>
                    <div class="month-dari">{{ \Qadir\PashtoCalendar\Support\Months::name($i, 'dari') }}</div>
                    <div class="month-en">{{ \Qadir\PashtoCalendar\Support\Months::name($i, 'en') }}</div>
                    @if($i === $now->month)
                        <div style="margin-top:8px; font-size:10px; color:var(--accent-1); letter-spacing:1px;">{{ pcal_trans('demo_current_label') }}</div>
                    @endif
                </div>
            @endfor
        </div>
    </section>

    <div class="divider"></div>

    {{-- DATE MANIPULATION --}}
    <section class="section">
        <div class="section-label">{{ pcal_trans('demo_section_manip') }}</div>
        <div class="section-title">{{ pcal_trans('demo_manip_title') }}</div>

        @php $t = \Qadir\PashtoCalendar\PashtoCalendar::now(); @endphp

        <div class="manip-grid">
            <div class="manip-item glass-card">
                <div class="manip-code">-&gt;addDays(10)</div>
                <div class="manip-value">{{ (clone $t)->addDays(10)->formatPashto() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.05s">
                <div class="manip-code">-&gt;subDays(5)</div>
                <div class="manip-value">{{ (clone $t)->subDays(5)->formatPashto() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.1s">
                <div class="manip-code">-&gt;addMonths(2)</div>
                <div class="manip-value">{{ (clone $t)->addMonths(2)->formatPashto() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.15s">
                <div class="manip-code">-&gt;addYears(1)</div>
                <div class="manip-value">{{ (clone $t)->addYears(1)->formatPashto() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.2s">
                <div class="manip-code">-&gt;startOfMonth()</div>
                <div class="manip-value">{{ $t->startOfMonth()->formatPashto() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.25s">
                <div class="manip-code">-&gt;endOfMonth()</div>
                <div class="manip-value">{{ $t->endOfMonth()->formatPashto() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.3s">
                <div class="manip-code">-&gt;diffForHumans()</div>
                <div class="manip-value">{{ $t->diffForHumans() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.35s">
                <div class="manip-code">-&gt;subDays(3)-&gt;diffForHumans()</div>
                <div class="manip-value">{{ (clone $t)->subDays(3)->diffForHumans() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.4s">
                <div class="manip-code">-&gt;isLeapYear()</div>
                <div class="manip-value">{{ $t->isLeapYear() ? pcal_trans('demo_yes') : pcal_trans('demo_no') }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.45s">
                <div class="manip-code">-&gt;daysInMonth()</div>
                <div class="manip-value">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($t->daysInMonth()) }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.5s">
                <div class="manip-code">-&gt;dayOfYear()</div>
                <div class="manip-value">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($t->dayOfYear()) }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.55s">
                <div class="manip-code">-&gt;weekOfYear()</div>
                <div class="manip-value">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($t->weekOfYear()) }}</div>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    {{-- MINI CALENDAR --}}
    <section class="section" id="mini-calendar">
        <div class="section-label">{{ pcal_trans('demo_section_mini') }}</div>
        <div class="section-title">{{ pcal_trans('demo_mini_title') }}</div>

        <div style="display:flex; justify-content:center; padding:20px 0;">
            <x-pashto-mini-calendar :year="$now->year" :month="$now->month" />
        </div>

        <div style="max-width:600px; margin:30px auto 0; text-align:center;">
            <div class="glass-card" style="opacity:1; transform:none;">
                <div style="font-size:16px; color:var(--accent-2); margin-bottom:12px;">{{ pcal_trans('demo_mini_howto_title') }}</div>
                <div style="font-size:14px; color: var(--text); line-height:2;">
                    {{ pcal_trans('demo_mini_howto_desc') }}
                </div>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    {{-- YEAR VIEW --}}
    <section class="section" id="year-view">
        <div class="section-label">{{ pcal_trans('demo_section_year') }}</div>
        <div class="section-title">{{ pcal_trans('demo_year_title') }}</div>

        <div style="text-align:center; margin-bottom:30px;">
            <a href="/pashto-calendar/year?year={{ $now->year }}" class="btn-primary" style="display:inline-flex; gap:8px;">
                📆 {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($now->year) }} {{ pcal_trans('demo_year_button') }}
            </a>
        </div>

        <div class="glass-card" style="max-width:700px; margin:0 auto; text-align:center; opacity:1; transform:none;">
            <div style="font-size:16px; color:var(--accent-2); margin-bottom:12px;">{{ pcal_trans('demo_year_about_title') }}</div>
            <div style="font-size:14px; color: var(--text); line-height:2;">
                {{ pcal_trans('demo_year_about_desc') }}
            </div>
        </div>
    </section>

    <div class="divider"></div>

    {{-- HOLIDAYS --}}
    <section class="section" id="holidays">
        <div class="section-label">{{ pcal_trans('demo_section_holidays') }}</div>
        <div class="section-title">{{ pcal_trans('demo_holidays_title') }}</div>

        <div class="holidays-grid">
            @foreach(\Qadir\PashtoCalendar\Support\Holidays::allParsed() as $index => $holiday)
                <div class="holiday-card" style="transition-delay: {{ $index * 0.05 }}s">
                    <div class="holiday-date">
                        {{ str_pad($holiday['month'],2,'0',STR_PAD_LEFT) }}/{{ str_pad($holiday['day'],2,'0',STR_PAD_LEFT) }}
                    </div>
                    <div>
                        <div class="holiday-name-ps">{{ $holiday['name_ps'] }}</div>
                        <div class="holiday-name-en">{{ $holiday['name_en'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="divider"></div>

    {{-- BLADE DIRECTIVES --}}
    <section class="section">
        <div class="section-label">{{ pcal_trans('demo_section_directives') }}</div>
        <div class="section-title">{{ pcal_trans('demo_directives_title') }}</div>

        <div class="glass-card" style="margin-bottom: 20px;">
            <div class="code-block">
<span class="code-comment">&#123;&#123;-- اوسنۍ نیټه --&#125;&#125;</span>
<span class="code-kw">&#64;</span><span class="code-fn">pashtoNow</span>
<span class="code-output">→ {{ pashto_now() }}</span>

<span class="code-comment">&#123;&#123;-- د میلادي نیټې بدلون --&#125;&#125;</span>
<span class="code-kw">&#64;</span><span class="code-fn">pashtoDate</span>(<span class="code-str">$post-&gt;created_at</span>)
<span class="code-output">→ {{ to_shamsi_pashto(now()) }}</span>

<span class="code-comment">&#123;&#123;-- معیاري بڼه --&#125;&#125;</span>
<span class="code-kw">&#64;</span><span class="code-fn">pashtoFormat</span>(<span class="code-str">'2024-03-20'</span>, <span class="code-str">'Y/m/d'</span>)
<span class="code-output">→ {{ to_shamsi('2024-03-20', 'Y/m/d') }}</span>

<span class="code-comment">&#123;&#123;-- پښتو شمیرې --&#125;&#125;</span>
<span class="code-kw">&#64;</span><span class="code-fn">pashtoNumber</span>(<span class="code-var">$year</span>)
<span class="code-output">→ {{ pashto_number($now->year) }}</span>

<span class="code-comment">&#123;&#123;-- د رخصتۍ چک --&#125;&#125;</span>
<span class="code-kw">&#64;</span><span class="code-fn">ifHoliday</span>(1, 1) نوروز مبارک! <span class="code-kw">&#64;</span><span class="code-fn">endIfHoliday</span>
<span class="code-output">→ {{ is_pashto_holiday(1, 1) ? '✅ نوروز مبارک!' : '(نه رخصتي)' }}</span>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    {{-- INSTALL --}}
    <section class="section" id="install" style="text-align:center;">
        <div class="section-label" style="text-align:center">{{ pcal_trans('demo_section_install') }}</div>
        <div class="section-title" style="text-align:center">{{ pcal_trans('demo_install_title') }}</div>

        <div style="display:flex; flex-direction:column; align-items:center; gap:16px; margin-bottom:50px;">
            <div class="install-box">
                <span>composer require qadir/pashto-calendar</span>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('composer require qadir/pashto-calendar'); this.textContent='{{ pcal_trans('demo_copied') }}'">{{ pcal_trans('demo_copy') }}</button>
            </div>
            <div class="install-box" style="border-color:rgba(59,130,246,0.3); color:var(--accent-1)">
                <span>php artisan vendor:publish --tag=pashto-calendar-config</span>
                <button class="copy-btn" style="border-color:rgba(59,130,246,0.3); color:var(--accent-1); background:rgba(59,130,246,0.1)" onclick="navigator.clipboard.writeText('php artisan vendor:publish --tag=pashto-calendar-config'); this.textContent='{{ pcal_trans('demo_copied') }}'">{{ pcal_trans('demo_copy') }}</button>
            </div>
            <div class="install-box" style="border-color:rgba(239,68,68,0.3); color:var(--danger)">
                <span>php artisan migrate</span>
                <button class="copy-btn" style="border-color:rgba(239,68,68,0.3); color:var(--danger); background:rgba(239,68,68,0.1)" onclick="navigator.clipboard.writeText('php artisan migrate'); this.textContent='{{ pcal_trans('demo_copied') }}'">{{ pcal_trans('demo_copy') }}</button>
            </div>
        </div>

        <div class="glass-card" style="max-width:700px; margin:0 auto; text-align:right;">
            <div class="code-block">
<span class="code-comment">// بیا مستقیم Blade کې وکاروه</span>
<span class="code-fn">&lt;x-pashto-calendar /&gt;</span>

<span class="code-comment">// یا Controller کې</span>
<span class="code-var">$today</span> = <span class="code-fn">pashto_date</span>();
<span class="code-var">$shamsi</span> = <span class="code-fn">to_shamsi</span>(<span class="code-fn">now</span>());

<span class="code-comment">// یا Carbon سره</span>
<span class="code-fn">now</span>()-&gt;<span class="code-fn">toPashtoString</span>();

<span class="code-comment">// یا Validation کې</span>
<span class="code-var">$request</span>-&gt;<span class="code-fn">validate</span>([
    <span class="code-str">'date'</span> =&gt; <span class="code-str">'required|pashto_date'</span>
]);
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="footer">
        <div class="footer-logo">PASHTO CALENDAR</div>
        <div class="footer-sub">
            {{ str_replace([':laravel', ':php'], [app()->version(), PHP_VERSION], pcal_trans('demo_footer_built')) }}
        </div>
        <div style="display:flex; justify-content:center; gap:16px; flex-wrap:wrap;">
            <a href="/pashto-calendar" class="btn-primary">{{ pcal_trans('demo_footer_calendar') }}</a>
            <a href="/pashto-calendar/demo" class="btn-ghost">{{ pcal_trans('demo_footer_demo') }}</a>
        </div>
        <div style="margin-top:40px; font-size:12px; color:rgba(148,163,184,0.3); letter-spacing:3px; font-family: 'Inter', sans-serif;">
            ✦ &nbsp; MIT LICENSE &nbsp; ✦
        </div>
    </footer>

</div>{{-- end .content --}}

<script>
// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });
});

// Scroll reveal
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.classList.add('visible');
    });
}, { threshold: 0.1 });

document.querySelectorAll(
    '.glass-card, .stat-card, .month-card, .holiday-card, .section-label, .section-title, .manip-item'
).forEach(el => observer.observe(el));

// Parallax effect on hero
window.addEventListener('scroll', () => {
    const hero = document.querySelector('.hero');
    if (hero) hero.style.transform = `translateY(${window.scrollY * 0.3}px)`;
});
</script>

</body>
</html>
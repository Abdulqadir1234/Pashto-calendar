<!DOCTYPE html>
<html lang="ps" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>د پښتو کلیندر — Pashto Calendar</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;600;700&family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --night:    #04080f;
            --deep:     #080d1a;
            --navy:     #0d1b35;
            --gold:     #f0a500;
            --gold2:    #ffd166;
            --teal:     #06d6a0;
            --rose:     #ef476f;
            --glass:    rgba(255,255,255,0.04);
            --glass-b:  rgba(255,255,255,0.08);
            --border:   rgba(240,165,0,0.2);
            --text:     #e8e0d0;
            --muted:    rgba(232,224,208,0.5);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--night);
            color: var(--text);
            font-family: 'Noto Naskh Arabic', serif;
            overflow-x: hidden;
            min-height: 100vh;
        }
        #stars-canvas {
            position: fixed; top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0; pointer-events: none;
        }
        .geo-bg {
            position: fixed; top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0; pointer-events: none; opacity: 0.03;
            background-image:
                repeating-linear-gradient(45deg, var(--gold) 0px, var(--gold) 1px, transparent 1px, transparent 30px),
                repeating-linear-gradient(-45deg, var(--gold) 0px, var(--gold) 1px, transparent 1px, transparent 30px);
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
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(240,165,0,0.12) 0%, transparent 70%);
            top: 50%; left: 50%; transform: translate(-50%, -50%);
            animation: pulse-glow 4s ease-in-out infinite;
        }
        .hero::after {
            content: ''; position: absolute;
            width: 800px; height: 800px;
            border: 1px solid rgba(240,165,0,0.06); border-radius: 50%;
            top: 50%; left: 50%; transform: translate(-50%, -50%);
            animation: spin-slow 40s linear infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.7; }
        }
        @keyframes spin-slow {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to   { transform: translate(-50%, -50%) rotate(360deg); }
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(240,165,0,0.1); border: 1px solid rgba(240,165,0,0.3);
            border-radius: 100px; padding: 8px 20px; font-size: 13px;
            color: var(--gold); margin-bottom: 30px;
            animation: fade-down 0.8s ease both; letter-spacing: 1px;
        }
        .hero-title {
            font-family: 'Cinzel', serif; font-size: clamp(20px, 5vw, 42px);
            font-weight: 700; color: #fff; margin-bottom: 10px;
            animation: fade-down 0.8s ease 0.1s both; letter-spacing: 2px;
        }
        .hero-title-ps {
            font-size: clamp(32px, 8vw, 72px); font-weight: 700;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold2) 50%, var(--teal) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; line-height: 1.2; margin-bottom: 20px;
            animation: fade-down 0.8s ease 0.2s both;
        }
        .hero-sub {
            font-size: 18px; color: var(--muted); margin-bottom: 50px;
            animation: fade-down 0.8s ease 0.3s both;
        }
        .live-date {
            background: var(--glass-b); backdrop-filter: blur(20px);
            border: 1px solid var(--border); border-radius: 24px;
            padding: 30px 50px; margin-bottom: 50px;
            animation: fade-up 0.8s ease 0.4s both; position: relative;
        }
        .live-date::before {
            content: ''; position: absolute; inset: -1px; border-radius: 24px;
            background: linear-gradient(135deg, rgba(240,165,0,0.3), transparent, rgba(6,214,160,0.2));
            z-index: -1;
        }
        .live-date-label { font-size: 12px; letter-spacing: 3px; color: var(--gold); margin-bottom: 15px; }
        .live-date-numbers { display: flex; gap: 20px; align-items: center; justify-content: center; }
        .date-unit { text-align: center; }
        .date-unit-num {
            font-size: 52px; font-weight: 700; line-height: 1;
            background: linear-gradient(180deg, #fff 0%, var(--gold2) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .date-unit-label { font-size: 11px; color: var(--muted); margin-top: 6px; letter-spacing: 1px; }
        .date-sep { font-size: 40px; color: var(--gold); opacity: 0.4; margin-bottom: 20px; }
        .hero-btns { display: flex; gap: 16px; flex-wrap: wrap; justify-content: center; animation: fade-up 0.8s ease 0.5s both; }
        .btn-primary {
            background: linear-gradient(135deg, var(--gold), #c87800); color: #000;
            font-weight: 700; padding: 14px 32px; border-radius: 12px;
            text-decoration: none; font-size: 15px; transition: all 0.3s ease;
            border: none; cursor: pointer; font-family: 'Noto Naskh Arabic', serif;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(240,165,0,0.4); }
        .btn-ghost {
            background: var(--glass); color: var(--text); font-weight: 600;
            padding: 14px 32px; border-radius: 12px; text-decoration: none;
            font-size: 15px; border: 1px solid var(--border); backdrop-filter: blur(10px);
            transition: all 0.3s ease; font-family: 'Noto Naskh Arabic', serif;
        }
        .btn-ghost:hover { background: var(--glass-b); transform: translateY(-2px); border-color: rgba(240,165,0,0.4); }
        .scroll-indicator {
            position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%);
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            color: var(--muted); font-size: 12px; animation: fade-in 1s ease 1s both;
        }
        .scroll-line {
            width: 1px; height: 40px;
            background: linear-gradient(to bottom, var(--gold), transparent);
            animation: scroll-pulse 2s ease-in-out infinite;
        }
        @keyframes scroll-pulse {
            0%, 100% { transform: scaleY(1); opacity: 1; }
            50% { transform: scaleY(0.5); opacity: 0.4; }
        }
        .section { padding: 100px 20px; max-width: 1100px; margin: 0 auto; }
        .section-label {
            font-size: 11px; letter-spacing: 4px; color: var(--gold);
            text-transform: uppercase; margin-bottom: 16px;
            opacity: 0; transform: translateY(20px); transition: all 0.6s ease;
        }
        .section-title {
            font-size: clamp(28px, 4vw, 42px); font-weight: 700; color: #fff;
            margin-bottom: 50px; opacity: 0; transform: translateY(20px); transition: all 0.6s ease 0.1s;
        }
        .section-label.visible, .section-title.visible { opacity: 1; transform: translateY(0); }
        .glass-card {
            background: var(--glass); backdrop-filter: blur(20px);
            border: 1px solid var(--border); border-radius: 20px; padding: 28px;
            transition: all 0.3s ease; opacity: 0; transform: translateY(30px);
        }
        .glass-card.visible { opacity: 1; transform: translateY(0); }
        .glass-card:hover {
            background: var(--glass-b); border-color: rgba(240,165,0,0.35);
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.4), 0 0 30px rgba(240,165,0,0.08);
        }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 80px; }
        .stat-card {
            background: var(--glass); border: 1px solid var(--border); border-radius: 20px;
            padding: 30px; text-align: center; position: relative; overflow: hidden;
            opacity: 0; transform: translateY(30px); transition: all 0.5s ease;
        }
        .stat-card.visible { opacity: 1; transform: translateY(0); }
        .stat-card::before {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle at center, rgba(240,165,0,0.05) 0%, transparent 60%);
        }
        .stat-num {
            font-size: 48px; font-weight: 700;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; line-height: 1; margin-bottom: 10px;
        }
        .stat-label { font-size: 13px; color: var(--muted); letter-spacing: 1px; }
        .months-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
        @media (max-width: 768px) { .months-grid { grid-template-columns: repeat(2, 1fr); } }
        .month-card {
            background: var(--glass); border: 1px solid var(--border); border-radius: 16px;
            padding: 20px 16px; text-align: center; transition: all 0.3s ease;
            cursor: default; opacity: 0; transform: scale(0.9);
        }
        .month-card.visible { opacity: 1; transform: scale(1); }
        .month-card:hover { background: rgba(240,165,0,0.08); border-color: rgba(240,165,0,0.4); transform: scale(1.04); }
        .month-card.current {
            background: linear-gradient(135deg, rgba(240,165,0,0.15), rgba(255,209,102,0.08));
            border-color: rgba(240,165,0,0.5);
        }
        .month-num { font-size: 11px; color: var(--gold); letter-spacing: 2px; margin-bottom: 8px; }
        .month-ps { font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 6px; }
        .month-dari { font-size: 13px; color: var(--muted); margin-bottom: 4px; }
        .month-en { font-size: 11px; color: rgba(232,224,208,0.3); font-family: 'Cinzel', serif; direction: ltr; }
        .holidays-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; }
        .holiday-card {
            display: flex; align-items: flex-start; gap: 16px;
            background: var(--glass); border: 1px solid rgba(239,71,111,0.2);
            border-radius: 16px; padding: 20px; transition: all 0.3s ease;
            opacity: 0; transform: translateX(20px);
        }
        .holiday-card.visible { opacity: 1; transform: translateX(0); }
        .holiday-card:hover { background: rgba(239,71,111,0.06); border-color: rgba(239,71,111,0.4); transform: translateY(-2px); }
        .holiday-date {
            background: rgba(239,71,111,0.15); border: 1px solid rgba(239,71,111,0.3);
            border-radius: 10px; padding: 8px 14px; font-size: 13px; color: var(--rose);
            font-weight: 700; white-space: nowrap; direction: ltr; font-family: 'Cinzel', serif;
        }
        .holiday-name-ps { font-size: 15px; font-weight: 600; color: #fff; margin-bottom: 4px; }
        .holiday-name-en { font-size: 12px; color: var(--muted); direction: ltr; }
        .code-block {
            background: rgba(4,8,15,0.8); border: 1px solid rgba(6,214,160,0.2);
            border-radius: 16px; padding: 28px; direction: ltr;
            font-family: 'Courier New', monospace; font-size: 14px;
            line-height: 1.8; overflow-x: auto; position: relative;
        }
        .code-block::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(to right, transparent, var(--teal), transparent);
        }
        .code-comment { color: #556; }
        .code-fn      { color: var(--teal); }
        .code-str     { color: var(--gold2); }
        .code-kw      { color: #c792ea; }
        .code-var     { color: #82aaff; }
        .code-output  { color: var(--gold); }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        .feature-icon { font-size: 36px; margin-bottom: 16px; }
        .feature-title { font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 10px; }
        .feature-desc { font-size: 14px; color: var(--muted); line-height: 1.7; }
        .manip-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 12px; }
        .manip-item {
            display: flex; justify-content: space-between; align-items: center;
            background: var(--glass); border: 1px solid var(--border);
            border-radius: 12px; padding: 16px 20px; transition: all 0.3s ease;
        }
        .manip-item:hover { background: var(--glass-b); border-color: rgba(240,165,0,0.4); }
        .manip-code { font-family: 'Courier New', monospace; font-size: 12px; color: var(--teal); direction: ltr; }
        .manip-value { font-size: 16px; font-weight: 700; color: var(--gold2); }
        .gold-divider { width: 100%; height: 1px; background: linear-gradient(to right, transparent, var(--gold), transparent); opacity: 0.2; margin: 0 auto; }
        .footer { padding: 60px 20px; text-align: center; position: relative; }
        .footer::before {
            content: ''; position: absolute; top: 0; left: 10%; right: 10%; height: 1px;
            background: linear-gradient(to right, transparent, var(--gold), transparent); opacity: 0.2;
        }
        .footer-logo {
            font-size: 32px; font-family: 'Cinzel', serif; font-weight: 700;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; margin-bottom: 12px;
        }
        .footer-sub { font-size: 14px; color: var(--muted); margin-bottom: 30px; }
        @keyframes fade-down { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fade-up   { from { opacity: 0; transform: translateY(20px);  } to { opacity: 1; transform: translateY(0); } }
        @keyframes fade-in   { from { opacity: 0; } to { opacity: 1; } }
        .navbar {
            position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
            z-index: 100; background: rgba(8,13,26,0.8); backdrop-filter: blur(20px);
            border: 1px solid var(--border); border-radius: 100px; padding: 12px 30px;
            display: flex; align-items: center; gap: 30px; animation: fade-down 0.6s ease both;
        }
        .nav-logo { font-family: 'Cinzel', serif; font-size: 13px; font-weight: 700; color: var(--gold); letter-spacing: 1px; text-decoration: none; }
        .nav-links { display: flex; gap: 24px; list-style: none; }
        .nav-links a { color: var(--muted); text-decoration: none; font-size: 13px; transition: color 0.2s; }
        .nav-links a:hover { color: var(--gold); }
        @media (max-width: 600px) { .nav-links { display: none; } .months-grid { grid-template-columns: repeat(2, 1fr); } }
        .install-box {
            background: rgba(4,8,15,0.9); border: 1px solid rgba(6,214,160,0.3);
            border-radius: 16px; padding: 20px 30px; direction: ltr;
            font-family: 'Courier New', monospace; font-size: 15px; color: var(--teal);
            display: inline-flex; align-items: center; gap: 12px; margin-bottom: 20px;
            position: relative; overflow: hidden;
        }
        .install-box::after {
            content: ''; position: absolute; inset: -1px; border-radius: 16px;
            background: linear-gradient(135deg, rgba(6,214,160,0.2), transparent); z-index: -1;
        }
        .copy-btn {
            background: rgba(6,214,160,0.1); border: 1px solid rgba(6,214,160,0.3);
            border-radius: 8px; color: var(--teal); padding: 4px 12px; font-size: 12px;
            cursor: pointer; transition: all 0.2s; font-family: 'Noto Naskh Arabic', serif;
        }
        .copy-btn:hover { background: rgba(6,214,160,0.2); }
    </style>
</head>
<body>

<canvas id="stars-canvas"></canvas>
<div class="geo-bg"></div>

<nav class="navbar">
    <a href="#" class="nav-logo">PASHTO CAL</a>
    <ul class="nav-links">
        <li><a href="#features">ځانګړتیاوې</a></li>
        <li><a href="#months">میاشتې</a></li>
        <li><a href="#holidays">رخصتۍ</a></li>
        <li><a href="#install">نصبول</a></li>
        <li><a href="/pashto-calendar" style="color: var(--gold)">کلیندر ➜</a></li>
    </ul>
</nav>

<div class="content">

    {{-- HERO --}}
    <section class="hero">
        <div class="hero-badge">✦ &nbsp; qadir/pashto-calendar &nbsp; ✦</div>
        <div class="hero-title">Afghan Solar Hijri Calendar</div>
        <div class="hero-title-ps">د پښتو کلیندر</div>
        <div class="hero-sub">د لاراویل لپاره بشپړ پښتو کلیندر پاکیج — RTL، پښتو شمیرې، رسمي رخصتۍ</div>

        <div class="live-date">
            <div class="live-date-label">✦ اوسنۍ نیټه ✦</div>
            <div class="live-date-numbers">
                <div class="date-unit">
                    <div class="date-unit-num">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($now->day) }}</div>
                    <div class="date-unit-label">ورځ</div>
                </div>
                <div class="date-sep">·</div>
                <div class="date-unit">
                    <div class="date-unit-num" style="font-size: 36px;">{{ $now->monthName() }}</div>
                    <div class="date-unit-label">میاشت</div>
                </div>
                <div class="date-sep">·</div>
                <div class="date-unit">
                    <div class="date-unit-num">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($now->year) }}</div>
                    <div class="date-unit-label">کال</div>
                </div>
            </div>
        </div>

        <div class="hero-btns">
            <a href="/pashto-calendar" class="btn-primary">📅 کلیندر خلاص کړه</a>
            <a href="#install" class="btn-ghost">نصبول ←</a>
            <a href="#features" class="btn-ghost">ځانګړتیاوې ←</a>
        </div>

        <div class="scroll-indicator">
            <span style="font-size:11px; letter-spacing:2px; opacity:0.5">SCROLL</span>
            <div class="scroll-line"></div>
        </div>
    </section>

    <div class="gold-divider"></div>

    {{-- STATS --}}
    <div class="section" style="padding-top: 80px; padding-bottom: 40px;">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-num">۱۲</div>
                <div class="stat-label">میاشتې / Months</div>
            </div>
            <div class="stat-card" style="transition-delay: 0.1s">
                <div class="stat-num">۳</div>
                <div class="stat-label">ژبې / Languages</div>
            </div>
            <div class="stat-card" style="transition-delay: 0.2s">
                <div class="stat-num">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber(count(\Qadir\PashtoCalendar\Support\Holidays::all())) }}</div>
                <div class="stat-label">رخصتۍ / Holidays</div>
            </div>
            <div class="stat-card" style="transition-delay: 0.3s">
                <div class="stat-num">∞</div>
                <div class="stat-label">پیښې / Events</div>
            </div>
        </div>
    </div>

    {{-- FEATURES --}}
    {{-- ✅ ALL @ symbols replaced with &#64; to prevent Blade from parsing them --}}
    <section class="section" id="features">
        <div class="section-label">✦ ځانګړتیاوې</div>
        <div class="section-title">هر هغه شی چې ته ورته اړتیا لرې</div>

        <div class="features-grid">
            <div class="glass-card">
                <div class="feature-icon">🔄</div>
                <div class="feature-title">نیټه بدلول</div>
                <div class="feature-desc">د میلادي نیټې د شمسي هجري کلیندر سره بشپړ تبدیلي — د کاربن سره بشپړ مدغم</div>
            </div>
            <div class="glass-card" style="transition-delay:0.1s">
                <div class="feature-icon">🌐</div>
                <div class="feature-title">درې ژبې</div>
                <div class="feature-desc">پښتو، دري، او انګلیسي — د یوې config بدلون سره ټوله اپ بدلیږي</div>
            </div>
            <div class="glass-card" style="transition-delay:0.2s">
                <div class="feature-icon">📅</div>
                <div class="feature-title">پیښې (Events)</div>
                <div class="feature-desc">د ډیټابیس پر بنسټ پیښې — د Alpine.js سره د سمدستي UI اپدیت</div>
            </div>
            <div class="glass-card" style="transition-delay:0.3s">
                <div class="feature-icon">✅</div>
                <div class="feature-title">د Validation قوانین</div>
                {{-- ✅ No @ symbols here — plain text only --}}
                <div class="feature-desc">pashto_date، pashto_date_format، before_pashto_date، after_pashto_date</div>
            </div>
            <div class="glass-card" style="transition-delay:0.4s">
                <div class="feature-icon">🎨</div>
                <div class="feature-title">Blade Directives</div>
                {{-- ✅ @ replaced with &#64; --}}
                <div class="feature-desc">&#64;pashtoDate، &#64;pashtoNow، &#64;pashtoNumber، &#64;ifHoliday — مستقیم view کې وکاروه</div>
            </div>
            <div class="glass-card" style="transition-delay:0.5s">
                <div class="feature-icon">⚡</div>
                <div class="feature-title">Carbon Macros</div>
                <div class="feature-desc">now()-&gt;toPashto()، now()-&gt;toPashtoString()، Carbon::parsePashto(1403,1,1)</div>
            </div>
        </div>
    </section>

    <div class="gold-divider"></div>

    {{-- MONTHS --}}
    <section class="section" id="months">
        <div class="section-label">✦ میاشتې</div>
        <div class="section-title">د کال ۱۲ میاشتې — درې ژبو کې</div>

        <div class="months-grid">
            @for($i = 1; $i <= 12; $i++)
                <div class="month-card {{ $i === $now->month ? 'current' : '' }}"
                     style="transition-delay: {{ ($i-1) * 0.05 }}s">
                    <div class="month-num">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="month-ps">{{ \Qadir\PashtoCalendar\Support\Months::name($i, 'pashto') }}</div>
                    <div class="month-dari">{{ \Qadir\PashtoCalendar\Support\Months::name($i, 'dari') }}</div>
                    <div class="month-en">{{ \Qadir\PashtoCalendar\Support\Months::name($i, 'en') }}</div>
                    @if($i === $now->month)
                        <div style="margin-top:8px; font-size:10px; color:var(--gold); letter-spacing:1px;">← اوس</div>
                    @endif
                </div>
            @endfor
        </div>
    </section>

    <div class="gold-divider"></div>

    {{-- DATE MANIPULATION --}}
    <section class="section">
        <div class="section-label">✦ نیټه اداره کول</div>
        <div class="section-title">د نیټې مینیپولیشن — Carbon-Style</div>

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
                <div class="manip-value">{{ $t->isLeapYear() ? 'هو ✓' : 'نه ✗' }}</div>
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

    <div class="gold-divider"></div>

    {{-- HOLIDAYS --}}
    <section class="section" id="holidays">
        <div class="section-label">✦ رسمي رخصتۍ</div>
        <div class="section-title">د افغانستان ملي رخصتۍ</div>

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

    <div class="gold-divider"></div>

    {{-- BLADE DIRECTIVES --}}
    {{-- ✅ ALL @ symbols use &#64; — Blade will never parse them --}}
    <section class="section">
        <div class="section-label">✦ Blade Directives</div>
        <div class="section-title">مستقیم د View کې وکاروه</div>

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

    <div class="gold-divider"></div>

    {{-- INSTALL --}}
    <section class="section" id="install" style="text-align:center;">
        <div class="section-label" style="text-align:center">✦ نصبول</div>
        <div class="section-title" style="text-align:center">د یوې کمانډ سره نصب کړه</div>

        <div style="display:flex; flex-direction:column; align-items:center; gap:16px; margin-bottom:50px;">
            <div class="install-box">
                <span>composer require qadir/pashto-calendar</span>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('composer require qadir/pashto-calendar'); this.textContent='✓ کاپي شو'">کاپي</button>
            </div>
            <div class="install-box" style="border-color:rgba(240,165,0,0.3); color:var(--gold)">
                <span>php artisan vendor:publish --tag=pashto-calendar-config</span>
                <button class="copy-btn" style="border-color:rgba(240,165,0,0.3); color:var(--gold); background:rgba(240,165,0,0.1)" onclick="navigator.clipboard.writeText('php artisan vendor:publish --tag=pashto-calendar-config'); this.textContent='✓ کاپي شو'">کاپي</button>
            </div>
            <div class="install-box" style="border-color:rgba(239,71,111,0.3); color:var(--rose)">
                <span>php artisan migrate</span>
                <button class="copy-btn" style="border-color:rgba(239,71,111,0.3); color:var(--rose); background:rgba(239,71,111,0.1)" onclick="navigator.clipboard.writeText('php artisan migrate'); this.textContent='✓ کاپي شو'">کاپي</button>
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
            Built by Abdul Qadir Nasrat — Laravel {{ app()->version() }} — PHP {{ PHP_VERSION }}
        </div>
        <div style="display:flex; justify-content:center; gap:16px; flex-wrap:wrap;">
            <a href="/pashto-calendar" class="btn-primary">📅 د کلیندر لیدل</a>
            <a href="/pashto-calendar/demo" class="btn-ghost">🔄 Demo بیا لوده کړه</a>
        </div>
        <div style="margin-top:40px; font-size:12px; color:rgba(232,224,208,0.2); letter-spacing:3px;">
            ✦ &nbsp; MIT LICENSE &nbsp; ✦
        </div>
    </footer>

</div>{{-- end .content --}}

<script>
const canvas = document.getElementById('stars-canvas');
const ctx    = canvas.getContext('2d');
let stars    = [];

function resize() {
    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight;
    generateStars();
}

function generateStars() {
    stars = [];
    for (let i = 0; i < 200; i++) {
        stars.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            r: Math.random() * 1.2 + 0.2,
            o: Math.random() * 0.8 + 0.1,
            speed: Math.random() * 0.3 + 0.05,
            phase: Math.random() * Math.PI * 2,
        });
    }
}

function drawStars(time) {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    stars.forEach(s => {
        const opacity = s.o * (0.6 + 0.4 * Math.sin(time * s.speed + s.phase));
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(240, 200, 130, ${opacity})`;
        ctx.fill();
    });
    if (Math.random() < 0.001) drawShootingStar();
}

function drawShootingStar() {
    const x1  = Math.random() * canvas.width;
    const y1  = Math.random() * canvas.height * 0.5;
    const len = 80 + Math.random() * 120;
    const angle = Math.PI / 6;
    const grad = ctx.createLinearGradient(x1, y1, x1 + len * Math.cos(angle), y1 + len * Math.sin(angle));
    grad.addColorStop(0,   'rgba(240,200,130,0)');
    grad.addColorStop(0.3, 'rgba(240,200,130,0.8)');
    grad.addColorStop(1,   'rgba(240,200,130,0)');
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(x1 + len * Math.cos(angle), y1 + len * Math.sin(angle));
    ctx.strokeStyle = grad;
    ctx.lineWidth = 1;
    ctx.stroke();
}

function animate(time) {
    drawStars(time / 1000);
    requestAnimationFrame(animate);
}

window.addEventListener('resize', resize);
resize();
requestAnimationFrame(animate);

// Scroll reveal
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.classList.add('visible');
    });
}, { threshold: 0.1 });

document.querySelectorAll(
    '.glass-card, .stat-card, .month-card, .holiday-card, .section-label, .section-title, .manip-item'
).forEach(el => observer.observe(el));

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });
});

// Parallax
window.addEventListener('scroll', () => {
    const hero = document.querySelector('.hero');
    if (hero) hero.style.transform = `translateY(${window.scrollY * 0.3}px)`;
});
</script>

</body>
</html>
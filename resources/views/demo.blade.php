<!DOCTYPE html>
<html lang="ps" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ pcal_trans('demo_page_title') ?: 'د پښتو کلیندر — Pashto Calendar' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Noto+Naskh+Arabic:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ═══════════════════════════════════════════════════════
           DESIGN TOKENS
        ═══════════════════════════════════════════════════════ */
        :root {
            /* Core palette */
            --bg-base:        #04060f;
            --bg-surface:     #080d1a;
            --bg-elevated:    #0d1425;
            --bg-card:        #0f1830;
            --bg-card-hover:  #131e38;

            /* Borders */
            --border-subtle:  rgba(148, 163, 184, 0.06);
            --border-default: rgba(148, 163, 184, 0.10);
            --border-strong:  rgba(148, 163, 184, 0.18);
            --border-accent:  rgba(59, 130, 246, 0.25);

            /* Accent colors */
            --blue:           #3b82f6;
            --blue-light:     #60a5fa;
            --blue-dim:       rgba(59, 130, 246, 0.12);
            --cyan:           #06b6d4;
            --cyan-dim:       rgba(6, 182, 212, 0.10);
            --emerald:        #10b981;
            --emerald-dim:    rgba(16, 185, 129, 0.10);
            --violet:         #8b5cf6;
            --violet-dim:     rgba(139, 92, 246, 0.10);
            --gold:           #f59e0b;
            --gold-dim:       rgba(245, 158, 11, 0.10);
            --rose:           #f43f5e;
            --rose-dim:       rgba(244, 63, 94, 0.10);

            /* Text */
            --text-primary:   #f1f5f9;
            --text-secondary: #94a3b8;
            --text-tertiary:  #475569;
            --text-muted:     #334155;

            /* Effects */
            --blur-sm:        blur(8px);
            --blur-md:        blur(16px);
            --blur-lg:        blur(32px);

            /* Radius */
            --r-xs:  6px;
            --r-sm:  10px;
            --r-md:  16px;
            --r-lg:  20px;
            --r-xl:  28px;
            --r-2xl: 36px;
            --r-full: 9999px;

            /* Transitions */
            --ease-out:    cubic-bezier(0.16, 1, 0.3, 1);
            --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
            --t-fast:  0.15s;
            --t-base:  0.25s;
            --t-slow:  0.4s;

            /* Shadows */
            --shadow-xs:  0 1px 2px rgba(0,0,0,0.4);
            --shadow-sm:  0 4px 12px rgba(0,0,0,0.4), 0 1px 3px rgba(0,0,0,0.3);
            --shadow-md:  0 8px 24px rgba(0,0,0,0.5), 0 2px 8px rgba(0,0,0,0.3);
            --shadow-lg:  0 20px 48px rgba(0,0,0,0.6), 0 4px 16px rgba(0,0,0,0.4);
            --shadow-xl:  0 32px 64px rgba(0,0,0,0.7), 0 8px 24px rgba(0,0,0,0.4);
            --glow-blue:  0 0 40px rgba(59, 130, 246, 0.18);
            --glow-cyan:  0 0 40px rgba(6, 182, 212, 0.15);
        }

        /* ═══════════════════════════════════════════════════════
           RESET & BASE
        ═══════════════════════════════════════════════════════ */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; -webkit-font-smoothing: antialiased; }

        body {
            background: var(--bg-base);
            color: var(--text-primary);
            font-family: 'Noto Naskh Arabic', serif;
            overflow-x: hidden;
            min-height: 100vh;
            line-height: 1.6;
        }

        /* ═══════════════════════════════════════════════════════
           BACKGROUND AURORA
        ═══════════════════════════════════════════════════════ */
        .bg-gradient-animated {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            overflow: hidden;
        }
        .bg-gradient-animated::before {
            content: '';
            position: absolute;
            width: 120vw; height: 120vh;
            top: -20vh; left: -10vw;
            background:
                radial-gradient(ellipse 60% 40% at 70% 20%, rgba(59, 130, 246, 0.09) 0%, transparent 60%),
                radial-gradient(ellipse 50% 50% at 20% 80%, rgba(6, 182, 212, 0.06) 0%, transparent 60%),
                radial-gradient(ellipse 40% 60% at 50% 50%, rgba(139, 92, 246, 0.04) 0%, transparent 70%),
                radial-gradient(ellipse 70% 30% at 80% 70%, rgba(16, 185, 129, 0.04) 0%, transparent 60%);
            animation: aurora 16s ease-in-out infinite alternate;
        }
        .bg-gradient-animated::after {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            opacity: 0.4;
        }
        @keyframes aurora {
            0%   { transform: translate(0,0) scale(1); }
            33%  { transform: translate(2%, 1%) scale(1.02); }
            66%  { transform: translate(-1%, 2%) scale(0.99); }
            100% { transform: translate(1%, -1%) scale(1.01); }
        }

        .content { position: relative; z-index: 1; }

        /* ═══════════════════════════════════════════════════════
           NAVBAR
        ═══════════════════════════════════════════════════════ */
        .navbar {
            position: fixed; top: 16px; left: 50%; transform: translateX(-50%);
            z-index: 200;
            background: rgba(8, 13, 26, 0.85);
            backdrop-filter: var(--blur-lg);
            -webkit-backdrop-filter: var(--blur-lg);
            border: 1px solid var(--border-strong);
            border-radius: var(--r-full);
            padding: 10px 20px;
            display: flex; align-items: center; gap: 0;
            animation: slideDown 0.7s var(--ease-out) both;
            box-shadow: var(--shadow-md), 0 0 0 1px rgba(255,255,255,0.03);
            max-width: calc(100vw - 32px);
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateX(-50%) translateY(-12px); }
            to   { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
        .nav-logo {
            font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 800;
            color: var(--text-primary); letter-spacing: 1.5px; text-decoration: none;
            padding: 4px 16px 4px 4px;
            border-right: 1px solid var(--border-default);
            margin-right: 16px;
            background: linear-gradient(135deg, var(--blue-light), var(--cyan));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            white-space: nowrap;
        }
        .nav-links {
            display: flex; gap: 2px; list-style: none; align-items: center;
        }
        .nav-links a {
            color: var(--text-secondary); text-decoration: none;
            font-size: 12px; font-family: 'Inter', sans-serif; font-weight: 500;
            padding: 5px 12px; border-radius: var(--r-full);
            transition: all var(--t-fast) ease;
            white-space: nowrap;
        }
        .nav-links a:hover {
            color: var(--text-primary);
            background: rgba(255,255,255,0.06);
        }
        .nav-links a.active-link {
            color: var(--blue-light);
            background: var(--blue-dim);
        }
        @media (max-width: 800px) {
            .nav-links .hide-sm { display: none; }
        }
        @media (max-width: 560px) {
            .nav-links { display: none; }
            .nav-logo { border: none; margin: 0; padding: 4px 8px; }
        }

        /* ═══════════════════════════════════════════════════════
           HERO
        ═══════════════════════════════════════════════════════ */
        .hero {
            min-height: 100vh;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center; padding: 120px 24px 80px;
            position: relative; overflow: hidden;
        }

        /* Decorative grid lines */
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(148,163,184,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148,163,184,0.025) 1px, transparent 1px);
            background-size: 64px 64px;
            mask-image: radial-gradient(ellipse 80% 60% at 50% 50%, black 0%, transparent 70%);
        }
        /* Central glow orb */
        .hero::after {
            content: '';
            position: absolute; width: 900px; height: 500px;
            top: 50%; left: 50%; transform: translate(-50%, -60%);
            background: radial-gradient(ellipse, rgba(59,130,246,0.08) 0%, rgba(6,182,212,0.04) 40%, transparent 70%);
            animation: orbPulse 6s ease-in-out infinite;
            pointer-events: none;
        }
        @keyframes orbPulse {
            0%, 100% { opacity: 1; transform: translate(-50%, -60%) scale(1); }
            50%       { opacity: 0.6; transform: translate(-50%, -58%) scale(1.05); }
        }

        .hero-inner { position: relative; z-index: 2; max-width: 860px; }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(59, 130, 246, 0.08);
            border: 1px solid rgba(59, 130, 246, 0.22);
            border-radius: var(--r-full); padding: 7px 20px;
            font-size: 12px; font-family: 'JetBrains Mono', monospace;
            color: var(--blue-light); margin-bottom: 32px;
            animation: fadeDown 0.9s var(--ease-out) 0.1s both;
            backdrop-filter: var(--blur-sm);
            letter-spacing: 0.5px;
        }
        .hero-badge::before {
            content: '';
            display: inline-block; width: 6px; height: 6px;
            background: var(--cyan); border-radius: 50%;
            box-shadow: 0 0 6px var(--cyan);
            animation: blink 2s ease infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; } 50% { opacity: 0.3; }
        }

        .hero-title {
            font-family: 'Inter', sans-serif;
            font-size: clamp(16px, 3.5vw, 32px);
            font-weight: 500; color: var(--text-secondary);
            margin-bottom: 12px; letter-spacing: -0.3px;
            animation: fadeDown 0.9s var(--ease-out) 0.2s both;
        }
        .hero-title-ps {
            font-size: clamp(40px, 9vw, 88px); font-weight: 700;
            line-height: 1.1; margin-bottom: 24px;
            animation: fadeDown 0.9s var(--ease-out) 0.3s both;
            background: linear-gradient(135deg,
                #fff 0%,
                var(--blue-light) 30%,
                var(--cyan) 60%,
                var(--violet) 100%
            );
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 40px rgba(59,130,246,0.3));
        }
        .hero-sub {
            font-size: clamp(15px, 2.5vw, 18px);
            color: var(--text-secondary); margin-bottom: 52px;
            animation: fadeDown 0.9s var(--ease-out) 0.4s both;
            max-width: 580px; margin-left: auto; margin-right: auto;
        }

        /* Live date card */
        .live-date {
            position: relative;
            background: rgba(13, 20, 37, 0.8);
            backdrop-filter: var(--blur-md);
            border: 1px solid var(--border-strong);
            border-radius: var(--r-xl);
            padding: 32px 56px; margin-bottom: 52px;
            animation: fadeUp 0.9s var(--ease-out) 0.5s both;
            box-shadow: var(--shadow-lg), var(--glow-blue);
            overflow: hidden;
        }
        /* Gradient border shimmer */
        .live-date::before {
            content: '';
            position: absolute; inset: 0; border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg,
                rgba(59,130,246,0.5) 0%,
                rgba(6,182,212,0.3) 40%,
                rgba(139,92,246,0.4) 100%
            );
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude;
            pointer-events: none;
        }
        /* Inner glow */
        .live-date::after {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.15), transparent);
        }

        .live-date-label {
            font-size: 10px; letter-spacing: 4px; text-transform: uppercase;
            color: var(--blue-light); margin-bottom: 20px;
            font-family: 'Inter', sans-serif; font-weight: 600;
            opacity: 0.8;
        }
        .live-date-numbers {
            display: flex; gap: 24px; align-items: center; justify-content: center;
        }
        .date-unit { text-align: center; }
        .date-unit-num {
            font-size: clamp(36px, 6vw, 60px); font-weight: 700; line-height: 1;
            color: #fff;
            text-shadow: 0 0 30px rgba(59,130,246,0.4);
        }
        .date-unit-label {
            font-size: 10px; color: var(--text-tertiary); margin-top: 8px;
            letter-spacing: 2px; text-transform: uppercase;
            font-family: 'Inter', sans-serif; font-weight: 500;
        }
        .date-sep {
            font-size: 32px; color: var(--border-strong);
            user-select: none; margin-bottom: 16px;
            font-family: 'Inter', sans-serif; font-weight: 300;
        }

        @media (max-width: 500px) {
            .live-date { padding: 24px 20px; }
            .live-date-numbers { gap: 12px; }
            .date-sep { font-size: 20px; }
        }

        /* Buttons */
        .hero-btns {
            display: flex; gap: 12px; flex-wrap: wrap; justify-content: center;
            animation: fadeUp 0.9s var(--ease-out) 0.6s both;
        }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, var(--blue) 0%, #1d4ed8 100%);
            color: #fff; font-weight: 600; padding: 13px 28px;
            border-radius: var(--r-md); text-decoration: none; font-size: 14px;
            transition: all var(--t-base) var(--ease-out);
            border: none; cursor: pointer;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 16px rgba(59,130,246,0.3), inset 0 1px 0 rgba(255,255,255,0.1);
            position: relative; overflow: hidden;
        }
        .btn-primary::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.12) 0%, transparent 60%);
            opacity: 0; transition: opacity var(--t-fast);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(59,130,246,0.45), inset 0 1px 0 rgba(255,255,255,0.15);
        }
        .btn-primary:hover::before { opacity: 1; }
        .btn-primary:active { transform: translateY(0); }

        .btn-ghost {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.04);
            color: var(--text-secondary); font-weight: 500;
            padding: 13px 28px; border-radius: var(--r-md); text-decoration: none;
            font-size: 14px; border: 1px solid var(--border-default);
            backdrop-filter: var(--blur-sm);
            transition: all var(--t-base) var(--ease-out);
            font-family: 'Inter', sans-serif;
        }
        .btn-ghost:hover {
            background: rgba(255,255,255,0.07);
            border-color: var(--border-strong);
            color: var(--text-primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        /* Scroll indicator */
        .scroll-indicator {
            position: absolute; bottom: 36px; left: 50%; transform: translateX(-50%);
            display: flex; flex-direction: column; align-items: center; gap: 10px;
            color: var(--text-muted); font-size: 10px;
            font-family: 'Inter', sans-serif; font-weight: 500; letter-spacing: 3px;
            text-transform: uppercase;
            animation: fadeIn 1s ease 1.5s both;
        }
        .scroll-line {
            width: 1px; height: 48px;
            background: linear-gradient(to bottom, var(--blue-light), transparent);
            animation: scrollPulse 2.5s ease-in-out infinite;
        }
        @keyframes scrollPulse {
            0%, 100% { transform: scaleY(1) translateY(0); opacity: 1; }
            50%       { transform: scaleY(0.5) translateY(12px); opacity: 0.3; }
        }

        /* ═══════════════════════════════════════════════════════
           SECTION STRUCTURE
        ═══════════════════════════════════════════════════════ */
        .section {
            padding: 96px 24px;
            max-width: 1120px; margin: 0 auto;
        }
        .section-header { margin-bottom: 56px; }
        .section-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 10px; letter-spacing: 4px; text-transform: uppercase;
            font-family: 'Inter', sans-serif; font-weight: 600;
            color: var(--cyan); margin-bottom: 14px;
        }
        .section-eyebrow::before {
            content: ''; display: block; width: 20px; height: 1px;
            background: var(--cyan); opacity: 0.6;
        }

        /* Keep existing class names but restyle */
        .section-label {
            font-size: 10px; letter-spacing: 4px; color: var(--cyan);
            text-transform: uppercase; margin-bottom: 14px;
            font-family: 'Inter', sans-serif; font-weight: 600;
            opacity: 0; transform: translateY(16px);
            transition: opacity 0.6s var(--ease-out), transform 0.6s var(--ease-out);
            display: flex; align-items: center; gap: 8px;
        }
        .section-label::before {
            content: ''; display: block; width: 20px; height: 1px;
            background: var(--cyan); opacity: 0.6; flex-shrink: 0;
        }
        .section-title {
            font-size: clamp(26px, 4vw, 44px); font-weight: 700;
            color: var(--text-primary); margin-bottom: 48px;
            line-height: 1.2; letter-spacing: -0.5px;
            font-family: 'Inter', sans-serif;
            opacity: 0; transform: translateY(16px);
            transition: opacity 0.6s var(--ease-out) 0.1s, transform 0.6s var(--ease-out) 0.1s;
        }
        .section-label.visible { opacity: 1; transform: translateY(0); }
        .section-title.visible  { opacity: 1; transform: translateY(0); }

        /* ═══════════════════════════════════════════════════════
           DIVIDER
        ═══════════════════════════════════════════════════════ */
        .divider {
            width: 100%; height: 1px;
            background: linear-gradient(to right, transparent 0%, var(--border-strong) 20%, var(--border-strong) 80%, transparent 100%);
        }

        /* ═══════════════════════════════════════════════════════
           GLASS CARD
        ═══════════════════════════════════════════════════════ */
        .glass-card {
            background: var(--bg-card);
            border: 1px solid var(--border-default);
            border-radius: var(--r-lg);
            padding: 28px;
            transition: all var(--t-base) var(--ease-out);
            opacity: 0; transform: translateY(24px);
            position: relative; overflow: hidden;
        }
        .glass-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(to right, transparent 10%, rgba(255,255,255,0.06) 50%, transparent 90%);
        }
        .glass-card.visible { opacity: 1; transform: translateY(0); }
        .glass-card:hover {
            background: var(--bg-card-hover);
            border-color: var(--border-accent);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md), 0 0 0 1px rgba(59,130,246,0.08);
        }

        /* ═══════════════════════════════════════════════════════
           STATS
        ═══════════════════════════════════════════════════════ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px; margin-bottom: 72px;
        }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-default);
            border-radius: var(--r-lg); padding: 32px 24px;
            text-align: center; position: relative; overflow: hidden;
            opacity: 0; transform: translateY(24px);
            transition: all var(--t-base) var(--ease-out);
        }
        .stat-card::before {
            content: '';
            position: absolute; inset: 0; opacity: 0;
            background: radial-gradient(ellipse at center top, var(--blue-dim) 0%, transparent 60%);
            transition: opacity var(--t-base);
        }
        .stat-card:hover { border-color: var(--border-accent); transform: translateY(-3px); }
        .stat-card:hover::before { opacity: 1; }
        .stat-card.visible { opacity: 1; transform: translateY(0); }

        .stat-num {
            font-size: 52px; font-weight: 800; line-height: 1; margin-bottom: 10px;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fff 0%, var(--blue-light) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stat-label {
            font-size: 12px; color: var(--text-secondary);
            letter-spacing: 1px; font-family: 'Inter', sans-serif; font-weight: 500;
        }

        /* ═══════════════════════════════════════════════════════
           FEATURES
        ═══════════════════════════════════════════════════════ */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .feature-icon {
            font-size: 28px; margin-bottom: 18px;
            display: flex; align-items: center; justify-content: flex-end;
        }
        .feature-icon-wrap {
            width: 48px; height: 48px; border-radius: var(--r-md);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px; margin-right: auto;
            font-size: 22px;
        }
        .feature-title {
            font-size: 17px; font-weight: 700; color: var(--text-primary);
            margin-bottom: 10px; font-family: 'Inter', sans-serif;
        }
        .feature-desc {
            font-size: 14px; color: var(--text-secondary); line-height: 1.8;
        }

        /* ═══════════════════════════════════════════════════════
           MONTHS GRID
        ═══════════════════════════════════════════════════════ */
        .months-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }
        @media (max-width: 768px) { .months-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 400px) { .months-grid { grid-template-columns: repeat(2, 1fr); } }

        .month-card {
            background: var(--bg-card);
            border: 1px solid var(--border-default);
            border-radius: var(--r-lg); padding: 22px 16px;
            text-align: center; transition: all var(--t-base) var(--ease-out);
            cursor: default; opacity: 0; transform: scale(0.94) translateY(10px);
            position: relative; overflow: hidden;
        }
        .month-card.visible { opacity: 1; transform: scale(1) translateY(0); }
        .month-card:hover {
            background: var(--bg-card-hover);
            border-color: rgba(59,130,246,0.3);
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        .month-card.current {
            background: linear-gradient(145deg,
                rgba(59,130,246,0.12) 0%,
                rgba(6,182,212,0.06) 100%
            );
            border-color: rgba(59,130,246,0.35);
            box-shadow: 0 0 28px rgba(59,130,246,0.1);
        }
        .month-card.current::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(to right, var(--blue), var(--cyan));
        }

        .month-num {
            font-size: 10px; font-family: 'JetBrains Mono', monospace; font-weight: 600;
            color: var(--cyan); letter-spacing: 2px; margin-bottom: 10px;
            opacity: 0.7;
        }
        .month-ps {
            font-size: 21px; font-weight: 700;
            color: var(--text-primary); margin-bottom: 6px;
        }
        .month-dari { font-size: 13px; color: var(--text-secondary); margin-bottom: 4px; }
        .month-en {
            font-size: 10px; color: var(--text-muted);
            font-family: 'Inter', sans-serif; direction: ltr;
            letter-spacing: 0.5px;
        }

        /* ═══════════════════════════════════════════════════════
           HOLIDAYS
        ═══════════════════════════════════════════════════════ */
        .holidays-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 14px;
        }
        .holiday-card {
            display: flex; align-items: center; gap: 16px;
            background: var(--bg-card);
            border: 1px solid var(--border-default);
            border-radius: var(--r-lg); padding: 18px 20px;
            transition: all var(--t-base) var(--ease-out);
            opacity: 0; transform: translateX(16px);
        }
        .holiday-card.visible { opacity: 1; transform: translateX(0); }
        .holiday-card:hover {
            background: var(--bg-card-hover);
            border-color: rgba(244,63,94,0.25);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }
        .holiday-date {
            background: var(--rose-dim);
            border: 1px solid rgba(244,63,94,0.2);
            border-radius: var(--r-sm); padding: 10px 14px;
            font-size: 13px; color: var(--rose); font-weight: 700;
            white-space: nowrap; direction: ltr;
            font-family: 'JetBrains Mono', monospace;
            min-width: 60px; text-align: center;
            flex-shrink: 0;
        }
        .holiday-name-ps { font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 3px; }
        .holiday-name-en {
            font-size: 12px; color: var(--text-tertiary);
            direction: ltr; font-family: 'Inter', sans-serif;
        }

        /* ═══════════════════════════════════════════════════════
           CODE BLOCKS
        ═══════════════════════════════════════════════════════ */
        .code-block {
            background: #020812;
            border: 1px solid rgba(6,182,212,0.15);
            border-radius: var(--r-md); padding: 28px 32px;
            direction: ltr; font-family: 'JetBrains Mono', monospace;
            font-size: 13.5px; line-height: 2.2; overflow-x: auto;
            position: relative;
        }
        .code-block::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(to right, transparent 0%, rgba(6,182,212,0.4) 50%, transparent 100%);
        }
        /* Traffic light dots */
        .code-block::after {
            content: '';
            position: absolute; top: 14px; right: 16px;
            width: 8px; height: 8px; border-radius: 50%;
            background: rgba(6,182,212,0.3);
            box-shadow: -14px 0 0 rgba(245,158,11,0.3), -28px 0 0 rgba(244,63,94,0.3);
        }
        .code-comment { color: #3a4a6b; font-style: italic; }
        .code-fn      { color: #67e8f9; }
        .code-str     { color: #fcd34d; }
        .code-kw      { color: #c084fc; }
        .code-var     { color: #93c5fd; }
        .code-output  { color: #4ade80; opacity: 0.85; }

        /* ═══════════════════════════════════════════════════════
           DATE MANIPULATION
        ═══════════════════════════════════════════════════════ */
        .manip-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 12px;
        }
        .manip-item {
            display: flex; justify-content: space-between; align-items: center;
            background: var(--bg-card); border: 1px solid var(--border-default);
            border-radius: var(--r-md); padding: 14px 18px;
            transition: all var(--t-fast) var(--ease-out);
            gap: 16px;
        }
        .manip-item:hover {
            background: var(--bg-card-hover);
            border-color: rgba(6,182,212,0.25);
        }
        .manip-code {
            font-family: 'JetBrains Mono', monospace; font-size: 11.5px;
            color: var(--cyan); direction: ltr; opacity: 0.8;
        }
        .manip-value {
            font-size: 15px; font-weight: 700;
            color: var(--gold);
        }

        /* ═══════════════════════════════════════════════════════
           INSTALL SECTION
        ═══════════════════════════════════════════════════════ */
        .install-box {
            background: #020812;
            border: 1px solid rgba(6,182,212,0.2);
            border-radius: var(--r-lg); padding: 18px 24px;
            direction: ltr; font-family: 'JetBrains Mono', monospace;
            font-size: 14px; color: var(--cyan);
            display: inline-flex; align-items: center; gap: 14px;
            margin-bottom: 12px;
            position: relative; overflow: hidden;
            max-width: 100%; width: 100%;
            box-sizing: border-box;
        }
        .install-box::before {
            content: '$';
            color: var(--text-muted); margin-right: 2px; flex-shrink: 0;
        }
        .install-box span { flex: 1; overflow: hidden; text-overflow: ellipsis; }
        .install-box::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(6,182,212,0.03) 0%, transparent 60%);
            pointer-events: none;
        }
        .copy-btn {
            background: rgba(6,182,212,0.08);
            border: 1px solid rgba(6,182,212,0.2);
            border-radius: var(--r-xs); color: var(--cyan);
            padding: 5px 14px; font-size: 11px;
            cursor: pointer; transition: all var(--t-fast);
            font-family: 'Inter', sans-serif; font-weight: 600;
            letter-spacing: 0.5px; white-space: nowrap; flex-shrink: 0;
        }
        .copy-btn:hover {
            background: rgba(6,182,212,0.16);
            border-color: rgba(6,182,212,0.4);
            transform: scale(1.02);
        }
        .copy-btn:active { transform: scale(0.98); }

        /* ═══════════════════════════════════════════════════════
           MINI CALENDAR
        ═══════════════════════════════════════════════════════ */
        .mini-calendar-card {
            max-width: 240px; width: 100%; margin: 0 auto;
            font-family: 'Noto Naskh Arabic', serif;
            background: var(--bg-card);
            border: 1px solid var(--border-default);
            border-radius: var(--r-xl); padding: 20px;
            box-shadow: var(--shadow-lg), var(--glow-blue);
            transition: all var(--t-base) var(--ease-out);
            position: relative; overflow: hidden;
        }
        .mini-calendar-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.1), transparent);
        }
        .mini-calendar-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl), var(--glow-blue);
            border-color: var(--border-accent);
        }
        .mini-cal-header {
            text-align: center; margin-bottom: 16px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border-subtle);
        }
        .mini-cal-year {
            font-size: 20px; font-weight: 700; display: block;
            background: linear-gradient(135deg, var(--blue-light), var(--cyan));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .mini-cal-month {
            font-size: 14px; font-weight: 600;
            color: var(--text-secondary); margin-top: 2px; display: block;
        }
        .mini-cal-grid {
            display: grid; grid-template-columns: repeat(7, 1fr);
            gap: 3px; margin-bottom: 14px;
        }
        .mini-cal-day-name {
            font-size: 10px; color: var(--text-muted);
            text-align: center; padding: 4px 0;
            font-family: 'Inter', sans-serif; font-weight: 500;
        }
        .mini-cal-day-name.friday { color: var(--blue-light); }
        .mini-cal-cell {
            aspect-ratio: 1; display: flex; align-items: center; justify-content: center;
            font-size: 12px; border-radius: var(--r-xs); cursor: pointer;
            transition: all var(--t-fast); color: var(--text-secondary);
            position: relative;
        }
        .mini-cal-cell.empty { background: transparent; pointer-events: none; }
        .mini-cal-cell:hover { background: var(--blue-dim); color: var(--text-primary); }
        .mini-cal-cell.today {
            background: linear-gradient(135deg, var(--blue), #1d4ed8);
            color: #fff; font-weight: 700;
            box-shadow: 0 2px 10px rgba(59,130,246,0.4);
        }
        .mini-cal-cell.holiday { color: var(--rose); font-weight: 600; }
        .mini-cal-cell.holiday:hover { background: var(--rose-dim); }
        .mini-cal-cell.has-event::after {
            content: ''; width: 4px; height: 4px; background: var(--cyan);
            border-radius: 50%; position: absolute; bottom: 2px;
            left: 50%; transform: translateX(-50%);
        }
        .mini-cal-link {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            font-size: 11px; color: var(--text-tertiary); text-decoration: none;
            transition: color var(--t-fast); padding: 8px 0 2px;
            border-top: 1px solid var(--border-subtle);
            font-family: 'Inter', sans-serif; font-weight: 500;
        }
        .mini-cal-link:hover { color: var(--blue-light); }

        /* ═══════════════════════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════════════════════ */
        .footer {
            padding: 80px 24px; text-align: center; position: relative;
            border-top: 1px solid var(--border-subtle);
        }
        .footer::before {
            content: '';
            position: absolute; top: 0; left: 15%; right: 15%; height: 1px;
            background: linear-gradient(to right,
                transparent, var(--blue-dim), var(--cyan-dim), var(--blue-dim), transparent
            );
        }
        .footer-logo {
            font-size: 28px; font-family: 'Inter', sans-serif; font-weight: 900;
            letter-spacing: 3px; margin-bottom: 14px;
            background: linear-gradient(135deg, #fff 0%, var(--blue-light) 50%, var(--cyan) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .footer-sub {
            font-size: 13px; color: var(--text-tertiary); margin-bottom: 32px;
            font-family: 'Inter', sans-serif;
        }

        /* ═══════════════════════════════════════════════════════
           ANIMATIONS
        ═══════════════════════════════════════════════════════ */
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; } to { opacity: 1; }
        }

        /* ═══════════════════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════════════════ */
        @media (max-width: 768px) {
            .section { padding: 72px 20px; }
            .hero { padding: 100px 20px 72px; }
            .live-date { padding: 24px 32px; }
            .holidays-grid { grid-template-columns: 1fr; }
            .features-grid { grid-template-columns: 1fr; }
            .manip-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 480px) {
            .hero-btns { flex-direction: column; align-items: stretch; }
            .btn-primary, .btn-ghost { text-align: center; justify-content: center; }
            .install-box { font-size: 11px; padding: 14px 16px; }
            .months-grid { grid-template-columns: repeat(2, 1fr); }
            .stat-card { padding: 24px 16px; }
        }
    </style>
</head>
<body>

<div class="bg-gradient-animated"></div>

{{-- ═══════════ NAVBAR ═══════════ --}}
<nav class="navbar">
    <a href="#" class="nav-logo">PASHTO CAL</a>
    <ul class="nav-links">
        <li class="hide-sm"><a href="#features">{{ pcal_trans('demo_features') }}</a></li>
        <li class="hide-sm"><a href="#months">{{ pcal_trans('demo_months') }}</a></li>
        <li><a href="#mini-calendar">{{ pcal_trans('demo_mini_cal') }}</a></li>
        <li class="hide-sm"><a href="#year-view">{{ pcal_trans('demo_year_view') }}</a></li>
        <li class="hide-sm"><a href="#holidays">{{ pcal_trans('demo_holidays') }}</a></li>
        <li><a href="#install">{{ pcal_trans('demo_install') }}</a></li>
        <li><a href="/pashto-calendar" class="active-link">{{ pcal_trans('demo_calendar_link') }}</a></li>
    </ul>
</nav>

<div class="content">

    {{-- ═══════════ HERO ═══════════ --}}
    <section class="hero">
        <div class="hero-inner">

            <div class="hero-badge">
                qadir/pashto-calendar
            </div>

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
                    <div class="date-sep">/</div>
                    <div class="date-unit">
                        <div class="date-unit-num" style="font-size: clamp(24px, 4vw, 40px);">{{ $now->monthName() }}</div>
                        <div class="date-unit-label">{{ pcal_trans('demo_month') }}</div>
                    </div>
                    <div class="date-sep">/</div>
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

        </div>

        <div class="scroll-indicator">
            <span>scroll</span>
            <div class="scroll-line"></div>
        </div>
    </section>

    <div class="divider"></div>

    {{-- ═══════════ STATS ═══════════ --}}
    <div class="section" style="padding-top: 72px; padding-bottom: 32px;">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-num">۱۲</div>
                <div class="stat-label">{{ pcal_trans('demo_stats_months') }}</div>
            </div>
            <div class="stat-card" style="transition-delay: 0.08s">
                <div class="stat-num">۳</div>
                <div class="stat-label">{{ pcal_trans('demo_stats_languages') }}</div>
            </div>
            <div class="stat-card" style="transition-delay: 0.16s">
                <div class="stat-num">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber(count(\Qadir\PashtoCalendar\Support\Holidays::all())) }}</div>
                <div class="stat-label">{{ pcal_trans('demo_stats_holidays') }}</div>
            </div>
            <div class="stat-card" style="transition-delay: 0.24s">
                <div class="stat-num" style="font-family:'Noto Naskh Arabic',serif;">&#8734;</div>
                <div class="stat-label">{{ pcal_trans('demo_stats_events') }}</div>
            </div>
        </div>
        <p style="text-align:center; color: var(--text-tertiary); font-size: 13px; font-family:'Inter',sans-serif;">
            {{ pcal_trans('demo_stats_note') }}
        </p>
    </div>

    <div class="divider"></div>

    {{-- ═══════════ FEATURES ═══════════ --}}
    <section class="section" id="features">
        <div class="section-label">{{ pcal_trans('demo_section_features') }}</div>
        <div class="section-title">{{ pcal_trans('demo_features_title') }}</div>

        <div class="features-grid">
            <div class="glass-card">
                <div class="feature-icon">🔄</div>
                <div class="feature-title">{{ pcal_trans('demo_feat_convert_title') }}</div>
                <div class="feature-desc">{{ pcal_trans('demo_feat_convert_desc') }}</div>
            </div>
            <div class="glass-card" style="transition-delay:0.08s">
                <div class="feature-icon">🌐</div>
                <div class="feature-title">{{ pcal_trans('demo_feat_lang_title') }}</div>
                <div class="feature-desc">{{ pcal_trans('demo_feat_lang_desc') }}</div>
            </div>
            <div class="glass-card" style="transition-delay:0.16s">
                <div class="feature-icon">📅</div>
                <div class="feature-title">{{ pcal_trans('demo_feat_events_title') }}</div>
                <div class="feature-desc">{{ pcal_trans('demo_feat_events_desc') }}</div>
            </div>
            <div class="glass-card" style="transition-delay:0.24s">
                <div class="feature-icon">✅</div>
                <div class="feature-title">{{ pcal_trans('demo_feat_validation_title') }}</div>
                <div class="feature-desc">{{ pcal_trans('demo_feat_validation_desc') }}</div>
            </div>
            <div class="glass-card" style="transition-delay:0.32s">
                <div class="feature-icon">🎨</div>
                <div class="feature-title">{{ pcal_trans('demo_feat_directives_title') }}</div>
                <div class="feature-desc">{{ pcal_trans('demo_feat_directives_desc') }}</div>
            </div>
            <div class="glass-card" style="transition-delay:0.40s">
                <div class="feature-icon">⚡</div>
                <div class="feature-title">{{ pcal_trans('demo_feat_carbon_title') }}</div>
                <div class="feature-desc">{{ pcal_trans('demo_feat_carbon_desc') }}</div>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    {{-- ═══════════ MONTHS ═══════════ --}}
    <section class="section" id="months">
        <div class="section-label">{{ pcal_trans('demo_section_months') }}</div>
        <div class="section-title">{{ pcal_trans('demo_months_title') }}</div>

        <div class="months-grid">
            @for($i = 1; $i <= 12; $i++)
                <div class="month-card {{ $i === $now->month ? 'current' : '' }}"
                     style="transition-delay: {{ ($i-1) * 0.04 }}s">
                    <div class="month-num">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="month-ps">{{ \Qadir\PashtoCalendar\Support\Months::name($i, 'pashto') }}</div>
                    <div class="month-dari">{{ \Qadir\PashtoCalendar\Support\Months::name($i, 'dari') }}</div>
                    <div class="month-en">{{ \Qadir\PashtoCalendar\Support\Months::name($i, 'en') }}</div>
                    @if($i === $now->month)
                        <div style="margin-top:10px; font-size:9px; color:var(--blue-light); letter-spacing:2px; font-family:'Inter',sans-serif; font-weight:600; text-transform:uppercase; opacity:0.9;">
                            {{ pcal_trans('demo_current_label') }}
                        </div>
                    @endif
                </div>
            @endfor
        </div>
    </section>

    <div class="divider"></div>

    {{-- ═══════════ DATE MANIPULATION ═══════════ --}}
    <section class="section">
        <div class="section-label">{{ pcal_trans('demo_section_manip') }}</div>
        <div class="section-title">{{ pcal_trans('demo_manip_title') }}</div>

        @php $t = \Qadir\PashtoCalendar\PashtoCalendar::now(); @endphp

        <div class="manip-grid">
            <div class="manip-item glass-card">
                <div class="manip-code">-&gt;addDays(10)</div>
                <div class="manip-value">{{ (clone $t)->addDays(10)->formatPashto() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.04s">
                <div class="manip-code">-&gt;subDays(5)</div>
                <div class="manip-value">{{ (clone $t)->subDays(5)->formatPashto() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.08s">
                <div class="manip-code">-&gt;addMonths(2)</div>
                <div class="manip-value">{{ (clone $t)->addMonths(2)->formatPashto() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.12s">
                <div class="manip-code">-&gt;addYears(1)</div>
                <div class="manip-value">{{ (clone $t)->addYears(1)->formatPashto() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.16s">
                <div class="manip-code">-&gt;startOfMonth()</div>
                <div class="manip-value">{{ $t->startOfMonth()->formatPashto() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.20s">
                <div class="manip-code">-&gt;endOfMonth()</div>
                <div class="manip-value">{{ $t->endOfMonth()->formatPashto() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.24s">
                <div class="manip-code">-&gt;diffForHumans()</div>
                <div class="manip-value">{{ $t->diffForHumans() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.28s">
                <div class="manip-code">-&gt;subDays(3)-&gt;diffForHumans()</div>
                <div class="manip-value">{{ (clone $t)->subDays(3)->diffForHumans() }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.32s">
                <div class="manip-code">-&gt;isLeapYear()</div>
                <div class="manip-value">{{ $t->isLeapYear() ? pcal_trans('demo_yes') : pcal_trans('demo_no') }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.36s">
                <div class="manip-code">-&gt;daysInMonth()</div>
                <div class="manip-value">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($t->daysInMonth()) }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.40s">
                <div class="manip-code">-&gt;dayOfYear()</div>
                <div class="manip-value">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($t->dayOfYear()) }}</div>
            </div>
            <div class="manip-item glass-card" style="transition-delay:0.44s">
                <div class="manip-code">-&gt;weekOfYear()</div>
                <div class="manip-value">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($t->weekOfYear()) }}</div>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    {{-- ═══════════ MINI CALENDAR ═══════════ --}}
    <section class="section" id="mini-calendar">
        <div class="section-label">{{ pcal_trans('demo_section_mini') }}</div>
        <div class="section-title">{{ pcal_trans('demo_mini_title') }}</div>

        <div style="display:flex; justify-content:center; padding:20px 0;">
            <x-pashto-mini-calendar :year="$now->year" :month="$now->month" />
        </div>

        <div style="max-width:580px; margin:32px auto 0; text-align:center;">
            <div class="glass-card" style="opacity:1; transform:none;">
                <div style="font-size:15px; font-weight:600; color:var(--cyan); margin-bottom:12px; font-family:'Inter',sans-serif;">
                    {{ pcal_trans('demo_mini_howto_title') }}
                </div>
                <div style="font-size:14px; color: var(--text-secondary); line-height:2;">
                    {{ pcal_trans('demo_mini_howto_desc') }}
                </div>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    {{-- ═══════════ YEAR VIEW ═══════════ --}}
    <section class="section" id="year-view">
        <div class="section-label">{{ pcal_trans('demo_section_year') }}</div>
        <div class="section-title">{{ pcal_trans('demo_year_title') }}</div>

        <div style="text-align:center; margin-bottom:32px;">
            <a href="/pashto-calendar/year?year={{ $now->year }}" class="btn-primary" style="display:inline-flex; gap:8px;">
                📆 {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($now->year) }} {{ pcal_trans('demo_year_button') }}
            </a>
        </div>

        <div class="glass-card" style="max-width:680px; margin:0 auto; text-align:center; opacity:1; transform:none;">
            <div style="font-size:15px; font-weight:600; color:var(--cyan); margin-bottom:12px; font-family:'Inter',sans-serif;">
                {{ pcal_trans('demo_year_about_title') }}
            </div>
            <div style="font-size:14px; color: var(--text-secondary); line-height:2;">
                {{ pcal_trans('demo_year_about_desc') }}
            </div>
        </div>
    </section>

    <div class="divider"></div>

    {{-- ═══════════ HOLIDAYS ═══════════ --}}
    <section class="section" id="holidays">
        <div class="section-label">{{ pcal_trans('demo_section_holidays') }}</div>
        <div class="section-title">{{ pcal_trans('demo_holidays_title') }}</div>

        <div class="holidays-grid">
            @foreach(\Qadir\PashtoCalendar\Support\Holidays::allParsed() as $index => $holiday)
                <div class="holiday-card" style="transition-delay: {{ $index * 0.04 }}s">
                    <div class="holiday-date">
                        {{ str_pad($holiday['month'],2,'0',STR_PAD_LEFT) }}/{{ str_pad($holiday['day'],2,'0',STR_PAD_LEFT) }}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div class="holiday-name-ps">{{ $holiday['name_ps'] }}</div>
                        <div class="holiday-name-en">{{ $holiday['name_en'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="divider"></div>

    {{-- ═══════════ BLADE DIRECTIVES ═══════════ --}}
    <section class="section">
        <div class="section-label">{{ pcal_trans('demo_section_directives') }}</div>
        <div class="section-title">{{ pcal_trans('demo_directives_title') }}</div>

        <div class="glass-card" style="margin-bottom: 20px; opacity:1; transform:none;">
            <div class="code-block">
<span class="code-comment">{{-- اوسنۍ نیټه --}}</span>
<span class="code-kw">&#64;</span><span class="code-fn">pashtoNow</span>
<span class="code-output">&#8594; {{ pashto_now() }}</span>

<span class="code-comment">{{-- د میلادي نیټې بدلون --}}</span>
<span class="code-kw">&#64;</span><span class="code-fn">pashtoDate</span>(<span class="code-str">$post-&gt;created_at</span>)
<span class="code-output">&#8594; {{ to_shamsi_pashto(now()) }}</span>

<span class="code-comment">{{-- معیاري بڼه --}}</span>
<span class="code-kw">&#64;</span><span class="code-fn">pashtoFormat</span>(<span class="code-str">'2024-03-20'</span>, <span class="code-str">'Y/m/d'</span>)
<span class="code-output">&#8594; {{ to_shamsi('2024-03-20', 'Y/m/d') }}</span>

<span class="code-comment">{{-- پښتو شمیرې --}}</span>
<span class="code-kw">&#64;</span><span class="code-fn">pashtoNumber</span>(<span class="code-var">$year</span>)
<span class="code-output">&#8594; {{ pashto_number($now->year) }}</span>

<span class="code-comment">{{-- د رخصتۍ چک --}}</span>
<span class="code-kw">&#64;</span><span class="code-fn">ifHoliday</span>(1, 1) نوروز مبارک! <span class="code-kw">&#64;</span><span class="code-fn">endIfHoliday</span>
<span class="code-output">&#8594; {{ is_pashto_holiday(1, 1) ? '✅ نوروز مبارک!' : '(نه رخصتي)' }}</span>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    {{-- ═══════════ INSTALL ═══════════ --}}
    <section class="section" id="install" style="text-align:center;">
        <div class="section-label" style="justify-content:center;">{{ pcal_trans('demo_section_install') }}</div>
        <div class="section-title" style="text-align:center">{{ pcal_trans('demo_install_title') }}</div>

        <div style="display:flex; flex-direction:column; align-items:center; gap:12px; margin-bottom:52px; max-width:700px; margin-left:auto; margin-right:auto; margin-bottom:52px;">
            <div class="install-box">
                <span>composer require qadir/pashto-calendar</span>
                <button class="copy-btn" onclick="navigator.clipboard.writeText('composer require qadir/pashto-calendar'); this.textContent='{{ pcal_trans('demo_copied') }}'">{{ pcal_trans('demo_copy') }}</button>
            </div>
            <div class="install-box" style="border-color:rgba(59,130,246,0.2); color:var(--blue-light)">
                <span>php artisan vendor:publish --tag=pashto-calendar-config</span>
                <button class="copy-btn" style="border-color:rgba(59,130,246,0.2); color:var(--blue-light); background:rgba(59,130,246,0.08)" onclick="navigator.clipboard.writeText('php artisan vendor:publish --tag=pashto-calendar-config'); this.textContent='{{ pcal_trans('demo_copied') }}'">{{ pcal_trans('demo_copy') }}</button>
            </div>
            <div class="install-box" style="border-color:rgba(244,63,94,0.2); color:var(--rose)">
                <span>php artisan migrate</span>
                <button class="copy-btn" style="border-color:rgba(244,63,94,0.2); color:var(--rose); background:rgba(244,63,94,0.08)" onclick="navigator.clipboard.writeText('php artisan migrate'); this.textContent='{{ pcal_trans('demo_copied') }}'">{{ pcal_trans('demo_copy') }}</button>
            </div>
        </div>

        <div class="glass-card" style="max-width:680px; margin:0 auto; text-align:right; opacity:1; transform:none;">
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

    {{-- ═══════════ FOOTER ═══════════ --}}
    <footer class="footer">
        <div class="footer-logo">PASHTO CALENDAR</div>
        <div class="footer-sub">
            {{ str_replace([':laravel', ':php'], [app()->version(), PHP_VERSION], pcal_trans('demo_footer_built')) }}
        </div>
        <div style="display:flex; justify-content:center; gap:14px; flex-wrap:wrap; margin-bottom:48px;">
            <a href="/pashto-calendar" class="btn-primary">{{ pcal_trans('demo_footer_calendar') }}</a>
            <a href="/pashto-calendar/demo" class="btn-ghost">{{ pcal_trans('demo_footer_demo') }}</a>
        </div>
        <div style="font-size:10px; color:var(--text-muted); letter-spacing:4px; font-family:'JetBrains Mono',monospace; text-transform:uppercase; opacity:0.5;">
            MIT LICENSE
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
<!DOCTYPE html>
<html lang="ps" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ pcal_trans('year_view_page_title') }}</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script> -->
    <link href="{{ asset('vendor/pashto-calendar/css/pashto-calendar.css') }}">
<script src="{{ asset('vendor/pashto-calendar/js/pashto-calendar.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(circle at top, #0d1b35 0%, #04080f 100%);
            font-family: 'Noto Naskh Arabic', serif;
            color: #e8e0d0;
            padding: 20px;
        }
        .year-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .month-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(240,165,0,0.2);
            border-radius: 20px;
            padding: 16px;
            transition: transform 0.2s;
        }
        .month-card:hover { transform: translateY(-3px); }
        .month-name {
            font-size: 20px;
            font-weight: 700;
            color: #ffd166;
            text-align: center;
            margin-bottom: 12px;
        }
        .mini-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }
        .day-name {
            font-size: 10px;
            color: #94a3b8;
            text-align: center;
            padding: 2px;
        }
        .day-name.friday { color: #f0a500; }
        .day-cell {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            border-radius: 4px;
            background: rgba(255,255,255,0.03);
        }
        .day-cell.empty { background: transparent; }
        .day-cell.today { background: #f0a500; color: #000; font-weight: bold; }
        .day-cell.holiday { color: #ef476f; }
        .month-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #94a3b8;
            font-size: 13px;
            text-decoration: none;
        }
        .month-link:hover { color: #f0a500; }
        .year-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .year-header h1 {
            font-size: 42px;
            font-weight: 700;
            background: linear-gradient(135deg, #f0a500, #ffd166);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .year-nav {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        .year-nav button {
            background: rgba(240,165,0,0.1);
            border: 1px solid rgba(240,165,0,0.3);
            color: #f0a500;
            padding: 8px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 18px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #94a3b8;
            text-decoration: none;
        }
        .back-link:hover { color: #f0a500; }
    </style>
</head>
<body>
    <div x-data="yearView()">
        <div class="year-header">
            <h1 x-text="headingText"></h1>
        </div>
        <div class="year-nav">
            <button @click="changeYear(-1)">{{ pcal_trans('previous') }}</button>
            <span style="font-size:20px; color:#ffd166;" x-text="year"></span>
            <button @click="changeYear(1)">{{ pcal_trans('next') }}</button>
        </div>
        <div class="year-container">
            <template x-for="(month, mIndex) in monthsData" :key="mIndex">
                <div class="month-card">
                    <div class="month-name" x-text="month.name"></div>
                    <div class="mini-grid">
                        <template x-for="(dayName, dIndex) in weekDays" :key="dIndex">
                            <div class="day-name" :class="{ 'friday': dIndex === 6 }" x-text="dayName"></div>
                        </template>
                        <template x-for="(cell, cIndex) in month.days" :key="cIndex">
                            <div class="day-cell"
                                 :class="{
                                    'empty': !cell.day,
                                    'today': cell.isToday,
                                    'holiday': cell.isHoliday
                                 }">
                                <span x-text="cell.day"></span>
                            </div>
                        </template>
                    </div>
                    <a :href="'/pashto-calendar?year=' + year + '&month=' + month.number"
                       class="month-link">
                        {{ pcal_trans('view_month') }}
                    </a>
                </div>
            </template>
        </div>
        <a href="/pashto-calendar" class="back-link">{{ pcal_trans('back_to_calendar') }}</a>
    </div>

    <script>
        // Pass translated week days and heading text to JavaScript
        const yearWeekDays = @json(pcal_trans('week_days'));
        const yearHeadingText = @json(pcal_trans('year_view_heading'));
        const yearLoadFailed = @json(pcal_trans('load_year_failed'));

        function yearView() {
            return {
                year: {{ $year }},
                monthsData: [],
                weekDays: yearWeekDays,
                headingText: yearHeadingText + ' ' + this.year, // will be set in init
                async init() {
                    this.headingText = yearHeadingText + ' ' + this.year;
                    await this.loadYear(this.year);
                },
                async loadYear(y) {
                    try {
                        const resp = await fetch(`/pashto-calendar/year-data/${y}`);
                        if (!resp.ok) throw new Error('Network error');
                        const data = await resp.json();
                        this.monthsData = data.months;
                        this.year = data.year;
                        this.headingText = yearHeadingText + ' ' + data.year;
                    } catch (e) {
                        console.error(e);
                        alert(yearLoadFailed);
                    }
                },
                async changeYear(delta) {
                    this.year += delta;
                    await this.loadYear(this.year);
                }
            }
        }
    </script>
</body>
</html>
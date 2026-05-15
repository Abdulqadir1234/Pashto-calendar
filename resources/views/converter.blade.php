<!DOCTYPE html>
<html lang="ps" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ pcal_trans('converter_title') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(circle at top, #0d1b35 0%, #04080f 100%);
            font-family: 'Noto Naskh Arabic', serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .glass-card {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(240,165,0,0.2);
            border-radius: 32px;
            padding: 40px 30px;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(240,165,0,0.1) inset;
            animation: fadeIn 0.4s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: none; } }
        .title {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #f0a500, #ffd166);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 30px;
        }
        .converter-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        @media (max-width: 640px) { .converter-grid { grid-template-columns: 1fr; } }
        .panel {
            background: rgba(255,255,255,0.025);
            border-radius: 20px;
            padding: 24px;
        }
        .panel h3 { font-size: 18px; color: #ffd166; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .input-label {
            font-size: 13px;
            color: #94a3b8;
            display: block;
            margin-bottom: 4px;
        }
        .input-modern {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(240,165,0,0.2);
            border-radius: 12px;
            padding: 12px 16px;
            color: #fff;
            font-size: 16px;
            margin-bottom: 12px;
            outline: none;
            transition: 0.2s;
            font-family: inherit;
        }
        .input-modern:focus { border-color: #f0a500; box-shadow: 0 0 0 3px rgba(240,165,0,0.15); }
        .input-modern::placeholder { color: rgba(232,224,208,0.3); }
        .btn-gold {
            background: linear-gradient(135deg, #f0a500, #c87800);
            color: #000;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-family: inherit;
            transition: 0.2s;
        }
        .btn-gold:hover { box-shadow: 0 8px 25px rgba(240,165,0,0.35); transform: scale(1.02); }
        .result-box {
            margin-top: 16px;
            padding: 14px;
            background: rgba(240,165,0,0.08);
            border-radius: 12px;
            border: 1px dashed rgba(240,165,0,0.3);
            text-align: center;
            font-size: 18px;
            color: #ffd166;
            word-break: break-word;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            transition: 0.2s;
        }
        .back-link:hover { color: #f0a500; }

        /* ── Date Picker (inline calendar icon) ── */
        .picker-wrapper { position: relative; margin-bottom: 12px; display: flex; align-items: center; }
        .picker-wrapper input { flex: 1; margin-bottom: 0; padding-right: 40px; }
        .picker-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(232,224,208,0.6);
            cursor: pointer;
            font-size: 18px;
            padding: 4px;
        }
        .picker-icon:hover { color: #f0a500; }
        .picker-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            max-width: 350px;
            background: #0d1b35;
            border: 1px solid rgba(240,165,0,0.25);
            border-radius: 16px;
            margin-top: 8px;
            z-index: 70;
            padding: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        .picker-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .picker-nav-btn {
            background: rgba(240,165,0,0.1);
            border: none;
            color: #f0a500;
            border-radius: 6px;
            padding: 4px 10px;
            cursor: pointer;
            font-size: 16px;
        }
        .picker-nav-btn:hover { background: rgba(240,165,0,0.2); }
        .picker-title {
            color: #ffd166;
            font-weight: 600;
            font-size: 14px;
            min-width: 120px;
            text-align: center;
        }
        .picker-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
        }
        .picker-day-name {
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            padding: 4px 0;
        }
        .picker-day {
            text-align: center;
            padding: 6px 0;
            border-radius: 6px;
            cursor: pointer;
            color: #e8e0d0;
            font-size: 14px;
            transition: 0.15s;
        }
        .picker-day:hover { background: rgba(240,165,0,0.2); }
        .picker-day.selected { background: #f0a500; color: #000; font-weight: 700; }
        .picker-day.today { border: 1px solid #f0a500; }
    </style>
</head>
<body>
    <div class="glass-card" x-data="converterApp()">
        <div class="title">{{ pcal_trans('converter_title') }}</div>
        <div class="converter-grid">

            {{-- Left: Gregorian → Pashto --}}
            <div class="panel">
                <h3>{{ pcal_trans('gregorian_to_pashto') }}</h3>
                <label class="input-label">{{ pcal_trans('gregorian_date_label') }}</label>
                <input type="date" x-model="gregorianInput" class="input-modern"
                       @change="convertGregorian" @keyup.enter="convertGregorian">
                <button class="btn-gold" @click="convertGregorian">{{ pcal_trans('convert') }}</button>
                <div x-show="pashtoResult" x-transition class="result-box" x-text="pashtoResult"></div>
            </div>

            {{-- Right: Pashto → Gregorian (type + calendar picker) --}}
            <div class="panel" x-data="pashtoDatePicker()" x-ref="picker">
                <h3>{{ pcal_trans('pashto_to_gregorian') }}</h3>

                <label class="input-label">{{ pcal_trans('pashto_date_label') }}</label>
                <div class="picker-wrapper">
                    <input type="text" x-model="dateText" class="input-modern"
                           placeholder="{{ pcal_trans('eg_date') }}"
                           @keyup.enter="convertFromText"
                           @focus="open = false">
                    <button type="button" class="picker-icon" @click.stop="open = !open" title="{{ pcal_trans('open_calendar') }}">
                        📅
                    </button>

                    {{-- Calendar Dropdown --}}
                    <div x-show="open" x-transition class="picker-dropdown" @click.away="open = false">
                        <div class="picker-header">
                            <button class="picker-nav-btn" @click="changeMonth(-1)">◀</button>
                            <div class="picker-title" x-text="monthName + ' ' + viewYear"></div>
                            <button class="picker-nav-btn" @click="changeMonth(1)">▶</button>
                        </div>

                        <div class="picker-grid">
                            <template x-for="day in ['ش','ی','د','س','چ','پ','ج']">
                                <div class="picker-day-name" x-text="day"></div>
                            </template>
                            <template x-for="(d, idx) in days" :key="idx">
                                <div x-show="d.day"
                                     class="picker-day"
                                     :class="{ 'selected': isSelected(d.day), 'today': isToday(d.day) }"
                                     @click="selectDate(d.day)"
                                     x-text="d.day">
                                </div>
                                <div x-show="!d.day" class="picker-day"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <button class="btn-gold" style="margin-top:8px;" @click="convertFromText">{{ pcal_trans('convert') }}</button>
                <div x-show="gregorianResult" x-transition class="result-box" x-text="gregorianResult"></div>
            </div>
        </div>
        <a href="/pashto-calendar" class="back-link">{{ pcal_trans('back_to_calendar') }}</a>
    </div>

    {{-- Inject translated alert messages into JavaScript --}}
    <script>
        window.converterTrans = {
            pickDateFirst: @json(pcal_trans('pick_date_first')),
            invalidFormat: @json(pcal_trans('invalid_format')),
            conversionFailed: @json(pcal_trans('conversion_failed')),
        };
    </script>

    <script>
        // ── Main converter (Gregorian → Pashto) ──
        function converterApp() {
            return {
                gregorianInput: '',
                pashtoResult: '',

                async convertGregorian() {
                    if (!this.gregorianInput) return;
                    try {
                        const resp = await fetch(`/pashto-calendar/convert/gregorian?date=${encodeURIComponent(this.gregorianInput)}`);
                        const data = await resp.json();
                        if (data.error) { alert(data.error); }
                        else { this.pashtoResult = `📆 ${data.formatted} (${data.year}-${data.month}-${data.day})`; }
                    } catch (e) { alert(window.converterTrans.conversionFailed); }
                }
            };
        }

        // ── Pashto Date Picker (typing + calendar) ──
        function pashtoDatePicker() {
            return {
                dateText: '',
                open: false,
                viewYear: {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->year }},
                viewMonth: {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->month }},
                selectedDay: null,
                selectedMonth: null,
                selectedYear: null,

                todayYear: {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->year }},
                todayMonth: {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->month }},
                todayDay: {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->day }},

                monthNames: ['', 'وری', 'غویی', 'غبرګولی', 'چنګاښ', 'زمری', 'وږی', 'تله', 'لړم', 'لیندۍ', 'مرغومی', 'سلواغه', 'کب'],
                gregorianResult: '',

                get monthName() {
                    return this.monthNames[this.viewMonth] || '';
                },

                get days() {
                    const dim = this.daysInMonth(this.viewYear, this.viewMonth);
                    const first = this.firstDayOfWeek(this.viewYear, this.viewMonth);
                    const cells = [];
                    for (let i = 0; i < first; i++) cells.push({ day: null });
                    for (let d = 1; d <= dim; d++) cells.push({ day: d });
                    while (cells.length < 42) cells.push({ day: null });
                    return cells;
                },

                daysInMonth(year, month) {
                    const lengths = [31,31,31,31,31,31,30,30,30,30,30,29];
                    const leap = [1,5,9,13,17,21,25,29];
                    if (month === 12 && leap.includes(year % 33)) return 30;
                    return lengths[month-1];
                },

                firstDayOfWeek(year, month) {
                    let total = 0;
                    for (let y = 1403; y < year; y++) total += this.isLeapYear(y) ? 366 : 365;
                    for (let m = 1; m < month; m++) total += this.daysInMonth(year, m);
                    return (total + 4) % 7;
                },

                isLeapYear(y) { return [1,5,9,13,17,21,25,29].includes(y % 33); },

                isSelected(day) {
                    return this.selectedYear && this.selectedMonth === this.viewMonth && this.selectedYear === this.viewYear && day === this.selectedDay;
                },

                isToday(day) {
                    return day === this.todayDay && this.viewMonth === this.todayMonth && this.viewYear === this.todayYear;
                },

                changeMonth(delta) {
                    let m = this.viewMonth + delta;
                    let y = this.viewYear;
                    if (m > 12) { m = 1; y++; }
                    if (m < 1)  { m = 12; y--; }
                    this.viewMonth = m;
                    this.viewYear = y;
                },

                selectDate(day) {
                    this.selectedYear = this.viewYear;
                    this.selectedMonth = this.viewMonth;
                    this.selectedDay = day;
                    this.dateText = `${this.selectedYear}/${String(this.selectedMonth).padStart(2,'0')}/${String(day).padStart(2,'0')}`;
                    this.open = false;
                    this.convertPashto();
                },

                async convertPashto() {
                    if (!this.selectedYear || !this.selectedMonth || !this.selectedDay) {
                        alert(window.converterTrans.pickDateFirst);
                        return;
                    }
                    try {
                        const resp = await fetch(`/pashto-calendar/convert/pashto?year=${this.selectedYear}&month=${this.selectedMonth}&day=${this.selectedDay}`);
                        const data = await resp.json();
                        if (data.error) { alert(data.error); }
                        else { this.gregorianResult = `📆 ${data.gregorian}`; }
                    } catch (e) { alert(window.converterTrans.conversionFailed); }
                },

                convertFromText() {
                    const parts = this.dateText.split('/');
                    if (parts.length === 3) {
                        const y = parseInt(parts[0]), m = parseInt(parts[1]), d = parseInt(parts[2]);
                        if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
                            this.selectedYear = y;
                            this.selectedMonth = m;
                            this.selectedDay = d;
                            this.viewYear = y;
                            this.viewMonth = m;
                            this.convertPashto();
                            return;
                        }
                    }
                    alert(window.converterTrans.invalidFormat);
                }
            };
        }
    </script>
</body>
</html>
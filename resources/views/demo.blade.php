<!DOCTYPE html>
<html lang="ps" dir="{{ config('pashto-calendar.rtl', true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pashto Calendar - Developer Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: '{{ config('pashto-calendar.font', 'Noto Naskh Arabic, serif') }}'; }
        .code { direction: ltr; text-align: left; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

{{-- ============================================================ --}}
{{-- HEADER                                                       --}}
{{-- ============================================================ --}}
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-10 px-6 text-center">
    <h1 class="text-3xl font-bold mb-2">📅 د پښتو کلیندر پاکیج</h1>
    <p class="text-blue-200 text-lg">Pashto Calendar Laravel Package — Developer Demo</p>
    <p class="text-blue-300 text-sm mt-1">qadir/pashto-calendar</p>

    <div class="mt-6 flex justify-center gap-4 flex-wrap">
        <a href="/pashto-calendar"
           class="bg-white text-blue-700 px-5 py-2 rounded-lg font-bold hover:bg-blue-50 transition">
            📅 کلیندر وګوره
        </a>
        <a href="https://github.com/qadir/pashto-calendar" target="_blank"
           class="bg-blue-500 text-white px-5 py-2 rounded-lg font-bold hover:bg-blue-400 transition">
            GitHub ➜
        </a>
    </div>
</div>

<div class="max-w-5xl mx-auto px-6 py-10 space-y-10">

    {{-- ============================================================ --}}
    {{-- INSTALLATION                                                 --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">⚡ نصبول (Installation)</h2>
        <div class="bg-gray-900 text-green-400 rounded-lg p-4 code text-sm space-y-1">
            <p>composer require qadir/pashto-calendar</p>
            <p>php artisan vendor:publish --tag=pashto-calendar-config</p>
            <p>php artisan migrate</p>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- CURRENT DATE                                                 --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">📆 اوسنۍ نیټه (Current Date)</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <div class="text-3xl font-bold text-blue-700">
                    {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($now->day) }}
                </div>
                <div class="text-blue-500 text-sm mt-1">ورځ</div>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <div class="text-3xl font-bold text-green-700">
                    {{ $now->monthName() }}
                </div>
                <div class="text-green-500 text-sm mt-1">میاشت</div>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <div class="text-3xl font-bold text-purple-700">
                    {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($now->year) }}
                </div>
                <div class="text-purple-500 text-sm mt-1">کال</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-gray-500 mb-1">پښتو بڼه</div>
                <div class="font-bold text-lg">{{ $now->formatPashto() }}</div>
                <div class="code text-gray-400 text-xs mt-1">PashtoCalendar::now()->formatPashto()</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-gray-500 mb-1">معیاري بڼه</div>
                <div class="font-bold text-lg">{{ $now->format('Y/m/d') }}</div>
                <div class="code text-gray-400 text-xs mt-1">PashtoCalendar::now()->format('Y/m/d')</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-gray-500 mb-1">د کال ورځ</div>
                <div class="font-bold text-lg">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($now->dayOfYear()) }}</div>
                <div class="code text-gray-400 text-xs mt-1">->dayOfYear()</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-gray-500 mb-1">د اونۍ شمیره</div>
                <div class="font-bold text-lg">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($now->weekOfYear()) }}</div>
                <div class="code text-gray-400 text-xs mt-1">->weekOfYear()</div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- DATE CONVERSION                                              --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">🔄 نیټه بدلول (Date Conversion)</h2>
        <div class="space-y-3 text-sm">

            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div>
                    <div class="text-gray-500">Gregorian → Shamsi</div>
                    <div class="code text-gray-400 text-xs">to_shamsi('2024-03-20')</div>
                </div>
                <div class="font-bold text-blue-700">{{ to_shamsi('2024-03-20') }}</div>
            </div>

            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div>
                    <div class="text-gray-500">Gregorian → Pashto readable</div>
                    <div class="code text-gray-400 text-xs">to_shamsi_pashto('2024-03-20')</div>
                </div>
                <div class="font-bold text-blue-700">{{ to_shamsi_pashto('2024-03-20') }}</div>
            </div>

            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div>
                    <div class="text-gray-500">Shamsi → Gregorian</div>
                    <div class="code text-gray-400 text-xs">to_gregorian(1403, 1, 1)</div>
                </div>
                <div class="font-bold text-blue-700 code">{{ to_gregorian(1403, 1, 1) }}</div>
            </div>

            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div>
                    <div class="text-gray-500">Pashto numerals</div>
                    <div class="code text-gray-400 text-xs">pashto_number(1403)</div>
                </div>
                <div class="font-bold text-blue-700">{{ pashto_number(1403) }}</div>
            </div>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MONTH NAMES — ALL 3 LANGUAGES                               --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">🌐 د میاشتو نومونه (Month Names)</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 text-right">#</th>
                        <th class="p-3 text-right">پښتو</th>
                        <th class="p-3 text-right">دري</th>
                        <th class="p-3 text-left">English</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 1; $i <= 12; $i++)
                        <tr class="{{ $i % 2 === 0 ? 'bg-gray-50' : '' }}
                                   {{ $i === $now->month ? 'bg-blue-50 font-bold' : '' }}">
                            <td class="p-3">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($i) }}</td>
                            <td class="p-3">{{ \Qadir\PashtoCalendar\Support\Months::name($i, 'pashto') }}</td>
                            <td class="p-3">{{ \Qadir\PashtoCalendar\Support\Months::name($i, 'dari') }}</td>
                            <td class="p-3 code">{{ \Qadir\PashtoCalendar\Support\Months::name($i, 'en') }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- HOLIDAYS                                                     --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">🎉 رسمي رخصتۍ (Afghan Holidays)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach(\Qadir\PashtoCalendar\Support\Holidays::allParsed() as $holiday)
                <div class="flex items-start gap-3 bg-red-50 rounded-lg p-3">
                    <div class="bg-red-100 text-red-700 rounded px-2 py-1 text-sm font-bold whitespace-nowrap code">
                        {{ $holiday['month'] }}/{{ $holiday['day'] }}
                    </div>
                    <div>
                        <div class="font-bold text-gray-800 text-sm">{{ $holiday['name_ps'] }}</div>
                        <div class="text-gray-500 text-xs code">{{ $holiday['name_en'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- DATE MANIPULATION                                            --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">🔧 نیټه اداره کول (Date Manipulation)</h2>
        <div class="space-y-3 text-sm">

            @php $today = \Qadir\PashtoCalendar\PashtoCalendar::now(); @endphp

            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">->addDays(10)</div>
                <div class="font-bold">{{ $today->addDays(10)->formatPashto() }}</div>
            </div>
            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">->subDays(5)</div>
                <div class="font-bold">{{ $today->subDays(5)->formatPashto() }}</div>
            </div>
            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">->addMonths(2)</div>
                <div class="font-bold">{{ $today->addMonths(2)->formatPashto() }}</div>
            </div>
            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">->addYears(1)</div>
                <div class="font-bold">{{ $today->addYears(1)->formatPashto() }}</div>
            </div>
            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">->startOfMonth()</div>
                <div class="font-bold">{{ $today->startOfMonth()->formatPashto() }}</div>
            </div>
            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">->endOfMonth()</div>
                <div class="font-bold">{{ $today->endOfMonth()->formatPashto() }}</div>
            </div>
            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">->diffForHumans()</div>
                <div class="font-bold">{{ $today->diffForHumans() }}</div>
            </div>
            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">->subDays(3)->diffForHumans()</div>
                <div class="font-bold">{{ $today->subDays(3)->diffForHumans() }}</div>
            </div>
            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">->isLeapYear()</div>
                <div class="font-bold">{{ $today->isLeapYear() ? 'هو' : 'نه' }}</div>
            </div>
            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">->daysInMonth()</div>
                <div class="font-bold">{{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($today->daysInMonth()) }}</div>
            </div>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- BLADE DIRECTIVES                                             --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">🎨 Blade Directives</h2>
        <div class="space-y-3 text-sm">

            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">@{{ pashtoNow }}</div>
                <div class="font-bold">@pashtoNow</div>
            </div>

            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">@{{ pashtoDate('2024-03-20') }}</div>
                <div class="font-bold">@pashtoDate('2024-03-20')</div>
            </div>

            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">@{{ pashtoFormat('2024-03-20', 'Y/m/d') }}</div>
                <div class="font-bold">@pashtoFormat('2024-03-20', 'Y/m/d')</div>
            </div>

            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">@{{ pashtoNumber(1403) }}</div>
                <div class="font-bold">@pashtoNumber(1403)</div>
            </div>

            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div class="code text-gray-500">@{{ ifHoliday(1,1) }} نوروز مبارک @{{ endIfHoliday }}</div>
                <div class="font-bold text-red-600">@ifHoliday(1, 1) نوروز مبارک ✅ @endIfHoliday</div>
            </div>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- VALIDATION                                                   --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">✅ Validation Rules</h2>
        <div class="bg-gray-900 text-green-400 rounded-lg p-4 code text-sm space-y-2">
            <p>// Valid Shamsi date</p>
            <p>$request->validate(['date' => 'pashto_date']);</p>
            <br>
            <p>// Specific format</p>
            <p>$request->validate(['date' => 'pashto_date_format:Y/m/d']);</p>
            <br>
            <p>// Date must be after</p>
            <p>$request->validate(['date' => 'after_pashto_date:1400/01/01']);</p>
            <br>
            <p>// Date must be before</p>
            <p>$request->validate(['date' => 'before_pashto_date:1410/12/29']);</p>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- FOOTER                                                       --}}
    {{-- ============================================================ --}}
    <div class="text-center text-gray-400 text-sm pb-10">
        <p>qadir/pashto-calendar — Built by Abdul Qadir Nasrat</p>
        <p class="mt-1">Laravel {{ app()->version() }} — PHP {{ PHP_VERSION }}</p>
        <div class="mt-4 flex justify-center gap-4">
            <a href="/pashto-calendar"
               class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                📅 د کلیندر لیدل
            </a>
        </div>
    </div>

</div>
</body>
</html>
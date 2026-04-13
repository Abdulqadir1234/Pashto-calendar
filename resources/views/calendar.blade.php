<!DOCTYPE html>
        {{-- ✅ Safe fallback if $rtl not passed --}}
        <html lang="ps" {{ ($rtl ?? true) ? 'dir=rtl' : 'dir=ltr' }}>


<head>
    <title>Pashto Calendar</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine --}}
    <script src="https://unpkg.com/alpinejs" defer></script>

    {{-- Pashto Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;700&display=swap" rel="stylesheet">

        
        {{-- ✅ Safe fallback if $font not passed --}}
        <style>
            body { font-family: {{ $font ?? 'Noto Naskh Arabic, serif' }}; }
        </style>
</head>

<body class="bg-gray-100 min-h-screen">

<div
    x-data="calendarApp()"
    class="container mx-auto mt-10 max-w-3xl px-4"
>

    {{-- ============================================================ --}}
    {{-- HEADER — month name + navigation                             --}}
    {{-- ============================================================ --}}
    <div class="flex justify-between items-center mb-6 bg-white rounded-lg shadow p-4">

        <a href="?month={{ $month - 1 }}&year={{ $year }}"
           class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
            &#8592; وړاندې
        </a>

        <h2 class="text-xl font-bold text-center text-gray-800">
            {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($year) }}
            &mdash;
            {{ \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) }}
        </h2>

        <a href="?month={{ $month + 1 }}&year={{ $year }}"
           class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
            وروسته &#8594;
        </a>

    </div>

    {{-- ============================================================ --}}
    {{-- WEEK DAY HEADERS                                             --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-7 gap-1 text-center mb-2">
        @foreach(['شنبه','یکشنبه','دوشنبه','سه‌شنبه','چهارشنبه','پنجشنبه','جمعه'] as $dayName)
            <div class="font-bold text-sm text-gray-600 py-2">
                {{ $dayName }}
            </div>
        @endforeach
    </div>

    {{-- ============================================================ --}}
    {{-- CALENDAR GRID                                                --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-7 gap-1">

        @foreach($days as $day)

            {{-- Empty cell --}}
            @if(isset($day['empty']) && $day['empty'])
                <div class="h-16 rounded"></div>
                @continue
            @endif

            {{-- Real day cell --}}
            <div
                class="h-16 border rounded-lg p-1 cursor-pointer transition
                    {{ $day['is_today']   ? 'border-blue-500 bg-blue-50'   : 'border-gray-200 bg-white' }}
                    {{ $day['is_friday']  ? 'bg-yellow-50 border-yellow-300' : '' }}
                    {{ $day['is_holiday'] ? 'bg-red-50 border-red-200'     : '' }}
                    hover:shadow-md hover:border-gray-400"

                @click="openModal(
                    {{ $day['day'] }},
                    '{{ \Qadir\PashtoCalendar\PashtoDate::monthNameStatic($month) }}',
                    @json($day['events'])
                )"
            >
                {{-- Day number --}}
                <div class="font-bold text-sm text-center
                    {{ $day['is_today']   ? 'text-blue-600' : 'text-gray-800' }}
                    {{ $day['is_friday']  ? 'text-yellow-700' : '' }}
                    {{ $day['is_holiday'] ? 'text-red-600'  : '' }}">
                    {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber($day['day']) }}
                </div>

                {{-- Holiday label --}}
                @if($day['is_holiday'] && $day['holiday_name'])
                    <div class="text-red-500 text-center leading-tight"
                         style="font-size: 8px;">
                        {{ $day['holiday_name'] }}
                    </div>
                @endif

                {{-- Event dots --}}
                @if($day['event_count'] > 0)
                    <div class="flex justify-center gap-1 mt-1 flex-wrap">
                        @foreach($day['events'] as $event)
                            <span
                                class="w-2 h-2 rounded-full inline-block"
                                style="background-color: {{ $event->color ?? '#3b82f6' }}">
                            </span>
                        @endforeach
                    </div>
                @endif

            </div>

        @endforeach

    </div>

    {{-- ============================================================ --}}
    {{-- ✅ FIXED MODAL — proper overlay + x-show + correct structure --}}
    {{-- ============================================================ --}}

    {{-- Dark overlay background --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 z-40"
        @click="open = false"
        style="display: none;"
    ></div>

    {{-- Modal box --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;"
    >
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6"
            @click.stop
        >

            {{-- Modal header --}}
            <div class="flex justify-between items-center mb-4 border-b pb-3">
                <h3 class="text-lg font-bold text-gray-800">
                    <span x-text="selectedMonthName"></span>
                    <span x-text="selectedDay"></span>
                </h3>
                <button
                    @click="open = false"
                    class="text-gray-400 hover:text-gray-600 text-2xl leading-none"
                >
                    &times;
                </button>
            </div>

            {{-- Events list --}}
            <div class="mb-4 max-h-40 overflow-y-auto">

                <template x-if="events.length === 0">
                    <p class="text-gray-400 text-center text-sm py-4">
                        هیڅ پیښه نشته
                    </p>
                </template>

                <template x-for="event in events" :key="event.id">
                    <div
                        class="flex items-center justify-between p-2 mb-2 rounded-lg text-white text-sm"
                        :style="'background-color:' + (event.color || '#3b82f6')"
                    >
                        <span x-text="event.title"></span>
                        <button
                            @click="deleteEvent(event.id)"
                            class="text-white opacity-70 hover:opacity-100 mr-2 text-lg leading-none"
                        >
                            &times;
                        </button>
                    </div>
                </template>

            </div>

            {{-- Add event form --}}
            <div class="border-t pt-4">

                <input
                    type="text"
                    placeholder="د پیښې سرلیک..."
                    x-model="form.title"
                    class="w-full border border-gray-300 rounded-lg p-2 mb-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                    @keydown.enter="saveEvent"
                >

                <input
                    type="text"
                    placeholder="توضیحات (اختیاري)"
                    x-model="form.description"
                    class="w-full border border-gray-300 rounded-lg p-2 mb-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                >

                <div class="flex items-center gap-3 mb-3">
                    <label class="text-sm text-gray-600">رنګ:</label>
                    <input
                        type="color"
                        x-model="form.color"
                        class="h-8 w-16 rounded border cursor-pointer"
                    >
                    <span class="text-xs text-gray-400" x-text="form.color"></span>
                </div>

                <div class="flex gap-2">
                    <button
                        @click="saveEvent"
                        :disabled="saving || !form.title"
                        class="flex-1 bg-blue-500 hover:bg-blue-600 disabled:bg-blue-300 text-white py-2 rounded-lg text-sm transition"
                    >
                        <span x-show="!saving">پیښه زیاتول</span>
                        <span x-show="saving">خوندي کول...</span>
                    </button>

                    <button
                        @click="open = false"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg text-sm transition"
                    >
                        بندول
                    </button>
                </div>

            </div>

        </div>
    </div>

</div>{{-- end x-data --}}

{{-- ============================================================ --}}
{{-- ✅ FIXED ALPINE LOGIC                                        --}}
{{-- ============================================================ --}}
<script>
function calendarApp() {
    return {
        open: false,
        saving: false,
        selectedDay: null,
        selectedMonthName: '',
        events: [],

        form: {
            title: '',
            description: '',
            color: '#3b82f6'
        },

        // ✅ Open modal with day data
        openModal(day, monthName, events) {
            this.selectedDay      = day;
            this.selectedMonthName = monthName;
            this.events           = events || [];
            this.form.title       = '';
            this.form.description = '';
            this.form.color       = '#3b82f6';
            this.open             = true;
        },

        // ✅ Save event via API
        async saveEvent() {

            if (!this.form.title.trim()) return;

            this.saving = true;

            try {
                let response = await fetch('/pashto-calendar/event', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify({
                        title:       this.form.title,
                        description: this.form.description,
                        year:        {{ $year }},
                        month:       {{ $month }},
                        day:         this.selectedDay,
                        color:       this.form.color
                    })
                });

                if (!response.ok) {
                    throw new Error('Server error');
                }

                let data = await response.json();

                // ✅ Add to UI instantly without page reload
                this.events.push(data);

                // Reset form
                this.form.title       = '';
                this.form.description = '';
                this.form.color       = '#3b82f6';

            } catch (error) {
                console.error('Failed to save event:', error);
                alert('د پیښې خوندي کول ناکام شول');
            } finally {
                this.saving = false;
            }
        },

        // ✅ Delete event
        async deleteEvent(id) {

            if (!confirm('ایا تاسو ډاډه یاست چې دا پیښه ړنګ کړئ؟')) return;

            try {
                let response = await fetch('/pashto-calendar/event/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                if (!response.ok) {
                    throw new Error('Server error');
                }

                // ✅ Remove from UI instantly
                this.events = this.events.filter(e => e.id !== id);

            } catch (error) {
                console.error('Failed to delete event:', error);
                alert('د پیښې ړنګول ناکام شول');
            }
        }
    }
}
</script>

</body>
</html>
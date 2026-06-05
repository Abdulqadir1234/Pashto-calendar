<div class="prayer-times-card" x-data="prayerTimes('{{ $times['city'] }}')">
    <div class="prayer-header">
        <span class="prayer-city" x-text="cityName"></span>
        <span class="prayer-date">
            {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber(\Qadir\PashtoCalendar\PashtoCalendar::now()->day) }}
            {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->monthName() }}
            {{ \Qadir\PashtoCalendar\PashtoDate::toPashtoNumber(\Qadir\PashtoCalendar\PashtoCalendar::now()->year) }}
        </span>
    </div>

    {{-- City Selector --}}
    <div class="city-selector">
        <select x-model="city" @change="loadTimes" class="city-dropdown">
            @foreach(config('pashto-prayer-cities') as $key => $cityData)
                <option value="{{ $key }}">{{ $cityData['name'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="prayer-grid">
        <template x-for="(time, key) in times" :key="key">
            <div class="prayer-item">
                <div class="prayer-name">
                    <span x-text="icons[key]"></span>
                    <span x-text="labels[key]"></span>
                </div>
                <div class="prayer-time" x-text="time"></div>
            </div>
        </template>
    </div>
</div>

<style>
    .prayer-times-card {
        background: var(--bg-glass, rgba(255,255,255,0.04));
        backdrop-filter: blur(16px);
        border: 1px solid var(--border, rgba(240,165,0,0.2));
        border-radius: 20px;
        padding: 20px;
        max-width: 320px;
        width: 100%;
        margin: 0 auto;
        box-shadow: 0 15px 35px rgba(0,0,0,0.4);
    }
    .prayer-header {
        text-align: center;
        margin-bottom: 10px;
    }
    .prayer-city {
        font-size: 20px;
        font-weight: 700;
        color: var(--gold, #f0a500);
        display: block;
    }
    .prayer-date {
        font-size: 13px;
        color: var(--muted, #94a3b8);
        margin-top: 4px;
        display: block;
    }
    .city-selector {
        text-align: center;
        margin-bottom: 16px;
    }
    .city-dropdown {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(240,165,0,0.2);
        border-radius: 10px;
        padding: 6px 12px;
        color: #fff;
        font-size: 14px;
        outline: none;
        font-family: inherit;
    }
    .city-dropdown option {
        background: #0d1b35;
        color: #e8e0d0;
    }
    .prayer-grid {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .prayer-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255,255,255,0.03);
        border-radius: 10px;
        padding: 10px 16px;
    }
    .prayer-name {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        color: var(--text, #e8e0d0);
    }
    .prayer-time {
        font-size: 16px;
        font-weight: 700;
        color: var(--gold2, #ffd166);
    }
</style>

<script>
    function prayerTimes(initialCity) {
        const prayerIcons = {
            fajr: '🌅', sunrise: '🌄', dhuhr: '☀️', asr: '🌤️', maghrib: '🌇', isha: '🌙'
        };
        const prayerLabels = {
            fajr: @json(pcal_trans('prayer_fajr')),
            sunrise: @json(pcal_trans('prayer_sunrise')),
            dhuhr: @json(pcal_trans('prayer_dhuhr')),
            asr: @json(pcal_trans('prayer_asr')),
            maghrib: @json(pcal_trans('prayer_maghrib')),
            isha: @json(pcal_trans('prayer_isha'))
        };

        return {
            city: initialCity,
            cityName: @json($times['city_name']),
            times: {
                fajr: @json($times['fajr']),
                sunrise: @json($times['sunrise']),
                dhuhr: @json($times['dhuhr']),
                asr: @json($times['asr']),
                maghrib: @json($times['maghrib']),
                isha: @json($times['isha'])
            },
            icons: prayerIcons,
            labels: prayerLabels,

            async loadTimes() {
                try {
                    const resp = await fetch(`/pashto-calendar/prayer-times/${this.city}`);
                    if (!resp.ok) throw new Error('Network error');
                    const data = await resp.json();
                    this.times = data.times;
                    this.cityName = data.city_name;
                } catch (e) {
                    alert('Failed to load prayer times');
                }
            }
        }
    }
</script>
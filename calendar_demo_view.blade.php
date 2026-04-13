<!DOCTYPE html>
<html lang="ps" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pashto Calendar Demo - {{ $month_name }} {{ $year }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;600;700&display=swap');
        body { font-family: 'Noto Sans Arabic', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">
            {{ $month_name }} {{ $year }}
        </h1>
        
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="grid grid-cols-7 gap-2 mb-4">
                <div class="text-center font-bold text-red-600">Sunday</div>
                <div class="text-center font-bold">Monday</div>
                <div class="text-center font-bold">Tuesday</div>
                <div class="text-center font-bold">Wednesday</div>
                <div class="text-center font-bold">Thursday</div>
                <div class="text-center font-bold">Friday</div>
                <div class="text-center font-bold text-green-600">Saturday</div>
            </div>
            
            <div class="grid grid-cols-7 gap-2">
                @foreach($calendar as $day)
                    @if($day['empty'])
                        <div class="h-24 border border-gray-200"></div>
                    @else
                        <div class="h-24 border border-gray-300 rounded p-2 relative 
                            {{ $day['is_today'] ? 'bg-blue-100 border-blue-500' : '' }}
                            {{ $day['is_holiday'] ? 'bg-red-50' : '' }}">
                            
                            <div class="font-bold text-sm mb-1">
                                {{ $day['day'] }}
                                @if($day['is_holiday'])
                                    <span class="text-red-600 text-xs">*{{ $day['holiday_name'] }}</span>
                                @endif
                            </div>
                            
                            @if($day['event_count'] > 0)
                                <div class="space-y-1">
                                    @foreach($day['events'] as $event)
                                        <div class="text-xs p-1 rounded 
                                            {{ $event->color == 'green' ? 'bg-green-200 text-green-800' : '' }}
                                            {{ $event->color == 'red' ? 'bg-red-200 text-red-800' : '' }}
                                            {{ $event->color == 'blue' ? 'bg-blue-200 text-blue-800' : '' }}
                                            {{ $event->color == 'yellow' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                            {{ $event->color == 'orange' ? 'bg-orange-200 text-orange-800' : '' }}
                                            {{ $event->color == 'purple' ? 'bg-purple-200 text-purple-800' : '' }}
                                            {{ $event->color == 'pink' ? 'bg-pink-200 text-pink-800' : '' }}">
                                            {{ $event->title }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            @if($day['is_today'])
                                <div class="absolute top-1 right-1 w-2 h-2 bg-blue-500 rounded-full"></div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Events Summary</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="font-semibold mb-2">All Events ({{ collect($calendar)->sum('event_count') }})</h3>
                    <ul class="space-y-1 text-sm">
                        @foreach(\Qadir\PashtoCalendar\Events\EventManager::all() as $event)
                            <li class="flex items-center space-x-2 space-x-reverse">
                                <span class="w-3 h-3 rounded-full bg-{{ $event->color }}-400"></span>
                                <span>{{ $event->title }} - {{ $event->day }}/{{ $event->month }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-2">Calendar Info</h3>
                    <ul class="text-sm space-y-1">
                        <li>Year: {{ $year }}</li>
                        <li>Month: {{ $month }} ({{ $month_name }})</li>
                        <li>Total Days: {{ count(array_filter($calendar, fn($d) => !$d['empty'])) }}</li>
                        <li>Events Found: {{ collect($calendar)->sum('event_count') }}</li>
                        <li>Holidays: {{ collect($calendar)->filter(fn($d) => $d['is_holiday'] ?? false)->count() }}</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <a href="/test-calendar" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2">JSON View</a>
            <a href="/all-events" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mr-2">All Events</a>
            <a href="/current-date" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">Current Date</a>
        </div>
    </div>
</body>
</html>

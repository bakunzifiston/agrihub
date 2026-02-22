@props(['kpis' => []])

@if (!empty($kpis))
<div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @foreach ($kpis as $kpi)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 border-l-4 {{ $kpi['color'] ?? 'border-primary' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $kpi['label'] }}</p>
                    <p class="mt-1 text-2xl font-bold {{ ($kpi['trend'] ?? null) === 'up' ? 'text-green-600' : (($kpi['trend'] ?? null) === 'down' ? 'text-red-600' : 'text-gray-900') }}">
                        @if (($kpi['format'] ?? null) === 'currency')
                            <span class="text-sm font-normal text-gray-500">RWF</span>
                        @endif
                        {{ $kpi['value'] }}
                    </p>
                    @if (isset($kpi['description']))
                        <p class="mt-1 text-xs text-gray-500">{{ $kpi['description'] }}</p>
                    @endif
                </div>
                @if (isset($kpi['icon']))
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                        {!! $kpi['icon'] !!}
                    </div>
                @endif
            </div>
            @if (isset($kpi['trend']) && isset($kpi['trend_value']))
                <div class="mt-2 flex items-center text-xs">
                    @if ($kpi['trend'] === 'up')
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-green-600 font-medium">{{ $kpi['trend_value'] }}</span>
                    @elseif ($kpi['trend'] === 'down')
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-red-600 font-medium">{{ $kpi['trend_value'] }}</span>
                    @endif
                    @if (isset($kpi['trend_period']))
                        <span class="ml-1 text-gray-500">{{ $kpi['trend_period'] }}</span>
                    @endif
                </div>
            @endif
        </div>
    @endforeach
</div>
@endif

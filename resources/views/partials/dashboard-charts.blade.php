@if (!empty($charts))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        @foreach ($charts as $chart)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">{{ $chart['title'] }}</h3>
                <div class="h-64">
                    <canvas id="{{ $chart['id'] }}"></canvas>
                </div>
                <script type="application/json" id="config-{{ $chart['id'] }}">{!! json_encode($chart) !!}</script>
            </div>
        @endforeach
    </div>
    @push('scripts')
    <script>
    (function() {
        function initCharts() {
            if (typeof window.Chart === 'undefined') return;
            document.querySelectorAll('script[type="application/json"][id^="config-"]').forEach(function(scriptEl) {
                var chartId = scriptEl.id.replace('config-', '');
                var canvas = document.getElementById(chartId);
                if (!canvas) return;
                var config = JSON.parse(scriptEl.textContent);
                var ctx = canvas.getContext('2d');
                if (!ctx) return;
                var chartConfig = {
                    type: config.type,
                    data: {
                        labels: config.labels,
                        datasets: config.datasets.map(function(ds) {
                            var d = { data: ds.data };
                            if (ds.backgroundColor) d.backgroundColor = ds.backgroundColor;
                            if (ds.borderColor) d.borderColor = ds.borderColor;
                            if (ds.label) d.label = ds.label;
                            if (ds.fill !== undefined) d.fill = ds.fill;
                            if (ds.tension !== undefined) d.tension = ds.tension;
                            return d;
                        })
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: config.type === 'doughnut' } }
                    }
                };
                new window.Chart(ctx, chartConfig);
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCharts);
        } else {
            initCharts();
        }
    })();
    </script>
    @endpush
@endif

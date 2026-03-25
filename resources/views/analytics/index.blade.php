<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Analytics Dashboard') }}
        </h2>
    </x-slot>

    <!-- Import Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-emerald-500/30 rounded-xl p-6 shadow-lg">
                    <h3 class="text-emerald-400 font-bold text-lg mb-2">Resolved Tickets</h3>
                    <p class="text-4xl text-white font-extrabold">{{ $statusCounts['Resolved'] }}</p>
                </div>
                <div
                    class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-blue-500/30 rounded-xl p-6 shadow-lg">
                    <h3 class="text-blue-400 font-bold text-lg mb-2">Open Tickets</h3>
                    <p class="text-4xl text-white font-extrabold">{{ $statusCounts['Open'] }}</p>
                </div>
                <div
                    class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-purple-500/30 rounded-xl p-6 shadow-lg">
                    <h3 class="text-purple-400 font-bold text-lg mb-2">In Progress</h3>
                    <p class="text-4xl text-white font-extrabold">{{ $statusCounts['In Progress'] }}</p>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status Chart -->
                <div
                    class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 rounded-xl p-6 shadow-lg">
                    <h3 class="text-gray-200 font-bold mb-4">Tickets by Status</h3>
                    <div class="relative h-64 w-full flex items-center justify-center">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <!-- Category Chart -->
                <div
                    class="backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 rounded-xl p-6 shadow-lg">
                    <h3 class="text-gray-200 font-bold mb-4">Tickets by Category</h3>
                    <div class="relative h-64 w-full flex items-center justify-center">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Dates Chart -->
            <div
                class="mt-6 backdrop-blur-md bg-white/10 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 rounded-xl p-6 shadow-lg">
                <h3 class="text-gray-200 font-bold mb-4">Ticket Volume (Timeline)</h3>
                <div class="relative h-72 w-full">
                    <canvas id="dateChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart Configuration -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Status Chart (Doughnut)
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Open', 'In Progress', 'Resolved'],
                    datasets: [{
                        data: [{{ $statusCounts['Open'] }}, {{ $statusCounts['In Progress'] }}, {{ $statusCounts['Resolved'] }}],
                        backgroundColor: ['#3b82f6', '#8b5cf6', '#10b981'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom', labels: { color: '#e5e7eb' } }
                    }
                }
            });

            // Category Chart (Bar)
            const ctxCategory = document.getElementById('categoryChart').getContext('2d');
            const categoryKeys = {!! json_encode(array_keys($categoryCounts)) !!};
            const categoryVals = {!! json_encode(array_values($categoryCounts)) !!};

            new Chart(ctxCategory, {
                type: 'bar',
                data: {
                    labels: categoryKeys,
                    datasets: [{
                        label: 'Tickets',
                        data: categoryVals,
                        backgroundColor: '#8b5cf6',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true, ticks: { color: '#9ca3af', stepSize: 1 }, grid: { color: '#374151' } },
                        x: { ticks: { color: '#9ca3af' }, grid: { display: false } }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // Date Chart (Line)
            const ctxDate = document.getElementById('dateChart').getContext('2d');
            const dateKeys = {!! json_encode(array_keys($dateCounts)) !!};
            const dateVals = {!! json_encode(array_values($dateCounts)) !!};

            new Chart(ctxDate, {
                type: 'line',
                data: {
                    labels: dateKeys,
                    datasets: [{
                        label: 'Tickets Created',
                        data: dateVals,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, ticks: { color: '#9ca3af', stepSize: 1 }, grid: { color: '#374151' } },
                        x: { ticks: { color: '#9ca3af' }, grid: { color: '#374151' } }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</x-app-layout>
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold">Dashboard SIMRS</h2>
            <p class="text-gray-600">Ringkasan aktivitas operasional rumah sakit hari ini.</p>
        </div>
        <div class="bg-blue-100 text-blue-800 font-bold px-4 py-2 rounded-lg">
            Tanggal: {{ \Carbon\Carbon::parse($today)->isoFormat('D MMMM Y') }}
        </div>
    </div>

    <!-- Grid 4 Card Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1: Total Kunjungan -->
        <div class="bg-white p-5 rounded-lg shadow-md border-l-4 border-blue-600 flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase">Total Kunjungan</p>
                <h3 class="text-2xl font-extrabold text-gray-800 mt-1">{{ $totalToday }}</h3>
                <span class="text-xs text-gray-400">Hari ini</span>
            </div>
            <div class="p-3 bg-blue-100 text-blue-600 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Card 2: Total Dokter Aktif -->
        <div class="bg-white p-5 rounded-lg shadow-md border-l-4 border-emerald-500 flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase">Dokter Aktif</p>
                <h3 class="text-2xl font-extrabold text-gray-800 mt-1">{{ $totalDoctors }}</h3>
                <span class="text-xs text-gray-400">Tersedia</span>
            </div>
            <div class="p-3 bg-emerald-100 text-emerald-600 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>

        <!-- Card 3: Pasien Laki-Laki -->
        <div class="bg-white p-5 rounded-lg shadow-md border-l-4 border-indigo-500 flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase">Pasien Laki-Laki</p>
                <h3 class="text-2xl font-extrabold text-gray-800 mt-1">{{ $totalMale }}</h3>
                <span class="text-xs text-gray-400">Hari ini</span>
            </div>
            <div class="p-3 bg-indigo-100 text-indigo-600 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>

        <!-- Card 4: Pasien Perempuan -->
        <div class="bg-white p-5 rounded-lg shadow-md border-l-4 border-pink-500 flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase">Pasien Perempuan</p>
                <h3 class="text-2xl font-extrabold text-gray-800 mt-1">{{ $totalFemale }}</h3>
                <span class="text-xs text-gray-400">Hari ini</span>
            </div>
            <div class="p-3 bg-pink-100 text-pink-600 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>

    </div>

    <!-- Grafik Kunjungan Per Poli -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="font-bold text-lg mb-4 text-gray-800">Grafik Kunjungan Berdasarkan Poliklinik</h3>
        <div class="relative w-full h-80">
            <canvas id="poliChart"></canvas>
        </div>
    </div>

    <!-- Script Chart.js tetap sama -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:navigated', initChart);
        document.addEventListener('DOMContentLoaded', initChart);

        function initChart() {
            const ctx = document.getElementById('poliChart');
            if (!ctx) return;

            const dataPoli = @json($kunjunganPerPoli);

            const labels = dataPoli.map(item => item.nama_poli);
            const totals = dataPoli.map(item => item.total);

            if (window.myPoliChart) {
                window.myPoliChart.destroy();
            }

            window.myPoliChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Kunjungan',
                        data: totals,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(239, 68, 68, 0.7)',
                            'rgba(139, 92, 246, 0.7)'
                        ],
                        borderColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(245, 158, 11)',
                            'rgb(239, 68, 68)',
                            'rgb(139, 92, 246)'
                        ],
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    </script>
</div>
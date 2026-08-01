<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMRS Sederhana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>

<body class="bg-gray-100 min-h-screen text-gray-800">

    <!-- Navbar Minimalis -->
    <nav class="bg-blue-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-bold">SIMRS App</h1>
            <div class="flex gap-4">
                <a href="/" class="hover:bg-blue-700 px-3 py-1 rounded transition">Dashboard</a>
                <a href="/patients" class="hover:bg-blue-700 px-3 py-1 rounded transition">Pasien</a>
                <a href="/polis" class="hover:bg-blue-700 px-3 py-1 rounded transition">Master Poli</a>
                <a href="/doctors" class="hover:bg-blue-700 px-3 py-1 rounded transition">Master Dokter</a>
                <a href="/schedules" class="hover:bg-blue-700 px-3 py-1 rounded transition">Jadwal Praktek</a>
                <a href="/registrations"
                    class="bg-yellow-400 text-blue-900 font-bold px-3 py-1 rounded hover:bg-yellow-300 transition">Pendaftaran</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6">
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>

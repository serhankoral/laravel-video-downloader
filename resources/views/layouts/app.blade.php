<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-950 text-white min-h-screen">
    <nav class="bg-gray-900 border-b border-gray-800 px-6 py-4">
        <div class="max-w-4xl mx-auto flex items-center gap-3">
            <span class="text-2xl">🎬</span>
            <h1 class="text-xl font-bold text-white">Video Downloader</h1>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-10">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>

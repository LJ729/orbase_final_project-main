<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <div id="laravel-reverb-config" class="hidden"
     data-key="{{ config('broadcasting.connections.reverb.key') }}"
     data-host="{{ config('broadcasting.connections.reverb.options.host') }}"
     data-port="{{ (int) config('broadcasting.connections.reverb.options.port', 8080) }}"
     data-scheme="{{ config('broadcasting.connections.reverb.options.scheme', 'http') }}">
</div>

<script>
    const reverbEl = document.getElementById('laravel-reverb-config');
    window.Laravel = {
        reverb: {
            key: reverbEl.dataset.key,
            host: reverbEl.dataset.host,
            port: parseInt(reverbEl.dataset.port, 10),
            scheme: reverbEl.dataset.scheme,
        },
    };
</script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
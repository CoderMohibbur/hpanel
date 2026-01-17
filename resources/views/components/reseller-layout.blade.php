@props([
    'title' => 'Reseller',
    'pageTitle' => 'Reseller',
    'pageDesc' => null,
])

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-900">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl font-semibold">{{ $pageTitle }}</h1>
                @if(!empty($pageDesc))
                    <p class="text-sm text-gray-500 mt-1">{{ $pageDesc }}</p>
                @endif
            </div>

            <div class="flex items-center gap-2">
                {{ $actions ?? '' }}

                <a href="{{ route('home') }}"
                   class="px-3 py-2 rounded-lg bg-white border text-sm hover:bg-gray-50">
                    Public Home
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </div>
</body>
</html>

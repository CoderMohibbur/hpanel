@props([
    'panel' => 'admin', // admin|reseller|client
    'title' => 'Panel',
    'pageTitle' => null,
    'pageDesc' => null,
])

@php
    $user = auth()->user();

    $brand = match ($panel) {
        'admin' => 'H Panel — Admin',
        'reseller' => 'H Panel — Reseller',
        default => 'H Panel — Client',
    };

    $brandHref = match ($panel) {
        'admin' => \Illuminate\Support\Facades\Route::has('admin.dashboard') ? route('admin.dashboard') : '/admin',
        'reseller' => \Illuminate\Support\Facades\Route::has('reseller.dashboard')
            ? route('reseller.dashboard')
            : '/reseller',
        default => route('dashboard'),
    };

    $nav = [];

    if ($panel === 'admin') {
        $nav = [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Users', 'route' => 'admin.users.index'],
            ['label' => 'Servers', 'route' => 'admin.servers.index'],
            ['label' => 'Plans', 'route' => 'admin.plans.index'],
            ['label' => 'Orders', 'route' => 'admin.orders.index'],
            ['label' => 'Wallet', 'route' => 'admin.wallet.index'],
            ['label' => 'Withdraw', 'route' => 'admin.withdraw.index'],
            ['label' => 'Reports', 'route' => 'admin.reports.index'],
        ];
    } elseif ($panel === 'reseller') {
        $nav = [
            ['label' => 'Dashboard', 'route' => 'reseller.dashboard'],
            ['label' => 'Orders', 'route' => 'reseller.orders.index'],
            ['label' => 'Services', 'route' => 'reseller.services.index'],
            ['label' => 'Wallet', 'route' => 'reseller.wallet.index'],
            ['label' => 'Withdraw', 'route' => 'reseller.withdraw.index'],
        ];
    } else {
        $nav = [
            ['label' => 'Dashboard', 'route' => 'dashboard'],
            ['label' => 'My Services', 'route' => 'client.services.index'],
            ['label' => 'My Orders', 'route' => 'client.orders.index'],
            ['label' => 'Invoices', 'route' => 'client.invoices.index'],
            ['label' => 'Support', 'route' => 'client.support.index'],
        ];
    }

    $approval = $user->approval_status ?? null;
@endphp

<!doctype html>
<html lang="en" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Mini Sidebar (icon-only) + hover expand + pinned toggle --}}
    <style>
        :root {
            --sidebar-expanded: 16rem;
            /* 64 */
            --sidebar-collapsed: 5rem;
            /* 20 */
        }

        /* Desktop behavior (sm+) */
        @media (min-width: 640px) {

            /* width controlled by body class */
            #logo-sidebar {
                width: var(--sidebar-expanded);
                transition: width .25s ease;
            }

            body.sidebar-collapsed #logo-sidebar {
                width: var(--sidebar-collapsed);
            }

            /* main margin controlled by body class */
            main.panel-main {
                margin-left: var(--sidebar-expanded);
                transition: margin-left .25s ease;
            }

            body.sidebar-collapsed main.panel-main {
                margin-left: var(--sidebar-collapsed);
            }

            /* hover expand only when collapsed (overlay, no content jump) */
            body.sidebar-collapsed.sidebar-hover #logo-sidebar {
                width: var(--sidebar-expanded);
                z-index: 49;
                /* navbar z-50 এর নিচে থাকবে */
                box-shadow: 0 10px 30px rgba(0, 0, 0, .25);
            }

            body.sidebar-collapsed.sidebar-hover main.panel-main {
                margin-left: var(--sidebar-collapsed);
            }

            /* hide labels when collapsed and not hovering */
            body.sidebar-collapsed:not(.sidebar-hover) .sidebar-label {
                display: none !important;
            }

            body.sidebar-collapsed:not(.sidebar-hover) .brand-text {
                display: none !important;
            }

            /* center icons when collapsed */
            body.sidebar-collapsed:not(.sidebar-hover) .nav-item {
                justify-content: center !important;
            }

            body.sidebar-collapsed:not(.sidebar-hover) .nav-icon {
                margin-right: 0 !important;
            }
        }
    </style>
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900">

    {{-- Top Navbar (Flowbite style) --}}
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button type="button" id="sidebar-toggle"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100
                               focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700
                               dark:focus:ring-gray-600">
                        <span class="sr-only">Toggle sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 5A.75.75 0 012.75 9h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 9.75zm0 5a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 14.75z" />
                        </svg>
                    </button>

                    <a href="{{ $brandHref }}" class="flex ms-2 md:me-24">
                        <span class="self-center text-lg font-semibold sm:text-xl whitespace-nowrap dark:text-white">
                            {{ $brand }}
                        </span>
                    </a>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Search (desktop) --}}
                    <div class="hidden md:block">
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="none"
                                    viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="text" placeholder="Search..."
                                class="block w-72 ps-10 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50
                                       focus:ring-gray-200 focus:border-gray-200
                                       dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                                       dark:focus:ring-gray-600 dark:focus:border-gray-600">
                        </div>
                    </div>

                    {{-- Dark mode toggle --}}
                    <button type="button" id="theme-toggle"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100
                               focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700
                               dark:focus:ring-gray-600">
                        <span class="sr-only">Toggle dark mode</span>
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707 8.001 8.001 0 1017.293 13.293z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zM4 11a1 1 0 100-2H3a1 1 0 100 2h1zm2.343 4.243a1 1 0 011.414 0l.707.707A1 1 0 116.05 17.364l-.707-.707a1 1 0 010-1.414zM11 17a1 1 0 10-2 0v1a1 1 0 102 0v-1zM6.464 4.05a1 1 0 010 1.414l-.707.707A1 1 0 114.343 4.757l.707-.707a1 1 0 011.414 0zm9.193.707a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707z">
                            </path>
                        </svg>
                    </button>

                    {{-- User dropdown --}}
                    <button type="button" id="user-menu-button"
                        class="flex text-sm rounded-full focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-600">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full object-cover"
                            src="{{ $user?->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user?->name ?? 'User') }}"
                            alt="user photo">
                    </button>

                    <div id="user-dropdown"
                        class="hidden absolute right-3 top-14 z-50 w-56 text-base list-none bg-white divide-y divide-gray-100 rounded-xl shadow
                               dark:bg-gray-700 dark:divide-gray-600">
                        <div class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user?->name }}</p>
                            <p class="text-xs text-gray-500 truncate dark:text-gray-300">{{ $user?->email }}</p>

                            @if ($approval)
                                <p
                                    class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold border
                                          bg-gray-50 text-gray-700 border-gray-200
                                          dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500">
                                    {{ strtoupper($approval) }}
                                </p>
                            @endif
                        </div>

                        <ul class="py-2">
                            @if (\Illuminate\Support\Facades\Route::has('profile.show'))
                                <li>
                                    <a href="{{ route('profile.show') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">
                                        Profile
                                    </a>
                                </li>
                            @endif

                            <li>
                                <a href="{{ route('home') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">
                                    Public Home
                                </a>
                            </li>
                        </ul>

                        <div class="py-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50
                                               dark:text-red-300 dark:hover:bg-gray-600">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </nav>

    {{-- Sidebar backdrop (mobile) --}}
    <div id="sidebar-backdrop" class="hidden fixed inset-0 z-30 bg-gray-900/50 sm:hidden"></div>

    {{-- Sidebar --}}
    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 h-screen pt-16 transition-transform duration-300 -translate-x-full
               bg-white border-r border-gray-200 sm:translate-x-0
               dark:bg-gray-800 dark:border-gray-700"
        aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto">
            <ul class="space-y-1 font-medium mt-2">
                @foreach ($nav as $item)
                    @php
                        $routeName = $item['route'];
                        $exists = \Illuminate\Support\Facades\Route::has($routeName);
                        $href = $exists ? route($routeName) : '#';
                        $active = $exists && request()->routeIs($routeName . '*');
                    @endphp

                    <li>
                        <x-panel.sidebar-link :href="$href" :active="$active" :disabled="!$exists">
                            <x-slot name="icon">
                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16z"></path>
                                </svg>
                            </x-slot>

                            {{ $item['label'] }}
                        </x-panel.sidebar-link>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                <p class="px-2 text-xs text-gray-500 dark:text-gray-400 brand-text">
                    Phase-0 UI Shell (Flowbite-style)
                </p>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <main class="panel-main p-4 pt-20">
        @if ($pageTitle)
            <div class="mb-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $pageTitle }}</h1>
                        @if ($pageDesc)
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $pageDesc }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        {{ $actions ?? '' }}
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div
                class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm
                        dark:border-green-800/40 dark:bg-green-900/20 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 text-sm
                        dark:border-red-800/40 dark:bg-red-900/20 dark:text-red-200">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>

    <script>
        // Theme (dark mode)
        (function() {
            const html = document.documentElement;
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = stored || (prefersDark ? 'dark' : 'light');

            if (theme === 'dark') html.classList.add('dark');

            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');

            function syncIcon() {
                const isDark = html.classList.contains('dark');
                if (darkIcon && lightIcon) {
                    darkIcon.classList.toggle('hidden', !isDark);
                    lightIcon.classList.toggle('hidden', isDark);
                }
            }
            syncIcon();

            const btn = document.getElementById('theme-toggle');
            if (btn) {
                btn.addEventListener('click', () => {
                    html.classList.toggle('dark');
                    localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
                    syncIcon();
                });
            }
        })();

        // Sidebar: mobile slide + desktop mini (icon-only) + hover expand + pinned toggle
        (function() {
            const sidebar = document.getElementById('logo-sidebar');
            const toggle = document.getElementById('sidebar-toggle');
            const backdrop = document.getElementById('sidebar-backdrop');

            const panel = @json($panel ?? 'admin');
            const key = `hpanel_sidebar_collapsed_${panel}`;

            function isDesktop() {
                return window.innerWidth >= 640;
            }

            function openMobile() {
                if (!sidebar) return;
                sidebar.classList.remove('-translate-x-full');
                backdrop && backdrop.classList.remove('hidden');
            }

            function closeMobile() {
                if (!sidebar) return;
                sidebar.classList.add('-translate-x-full');
                backdrop && backdrop.classList.add('hidden');
            }

            function setPinnedCollapsed(collapsed) {
                document.body.classList.toggle('sidebar-collapsed', collapsed);
                document.body.classList.remove('sidebar-hover');
                localStorage.setItem(key, collapsed ? '1' : '0');
            }

            // init
            (function init() {
                if (!sidebar) return;
                if (isDesktop()) {
                    const collapsed = localStorage.getItem(key) === '1';
                    setPinnedCollapsed(collapsed);
                    backdrop && backdrop.classList.add('hidden');
                    sidebar.classList.remove('-translate-x-full');
                } else {
                    document.body.classList.remove('sidebar-collapsed', 'sidebar-hover');
                    closeMobile();
                }
            })();

            // toggle click
            toggle && toggle.addEventListener('click', () => {
                if (!sidebar) return;

                if (isDesktop()) {
                    const nowCollapsed = !document.body.classList.contains('sidebar-collapsed');
                    setPinnedCollapsed(nowCollapsed);
                } else {
                    const closed = sidebar.classList.contains('-translate-x-full');
                    closed ? openMobile() : closeMobile();
                }
            });

            // hover expand only when collapsed (desktop)
            sidebar && sidebar.addEventListener('mouseenter', () => {
                if (!isDesktop()) return;
                if (!document.body.classList.contains('sidebar-collapsed')) return;
                document.body.classList.add('sidebar-hover');
            });
            sidebar && sidebar.addEventListener('mouseleave', () => {
                if (!isDesktop()) return;
                document.body.classList.remove('sidebar-hover');
            });

            // mobile close handlers
            backdrop && backdrop.addEventListener('click', closeMobile);
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !isDesktop()) closeMobile();
            });

            // resize
            window.addEventListener('resize', () => {
                if (!sidebar) return;

                if (isDesktop()) {
                    const collapsed = localStorage.getItem(key) === '1';
                    setPinnedCollapsed(collapsed);
                    backdrop && backdrop.classList.add('hidden');
                    sidebar.classList.remove('-translate-x-full');
                } else {
                    document.body.classList.remove('sidebar-collapsed', 'sidebar-hover');
                    closeMobile();
                }
            });
        })();

        // User dropdown toggle
        (function() {
            const btn = document.getElementById('user-menu-button');
            const dd = document.getElementById('user-dropdown');

            function close() {
                dd && dd.classList.add('hidden');
            }

            function toggle() {
                dd && dd.classList.toggle('hidden');
            }

            btn && btn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggle();
            });

            document.addEventListener('click', close);
        })();
    </script>
</body>

</html>

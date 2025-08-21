{{-- resources/views/components/header.blade.php --}}
<header x-data="header()" x-init="init()"
    class="fixed top-0 inset-x-0 z-50 border-b border-gray-200 dark:border-slate-800
         bg-white/80 dark:bg-[#0f172a]/95 backdrop-blur supports-[backdrop-filter]:bg-white/90
         text-gray-900 dark:text-slate-200">

    <div class="mx-auto max-w-full h-16 px-3 sm:px-6 lg:px-8 grid grid-cols-3 items-center gap-2">

        {{-- Left: menu toggle + logo --}}
        <div class="flex items-center gap-2">
            {{-- Sidebar toggle: visible on all breakpoints --}}
            <button @click="window.dispatchEvent(new CustomEvent('toggle-sidebar'))"
                class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 focus:outline-none"
                aria-label="Toggle sidebar">
                <i data-lucide="menu" class="h-5 w-5"></i>
            </button>

            {{-- Logo: hidden on mobile (xs), visible on sm+ --}}
            <a href="{{ url('/') }}" class="hidden sm:flex items-center gap-2 shrink-0 select-none">
                <img src="{{ asset('images/logo/logo.png') }}" alt="H-Panel" class="h-8">
                <span class="font-semibold tracking-tight">{{ config('app.name', 'H-Panel') }}</span>
            </a>
        </div>




        {{-- Center: search (always centered) --}}
        <div class="flex justify-center">
            <button @click="openSearch = true; $nextTick(()=>$refs.search?.focus())"
                class="group flex items-center gap-2 h-10 w-full max-w-xs sm:max-w-md px-3 rounded-xl
                     border border-gray-200 dark:border-slate-700
                     bg-gray-50 dark:bg-slate-800/90
                     hover:bg-white dark:hover:bg-slate-800
                     text-gray-700 dark:text-slate-100 transition-colors">
                <i data-lucide="search" class="h-4 w-4 opacity-80"></i>
                <span class="text-sm opacity-90">Search</span>
                <kbd
                    class="ml-auto hidden sm:inline text-[10px] px-1.5 py-0.5 rounded border
                   bg-white dark:bg-slate-900 dark:border-slate-700">Ctrl
                    K</kbd>
            </button>
        </div>

        {{-- Right: actions --}}
        <div class="ml-auto flex items-center justify-end gap-1.5">
            <a href="#"
                class="hidden md:inline-flex items-center gap-2 h-10 px-3 rounded-xl text-sm font-semibold
                bg-indigo-600 text-white hover:bg-indigo-700 shadow">
                <i data-lucide="gift" class="h-4 w-4"></i>
                Refer & earn
            </a>

            {{-- Flowbite-style theme toggle --}}
            <button id="theme-toggle" type="button"
                class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 focus:outline-none"
                aria-label="Toggle theme">
                {{-- show when DARK (sun icon to switch to light) --}}
                <svg id="theme-toggle-dark-icon" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364-1.414 1.414M7.05 16.95l-1.414 1.414m12.728 0-1.414-1.414M7.05 7.05 5.636 5.636M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8z" />
                </svg>
                {{-- show when LIGHT (moon icon to switch to dark) --}}
                <svg id="theme-toggle-light-icon" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                </svg>
            </button>

            {{-- Notifications --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open=!open" class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 relative">
                    <i data-lucide="bell" class="h-5 w-5"></i>
                    <span
                        class="absolute -top-0.5 -right-0.5 h-4 min-w-4 px-0.5 text-[10px] grid place-items-center
                       rounded-full bg-rose-600 text-white">3</span>
                </button>
                <div x-show="open" x-transition @click.outside="open=false"
                    class="absolute right-0 mt-2 w-72 rounded-xl border border-gray-200 dark:border-slate-800
                    bg-white dark:bg-[#0b1220] shadow-lg overflow-hidden">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-600 dark:text-slate-300">Notifications</div>
                    <ul class="max-h-64 overflow-y-auto text-sm">
                        <li class="px-3 py-2 hover:bg-gray-50 dark:hover:bg-slate-800/70">Domain transfer approved</li>
                        <li class="px-3 py-2 hover:bg-gray-50 dark:hover:bg-slate-800/70">Invoice #324 paid</li>
                        <li class="px-3 py-2 hover:bg-gray-50 dark:hover:bg-slate-800/70">Security tip: enable 2FA</li>
                    </ul>
                    <a href="#"
                        class="block px-3 py-2 text-xs text-indigo-600 dark:text-indigo-400 hover:underline">View
                        all</a>
                </div>
            </div>

            {{-- Profile --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open=!open"
                    class="ml-1 h-9 w-9 rounded-full bg-gradient-to-tr from-indigo-500 to-fuchsia-500
                       text-white grid place-items-center shadow">
                    <span class="text-xs font-bold">HS</span>
                </button>
                <div x-show="open" x-transition @click.outside="open=false"
                    class="absolute right-0 mt-2 w-56 rounded-xl border border-gray-200 dark:border-slate-800
                    bg-white dark:bg-[#0b1220] shadow-lg overflow-hidden text-sm">
                    <a href="#" class="block px-3 py-2 hover:bg-gray-50 dark:hover:bg-slate-800/70">My profile</a>
                    <a href="#" class="block px-3 py-2 hover:bg-gray-50 dark:hover:bg-slate-800/70">Billing</a>
                    <hr class="border-gray-200/70 dark:border-slate-800/70">
                    <form method="POST" action="#">
                        @csrf
                        <button class="w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-slate-800/70">Sign
                            out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Command Palette --}}
    <div x-show="openSearch" x-transition.opacity class="fixed inset-0 z-50" aria-modal="true" role="dialog">
        <div class="absolute inset-0" @click="openSearch=false"></div>
        <div
            class="relative mx-auto mt-24 w-[92%] max-w-xl rounded-2xl border border-gray-200 dark:border-slate-800
                bg-white dark:bg-[#0b1220] shadow-2xl overflow-hidden">
            <div class="flex items-center gap-2 px-3 h-12 border-b border-gray-200 dark:border-slate-800">
                <i data-lucide="search" class="h-4 w-4 opacity-80"></i>
                <input x-ref="search" id="global-search" type="text" placeholder="Type to search…"
                    class="flex-1 bg-transparent outline-none text-sm
                      placeholder-gray-400 dark:placeholder-slate-400 text-gray-900 dark:text-slate-100" />
                <button class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800" @click="openSearch=false">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
            <ul class="py-2 max-h-80 overflow-y-auto text-sm">
                <li class="px-3 py-2 hover:bg-gray-50 dark:hover:bg-slate-800/70 cursor-pointer">Domains → Portfolio
                </li>
                <li class="px-3 py-2 hover:bg-gray-50 dark:hover:bg-slate-800/70 cursor-pointer">Emails → Inboxes</li>
                <li class="px-3 py-2 hover:bg-gray-50 dark:hover:bg-slate-800/70 cursor-pointer">VPS → Instances</li>
            </ul>
        </div>
    </div>
</header>

<script>
    // Alpine state for search only (dark handled by Flowbite-style toggler below)
    function header() {
        return {
            openSearch: false,
            init() {
                window.addEventListener('keydown', (e) => {
                    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                        e.preventDefault();
                        this.openSearch = true;
                        this.$nextTick(() => this.$refs.search?.focus());
                    }
                    if (e.key === 'Escape' && this.openSearch) {
                        this.openSearch = false;
                    }
                });
            }
        }
    }
</script>

{{-- Flowbite-style theme toggle logic (no dependency besides Tailwind) --}}
<script>
    (function() {
        const btn = document.getElementById('theme-toggle');
        const sun = document.getElementById('theme-toggle-dark-icon'); // shown in dark
        const moon = document.getElementById('theme-toggle-light-icon'); // shown in light

        const stored = localStorage.getItem('color-theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const isDark = stored ? stored === 'dark' : prefersDark;

        document.documentElement.classList.toggle('dark', isDark);
        sun.classList.toggle('hidden', !isDark);
        moon.classList.toggle('hidden', isDark);

        btn?.addEventListener('click', () => {
            const nowDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('color-theme', nowDark ? 'dark' : 'light');
            sun.classList.toggle('hidden', !nowDark);
            moon.classList.toggle('hidden', nowDark);
        });
    })();
</script>

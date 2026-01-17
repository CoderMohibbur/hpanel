{{-- resources/views/components/sidebar.blade.php --}}
{{-- resources/views/components/hpanel/sidebar.blade.php --}}
<aside x-data="{
    open: false,
    setPad() {
        const pad = (this.open && window.innerWidth >= 1024) ? '16rem' : '0px';
        document.documentElement.style.setProperty('--sidebar-pad', pad);
        // mobile scroll lock only when overlayed
        document.body.classList.toggle('overflow-hidden', this.open && window.innerWidth < 1024);
    }
}" x-init="open = window.matchMedia('(min-width:1024px)').matches; // lg ও বড় স্ক্রিনে ডিফল্ট open
setPad();
$watch('open', () => setPad());
window.addEventListener('resize', setPad);
window.addEventListener('toggle-sidebar', () => { open = !open });
window.addEventListener('open-sidebar', () => { open = true });
window.addEventListener('close-sidebar', () => { open = false });">
    {{-- Backdrop same as before --}}
    <div x-show="open" x-transition.opacity class="fixed inset-0 z-40 bg-black/45 lg:hidden" @click="open=false"></div>

    {{-- Sidebar panel --}}
    <nav class="fixed z-50 inset-y-0 left-0 top-16
              w-72 lg:w-64 transform transition-transform duration-200
              bg-white/90 backdrop-blur
              border-r border-gray-200
              text-gray-900
              flex flex-col"
        :class="{ '-translate-x-full': !open }" @keydown.escape.window="open=false">

        {{-- Mobile top bar (h-12 = 3rem) --}}
        <div
            class="lg:hidden flex items-center justify-between px-3 h-12 border-b border-gray-200 shrink-0">
            <span class="font-semibold text-sm">Navigation</span>
            <button @click="open=false" class="p-2 rounded-lg hover:bg-gray-100"
                aria-label="Close sidebar">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>

        {{-- Body: now scrolls when menu is long --}}
        <div class="flex-1 min-h-0 px-3 py-3 overflow-y-auto pr-2 main-scroll overscroll-contain">

            {{-- Search (sidebar) – visible on all breakpoints & sticky --}}
            <div
                class="mb-3 sticky top-0 z-10 -mx-1 px-1 pt-1 pb-2
         bg-transparent border-b border-transparent">

                <div
                    class="group flex items-center gap-2 h-10 lg:h-11 px-3 rounded-xl lg:rounded-2xl
           bg-gray-50
            border border-gray-200
           text-gray-700
           
           transition
           focus-within:ring-2 focus-within:ring-indigo-400/40">

                    <i data-lucide="search" class="h-4 w-4 opacity-70"></i>

                    <input type="text" placeholder="Search menu…"
                        class="flex-1 bg-transparent outline-none text-sm
                  text-gray-900
                  placeholder-gray-400" />

                    {{-- desktop hint (optional) --}}
                    <kbd
                        class="hidden lg:inline ml-2 text-[10px] px-1 py-0.5 rounded
               border border-gray-300/60
               text-gray-500">/</kbd>
                </div>
            </div>




            @php
                $items = [
                    ['to' => '/', 'icon' => 'home', 'label' => 'Home', 'match' => 'dashboard'],
                    ['to' => '/hosting', 'icon' => 'layout-dashboard', 'label' => 'Websites', 'match' => 'websites*'],

                    [
                        'to' => '#',
                        'icon' => 'globe',
                        'label' => 'Domains',
                        'match' => 'domains*',
                        'children' => [
                            ['to' => route('domains.index'), 'label' => 'Domain portfolio'],
                            ['to' => route('domains.register'), 'label' => 'Get a New Domain'],
                            ['to' => route('domains.transfer'), 'label' => 'Transfers'],
                        ],
                    ],

                    ['to' => '/horizons', 'icon' => 'radar', 'label' => 'Horizons', 'match' => 'horizons*'],
                    ['to' => '/email', 'icon' => 'mail', 'label' => 'Emails', 'match' => 'emails*'],
                    ['to' => route('vps.index'), 'icon' => 'server', 'label' => 'VPS', 'match' => 'vps*'],
                    ['to' => '#', 'icon' => 'shield', 'label' => 'Dark web monitoring', 'match' => 'monitor*'],

                    [
                        'to' => '#',
                        'icon' => 'credit-card',
                        'label' => 'Billing',
                        'match' => 'billing*',
                        'children' => [
                            ['to' => '#', 'label' => 'Invoices'],
                            ['to' => '#', 'label' => 'Subscriptions'],
                            ['to' => '#', 'label' => 'Payment methods'],
                        ],
                    ],

                    [
                        'to' => '#',
                        'icon' => 'users',
                        'label' => 'Account Sharing',
                        'match' => 'account*',
                        'children' => [
                            ['to' => '#', 'label' => 'Users'],
                            ['to' => '#', 'label' => 'Invite user'],
                            ['to' => '#', 'label' => 'Roles & permissions'],
                        ],
                    ],
                ];
            @endphp

            <ul class="space-y-1 text-sm">
                @foreach ($items as $i)
                    @php
                        $active = isset($i['match']) ? request()->is($i['match']) : false;
                        $hasChildren = isset($i['children']);

                        $activeClass = $active
                            ? 'bg-indigo-500/10 text-indigo-700
                 border border-indigo-200/60
                 relative before:absolute before:left-0 before:top-1.5 before:h-7 before:w-1.5
                 before:rounded-full before:bg-indigo-500 before:dark:bg-indigo-400'
                            : 'border border-transparent';
                    @endphp

                    <li x-data="{ openItem: {{ $hasChildren && $active ? 'true' : 'false' }} }">
                        <a href="{{ $i['to'] }}"
                            class="group flex items-center gap-3 h-10 px-3 rounded-xl
                      hover:bg-gray-100 {{ $activeClass }}">
                            <i data-lucide="{{ $i['icon'] }}" class="h-5 w-5 opacity-75"></i>
                            <span class="flex-1 truncate">{{ $i['label'] }}</span>

                            @if ($hasChildren)
                                <button type="button" @click.prevent="openItem = !openItem"
                                    class="p-1 rounded-md hover:bg-gray-200/60
                               focus:outline-none focus:ring-2 focus:ring-indigo-400/50">
                                    <i x-show="!openItem" data-lucide="chevron-down" class="h-4 w-4"></i>
                                    <i x-show="openItem" data-lucide="chevron-up" class="h-4 w-4"></i>
                                </button>
                            @endif
                        </a>

                        @if ($hasChildren)
                            {{-- শুধু সাবমেনু স্ক্রলযোগ্য --}}
                            <ul x-show="openItem" x-collapse
                                class="ml-10 my-1 space-y-1 max-h-64 overflow-y-auto pr-1 sub-scroll
                         rounded-md bg-gray-50/70 border border-gray-200">
                                @foreach ($i['children'] as $c)
                                    <li>
                                        <a href="{{ $c['to'] }}" @click="$dispatch('close-sidebar')"
                                            class="block px-3 py-2 rounded-lg
                              hover:bg-white/70
                              focus:outline-none focus:ring-2 focus:ring-indigo-400/40">
                                            {{ $c['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>

            {{-- bottom padding so last dropdown never clips on small screens --}}
            <div class="h-4 lg:h-2"></div>
        </div>
    </nav>

    <style>
        /* Scrollbar theming */
        .main-scroll::-webkit-scrollbar {
            width: 10px
        }

        .main-scroll::-webkit-scrollbar-thumb {
            background: rgba(100, 116, 139, .25);
            border-radius: 8px
        }

        .main-scroll::-webkit-scrollbar-track {
            background: transparent
        }

        .sub-scroll::-webkit-scrollbar {
            height: 8px;
            width: 8px
        }

        .sub-scroll::-webkit-scrollbar-thumb {
            background: rgba(100, 116, 139, .35);
            border-radius: 8px
        }

        .sub-scroll::-webkit-scrollbar-track {
            background: transparent
        }

        @media (prefers-color-scheme: dark) {
            .main-scroll::-webkit-scrollbar-thumb {
                background: rgba(148, 163, 184, .25)
            }

            .sub-scroll::-webkit-scrollbar-thumb {
                background: rgba(148, 163, 184, .35)
            }
        }
    </style>
</aside>

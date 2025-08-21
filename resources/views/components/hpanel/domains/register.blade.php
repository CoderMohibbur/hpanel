{{-- resources/views/components/hpanel/domains/register.blade.php --}}
<x-hpanel-layout>
    <section x-data="registerPage()" x-init="init()" class="reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            {{-- Header + breadcrumb --}}
            <div class="mb-5">
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">Get a New Domain</h1>
                <div class="mt-1 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <a href="{{ route('domains.index') }}"
                        class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-slate-200">
                        <i data-lucide="arrow-left" class="h-4 w-4"></i> Back to portfolio
                    </a>
                </div>
            </div>

            {{-- Segmented tabs --}}
            <div class="mx-auto w-full sm:w-auto">
                <div
                    class="inline-flex rounded-2xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] p-1 relative">
                    <button @click="tab='find'" class="px-4 sm:px-5 h-10 rounded-xl text-sm font-medium transition"
                        :class="tab === 'find' ? 'bg-indigo-600 text-white shadow' : 'text-gray-700 dark:text-slate-200'">
                        Find new domain
                    </button>
                    <button @click="tab='ai'"
                        class="px-4 sm:px-5 h-10 rounded-xl text-sm font-medium transition relative"
                        :class="tab === 'ai' ? 'bg-indigo-600 text-white shadow' : 'text-gray-700 dark:text-slate-200'">
                        <span class="mr-1">✨</span> Generate domain using AI
                        {{-- pink ping dot (subtle) --}}
                        <span class="absolute -top-1 -right-1 inline-flex h-2.5 w-2.5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-fuchsia-500 opacity-60"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-fuchsia-500"></span>
                        </span>
                    </button>
                </div>
            </div>

            {{-- Card: search / AI --}}
            <div
                class="mt-4 rounded-2xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] p-4 sm:p-6">
                {{-- FIND tab --}}
                <form x-show="tab==='find'" x-transition method="GET" action="{{ route('domains.register') }}"
                    class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div
                            class="flex-1 flex items-center gap-2 h-12 px-3 rounded-xl
                      bg-gray-50 dark:bg-slate-800/80
                      border border-gray-200 dark:border-slate-700">
                            <i data-lucide="search" class="h-5 w-5 opacity-80"></i>
                            <input name="q" value="{{ $q ?? '' }}" x-ref="qinput" type="text"
                                placeholder="Enter your desired domain (e.g. mybrand)"
                                class="flex-1 bg-transparent outline-none text-sm text-gray-800 dark:text-slate-100
                          placeholder-gray-400 dark:placeholder-slate-400">
                        </div>
                        <button
                            class="h-12 px-5 rounded-xl text-sm font-semibold text-white
                         bg-gradient-to-r from-indigo-500 to-fuchsia-500
                         hover:from-indigo-600 hover:to-fuchsia-600 shadow">
                            Search
                        </button>
                    </div>

                    {{-- Quick TLDs with prices --}}
                    <div class="pt-2 grid grid-cols-2 sm:grid-cols-6 gap-2 text-sm">
                        @php
                            $tlds = [
                                ['.com', 'US$ 2.99'],
                                ['.net', 'US$ 11.99'],
                                ['.io', 'US$ 31.99'],
                                ['.org', 'US$ 7.99'],
                                ['.online', 'US$ 0.99'],
                                ['.shop', 'US$ 0.99'],
                            ];
                        @endphp
                        @foreach ($tlds as [$t, $price])
                            <button type="button" @click="appendTld('{{ $t }}')"
                                class="flex items-center justify-between px-3 py-2 rounded-xl border
                     border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/40
                     text-gray-800 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                                <span class="font-semibold">{{ $t }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $price }}</span>
                            </button>
                        @endforeach
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400">Type your desired domain name to check instant
                        availability.</p>
                </form>

                {{-- AI tab --}}
                <div x-show="tab==='ai'" x-transition class="space-y-4">
                    <div
                        class="rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/40 p-3">
                        <label class="text-sm text-gray-600 dark:text-gray-300">Describe your brand / niche</label>
                        <textarea x-model="prompt" rows="3" placeholder="e.g. Minimal clothing brand for eco-conscious Gen-Z"
                            class="mt-2 w-full rounded-lg bg-white dark:bg-[#0b1220] border border-gray-200 dark:border-slate-700
                           text-sm text-gray-800 dark:text-slate-100 outline-none p-3"></textarea>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="text-xs text-gray-500 dark:text-gray-400">We’ll suggest short, brandable .com
                                and alternates.</div>
                            <div class="flex items-center gap-2">
                                <button @click="generate()"
                                    class="h-10 px-4 rounded-xl text-sm font-semibold text-white
                                    bg-indigo-600 hover:bg-indigo-700 shadow">
                                    Generate ideas
                                </button>
                                <button @click="takeFirst()"
                                    class="h-10 px-3 rounded-xl text-sm font-semibold
                                    bg-gray-100 dark:bg-slate-800 text-gray-800 dark:text-slate-200">
                                    Use first idea
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- AI suggestions --}}
                    <div x-show="suggestions.length" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <template x-for="s in suggestions" :key="s">
                            <button @click="fillAndSearch(s)"
                                class="text-left px-3 py-3 rounded-xl border border-gray-200 dark:border-slate-700
                           bg-white dark:bg-[#0b1220] hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                                <div class="font-medium text-gray-900 dark:text-white" x-text="s + '.com'"></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Tap to search availability</div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Results --}}
            @if (!empty($q))
                <div
                    class="mt-6 rounded-2xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] overflow-hidden">
                    <div
                        class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-slate-800 flex items-center justify-between">
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            Results for: <span
                                class="font-semibold text-gray-900 dark:text-white">{{ $q }}</span>
                        </div>
                        <div class="hidden sm:block text-xs text-gray-500 dark:text-gray-400">Prices shown for the first
                            year.</div>
                    </div>

                    @if (empty($results))
                        <div class="px-6 py-10 text-center text-sm text-gray-600 dark:text-gray-300">No matches found.
                            Try another name or a different TLD.</div>
                    @else
                        <ul class="divide-y divide-gray-200 dark:divide-slate-800">
                            @foreach ($results as $row)
                                <li class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="font-medium text-gray-900 dark:text-white">{{ $row['domain'] }}</span>
                                            @if ($row['available'])
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px]
                                   bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300">
                                                    <i data-lucide="check" class="h-3.5 w-3.5"></i> Available
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px]
                                   bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300">
                                                    <i data-lucide="x-circle" class="h-3.5 w-3.5"></i> Taken
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Great for:
                                            brandable sites, SEO & email.</div>
                                    </div>

                                    <div class="sm:w-36 sm:text-right">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">US$
                                            {{ number_format($row['price'], 2) }}</div>
                                        <div class="text-[11px] text-gray-500 dark:text-gray-400">1st year</div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if ($row['available'])
                                            <button
                                                class="h-10 px-3 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow">
                                                Add to cart
                                            </button>
                                        @else
                                            <a href="https://who.is/whois/{{ urlencode($row['domain']) }}"
                                                target="_blank"
                                                class="h-10 px-3 rounded-xl text-sm font-semibold bg-gray-100 dark:bg-slate-800
                              text-gray-700 dark:text-slate-200 hover:bg-gray-200 dark:hover:bg-slate-700">
                                                WHOIS
                                            </a>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
        </div>

        {{-- Alpine helpers --}}
        <script>
            function registerPage() {
                return {
                    tab: 'find',
                    prompt: '',
                    suggestions: [],
                    init() {},
                    appendTld(t) {
                        const el = this.$refs.qinput;
                        if (!el) return;
                        const v = (el.value || '').replace(/\s+/g, '').replace(/\.+$/, '');
                        el.value = v ? v + t : ('yourbrand' + t);
                        el.focus();
                    },
                    generate() {
                        const base = (this.prompt || 'green threads').toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim();
                        const seeds = base ? base.split(/\s+/).slice(0, 3).join('') : 'brand';
                        const pool = ['hub', 'ly', 'nest', 'base', 'works', 'forge', 'wave', 'craft', 'studio', 'stack', 'loop',
                            'grid', 'spark'
                        ];
                        const rnd = () => pool[Math.floor(Math.random() * pool.length)];
                        this.suggestions = [
                            seeds + rnd(),
                            rnd() + seeds,
                            seeds + '-' + rnd(),
                            seeds.slice(0, 8) + rnd(),
                            rnd() + '-' + seeds
                        ].map(s => s.replace(/--+/g, '-'));
                    },
                    takeFirst() {
                        if (!this.suggestions.length) this.generate();
                        this.fillAndSearch(this.suggestions[0] || 'mybrand');
                    },
                    fillAndSearch(s) {
                        const el = this.$refs.qinput;
                        if (!el) return;
                        el.value = s + '.com';
                        // submit the find form (first form on card)
                        this.tab = 'find';
                        setTimeout(() => {
                            el.form?.submit();
                        }, 50);
                    }
                }
            }
        </script>
    </section>
</x-hpanel-layout>

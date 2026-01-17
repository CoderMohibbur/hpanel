{{-- components/hpanel/home.blade.php --}}
<x-hpanel-layout>
    <section x-data="themeSwitcher()" x-init="init()" :class="{ 'dark': isDark }">

        {{-- Only ripple/float keyframes kept; reveal handled by main layout --}}
        <style>
            @keyframes float {
                from {
                    transform: translateY(0)
                }

                50% {
                    transform: translateY(-6px)
                }

                to {
                    transform: translateY(0)
                }
            }

            /* Touch ripple */
            .ripple {
                position: relative;
                overflow: hidden
            }

            .ripple:after {
                content: '';
                position: absolute;
                inset: auto;
                width: 0;
                height: 0;
                border-radius: 9999px;
                background: rgba(255, 255, 255, .35);
                transform: translate(-50%, -50%);
                pointer-events: none;
                opacity: 0
            }

            .ripple:active:after {
                left: var(--x);
                top: var(--y);
                opacity: 1;
                width: 200px;
                height: 200px;
                transition: width .4s ease, height .4s ease, opacity .8s ease;
                opacity: 0
            }
        </style>



        <div class="min-h-[88vh] pb-2 bg-gradient-to-b from-white to-gray-50">
            <!-- HERO -->
            <div class="relative overflow-hidden">
                <!-- subtle blobs -->
                <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                    <div
                        class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-gradient-to-tr from-indigo-400/15 to-fuchsia-400/15 blur-3xl animate-[float_18s_ease-in-out_infinite]">
                    </div>
                    <div
                        class="absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-gradient-to-tr from-emerald-400/15 to-cyan-400/15 blur-3xl animate-[float_22s_ease-in-out_infinite]">
                    </div>
                </div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 md:pt-16">
                    <div class="grid md:grid-cols-2 gap-8 items-center">
                        <div class="reveal">
                            <span
                                class="inline-flex items-center gap-2 text-xs font-semibold px-2.5 py-1.5 rounded-full
                           bg-indigo-50 text-indigo-700 border border-indigo-100">
                                NEW <span class="opacity-70">Build in minutes</span>
                            </span>
                            <h1
                                class="mt-4 text-3xl md:text-5xl font-bold tracking-tight text-gray-900">
                                Go from idea to a <span
                                    class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-fuchsia-600">web
                                    app</span> in minutes
                            </h1>
                            <p class="mt-4 text-gray-600 max-w-xl">
                                Chat with AI to scaffold your site fast—no code needed. Clean UI, blazing speed, and
                                delightful touches.
                            </p>

                            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                <a href="#plans" @pointerdown="ripple($event)"
                                    class="ripple inline-flex justify-center items-center px-5 py-3 rounded-xl
                          bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-lg">
                                    Try now
                                </a>
                                <a href="#domain"
                                    class="inline-flex justify-center items-center px-5 py-3 rounded-xl
                          bg-white border border-gray-200
                          text-gray-800 hover:shadow">
                                    Explore features
                                </a>
                            </div>

                            <div class="mt-6 flex items-center gap-6 text-sm text-gray-500">
                                <div class="flex -space-x-2">
                                    <img class="h-8 w-8 rounded-full ring-2 ring-white"
                                        src="https://i.pravatar.cc/64?img=12" alt="">
                                    <img class="h-8 w-8 rounded-full ring-2 ring-white"
                                        src="https://i.pravatar.cc/64?img=5" alt="">
                                    <img class="h-8 w-8 rounded-full ring-2 ring-white"
                                        src="https://i.pravatar.cc/64?img=9" alt="">
                                </div>
                                <span>10k+ happy builders</span>
                            </div>
                        </div>




                        <!-- Preview Card -->
                        <div class="reveal">
                            <div
                                class="relative rounded-2xl border border-gray-200
              bg-white/70 backdrop-blur p-4 md:p-6 mb-5 sm:mb-5 md:mb-5 shadow-xl
              overflow-hidden">
                                <!-- ← যোগ করুন -->

                                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1400&auto=format&fit=crop"
                                    class="rounded-xl object-cover aspect-[16/10]">

                                <!-- ভেতরের কোণায় রাখুন -->
                                <div
                                    class="absolute bottom-4 left-4 bg-white
                border border-gray-200 rounded-xl shadow
                p-3 w-52">
                                    <div class="text-xs text-gray-500">Live components</div>
                                    <div
                                        class="mt-1 h-2.5 w-40 bg-gradient-to-r from-indigo-500 to-fuchsia-500 rounded-full animate-pulse">
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <!-- PRICING STRIP -->
            <section id="plans" class="reveal mt-14">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid lg:grid-cols-3 gap-6">
                        <div
                            class="lg:col-span-2 rounded-2xl bg-white border border-gray-200 p-6 shadow hover:shadow-lg transition">
                            <div class="flex items-center justify-between gap-6 flex-wrap">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Website Builder &
                                        Web Hosting</h3>
                                    <p class="text-sm text-gray-600 mt-1">Get your site online
                                        quickly.</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl md:text-4xl font-extrabold text-gray-900">
                                        US$ 2.99<span
                                            class="text-base font-medium text-gray-500">/mo</span>
                                    </div>
                                    <div class="text-xs text-gray-500">Renews at US$ 10.99/mo</div>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="#" @pointerdown="ripple($event)"
                                    class="ripple inline-flex items-center justify-center w-full sm:w-auto px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow">
                                    Explore plans
                                </a>
                            </div>
                        </div>

                        <div
                            class="rounded-2xl bg-gradient-to-br from-indigo-600 to-fuchsia-600 text-white p-6 shadow-lg">
                            <div class="text-sm opacity-90">SAVE 75%</div>
                            <div class="mt-2 text-2xl font-bold">Limited-time Offer</div>
                            <ul class="mt-4 space-y-2 text-sm">
                                <li>• Free SSL & CDN</li>
                                <li>• 24/7 Support</li>
                                <li>• One-click deploy</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- DOMAIN SEARCH -->
            <section id="domain" class="reveal py-14">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div
                        class="rounded-2xl bg-white border border-gray-200 p-6 shadow">
                        <div class="flex items-center justify-between gap-4 flex-wrap">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Start with a domain name
                                </h3>
                                <p class="text-sm text-gray-600">Find the perfect domain instantly.
                                </p>
                            </div>
                            <form class="flex-1 min-w-[260px]" @submit.prevent>
                                <div
                                    class="flex rounded-xl border border-gray-200 overflow-hidden">
                                    <input type="text" placeholder="Enter your desired domain"
                                        class="w-full px-4 py-3 bg-transparent focus:outline-none text-gray-800 placeholder-gray-400">
                                    <button @pointerdown="ripple($event)"
                                        class="ripple px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold">
                                        Search
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="mt-4 text-sm text-gray-500">
                            Already have a domain? <a href="#"
                                class="text-indigo-600 hover:underline">Transfer it</a>
                            or <a href="#" class="text-indigo-600 hover:underline">connect
                                hosting</a>.
                        </div>
                    </div>
                </div>
            </section>

            <!-- FEATURES -->
            <section class="reveal pb-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid md:grid-cols-3 gap-6">
                        @php
                            $features = [
                                [
                                    'title' => 'AI Site Wizard',
                                    'desc' => 'Describe your idea, get a ready layout instantly.',
                                    'icon' => '⚡',
                                ],
                                [
                                    'title' => 'Responsive by default',
                                    'desc' => 'Pixel-perfect on phone, tablet & desktop.',
                                    'icon' => '📱',
                                ],
                                [
                                    'title' => 'Superfast',
                                    'desc' => 'Optimized Tailwind + cache friendly assets.',
                                    'icon' => '🚀',
                                ],
                            ];
                        @endphp
                        @foreach ($features as $f)
                            <div
                                class="group rounded-2xl border border-gray-200 bg-white p-6 shadow hover:shadow-xl transition will-change-transform hover:-translate-y-0.5">
                                <div class="text-3xl">{{ $f['icon'] }}</div>
                                <h4 class="mt-3 font-semibold text-gray-900">{{ $f['title'] }}</h4>
                                <p class="mt-2 text-sm text-gray-600">{{ $f['desc'] }}</p>
                                <div
                                    class="mt-4 h-1 w-0 group-hover:w-full transition-all bg-gradient-to-r from-indigo-500 to-fuchsia-500 rounded-full">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>

        {{-- Scripts: only theme + ripple (reveal is handled by main layout) --}}
        <script>
            function themeSwitcher() {
                return {
                    isDark: false,
                    init() {
                        this.isDark = localStorage.getItem('theme') === 'dark' ||
                            window.matchMedia('(prefers-color-scheme: dark)').matches;

                        // ripple pointer coordinates
                        document.querySelectorAll('.ripple').forEach(btn => {
                            btn.addEventListener('pointerdown', (ev) => {
                                btn.style.setProperty('--x', (ev.offsetX || 0) + 'px');
                                btn.style.setProperty('--y', (ev.offsetY || 0) + 'px');
                            });
                        });
                    },
                    toggle() {
                        this.isDark = !this.isDark;
                        localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                    }
                }
            }
        </script>
    </section>
</x-hpanel-layout>

{{-- resources/views/components/hpanel/hosting.blade.php --}}
<x-hpanel-layout>
    <section x-data="hostingPage()" x-init="init()" class="reveal">

        <style>
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

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            {{-- Heading --}}
            <div class="text-center">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Choose Your Web Hosting Plan
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Fit to scale with a 30-day money-back guarantee.</p>
            </div>

            {{-- Category tabs --}}
            <div class="mt-6 flex items-center justify-center gap-2 text-sm">
                <template x-for="k in ['web','cloud','agency']" :key="k">
                    <button
                        class="px-4 py-1.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-[#0b1220] text-gray-700 dark:text-slate-200 transition"
                        :class="tab === k ? 'ring-2 ring-indigo-400/60 font-semibold' :
                            'hover:bg-gray-50 dark:hover:bg-slate-800'"
                        x-text="labels[k]" @click="tab=k"></button>
                </template>
            </div>

            {{-- Billing toggle --}}
            <div class="mt-6 flex items-center justify-center gap-4">
                <span class="text-sm text-gray-600 dark:text-gray-400">Billing:</span>
                <div
                    class="inline-flex rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-[#0b1220] p-1">
                    <button @click="cycle='monthly'" class="px-3 py-1.5 text-sm rounded-lg"
                        :class="cycle === 'monthly' ? 'bg-indigo-600 text-white shadow' : 'text-gray-700 dark:text-slate-200'">Monthly</button>
                    <button @click="cycle='yearly'" class="px-3 py-1.5 text-sm rounded-lg"
                        :class="cycle === 'yearly' ? 'bg-indigo-600 text-white shadow' : 'text-gray-700 dark:text-slate-200'">Yearly
                        <span class="ml-1 opacity-80">(save 20%)</span></button>
                </div>
            </div>


            {{-- Plans grid --}}
            <div class="mt-8 grid lg:grid-cols-3 gap-6">
                <template x-for="p in plans[tab]" :key="p.slug">
                    <div class="reveal">
                        <!-- Accent plan gets gradient ring -->
                        <div class="group/card rounded-2xl"
                            :class="p.accent ?
                                'p-[1px] bg-gradient-to-r from-indigo-500/60 to-fuchsia-500/60' :
                                'p-[1px] bg-transparent'">

                            <div
                                class="relative h-full rounded-xl
                    bg-white/90 dark:bg-[#0b1220]/90 backdrop-blur-sm
                    border border-gray-200/70 dark:border-slate-800/70
                    shadow-md transition-all duration-300
                    group-hover/card:shadow-xl group-hover/card:-translate-y-0.5 overflow-hidden">

                                <!-- soft top glow (only visible on hover/accent) -->
                                <div class="absolute inset-x-0 top-0 h-1
                      bg-gradient-to-r from-indigo-500/40 to-fuchsia-500/40
                      opacity-0 group-hover/card:opacity-100 transition duration-300"
                                    :class="p.accent ? 'opacity-100' : ''"></div>

                                <!-- badge (inside card, no border overlap) -->
                                <template x-if="p.tag">
                                    <span
                                        class="absolute top-3 right-3 inline-flex items-center gap-1 px-2.5 py-1
                     rounded-full text-[11px] font-semibold
                     bg-indigo-600 text-white shadow">
                                        <span x-text="p.tag"></span>
                                    </span>
                                </template>

                                <div class="p-5" x-data="{ openFeat: false }">
                                    <h3 class="font-semibold text-gray-900 dark:text-white" x-text="p.name"></h3>

                                    <div class="mt-2 flex items-end gap-2">
                                        <div class="text-3xl font-extrabold text-gray-900 dark:text-white">
                                            US$ <span x-text="priceForCard(p)"></span>
                                        </div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">/
                                            <span x-text="cycle==='monthly' ? 'mo' : 'yr'"></span>
                                        </span>

                                        <!-- small billing chip -->
                                        <span
                                            class="ml-auto text-[11px] px-2 py-0.5 rounded-full
                       bg-gray-100 text-gray-600
                       dark:bg-slate-800 dark:text-slate-300">
                                            <span
                                                x-text="cycle==='monthly' ? 'Billed monthly' : 'Billed yearly'"></span>
                                        </span>
                                    </div>

                                    <!-- CTA -->
                                    <button @pointerdown="ripple($event)" @click="openCheckout(p)"
                                        class="ripple mt-4 w-full h-11 rounded-xl font-semibold tracking-tight
                     bg-gradient-to-r from-indigo-600 to-fuchsia-600
                     hover:from-indigo-600/90 hover:to-fuchsia-600/90
                     text-white shadow-lg shadow-indigo-600/10 transition">
                                        Select
                                    </button>

                                    <!-- divider -->
                                    <div class="my-4 border-t border-gray-200/70 dark:border-slate-800/70"></div>

                                    <!-- Features: collapsible -->
                                    <div class="relative transition-all"
                                        :class="openFeat ? '' : 'max-h-40 overflow-hidden'">
                                        <ul class="space-y-2 text-sm">
                                            <template x-for="f in p.features" :key="f">
                                                <li class="flex items-start gap-2">
                                                    <!-- inline check icon -->
                                                    <svg class="h-4 w-4 text-emerald-500 mt-0.5" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m5 13 4 4L19 7" />
                                                    </svg>
                                                    <span class="text-gray-700 dark:text-slate-200"
                                                        x-text="f"></span>
                                                </li>
                                            </template>
                                        </ul>

                                        <!-- fade overlay when collapsed -->
                                        <div x-show="!openFeat"
                                            class="pointer-events-none absolute inset-x-0 bottom-0 h-10
                          bg-gradient-to-t from-white/95 dark:from-[#0b1220]/95 to-transparent">
                                        </div>
                                    </div>

                                    <!-- Toggle -->
                                    <button @click.prevent="openFeat = !openFeat"
                                        class="mt-4 inline-flex items-center gap-1 text-xs font-medium
                     text-indigo-600 dark:text-indigo-400 hover:underline">
                                        <svg x-show="!openFeat" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" />
                                        </svg>
                                        <svg x-show="openFeat" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5" />
                                        </svg>
                                        <span x-show="!openFeat">Show all features</span>
                                        <span x-show="openFeat">Hide features</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>


            {{-- Trust strip --}}
            <div
                class="reveal mt-8 rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] p-4 sm:p-6">
                <div class="grid md:grid-cols-4 gap-6 items-center text-sm">
                    <div class="text-gray-700 dark:text-slate-200">Excellent</div>
                    <div class="text-gray-600 dark:text-gray-300">Blazing speed & 99.9% uptime SLA</div>
                    <div class="text-gray-600 dark:text-gray-300">Free SSL, CDN & malware protection</div>
                    <div class="text-gray-600 dark:text-gray-300">24/7 priority support</div>
                </div>
            </div>

            {{-- FAQ --}}
            <div class="reveal mt-14">
                <div class="mx-auto max-w-7xl">
                    <h2 class="text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Hosting Plan FAQ
                    </h2>
                    <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-300">
                        Short, clear answers to the most common questions.
                    </p>

                    <div
                        class="mt-6 rounded-xl border border-gray-200/70 dark:border-slate-800/70
             bg-white/90 dark:bg-[#0b1220]/90 backdrop-blur-sm shadow-sm">

                        @php
                            $faqs = [
                                [
                                    'q' => 'Why do I need a hosting plan?',
                                    'a' =>
                                        'Your website lives on a server so people can access it from anywhere. Our plans deliver performance, security and easy scaling.',
                                ],
                                [
                                    'q' => 'I already have a website. Can I migrate it?',
                                    'a' => 'Yes, guided migration tools + help for common stacks (CMS, Laravel, WP).',
                                ],
                                [
                                    'q' => 'Can I upgrade my plan later?',
                                    'a' =>
                                        'Absolutely. Upgrade anytime with zero downtime—billing prorates automatically.',
                                ],
                                [
                                    'q' => 'Do you offer backups?',
                                    'a' => 'Daily or weekly backups depending on plan, with one-click restore.',
                                ],
                            ];
                        @endphp

                        @foreach ($faqs as $f)
                            <details
                                class="group border-b border-gray-200/70 dark:border-slate-800/70 last:border-0
               transition-colors">
                                <summary
                                    class="flex items-start gap-3 px-5 sm:px-6 py-4 cursor-pointer select-none
                 hover:bg-gray-50/80 dark:hover:bg-slate-900/60
                 group-open:bg-indigo-50/40 dark:group-open:bg-indigo-500/5
                 group-open:border-indigo-200/70 dark:group-open:border-indigo-600/40">

                                    <!-- leading bubble icon -->
                                    <span
                                        class="mt-0.5 inline-grid place-items-center h-7 w-7 rounded-full
                   bg-indigo-50 text-indigo-700
                   dark:bg-indigo-500/10 dark:text-indigo-300">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 10a4 4 0 1 1 8 0c0 3-4 3-4 6m0 4h.01" />
                                        </svg>
                                    </span>

                                    <span class="flex-1 font-medium text-gray-900 dark:text-white">
                                        {{ $f['q'] }}
                                    </span>

                                    <!-- chevron -->
                                    <span
                                        class="ml-3 shrink-0 grid place-items-center h-6 w-6 rounded-full
                   border border-gray-300/70 dark:border-slate-700/70
                   text-gray-600 dark:text-slate-300 transition-transform
                   group-open:rotate-180">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                                        </svg>
                                    </span>
                                </summary>

                                <!-- smooth expand -->
                                <div
                                    class="px-5 sm:px-6 pb-4 pt-0 grid transition-all duration-300 ease-out
                    grid-rows-[0fr] group-open:grid-rows-[1fr]">
                                    <div class="overflow-hidden">
                                        <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300">
                                            {{ $f['a'] }}
                                        </p>
                                    </div>
                                </div>
                            </details>
                        @endforeach
                    </div>

                    <div class="mt-5 text-center text-xs text-gray-500 dark:text-gray-400">
                        Still have questions?
                        <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Contact
                            support</a>
                    </div>
                </div>
            </div>

        </div>

        {{-- ===== Checkout Modal (teleported to <body>) ===== --}}
        <template x-teleport="body">
            <div x-show="checkout.open" @keydown.escape.window="closeCheckout()"
                class="fixed inset-0 z-[90] flex items-start justify-center
              pt-26
               sm:pt-26 md:pt-20 p-4 sm:p-6">
                {{-- <-- একটু নিচে নামানোর জন্য top padding --}}

                {{-- Backdrop --}}
                <div x-transition.opacity class="absolute inset-0 bg-black/55 backdrop-blur-[2px]"
                    @click="closeCheckout()"></div>

                {{-- Modal panel --}}
                <div x-trap.noscroll="checkout.open" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-6 scale-[0.98]"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-2 scale-[0.98]" role="dialog" aria-modal="true"
                    class="relative w-full max-w-3xl max-h-[82vh] overflow-y-auto
                rounded-xl border border-gray-200 dark:border-slate-800
                bg-white dark:bg-[#0b1220] shadow-2xl ring-1 ring-black/5 dark:ring-white/5">

                    {{-- Header (sticky) --}}
                    <div
                        class="sticky top-0 z-10 px-5 sm:px-6 py-4
                  border-b border-gray-200 dark:border-slate-800
                  bg-white/95 dark:bg-[#0b1220]/95 backdrop-blur
                  flex items-start gap-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white"
                                x-text="checkout.plan?.name || ''"></h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Choose a billing period
                            </p>
                        </div>
                        <button class="shrink-0 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800"
                            @click="closeCheckout()" aria-label="Close">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="p-5 sm:p-6 space-y-3">
                        <template x-for="opt in checkout.options" :key="opt.months">
                            <label
                                class="block rounded-xl border-2 cursor-pointer transition
                   border-gray-200 dark:border-slate-800
                   hover:border-indigo-300 dark:hover:border-indigo-500/50
                   hover:bg-gray-50/60 dark:hover:bg-slate-900/40"
                                :class="checkout.months === opt.months ?
                                    'border-indigo-500 ring-2 ring-indigo-400/30 bg-indigo-50/40 dark:bg-indigo-500/5' :
                                    ''">
                                <div class="flex items-center gap-3 p-3">
                                    <input type="radio" class="accent-indigo-600" :value="opt.months"
                                        x-model.number="checkout.months">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm text-gray-900 dark:text-white"
                                                x-text="opt.months + ' months'"></span>
                                            <template x-if="opt.save > 0">
                                                <span
                                                    class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                             bg-indigo-100 text-indigo-700
                             dark:bg-indigo-500/15 dark:text-indigo-300"
                                                    x-text="'SAVE ' + Math.round(opt.save) + '%'"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs line-through text-gray-400"
                                            x-text="'US$ ' + opt.rack.toFixed(2)"></div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white"
                                            x-text="'US$ ' + opt.perMonth.toFixed(2) + '/mo'"></div>
                                    </div>
                                </div>
                            </label>
                        </template>

                        <div
                            class="mt-2 rounded-xl border border-gray-200 dark:border-slate-800
                 divide-y divide-gray-200 dark:divide-slate-800">
                            <div class="flex items-center justify-between px-3 py-2 text-sm">
                                <span class="text-gray-600 dark:text-gray-300">Expiration date</span>
                                <span class="text-gray-900 dark:text-white" x-text="expiryDate()"></span>
                            </div>
                            <div class="flex items-center justify-between px-3 py-2 text-sm">
                                <span class="text-gray-600 dark:text-gray-300">
                                    Subtotal
                                    <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Add
                                        coupon code</a>
                                </span>
                                <span class="text-gray-900 dark:text-white"
                                    x-text="'US$ ' + subtotal().toFixed(2)"></span>
                            </div>
                            <div class="flex items-center justify-between px-3 py-2 text-sm font-semibold">
                                <span class="text-gray-900 dark:text-white">Total</span>
                                <span class="text-gray-900 dark:text-white"
                                    x-text="'US$ ' + subtotal().toFixed(2)"></span>
                            </div>
                        </div>

                        {{-- Footer (sticky) --}}
                        <div
                            class="sticky bottom-0 bg-white/95 dark:bg-[#0b1220]/95 backdrop-blur
                    -mx-5 sm:-mx-6 px-5 sm:px-6 pt-3 pb-4 flex items-center justify-end gap-3">
                            <button
                                class="px-4 h-10 rounded-xl text-sm
                   bg-gray-100 hover:bg-gray-200 text-gray-900
                   dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-100"
                                @click="closeCheckout()">Cancel</button>

                            <button
                                class="px-4 h-10 rounded-xl text-sm text-white shadow
                   bg-gradient-to-r from-indigo-600 to-fuchsia-600
                   hover:from-indigo-700 hover:to-fuchsia-700"
                                @click="goToPayment()">Choose payment method</button>
                        </div>

                        <p class="text-[11px] text-gray-500 dark:text-gray-400">
                            By checking out, you agree with our Terms of Service and Privacy Policy. You can cancel
                            recurring payments any time.
                        </p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">
                            Renews at <span x-text="'US$ ' + renewPrice().toFixed(2) + '/mo'"></span>
                            for <span x-text="renewMonths()"></span>.
                        </p>
                    </div>
                </div>
            </div>
        </template>




        {{-- Alpine core --}}
        <script>
            function hostingPage() {
                const discount = 0.8; // yearly (card) save 20%
                return {
                    tab: 'web',
                    cycle: 'monthly',
                    labels: {
                        web: 'Web Hosting',
                        cloud: 'Cloud Hosting',
                        agency: 'Agency Hosting'
                    },

                    plans: {
                        web: [{
                                slug: 'single',
                                name: 'Single Web Hosting',
                                tag: null,
                                accent: false,
                                price_m: 1.99,
                                rack: 9.99,
                                features: ['1 website', '50 GB SSD storage', 'Free SSL', 'Unlimited bandwidth',
                                    'Weekly backups', 'Malware scanner', 'Managed WordPress'
                                ]
                            },
                            {
                                slug: 'premium',
                                name: 'Premium Web Hosting',
                                tag: 'MOST POPULAR',
                                accent: true,
                                price_m: 2.99,
                                rack: 12.99,
                                features: ['100 websites', '100 GB NVMe storage', 'Free domain (1st year)',
                                    'Free SSL & CDN', 'Daily backups', 'Staging & Git', 'Priority support'
                                ]
                            },
                            {
                                slug: 'business',
                                name: 'Business Web Hosting',
                                tag: null,
                                accent: false,
                                price_m: 3.99,
                                rack: 13.99,
                                features: ['300 websites', '200 GB NVMe storage', 'Free domain (1st year)',
                                    'Dedicated resources', 'Unlimited databases', 'Advanced security suite'
                                ]
                            },
                        ],
                        cloud: [{
                                slug: 'cloud-starter',
                                name: 'Cloud Starter',
                                tag: null,
                                accent: false,
                                price_m: 5.99,
                                rack: 14.99,
                                features: ['2 vCPU', '3 GB RAM', '100 GB NVMe', 'Built-in CDN', 'Daily backups',
                                    'Isolated resources'
                                ]
                            },
                            {
                                slug: 'cloud-pro',
                                name: 'Cloud Pro',
                                tag: 'BEST VALUE',
                                accent: true,
                                price_m: 9.99,
                                rack: 24.99,
                                features: ['4 vCPU', '8 GB RAM', '200 GB NVMe', 'Autoscaling', 'Priority support',
                                    'Staging & Git'
                                ]
                            },
                            {
                                slug: 'cloud-max',
                                name: 'Cloud Max',
                                tag: null,
                                accent: false,
                                price_m: 19.99,
                                rack: 39.99,
                                features: ['8 vCPU', '16 GB RAM', '400 GB NVMe', 'Advanced WAF', 'Dedicated IP',
                                    'Uptime SLA 99.99%'
                                ]
                            },
                        ],
                        agency: [{
                                slug: 'agency-lite',
                                name: 'Agency Lite',
                                tag: null,
                                accent: false,
                                price_m: 7.99,
                                rack: 19.99,
                                features: ['25 client sites', 'White-label panel', 'Team roles', 'Monthly reports',
                                    'One-click staging'
                                ]
                            },
                            {
                                slug: 'agency-plus',
                                name: 'Agency Plus',
                                tag: 'AGENCY PICK',
                                accent: true,
                                price_m: 14.99,
                                rack: 34.99,
                                features: ['100 client sites', 'Priority migrations', 'White-label billing', 'SLA support',
                                    'Bulk updates'
                                ]
                            },
                            {
                                slug: 'agency-elite',
                                name: 'Agency Elite',
                                tag: null,
                                accent: false,
                                price_m: 29.99,
                                rack: 59.99,
                                features: ['Unlimited sites', 'Dedicated manager', 'Custom SLAs', 'Audit & hardening',
                                    'Advanced analytics'
                                ]
                            },
                        ]
                    },

                    priceForCard(p) {
                        const yearly = p.price_m * 12 * 0.8; // 20% off
                        return (this.cycle === 'monthly' ? p.price_m : yearly).toFixed(2);
                    },
                    monthLabel(n) {
                        return n === 1 ? '1 month' : `${n} months`;
                    },
                    periodOptions(plan) {
                        // 48(best), 24, 12, 1 (rack)
                        const mk = (m, per) => ({
                            months: m,
                            perMonth: per,
                            rack: plan.rack,
                            save: (1 - per / plan.rack) * 100
                        });
                        return [
                            mk(48, plan.price_m),
                            mk(24, +(plan.price_m * 1.15).toFixed(2)),
                            mk(12, +(plan.price_m * 1.5).toFixed(2)),
                            mk(1, plan.rack),
                        ];
                    },


                    // ===== Card helpers =====
                    priceForCard(p) {
                        const yearly = p.price_m * 12 * discount;
                        return (this.cycle === 'monthly' ? p.price_m : yearly).toFixed(2);
                    },

                    // ===== Checkout modal state =====
                    checkout: {
                        open: false,
                        plan: null,
                        months: 48,
                        options: []
                    },

                    periodOptions(plan) {
                        // tiers: 48 (best), 24, 12, 1(rack)
                        const p48 = plan.price_m;
                        const p24 = +(plan.price_m * 1.15).toFixed(2);
                        const p12 = +(plan.price_m * 1.5).toFixed(2);
                        const p01 = plan.rack;
                        const mk = (m, per) => ({
                            months: m,
                            perMonth: per,
                            rack: plan.rack,
                            save: (1 - per / plan.rack) * 100
                        });
                        return [mk(48, p48), mk(24, p24), mk(12, p12), mk(1, p01)];
                    },

                    openCheckout(plan) {
                        this.checkout.plan = plan;
                        this.checkout.options = this.periodOptions(plan);
                        this.checkout.months = this.checkout.options[0].months;
                        this.checkout.open = true;
                        document.body.classList.add('overflow-hidden');
                    },
                    closeCheckout() {
                        this.checkout.open = false;
                        document.body.classList.remove('overflow-hidden');
                    },

                    // computed pricing in modal
                    selectedOpt() {
                        return this.checkout.options.find(o => o.months === this.checkout.months) || this.checkout.options[0];
                    },
                    subtotal() {
                        const o = this.selectedOpt();
                        return o.perMonth * o.months;
                    },
                    renewPrice() {
                        return this.checkout.plan ? this.checkout.plan.rack : 0;
                    },
                    renewMonths() {
                        return this.checkout.months === 1 ? '1 month' : '1 year';
                    }, // tweak as you like

                    expiryDate() {
                        const addM = this.checkout.months || 1;
                        const d = new Date();
                        const y = d.getFullYear(),
                            m = d.getMonth() + addM,
                            day = d.getDate();
                        const out = new Date(y, m, day);
                        return out.toISOString().slice(0, 10);
                    },

                    goToPayment() {
                        const slug = this.checkout.plan?.slug || '';
                        const months = this.checkout.months || 1;
                        // 👉 চাইলে এখানে route() ব্যবহার করো
                        window.location.href = `/checkout?plan=${encodeURIComponent(slug)}&months=${months}`;
                    },

                    // utils
                    init() {
                        document.querySelectorAll('.ripple').forEach(btn => {
                            btn.addEventListener('pointerdown', (ev) => {
                                btn.style.setProperty('--x', (ev.offsetX || 0) + 'px');
                                btn.style.setProperty('--y', (ev.offsetY || 0) + 'px');
                            });
                        });
                    }
                }
            }
        </script>
    </section>
</x-hpanel-layout>

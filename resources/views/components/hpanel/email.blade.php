{{-- resources/views/components/hpanel/email.blade.php --}}
<x-hpanel-layout>
<section x-data="emailPlans()" x-init="init()" class="reveal">

  {{-- tiny ripple for click/touch feedback --}}
  <style>
    .ripple{position:relative;overflow:hidden}
    .ripple:after{content:'';position:absolute;inset:auto;width:0;height:0;border-radius:9999px;background:rgba(255,255,255,.35);transform:translate(-50%,-50%);pointer-events:none;opacity:0}
    .ripple:active:after{left:var(--x);top:var(--y);opacity:1;width:200px;height:200px;transition:width .4s ease,height .4s ease,opacity .8s ease;opacity:0}
  </style>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Heading --}}
    <div class="text-center">
      <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
        Select Your Email Plan
      </h1>
      <p class="mt-2 text-gray-600 dark:text-gray-300">
        Branded mailboxes with robust security & migration support.
      </p>
    </div>

    {{-- Billing period dropdown --}}
    <div class="mt-6 flex items-center justify-center gap-3">
      <label class="text-sm text-gray-600 dark:text-gray-400">Choose billing period</label>
      <div class="relative">
        <select x-model.number="months" @change="onMonthsChange()"
                class="peer appearance-none pl-3 pr-8 py-2 text-sm rounded-xl border
                       border-gray-200 dark:border-slate-700 bg-white dark:bg-[#0b1220]
                       text-gray-800 dark:text-slate-100">
          <option value="48">48 months</option>
          <option value="24">24 months</option>
          <option value="12">12 months</option>
          <option value="1">1 month</option>
        </select>
        <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 h-4 w-4
                    text-gray-500 dark:text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
        </svg>
      </div>
      <span class="text-xs text-gray-500 dark:text-gray-400">(<span x-text="monthsLabel"></span>)</span>
    </div>

    {{-- Plans grid --}}
    <div class="mt-8 grid lg:grid-cols-3 gap-6">
      <template x-for="p in plans" :key="p.slug">
        <div class="reveal">
          <div class="group/card rounded-2xl"
               :class="p.accent ? 'p-[1px] bg-gradient-to-r from-indigo-500/60 to-fuchsia-500/60' : 'p-[1px] bg-transparent'">
            <div class="relative h-full rounded-xl
                        bg-white/90 dark:bg-[#0b1220]/90 backdrop-blur-sm
                        border border-gray-200/70 dark:border-slate-800/70
                        shadow-md transition-all duration-300
                        group-hover/card:shadow-xl group-hover/card:-translate-y-0.5 overflow-hidden">

              <div class="absolute inset-x-0 top-0 h-1
                          bg-gradient-to-r from-indigo-500/40 to-fuchsia-500/40
                          opacity-0 group-hover/card:opacity-100 transition duration-300"
                   :class="p.accent ? 'opacity-100' : ''"></div>

              <template x-if="p.tag">
                <span class="absolute top-3 right-3 inline-flex items-center gap-1 px-2.5 py-1
                               rounded-full text-[11px] font-semibold bg-indigo-600 text-white shadow"
                      x-text="p.tag"></span>
              </template>

              <div class="p-5" x-data="{ openFeat:false }">
                <h3 class="font-semibold text-gray-900 dark:text-white" x-text="p.name"></h3>

                {{-- price block --}}
                <div class="mt-2">
                  <div class="flex items-center gap-2">
                    <span class="text-xs line-through text-gray-400" x-text="'US$ ' + p.rack.toFixed(2)"></span>
                    <template x-if="savePercent(p) > 0">
                      <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                                   bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300"
                            x-text="'Save ' + savePercent(p) + '%'"></span>
                    </template>
                  </div>
                  <div class="mt-1 flex items-end gap-2">
                    <div class="text-3xl font-extrabold text-gray-900 dark:text-white">
                      US$ <span x-text="perMonth(p).toFixed(2)"></span>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">/mo</span>
                  </div>
                  <div class="text-[11px] text-gray-500 dark:text-gray-400">Price per mailbox</div>
                </div>

                {{-- CTA => open modal --}}
                <button @pointerdown="ripple($event)" @click="openCheckout(p)"
                        class="ripple mt-4 w-full h-11 rounded-xl font-semibold tracking-tight
                               bg-gradient-to-r from-indigo-600 to-fuchsia-600
                               hover:from-indigo-600/90 hover:to-fuchsia-600/90
                               text-white shadow-lg shadow-indigo-600/10 transition">
                  Get started
                </button>

                <div class="mt-2 text-[11px] text-gray-500 dark:text-gray-400 space-y-0.5">
                  <div>US$ <span x-text="p.rack.toFixed(2)"></span>/mo when you renew</div>
                  <div>*Price applies when purchasing for <span x-text="monthsLabel"></span>.</div>
                </div>

                <div class="my-4 border-t border-gray-200/70 dark:border-slate-800/70"></div>

                {{-- Features (collapsible) --}}
                <div class="relative transition-all" :class="openFeat? '' : 'max-h-40 overflow-hidden'">
                  <ul class="space-y-2 text-sm">
                    <template x-for="f in p.features" :key="f">
                      <li class="flex items-start gap-2">
                        <svg class="h-4 w-4 text-emerald-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/>
                        </svg>
                        <span class="text-gray-700 dark:text-slate-200" x-text="f"></span>
                      </li>
                    </template>
                  </ul>
                  <div x-show="!openFeat" class="pointer-events-none absolute inset-x-0 bottom-0 h-10
                                               bg-gradient-to-t from-white/95 dark:from-[#0b1220]/95 to-transparent"></div>
                </div>

                <button @click.prevent="openFeat=!openFeat"
                        class="mt-4 inline-flex items-center gap-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                  <svg x-show="!openFeat" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"/>
                  </svg>
                  <svg x-show="openFeat" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5"/>
                  </svg>
                  <span x-show="!openFeat">See all features</span>
                  <span x-show="openFeat">Hide features</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </template>
    </div>

    {{-- Trust strip --}}
    <div class="reveal mt-8 rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] p-4 sm:p-6">
      <div class="grid md:grid-cols-4 gap-6 items-center text-sm">
        <div class="text-gray-700 dark:text-slate-200">99.9% uptime SLA</div>
        <div class="text-gray-600 dark:text-gray-300">Free SSL & advanced anti‑spam</div>
        <div class="text-gray-600 dark:text-gray-300">Easy migration & aliases</div>
        <div class="text-gray-600 dark:text-gray-300">24/7 expert support</div>
      </div>
    </div>

    {{-- FAQ --}}
    <div class="reveal mt-14">
      <div class="mx-auto max-w-7xl">
        <h2 class="text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Email Plan FAQ</h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-300">Short, clear answers to the most common questions.</p>

        @php
          $faqs = [
            ['q'=>'Do I get domain-based emails?','a'=>'Yes—use your own domain or connect an existing one.'],
            ['q'=>'Can I migrate from another provider?','a'=>'We support IMAP/POP and Google Workspace migration wizards.'],
            ['q'=>'How is storage counted?','a'=>'Per-mailbox quota; you can purchase extra storage when needed.'],
            ['q'=>'Is spam & phishing protection included?','a'=>'Yes, advanced filtering and malware scanning on all plans.'],
          ];
        @endphp

        <div class="mt-6 rounded-xl border border-gray-200/70 dark:border-slate-800/70 bg-white/90 dark:bg-[#0b1220]/90 backdrop-blur-sm shadow-sm">
          @foreach ($faqs as $f)
            <details class="group border-b border-gray-200/70 dark:border-slate-800/70 last:border-0">
              <summary class="flex items-start gap-3 px-5 sm:px-6 py-4 cursor-pointer select-none
                              hover:bg-gray-50/80 dark:hover:bg-slate-900/60
                              group-open:bg-indigo-50/40 dark:group-open:bg-indigo-500/5">
                <span class="mt-0.5 inline-grid place-items-center h-7 w-7 rounded-full
                             bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                  <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10a4 4 0 1 1 8 0c0 3-4 3-4 6m0 4h.01"/>
                  </svg>
                </span>
                <span class="flex-1 font-medium text-gray-900 dark:text-white">{{ $f['q'] }}</span>
                <span class="ml-3 shrink-0 grid place-items-center h-6 w-6 rounded-full
                             border border-gray-300/70 dark:border-slate-700/70
                             text-gray-600 dark:text-slate-300 transition-transform group-open:rotate-180">
                  <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                  </svg>
                </span>
              </summary>
              <div class="px-5 sm:px-6 pb-4 pt-0 grid transition-all duration-300 ease-out grid-rows-[0fr] group-open:grid-rows-[1fr]">
                <div class="overflow-hidden">
                  <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300">{{ $f['a'] }}</p>
                </div>
              </div>
            </details>
          @endforeach
        </div>

        <div class="mt-5 text-center text-xs text-gray-500 dark:text-gray-400">
          Still need help? <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Talk to sales</a>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== Checkout Modal (Email) ===== --}}
  <template x-teleport="body">
    <div x-show="checkout.open" @keydown.escape.window="closeCheckout()"
         class="fixed inset-0 z-[90] flex items-start justify-center pt-26 sm:pt-26 md:pt-20 p-4 sm:p-6">
      <div x-transition.opacity class="absolute inset-0 bg-black/55 backdrop-blur-[2px]" @click="closeCheckout()"></div>

      <div x-trap.noscroll="checkout.open"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-6 scale-[0.98]"
           x-transition:enter-end="opacity-100 translate-y-0 scale-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100 translate-y-0 scale-100"
           x-transition:leave-end="opacity-0 translate-y-2 scale-[0.98]"
           role="dialog" aria-modal="true"
           class="relative w-full max-w-3xl max-h-[82vh] overflow-y-auto
                  rounded-2xl border border-gray-200 dark:border-slate-800
                  bg-white dark:bg-[#0b1220] shadow-2xl ring-1 ring-black/5 dark:ring-white/5">

        {{-- Header --}}
        <div class="sticky top-0 z-10 px-5 sm:px-6 py-4 border-b border-gray-200 dark:border-slate-800
                    bg-white/95 dark:bg-[#0b1220]/95 backdrop-blur flex items-start gap-3">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="checkout.plan?.name || ''"></h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Choose a billing period and finish the checkout</p>
          </div>

          {{-- per-account/mo chip --}}
          <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold
                       bg-gray-100 text-gray-700 dark:bg-slate-800 dark:text-slate-200">
            <span x-text="'US$ ' + selectedPerMonth().toFixed(2) + '/account/mo'"></span>
          </span>

          <button class="ml-2 shrink-0 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800"
                  @click="closeCheckout()" aria-label="Close">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        {{-- Body --}}
        <div class="p-5 sm:p-6 space-y-4">

          {{-- Options list (48/24/12/1) --}}
          <div class="space-y-3">
            <template x-for="opt in checkout.options" :key="opt.months">
              <label class="block rounded-xl border-2 cursor-pointer transition
                            border-gray-200 dark:border-slate-800 hover:border-indigo-300 dark:hover:border-indigo-500/50
                            hover:bg-gray-50/60 dark:hover:bg-slate-900/40"
                     :class="checkout.months===opt.months ? 'border-indigo-500 ring-2 ring-indigo-400/30 bg-indigo-50/40 dark:bg-indigo-500/5' : ''">
                <div class="flex items-center gap-3 p-3">
                  <input type="radio" class="accent-indigo-600" :value="opt.months" x-model.number="checkout.months">
                  <div class="flex-1">
                    <div class="flex items-center gap-3">
                      <span class="text-sm text-gray-900 dark:text-white" x-text="opt.months + ' months'"></span>
                      <template x-if="opt.save>0">
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                                     bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300"
                              x-text="'SAVE ' + Math.round(opt.save) + '%'"></span>
                      </template>
                    </div>
                  </div>
                  <div class="text-right min-w-[140px]">
                    <div class="text-xs line-through text-gray-400" x-text="'US$ ' + opt.rack.toFixed(2)"></div>
                    <div class="inline-flex items-center gap-1 text-sm font-semibold text-gray-900 dark:text-white">
                      <span x-text="'US$ ' + opt.perMonth.toFixed(2)"></span>
                      <span class="text-[11px] text-gray-500 dark:text-gray-400">/mailbox/mo</span>
                    </div>
                  </div>
                </div>
              </label>
            </template>
          </div>

          {{-- Quantity --}}
          <div class="mt-1 rounded-xl border border-gray-200 dark:border-slate-800 p-3">
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-800 dark:text-slate-100">Need more mailboxes?</span>
              <div class="inline-flex items-center gap-2">
                <button @click="dec()" class="h-9 w-9 grid place-items-center rounded-lg
                                     bg-gray-100 hover:bg-gray-200 text-gray-900
                                     dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-100">−</button>
                <input type="number" min="1" x-model.number="checkout.qty"
                       class="h-9 w-14 text-center rounded-lg border border-gray-200 dark:border-slate-700
                              bg-white dark:bg-[#0b1220] text-gray-900 dark:text-white"/>
                <button @click="inc()" class="h-9 w-9 grid place-items-center rounded-lg
                                     bg-indigo-600 hover:bg-indigo-700 text-white">+</button>
              </div>
            </div>
          </div>

          {{-- Totals --}}
          <div class="rounded-xl border border-gray-200 dark:border-slate-800 divide-y divide-gray-200 dark:divide-slate-800">
            <div class="flex items-center justify-between px-3 py-2 text-sm">
              <span class="text-gray-600 dark:text-gray-300">Total</span>
              <span class="text-gray-900 dark:text-white" x-text="'US$ ' + subtotal().toFixed(2)"></span>
            </div>
            <div class="flex items-center justify-between px-3 py-2 text-sm">
              <span class="text-gray-600 dark:text-gray-300">Plan name</span>
              <span class="text-gray-900 dark:text-white" x-text="checkout.plan?.name || ''"></span>
            </div>
            <div class="flex items-center justify-between px-3 py-2 text-sm">
              <span class="text-gray-600 dark:text-gray-300">Coupon code
                <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Add</a>
              </span>
              <span class="text-gray-900 dark:text-white"></span>
            </div>
          </div>

          {{-- Footer --}}
          <div class="sticky bottom-0 bg-white/95 dark:bg-[#0b1220]/95 backdrop-blur
                      -mx-5 sm:-mx-6 px-5 sm:px-6 pt-3 pb-4 flex items-center justify-end gap-3">
            <button class="px-4 h-10 rounded-xl text-sm bg-gray-100 hover:bg-gray-200 text-gray-900
                           dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-100"
                    @click="closeCheckout()">Cancel</button>
            <button class="px-4 h-10 rounded-xl text-sm text-white shadow
                           bg-gradient-to-r from-indigo-600 to-fuchsia-600 hover:from-indigo-700 hover:to-fuchsia-700"
                    @click="goToPayment()">Choose payment method</button>
          </div>

          <p class="text-[11px] text-gray-500 dark:text-gray-400">
            By checking out, you agree with our Terms of Service and Privacy Policy. You can cancel recurring payments any time.
          </p>
          <p class="text-[11px] text-gray-500 dark:text-gray-400">
            Plan renews for <span x-text="'US$ ' + renewPrice().toFixed(2) + ' /mo'"></span>.
          </p>
        </div>
      </div>
    </div>
  </template>

  {{-- Alpine core --}}
  <script>
    function emailPlans(){
      return {
        months: 12,
        monthsLabel: '12 months',

        plans: [
          { slug:'business-starter', name:'Business Starter', tag:null, accent:false, price_m:0.39, rack:2.99, locked:false,
            features:['10 GB Storage per Mailbox','10 Forwarding Rules','10 Email Aliases','1000 emails/day per Mailbox','Free domain','Advanced spam, malware, and phishing protection','Option to get extra mailbox storage','No Hostinger signature in Webmail'] },
          { slug:'business-premium', name:'Business Premium', tag:'MOST POPULAR', accent:true, price_m:1.99, rack:5.99, locked:false,
            features:['50 GB Storage per Mailbox','50 Forwarding Rules','50 Email Aliases','3000 emails/day per Mailbox','Free domain','Advanced spam, malware, and phishing protection','Option to get extra mailbox storage','No Hostinger signature in Webmail'] },
          { slug:'google-workspace', name:'Google Workspace', tag:null, accent:false, price_m:5.99, rack:5.99, locked:true,
            features:['30 GB Storage per Mailbox','Email Matching your Domain Name','Gmail','Calendar','Docs, Sheets, Slides','Chat Team Messaging','AppSheet','Meet Video Conferencing'] },
        ],

        // card pricing based on period dropdown
        perMonth(p){
          if (p.locked) return p.rack;
          if (this.months === 48) return p.price_m;
          if (this.months === 24) return +(p.price_m * 1.15).toFixed(2);
          if (this.months === 12) return +(p.price_m * 1.50).toFixed(2);
          return p.rack;
        },
        savePercent(p){
          const per = this.perMonth(p); const s = Math.max(0, 1 - (per / p.rack)); return Math.round(s * 100);
        },
        onMonthsChange(){ this.monthsLabel = this.months === 1 ? '1 month' : `${this.months} months`; },

        // ===== Modal state & helpers
        checkout:{ open:false, plan:null, months:48, options:[], qty:1 },

        periodOptions(plan){
          // 48(best), 24, 12, 1 (rack); Workspace locked -> all rack
          if (plan.locked){
            const mk=(m)=>({months:m, perMonth:plan.rack, rack:plan.rack, save:0});
            return [mk(12), mk(1)];
          }
          const p48=plan.price_m, p24=+(plan.price_m*1.15).toFixed(2), p12=+(plan.price_m*1.5).toFixed(2), p01=plan.rack;
          const mk=(m,per)=>({months:m, perMonth:per, rack:plan.rack, save:(1 - per/plan.rack)*100});
          return [mk(48,p48), mk(24,p24), mk(12,p12), mk(1,p01)];
        },

        openCheckout(plan){
          this.checkout.plan = plan;
          this.checkout.options = this.periodOptions(plan);
          this.checkout.months = this.checkout.options[0].months;
          this.checkout.qty = 1;
          this.checkout.open = true;
          document.body.classList.add('overflow-hidden');
        },
        closeCheckout(){
          this.checkout.open = false;
          document.body.classList.remove('overflow-hidden');
        },

        selectedOpt(){ return this.checkout.options.find(o=>o.months===this.checkout.months) || this.checkout.options[0]; },
        selectedPerMonth(){ return this.selectedOpt().perMonth; },
        subtotal(){ const o=this.selectedOpt(); return o.perMonth * this.checkout.months * (this.checkout.qty||1); },
        inc(){ this.checkout.qty = Math.min(999, (this.checkout.qty||1)+1); },
        dec(){ this.checkout.qty = Math.max(1, (this.checkout.qty||1)-1); },

        renewPrice(){ return this.checkout.plan ? this.checkout.plan.rack : 0; },
        renewMonths(){ return this.checkout.months===1 ? '1 month' : '1 year'; },
        expiryDate(){
          const addM=this.checkout.months||1; const d=new Date(); const out=new Date(d.getFullYear(), d.getMonth()+addM, d.getDate());
          return out.toISOString().slice(0,10);
        },

        goToPayment(){
          const slug=this.checkout.plan?.slug || ''; const months=this.checkout.months || 1; const qty=this.checkout.qty || 1;
          window.location.href = `/checkout/email?plan=${encodeURIComponent(slug)}&months=${months}&qty=${qty}`;
        },

        ripple(ev){ const el=ev.currentTarget; el.style.setProperty('--x',(ev.offsetX||0)+'px'); el.style.setProperty('--y',(ev.offsetY||0)+'px'); },
        init(){ this.onMonthsChange(); }
      }
    }
  </script>
</section>
</x-hpanel-layout>

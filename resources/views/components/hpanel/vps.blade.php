{{-- resources/views/components/hpanel/vps.blade.php --}}
<x-hpanel-layout>
<section x-data="vpsPage()" x-init="init()" class="reveal">
  <style>
    .ripple{position:relative;overflow:hidden}
    .ripple:after{content:'';position:absolute;inset:auto;width:0;height:0;border-radius:9999px;background:rgba(255,255,255,.35);transform:translate(-50%,-50%);pointer-events:none;opacity:0}
    .ripple:active:after{left:var(--x);top:var(--y);opacity:1;width:200px;height:200px;transition:width .4s ease,height .4s ease,opacity .8s ease;opacity:0}
  </style>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Breadcrumb + Title --}}
    <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
      <a href="/" class="hover:underline">Home</a>
      <span class="mx-2">/</span>
      <span class="font-medium text-gray-700 dark:text-gray-200">VPS</span>
    </div>

    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
      Build applications, host websites, or play games with VPS
    </h1>
    <p class="mt-2 text-gray-600 dark:text-gray-300">
      Choose KVM for dedicated resources or Game Panel for your game servers.
    </p>

    {{-- Two big option cards --}}
    <div class="mt-6 grid md:grid-cols-2 gap-6">
      <div class="rounded-2xl border border-gray-200/70 dark:border-slate-800/70 bg-white/90 dark:bg-[#0b1220]/90 shadow-sm">
        <div class="p-5 sm:p-6">
          <img src="/images/vps/kvm-stacks.png" alt="KVM VPS Stacks" class="h-10 opacity-90">
          <div class="mt-5 rounded-xl border border-gray-200 dark:border-slate-800 p-4 bg-gray-50/70 dark:bg-slate-900/40">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">KVM VPS</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
              Build your application or website with dedicated resources and complete control of your server.
            </p>
            <a href="{{ url('/vps/kvm') }}"
               @pointerdown="ripple($event)"
               class="ripple mt-4 inline-flex items-center justify-center h-10 px-4 rounded-xl font-semibold
                      bg-indigo-600 hover:bg-indigo-700 text-white shadow">
              Get now
            </a>
          </div>
        </div>
      </div>

      <div class="rounded-2xl border border-gray-200/70 dark:border-slate-800/70 bg-white/90 dark:bg-[#0b1220]/90 shadow-sm">
        <div class="p-5 sm:p-6">
          <img src="/images/vps/game-icons.png" alt="Game Panel Icons" class="h-10 opacity-90">
          <div class="mt-5 rounded-xl border border-gray-200 dark:border-slate-800 p-4 bg-gray-50/70 dark:bg-slate-900/40">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Game Panel</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
              Host your favorite games with top‑tier processors and full customization for an unbeatable gaming experience.
            </p>
            <a href="{{ url('/vps/game-panel') }}"
               @pointerdown="ripple($event)"
               class="ripple mt-4 inline-flex items-center justify-center h-10 px-4 rounded-xl font-semibold
                      bg-indigo-600 hover:bg-indigo-700 text-white shadow">
              Get now
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- VPS plans quick grid (optional, can hide/remove) --}}
    <div class="reveal mt-10">
      <h2 class="text-xl font-bold text-gray-900 dark:text-white text-center">Popular KVM VPS Plans</h2>
      <div class="mt-6 grid md:grid-cols-3 gap-6">
        <template x-for="p in plans" :key="p.slug">
          <div class="group rounded-2xl p-[1px]"
               :class="p.accent ? 'bg-gradient-to-r from-indigo-500/60 to-fuchsia-500/60' : 'bg-transparent'">
            <div class="rounded-xl border border-gray-200/70 dark:border-slate-800/70 bg-white/90 dark:bg-[#0b1220]/90 shadow-md p-5">
              <div class="flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white" x-text="p.name"></h3>
                <template x-if="p.tag">
                  <span class="text-[10px] px-2 py-0.5 rounded-full bg-indigo-600 text-white font-semibold" x-text="p.tag"></span>
                </template>
              </div>
              <div class="mt-2 flex items-end gap-2">
                <div class="text-3xl font-extrabold text-gray-900 dark:text-white">
                  US$ <span x-text="p.price.toFixed(2)"></span>
                </div>
                <span class="text-sm text-gray-500 dark:text-gray-400">/mo</span>
              </div>

              <ul class="mt-4 space-y-2 text-sm">
                <li class="flex gap-2">
                  <svg class="h-4 w-4 text-emerald-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                  <span class="text-gray-700 dark:text-slate-200" x-text="p.cpu + ' vCPU'"></span>
                </li>
                <li class="flex gap-2">
                  <svg class="h-4 w-4 text-emerald-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                  <span class="text-gray-700 dark:text-slate-200" x-text="p.ram + ' GB RAM'"></span>
                </li>
                <li class="flex gap-2">
                  <svg class="h-4 w-4 text-emerald-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                  <span class="text-gray-700 dark:text-slate-200" x-text="p.ssd + ' GB NVMe'"></span>
                </li>
                <li class="flex gap-2">
                  <svg class="h-4 w-4 text-emerald-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                  <span class="text-gray-700 dark:text-slate-200">Dedicated IPv4 & full root</span>
                </li>
              </ul>

              <button @pointerdown="ripple($event)" class="ripple mt-5 w-full h-11 rounded-xl font-semibold
                      bg-gradient-to-r from-indigo-600 to-fuchsia-600 text-white hover:from-indigo-700 hover:to-fuchsia-700">
                Get started
              </button>
            </div>
          </div>
        </template>
      </div>
    </div>

    {{-- ===== VPS Hosting FAQ (same style as hosting) ===== --}}
    <div class="reveal mt-12">
      <h2 class="text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white">VPS Hosting FAQ</h2>
      <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-300">
        Short, clear answers to the most common questions.
      </p>

      @php
        $faqs = [
          ['q' => 'What is VPS Hosting?', 'a' => 'Virtual Private Server (VPS) uses virtualization to give you dedicated resources on a shared node. You get root access, custom stacks, and isolated performance.'],
          ['q' => "What are the Benefits of Hostinger’s KVM VPS?", 'a' => 'True KVM isolation, NVMe storage, dedicated IP, full root, snapshots/backups, and global DC locations for low‑latency.'],
          ['q' => 'What is a Game Panel?', 'a' => 'A control panel tailored for game servers (e.g., Minecraft, ARK). It lets you create, start/stop, update and monitor servers with one click.'],
          ['q' => 'What are the Benefits of Hosting My Own Game Panel Hosting?', 'a' => 'Full control of mods, performance tuning, player slots, and security. Scale resources as your community grows.'],
          ['q' => 'What Locations are Available for VPS hosting?', 'a' => 'Multiple regions (US/EU/Asia). Pick the closest to your users for best latency. Exact options appear at checkout.'],
          ['q' => 'Will I get any kind of assistance while using Linux VPS hosting?', 'a' => 'Yes. 24/7 support plus docs and templates. Managed add‑ons are available if you prefer hands‑off operations.'],
        ];
      @endphp

      <div class="mt-6 rounded-xl border border-gray-200/70 dark:border-slate-800/70
                  bg-white/90 dark:bg-[#0b1220]/90 backdrop-blur-sm shadow-sm">
        @foreach ($faqs as $f)
          <details class="group border-b border-gray-200/70 dark:border-slate-800/70 last:border-0">
            <summary class="flex items-start gap-3 px-5 sm:px-6 py-4 cursor-pointer select-none
                            hover:bg-gray-50/80 dark:hover:bg-slate-900/60
                            group-open:bg-indigo-50/40 dark:group-open:bg-indigo-500/5
                            group-open:border-indigo-200/70 dark:group-open:border-indigo-600/40">
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
            <div class="px-5 sm:px-6 pb-4 pt-0 grid transition-all duration-300 ease-out
                        grid-rows-[0fr] group-open:grid-rows-[1fr]">
              <div class="overflow-hidden">
                <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300">{{ $f['a'] }}</p>
              </div>
            </div>
          </details>
        @endforeach
      </div>

      <div class="mt-5 text-center text-xs text-gray-500 dark:text-gray-400">
        Still have questions?
        <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Contact support</a>
      </div>
    </div>
  </div>

  {{-- Alpine core --}}
  <script>
    function vpsPage(){
      return {
        plans: [
          { slug:'vps-1', name:'VPS 1', cpu:2, ram:2, ssd:40, price:4.99, accent:false, tag:null },
          { slug:'vps-2', name:'VPS 2', cpu:3, ram:4, ssd:80, price:7.99, accent:true,  tag:'POPULAR' },
          { slug:'vps-3', name:'VPS 3', cpu:4, ram:8, ssd:160, price:12.99, accent:false, tag:null },
        ],
        ripple(ev){ const el=ev.currentTarget; el.style.setProperty('--x',(ev.offsetX||0)+'px'); el.style.setProperty('--y',(ev.offsetY||0)+'px'); },
        init(){ /* no-op */ }
      }
    }
  </script>
</section>
</x-hpanel-layout>

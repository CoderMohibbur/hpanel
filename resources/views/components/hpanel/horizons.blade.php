<x-hpanel-layout>
<section x-data="horizons()" x-init="init()" class="reveal">

  {{-- ripple --}}
  <style>
    .ripple{position:relative;overflow:hidden}
    .ripple:after{content:'';position:absolute;inset:auto;width:0;height:0;border-radius:9999px;background:rgba(255,255,255,.35);transform:translate(-50%,-50%);pointer-events:none;opacity:0}
    .ripple:active:after{left:var(--x);top:var(--y);opacity:1;width:200px;height:200px;transition:width .4s ease,height .4s ease,opacity .8s ease;opacity:0}
  </style>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Title + breadcrumb --}}
    <div class="mb-5">
      <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">Horizons</h1>
      <div class="mt-1 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-slate-200">
          <i data-lucide="home" class="h-4 w-4"></i> <span>Home</span>
        </a>
        <span class="opacity-60">—</span>
        <span>Horizons</span>
      </div>
    </div>

    {{-- Hero / preview (image added) --}}
    <div class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-slate-800">
      <div class="grid md:grid-cols-2 gap-6 bg-gradient-to-br from-indigo-600 to-fuchsia-600 text-white">
        <div class="p-6 sm:p-8">
          <span class="inline-flex items-center gap-2 text-[11px] font-bold px-2.5 py-1 rounded-full bg-white/15 ring-1 ring-white/30">
            NEW
          </span>
          <h2 class="mt-4 text-2xl sm:text-3xl font-bold leading-tight">
            Go from idea to a web app or site — in minutes
          </h2>
          <p class="mt-3 text-white/90">
            Chat your idea and publish in one click—no code. Clean Tailwind UI, responsive by default.
          </p>

          <div class="mt-5 flex items-center gap-3">
            <a href="#faq" class="inline-flex items-center gap-2 h-10 px-3 rounded-xl text-sm font-semibold bg-white/10 hover:bg-white/15 ring-1 ring-white/30">
              <i data-lucide="help-circle" class="h-4 w-4"></i> Learn more
            </a>
            <a href="{{ route('hosting.index', []) ?? '#' }}"
               @pointerdown="ripple($event)"
               class="ripple inline-flex items-center justify-center h-10 px-4 rounded-xl text-sm font-semibold bg-white text-indigo-700 hover:bg-white/90 shadow">
              Try now
            </a>
          </div>
        </div>

        {{-- Right preview image (example) --}}
        <div class="relative">
          {{-- নিজের ছবি রাখলে: public/images/horizons/preview.png --}}
          <img
            src="{{ asset('images/horizons/preview.pngd') }}"
            onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1400&auto=format&fit=crop';"
            class="h-full w-full object-cover"
            alt="Horizons preview">
          <div class="absolute inset-0 bg-gradient-to-l from-indigo-600/40 to-transparent"></div>
        </div>
      </div>
    </div>

    {{-- Feature cards --}}
    <section class="mt-8 grid md:grid-cols-3 gap-6">
      @php
        $features = [
          ['title'=>'AI-first building', 'desc'=>'Describe your vision; Horizons scaffolds pages/sections with Tailwind.', 'icon'=>'wand-2'],
          ['title'=>'Responsive & fast', 'desc'=>'Every block is mobile-ready with accessible components.', 'icon'=>'rocket'],
          ['title'=>'One-click publish', 'desc'=>'Connect domain and deploy instantly with CDN & SSL.', 'icon'=>'upload-cloud'],
        ];
      @endphp
      @foreach ($features as $f)
        <div class="rounded-2xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] p-5 shadow hover:shadow-lg transition will-change-transform hover:-translate-y-0.5">
          <div class="flex items-center gap-3">
            <div class="h-10 w-10 grid place-items-center rounded-xl bg-indigo-600/10 text-indigo-600 dark:text-indigo-300">
              <i data-lucide="{{ $f['icon'] }}"></i>
            </div>
            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $f['title'] }}</h3>
          </div>
          <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">{{ $f['desc'] }}</p>
        </div>
      @endforeach
    </section>

    {{-- How it works --}}
    <section class="mt-8 rounded-2xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] p-5 sm:p-6">
      <h3 class="font-semibold text-gray-900 dark:text-white">How it works</h3>
      <div class="mt-4 grid md:grid-cols-3 gap-4 text-sm">
        <div class="rounded-xl border border-gray-200 dark:border-slate-800 p-4">
          <div class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">Step 1</div>
          <div class="mt-1 font-medium text-gray-900 dark:text-white">Describe your idea</div>
          <p class="mt-1 text-gray-600 dark:text-gray-300">Tell Horizons what you want—brand, sections, tone.</p>
        </div>
        <div class="rounded-xl border border-gray-200 dark:border-slate-800 p-4">
          <div class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">Step 2</div>
          <div class="mt-1 font-medium text-gray-900 dark:text-white">Get an editable draft</div>
          <p class="mt-1 text-gray-600 dark:text-gray-300">AI scaffolds responsive pages. Tweak visually.</p>
        </div>
        <div class="rounded-xl border border-gray-200 dark:border-slate-800 p-4">
          <div class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">Step 3</div>
          <div class="mt-1 font-medium text-gray-900 dark:text-white">Publish in one click</div>
          <p class="mt-1 text-gray-600 dark:text-gray-300">Connect a domain and go live with rollback.</p>
        </div>
      </div>
    </section>

    {{-- === FAQ (your requested style) === --}}
    <div id="faq" class="reveal mt-14">
      <div class="mx-auto max-w-7xl">
        <h2 class="text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
          Hosting Plan FAQ
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-300">
          Short, clear answers to the most common questions.
        </p>

        <div class="mt-6 rounded-xl border border-gray-200/70 dark:border-slate-800/70
                    bg-white/90 dark:bg-[#0b1220]/90 backdrop-blur-sm shadow-sm">
          @php
            $faqs = [
              ['q'=>'Why do I need a hosting plan?','a'=>'Your website lives on a server so people can access it from anywhere. Our plans deliver performance, security and easy scaling.'],
              ['q'=>'I already have a website. Can I migrate it?','a'=>'Yes, guided migration tools + help for common stacks (CMS, Laravel, WP).'],
              ['q'=>'Can I upgrade my plan later?','a'=>'Absolutely. Upgrade anytime with zero downtime—billing prorates automatically.'],
              ['q'=>'Do you offer backups?','a'=>'Daily or weekly backups depending on plan, with one-click restore.'],
            ];
          @endphp

          @foreach ($faqs as $f)
            <details class="group border-b border-gray-200/70 dark:border-slate-800/70 last:border-0 transition-colors">
              <summary class="flex items-start gap-3 px-5 sm:px-6 py-4 cursor-pointer select-none
                               hover:bg-gray-50/80 dark:hover:bg-slate-900/60
                               group-open:bg-indigo-50/40 dark:group-open:bg-indigo-500/5
                               group-open:border-indigo-200/70 dark:group-open:border-indigo-600/40">

                {{-- leading bubble icon --}}
                <span class="mt-0.5 inline-grid place-items-center h-7 w-7 rounded-full
                             bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                  <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10a4 4 0 1 1 8 0c0 3-4 3-4 6m0 4h.01"/>
                  </svg>
                </span>

                <span class="flex-1 font-medium text-gray-900 dark:text-white">{{ $f['q'] }}</span>

                {{-- chevron --}}
                <span class="ml-3 shrink-0 grid place-items-center h-6 w-6 rounded-full
                             border border-gray-300/70 dark:border-slate-700/70
                             text-gray-600 dark:text-slate-300 transition-transform group-open:rotate-180">
                  <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                  </svg>
                </span>
              </summary>

              {{-- smooth expand --}}
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

  </div>

  {{-- Alpine --}}
  <script>
    function horizons(){
      return {
        init(){
          document.querySelectorAll('.ripple').forEach(btn=>{
            btn.addEventListener('pointerdown',(ev)=>{
              btn.style.setProperty('--x',(ev.offsetX||0)+'px');
              btn.style.setProperty('--y',(ev.offsetY||0)+'px');
            });
          });
        },
        ripple(e){}
      }
    }
  </script>
</section>
</x-hpanel-layout>

{{-- resources/views/components/hpanel/domains/portfolio.blade.php --}}
<x-hpanel-layout>
<section
  x-data="domainPortfolio({ domains: @js($domains ?? []) })"
  x-init="init()"
  class="reveal"
>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Header + breadcrumb + primary actions --}}
    <div class="mb-6 flex items-start justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
          <a href="{{ url('/') }}" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-slate-200">
            <i data-lucide="home" class="h-4 w-4"></i> Home
          </a>
          <span class="opacity-60">/</span>
          <span>Domain portfolio</span>
        </div>
        <h1 class="mt-1 text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">
          Domain portfolio
        </h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
          Manage registrations, renewals, DNS and transfers — all in one place.
        </p>
      </div>
    </div>

    {{-- Empty state (when no domains at all) --}}
    <template x-if="stats.total === 0">
      <div class="rounded-2xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] shadow-sm">
        <div class="px-6 sm:px-10 py-14 text-center max-w-3xl mx-auto">
          <img src="{{ asset('images/domain.png') }}" alt="Domain" class="mx-auto h-14 w-14 text-indigo-400/60 dark:text-indigo-300/70">

          <h2 class="mt-6 text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
            Get a domain to get your website online
          </h2>
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
            Register a new domain or transfer a domain you already own.
          </p>

          <div class="mt-6 flex items-center justify-center gap-4">
            <a href="{{ route('domains.register') }}"
               class="inline-flex h-10 items-center justify-center px-4 rounded-xl text-sm font-semibold
                      text-white bg-gradient-to-r from-indigo-500 to-fuchsia-500
                      hover:from-indigo-600 hover:to-fuchsia-600 shadow">
              Get new domain
            </a>
            <a href="{{ route('domains.transfer') }}"
               class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
              Transfer domain
            </a>
          </div>
        </div>
      </div>
    </template>

    {{-- When domains exist --}}
    <template x-if="stats.total > 0">
      <div>
        {{-- Stat summary --}}
        <div class="grid sm:grid-cols-3 gap-3 mb-5">
          <div class="rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] p-4">
            <div class="text-xs text-gray-500 dark:text-gray-400">Total domains</div>
            <div class="mt-1 text-xl font-semibold text-gray-900 dark:text-white" x-text="stats.total"></div>
          </div>
          <div class="rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] p-4">
            <div class="text-xs text-gray-500 dark:text-gray-400">Active</div>
            <div class="mt-1 text-xl font-semibold text-emerald-600 dark:text-emerald-400" x-text="stats.active"></div>
          </div>
          <div class="rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] p-4">
            <div class="text-xs text-gray-500 dark:text-gray-400">Expiring (≤30 days)</div>
            <div class="mt-1 text-xl font-semibold text-amber-600 dark:text-amber-400" x-text="stats.expiring"></div>
          </div>
        </div>

        {{-- Controls: tabs + search + sort --}}
        <div class="mb-4 flex flex-col lg:flex-row items-stretch lg:items-center gap-3">
          {{-- Tabs --}}
          <div class="inline-flex rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] p-1">
            <button class="px-3 h-9 rounded-lg text-sm"
                    :class="tab==='all'      ? 'bg-indigo-600 text-white shadow' : 'text-gray-700 dark:text-slate-200'"
                    @click="tab='all'">All</button>
            <button class="px-3 h-9 rounded-lg text-sm"
                    :class="tab==='active'   ? 'bg-indigo-600 text-white shadow' : 'text-gray-700 dark:text-slate-200'"
                    @click="tab='active'">Active</button>
            <button class="px-3 h-9 rounded-lg text-sm"
                    :class="tab==='expiring' ? 'bg-indigo-600 text-white shadow' : 'text-gray-700 dark:text-slate-200'"
                    @click="tab='expiring'">Expiring soon</button>
            <button class="px-3 h-9 rounded-lg text-sm"
                    :class="tab==='expired'  ? 'bg-indigo-600 text-white shadow' : 'text-gray-700 dark:text-slate-200'"
                    @click="tab='expired'">Expired</button>
          </div>

          {{-- Spacer --}}
          <div class="flex-1"></div>

          {{-- Search --}}
          <div class="flex items-center gap-2 h-10 px-3 rounded-xl
                      bg-gray-50 dark:bg-slate-800/80
                      border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-100 w-full lg:w-80">
            <i data-lucide="search" class="h-4 w-4 opacity-75"></i>
            <input x-model.trim="q" type="text" placeholder="Search domains…"
                   class="flex-1 bg-transparent outline-none text-sm placeholder-gray-400 dark:placeholder-slate-400">
            <button x-show="q" @click="q=''" class="p-1 rounded-md hover:bg-gray-200/60 dark:hover:bg-slate-700/60">
              <i data-lucide="x" class="h-4 w-4"></i>
            </button>
          </div>

          {{-- Sort --}}
          <div class="inline-flex items-center h-10 px-3 rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220]">
            <span class="text-sm text-gray-600 dark:text-gray-300 mr-2">Sort:</span>
            <select x-model="sortBy" class="bg-transparent text-sm outline-none text-gray-800 dark:text-slate-100">
              <option value="expiryAsc">Expiry (soonest)</option>
              <option value="expiryDesc">Expiry (latest)</option>
              <option value="nameAsc">Name A–Z</option>
              <option value="nameDesc">Name Z–A</option>
            </select>
          </div>
        </div>

        {{-- Table --}}
        <div class="rounded-2xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] overflow-hidden">
          <template x-if="rows.length === 0">
            <div class="px-6 py-12 text-center text-sm text-gray-600 dark:text-gray-300">
              No domains match your filters.
            </div>
          </template>

          <template x-if="rows.length > 0">
            <div class="overflow-x-auto">
              <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-900/40 text-gray-600 dark:text-slate-300">
                  <tr>
                    <th class="px-4 py-3 text-left">Domain</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Auto-renew</th>
                    <th class="px-4 py-3 text-left">Expiry</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-800 text-gray-800 dark:text-slate-100">
                  <template x-for="d in rows" :key="d.name">
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50">
                      <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                          <div class="h-7 w-7 grid place-items-center rounded-full bg-gray-100 dark:bg-slate-800">
                            <i data-lucide="globe" class="h-4 w-4"></i>
                          </div>
                          <div>
                            <div class="font-medium" x-text="d.name"></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400" x-text="d.registrar ?? '—'"></div>
                          </div>
                        </div>
                      </td>

                      <td class="px-4 py-3">
                        <template x-if="d.status==='active'">
                          <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px]
                                        bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300">
                            <i data-lucide="check" class="h-3.5 w-3.5"></i> Active
                          </span>
                        </template>
                        <template x-if="d.status==='expiring'">
                          <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px]
                                        bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300">
                            <i data-lucide="alert-triangle" class="h-3.5 w-3.5"></i> Expiring soon
                          </span>
                        </template>
                        <template x-if="d.status==='expired'">
                          <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px]
                                        bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300">
                            <i data-lucide="x-circle" class="h-3.5 w-3.5"></i> Expired
                          </span>
                        </template>
                      </td>

                      <td class="px-4 py-3">
                        <button @click="d.auto = !d.auto"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition
                                       ring-1 ring-inset"
                                :class="d.auto
                                        ? 'bg-indigo-600 ring-indigo-500/40'
                                        : 'bg-gray-200 dark:bg-slate-700 ring-gray-300/50 dark:ring-slate-600/50'">
                          <span class="sr-only">Toggle auto-renew</span>
                          <span class="inline-block h-5 w-5 transform rounded-full bg-white dark:bg-slate-200 transition"
                                :class="d.auto ? 'translate-x-5' : 'translate-x-1'"></span>
                        </button>
                      </td>

                      <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                          <span x-text="formatDate(d.expiry)"></span>
                          <span class="text-xs text-gray-500 dark:text-gray-400" x-text="daysLeftText(d)"></span>
                        </div>
                      </td>

                      <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-1">
                          <a href="#"
                             class="px-3 h-8 rounded-lg text-xs font-semibold bg-gray-100 dark:bg-slate-800
                                    text-gray-800 dark:text-slate-200 hover:bg-gray-200 dark:hover:bg-slate-700">
                            DNS
                          </a>
                          <a href="#"
                             class="px-3 h-8 rounded-lg text-xs font-semibold text-white
                                    bg-indigo-600 hover:bg-indigo-700">
                            Manage
                          </a>
                          <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800">
                            <i data-lucide="more-horizontal" class="h-4 w-4"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </template>
        </div>
      </div>
    </template>

  </div>

  {{-- Alpine logic --}}
  <script>
    function domainPortfolio({ domains }) {
      return {
        raw: domains || [],
        tab: 'all',
        q: '',
        sortBy: 'expiryAsc',

        init() {
          // demo fallback data (optional)
          if (!Array.isArray(this.raw) || this.raw.length === 0) return;
        },

        get stats() {
          const total = this.raw.length;
          let active = 0, expiring = 0;
          const now = new Date();
          for (const d of this.raw) {
            const dd = new Date(d.expiry || now);
            const days = Math.ceil((dd - now) / 86400000);
            if (days < 0) continue;
            if (days <= 30) expiring++;
            active++;
          }
          return { total, active, expiring };
        },

        get rows() {
          const now = new Date();
          // map with computed status
          let arr = (this.raw || []).map(d => {
            const dd = new Date(d.expiry || now);
            const days = Math.ceil((dd - now) / 86400000);
            let status = 'active';
            if (days < 0) status = 'expired';
            else if (days <= 30) status = 'expiring';
            return { ...d, status };
          });

          // tab filter
          if (this.tab !== 'all') {
            arr = arr.filter(d => d.status === this.tab);
          }

          // search
          if (this.q) {
            const q = this.q.toLowerCase();
            arr = arr.filter(d => (d.name || '').toLowerCase().includes(q));
          }

          // sort
          const cmp = {
            expiryAsc:  (a,b)=> new Date(a.expiry) - new Date(b.expiry),
            expiryDesc: (a,b)=> new Date(b.expiry) - new Date(a.expiry),
            nameAsc:    (a,b)=> (a.name || '').localeCompare(b.name || ''),
            nameDesc:   (a,b)=> (b.name || '').localeCompare(a.name || ''),
          }[this.sortBy] || (()=>0);

          return arr.sort(cmp);
        },

        formatDate(v) {
          if (!v) return '—';
          const d = new Date(v);
          if (isNaN(d)) return v;
          return d.toISOString().slice(0,10);
        },

        daysLeftText(d) {
          const dd = new Date(d.expiry);
          if (isNaN(dd)) return '';
          const days = Math.ceil((dd - new Date()) / 86400000);
          if (days < 0)  return '(expired)';
          if (days === 0) return '(today)';
          return `(${days}d left)`;
        },
      }
    }
  </script>
</section>
</x-hpanel-layout>

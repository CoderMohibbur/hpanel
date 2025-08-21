{{-- resources/views/components/hpanel/domains/transfer.blade.php --}}
<x-hpanel-layout>
<section class="reveal">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    {{-- Header --}}
    <div class="mb-5">
      <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">Transfer domain</h1>
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Move a domain you already own to us.</p>
    </div>

    <form method="GET" action="{{ route('domains.transfer') }}"
          class="rounded-2xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] p-4 sm:p-6">
      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Domain name</label>
          <input name="domain" value="{{ $domain ?? '' }}" placeholder="yourdomain.com"
                 class="w-full h-11 rounded-xl px-3 bg-gray-50 dark:bg-slate-800/80
                        border border-gray-200 dark:border-slate-700 text-sm
                        text-gray-800 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-400">
        </div>
        <div>
          <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Auth/EPP code</label>
          <input placeholder="EPP-XXXXXXXX" class="w-full h-11 rounded-xl px-3 bg-gray-50 dark:bg-slate-800/80
                        border border-gray-200 dark:border-slate-700 text-sm
                        text-gray-800 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-400">
        </div>
      </div>

<div class="mt-4 flex justify-end gap-3">
  <a href="{{ route('domains.index') }}"
     class="inline-flex items-center justify-center h-11 px-4 rounded-xl text-sm font-medium
            bg-gray-100 dark:bg-slate-800 text-gray-800 dark:text-slate-200
            border border-gray-200 dark:border-slate-700
            hover:bg-gray-200 dark:hover:bg-slate-700">
    Cancel
  </a>

  <button type="submit"
    class="inline-flex items-center justify-center h-11 px-4 rounded-xl text-sm font-semibold text-white
           bg-gradient-to-r from-indigo-500 to-fuchsia-500 hover:from-indigo-600 hover:to-fuchsia-600 shadow">
    Check & continue
  </button>
</div>


      {{-- Eligibility note (demo) --}}
      @if (!is_null($eligible))
        <div class="mt-4 rounded-xl p-3
                    {{ $eligible ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' }}">
          @if ($eligible)
            Looks good! Your domain seems eligible for transfer. You can proceed to payment & confirmation.
          @else
            This domain doesn’t look eligible right now. Ensure it’s unlocked and >60 days old, then try again.
          @endif
        </div>
      @endif
    </form>

    {{-- Tips --}}
    <div class="mt-6 rounded-2xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#0b1220] p-4 sm:p-6">
      <h3 class="font-semibold text-gray-900 dark:text-white">Before you start</h3>
      <ul class="mt-3 space-y-2 text-sm text-gray-600 dark:text-gray-300">
        <li>• The domain must be unlocked at the current registrar.</li>
        <li>• Get your Auth/EPP code from the current registrar.</li>
        <li>• Disable privacy protection temporarily to receive emails.</li>
      </ul>
    </div>
  </div>
</section>
</x-hpanel-layout>

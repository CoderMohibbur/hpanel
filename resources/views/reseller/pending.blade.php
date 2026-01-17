<x-reseller-layout
    title="Reseller Status"
    page-title="Reseller Panel Access"
    page-desc="আপনার অ্যাকাউন্ট অনুমোদনের অবস্থান দেখুন।"
>
    @php
        $u = auth()->user();
        $status = $u->approval_status ?? 'pending';

        $badgeClass = match ($status) {
            'approved' => 'bg-green-50 text-green-700 border-green-200',
            'rejected' => 'bg-red-50 text-red-700 border-red-200',
            'suspended' => 'bg-amber-50 text-amber-700 border-amber-200',
            default => 'bg-blue-50 text-blue-700 border-blue-200',
        };

        $title = match ($status) {
            'rejected' => 'আপনার রিসেলার আবেদনটি Reject হয়েছে',
            'suspended' => 'আপনার রিসেলার অ্যাকাউন্ট Suspended',
            default => 'আপনার রিসেলার অ্যাকাউন্ট Pending আছে',
        };

        $desc = match ($status) {
            'rejected' => 'আপনার আবেদনটি রিভিউ করে বাতিল করা হয়েছে। প্রয়োজন হলে সাপোর্টে যোগাযোগ করুন।',
            'suspended' => 'অ্যাকাউন্ট সাময়িকভাবে স্থগিত করা হয়েছে। বিস্তারিত জানতে সাপোর্টে যোগাযোগ করুন।',
            default => 'আপনার আবেদনটি রিভিউ হচ্ছে। অনুমোদন হলে আপনি রিসেলার প্যানেলে প্রবেশ করতে পারবেন।',
        };
    @endphp

    <div class="max-w-3xl">
        <div class="bg-white border rounded-2xl p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold">{{ $title }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $desc }}</p>
                </div>

                <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs font-medium {{ $badgeClass }}">
                    {{ strtoupper($status) }}
                </span>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-xl border bg-gray-50 p-4">
                    <div class="text-xs text-gray-500">Account</div>
                    <div class="mt-1 font-medium">{{ $u->name }}</div>
                    <div class="text-sm text-gray-600">{{ $u->email }}</div>
                </div>

                <div class="rounded-xl border bg-gray-50 p-4">
                    <div class="text-xs text-gray-500">Reseller Profile Status</div>
                    <div class="mt-1 font-medium">
                        {{ $u->reseller_profile_status ?? '—' }}
                    </div>
                    <div class="text-sm text-gray-600">
                        (incomplete / submitted / verified)
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap items-center gap-2">
                <a href="{{ route('home') }}"
                   class="px-4 py-2 rounded-xl bg-white border text-sm hover:bg-gray-50">
                    Public Home
                </a>

                <a href="{{ route('profile.show') }}"
                   class="px-4 py-2 rounded-xl bg-white border text-sm hover:bg-gray-50">
                    My Profile
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm">
                        Logout
                    </button>
                </form>
            </div>

            <div class="mt-4 text-xs text-gray-500">
                নোট: Approved হলে সরাসরি <span class="font-medium">/reseller</span> dashboard এ ঢুকতে পারবেন।
            </div>
        </div>
    </div>
</x-reseller-layout>

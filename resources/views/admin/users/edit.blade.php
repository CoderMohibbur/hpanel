<x-admin-layout
    title="Edit User"
    page-title="Edit User"
    page-desc="Role/Approval এবং Reseller Tree সেট করুন।"
>
    <x-slot name="actions">
        <a href="{{ route('admin.users.index') }}"
           class="px-3 py-2 rounded-lg bg-white border text-sm hover:bg-gray-50">
            ← Back
        </a>
    </x-slot>

    @php
        $roleBadge = [
            'super_admin' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'reseller'    => 'bg-blue-50 text-blue-700 border-blue-200',
            'client'      => 'bg-slate-50 text-slate-700 border-slate-200',
        ];

        $approvalBadge = [
            'approved'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'pending'    => 'bg-amber-50 text-amber-700 border-amber-200',
            'rejected'   => 'bg-rose-50 text-rose-700 border-rose-200',
            'suspended'  => 'bg-red-50 text-red-700 border-red-200',
        ];

        $initials = strtoupper(mb_substr($user->name, 0, 1));
        $rClass = $roleBadge[$user->role] ?? 'bg-gray-50 text-gray-700 border-gray-200';
        $aClass = $approvalBadge[$user->approval_status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Left: Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white border rounded-2xl p-5">
                <div class="flex items-center gap-4 pb-4 border-b">
                    <div class="w-12 h-12 rounded-2xl bg-gray-100 border flex items-center justify-center text-gray-700 font-semibold text-lg">
                        {{ $initials }}
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-900">{{ $user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs {{ $rClass }}">
                                {{ $user->role }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs {{ $aClass }}">
                                {{ $user->approval_status }}
                            </span>
                        </div>
                    </div>
                </div>

                <form
                    method="POST"
                    action="{{ route('admin.users.update', $user) }}"
                    class="mt-5 space-y-6"
                    x-data="{ role: '{{ $user->role }}' }"
                >
                    @csrf
                    @method('PUT')

                    {{-- Section: Role --}}
                    <div>
                        <div class="text-sm font-semibold text-gray-900">Role</div>
                        <p class="text-sm text-gray-500 mt-1">এই ইউজার কোন dashboard/access পাবে সেটি নির্ধারণ করুন।</p>

                        <div class="mt-3">
                            <label class="text-sm font-medium text-gray-700">User Role</label>
                            <select
                                name="role"
                                x-model="role"
                                class="w-full mt-1 px-3 py-2.5 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                            >
                                <option value="client">Client</option>
                                <option value="reseller">Reseller</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                            @error('role')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Section: Approval --}}
                    <div class="pt-5 border-t">
                        <div class="text-sm font-semibold text-gray-900">Approval Status</div>
                        <p class="text-sm text-gray-500 mt-1">Suspended দিলে login/access বন্ধ রাখতে পারবেন।</p>

                        <div class="mt-3">
                            <label class="text-sm font-medium text-gray-700">Approval</label>
                            <select
                                name="approval_status"
                                class="w-full mt-1 px-3 py-2.5 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                            >
                                <option value="approved" @selected($user->approval_status === 'approved')>Approved</option>
                                <option value="pending" @selected($user->approval_status === 'pending')>Pending</option>
                                <option value="rejected" @selected($user->approval_status === 'rejected')>Rejected</option>
                                <option value="suspended" @selected($user->approval_status === 'suspended')>Suspended</option>
                            </select>
                            @error('approval_status')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Section: Reseller Tree --}}
                    <div class="pt-5 border-t">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Reseller Tree</div>
                                <p class="text-sm text-gray-500 mt-1">শুধু Reseller role হলে এই ফিল্ডগুলো প্রযোজ্য।</p>
                            </div>

                            <div class="text-xs text-gray-500">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full border bg-gray-50">
                                    Auto-hide: <span class="font-medium text-gray-700">ON</span>
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4" :class="role !== 'reseller' ? 'opacity-50' : ''">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Parent Reseller</label>
                                <select
                                    name="parent_reseller_id"
                                    class="w-full mt-1 px-3 py-2.5 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                                    :disabled="role !== 'reseller'"
                                >
                                    <option value="">— none —</option>
                                    @foreach ($resellers as $r)
                                        <option value="{{ $r->id }}" @selected((string) $user->parent_reseller_id === (string) $r->id)>
                                            #{{ $r->id }} — {{ $r->name }} ({{ $r->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_reseller_id')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Reseller Profile Status</label>
                                <select
                                    name="reseller_profile_status"
                                    class="w-full mt-1 px-3 py-2.5 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                                    :disabled="role !== 'reseller'"
                                >
                                    <option value="">— null —</option>
                                    <option value="incomplete" @selected($user->reseller_profile_status === 'incomplete')>incomplete</option>
                                    <option value="submitted" @selected($user->reseller_profile_status === 'submitted')>submitted</option>
                                    <option value="verified" @selected($user->reseller_profile_status === 'verified')>verified</option>
                                </select>
                                @error('reseller_profile_status')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <template x-if="role !== 'reseller'">
                            <div class="mt-3 text-xs text-gray-500">
                                ℹ️ Reseller নয় বলে Parent Reseller/Profile Status disable আছে। Reseller সিলেক্ট করলে enable হবে।
                            </div>
                        </template>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-6 border-t flex items-center justify-end gap-2">
                        <a href="{{ route('admin.users.index') }}"
                           class="px-4 py-2.5 rounded-xl bg-white border text-sm hover:bg-gray-50">
                            Cancel
                        </a>
                        <button class="px-4 py-2.5 rounded-xl bg-gray-900 text-white text-sm hover:bg-gray-800">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Info Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white border rounded-2xl p-5">
                <div class="text-sm font-semibold text-gray-900">User Info</div>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">User ID</span>
                        <span class="font-medium text-gray-900">#{{ $user->id }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Role</span>
                        <span class="inline-flex px-3 py-1 rounded-full border text-xs {{ $rClass }}">{{ $user->role }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Approval</span>
                        <span class="inline-flex px-3 py-1 rounded-full border text-xs {{ $aClass }}">{{ $user->approval_status }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Parent Reseller</span>
                        <span class="font-medium text-gray-900">
                            {{ $user->parent_reseller_id ? '#'.$user->parent_reseller_id : '—' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Created</span>
                        <span class="font-medium text-gray-900">
                            {{ optional($user->created_at)->format('d M, Y') }}
                        </span>
                    </div>
                </div>

                <div class="mt-5 rounded-2xl border bg-gray-50 p-4 text-xs text-gray-600 leading-relaxed">
                    ✅ Best practice: User delete না করে approval_status = suspended ব্যবহার করুন।
                    Audit/History safe থাকবে।
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

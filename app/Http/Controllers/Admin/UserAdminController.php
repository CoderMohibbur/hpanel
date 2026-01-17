<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Services\AuditLogger;
use App\Http\Controllers\Controller;

class UserAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $role = $request->query('role');
        $approval = $request->query('approval_status');

        $users = User::query()
            ->select(['id', 'name', 'email', 'role', 'approval_status', 'parent_reseller_id', 'reseller_profile_status', 'created_at'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($role, fn($query) => $query->where('role', $role))
            ->when($approval, fn($query) => $query->where('approval_status', $approval))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q', 'role', 'approval'));
    }

    public function edit(User $user)
    {
        $resellers = User::query()
            ->where('role', User::ROLE_RESELLER)
            ->where('approval_status', User::APPROVAL_APPROVED)
            ->orderBy('name')
            ->limit(500)
            ->get(['id', 'name', 'email']);

        return view('admin.users.edit', compact('user', 'resellers'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', 'in:super_admin,reseller,client'],
            'approval_status' => ['required', 'in:pending,approved,rejected,suspended'],
            'parent_reseller_id' => [
                'nullable',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($user) {
                    if (!$value) return;
                    if ((int)$value === (int)$user->id) {
                        $fail('Parent reseller cannot be the same user.');
                        return;
                    }
                    $parent = User::query()->select('id', 'role', 'approval_status')->find($value);
                    if (!$parent || $parent->role !== User::ROLE_RESELLER || $parent->approval_status !== User::APPROVAL_APPROVED) {
                        $fail('Parent reseller must be an approved reseller.');
                    }
                },
            ],
            'reseller_profile_status' => ['nullable', 'in:incomplete,submitted,verified'],
        ]);

        $keys = ['role', 'approval_status', 'parent_reseller_id', 'reseller_profile_status'];
        $before = $user->only($keys);

        if (($data['role'] ?? null) !== User::ROLE_RESELLER) {
            $data['parent_reseller_id'] = null;
            $data['reseller_profile_status'] = null;
        }

        $user->update($data);

        // যদি আপনি spatie syncRoles বসিয়ে থাকেন
        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles([$user->role]);
        }

        $after = $user->fresh()->only($keys);

        // diff বের করে কেবল change হলে log
        $oldDiff = [];
        $newDiff = [];
        foreach ($after as $k => $v) {
            if (($before[$k] ?? null) !== $v) {
                $oldDiff[$k] = $before[$k] ?? null;
                $newDiff[$k] = $v;
            }
        }

        if (!empty($newDiff)) {
            AuditLog::create([
                'actor_id' => $request->user()?->id,
                'action' => 'admin.users.update',
                'target_type' => 'users',
                'target_id' => (int) $user->id,
                'old_values' => $oldDiff,
                'new_values' => $newDiff,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
}

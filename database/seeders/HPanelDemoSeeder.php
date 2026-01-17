<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HPanelDemoSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $admin = User::updateOrCreate(
            ['email' => 'admin@hpanel.test'],
            [
                'name' => 'Super Admin',
                'password' => $password,
                'email_verified_at' => now(),
                'role' => User::ROLE_SUPER_ADMIN,
                'approval_status' => User::APPROVAL_APPROVED,
                'parent_reseller_id' => null,
                'reseller_profile_status' => null,
            ]
        );
        $admin->syncRoles([$admin->role]);

        $resellerApproved = User::updateOrCreate(
            ['email' => 'reseller.approved@hpanel.test'],
            [
                'name' => 'Approved Reseller',
                'password' => $password,
                'email_verified_at' => now(),
                'role' => User::ROLE_RESELLER,
                'approval_status' => User::APPROVAL_APPROVED,
                'parent_reseller_id' => null,
                'reseller_profile_status' => User::PROFILE_VERIFIED,
            ]
        );
        $resellerApproved->syncRoles([$resellerApproved->role]);

        $resellerPending = User::updateOrCreate(
            ['email' => 'reseller.pending@hpanel.test'],
            [
                'name' => 'Pending Reseller',
                'password' => $password,
                'email_verified_at' => now(),
                'role' => User::ROLE_RESELLER,
                'approval_status' => User::APPROVAL_PENDING,
                'parent_reseller_id' => null,
                'reseller_profile_status' => User::PROFILE_SUBMITTED,
            ]
        );
        $resellerPending->syncRoles([$resellerPending->role]);

        $client = User::updateOrCreate(
            ['email' => 'client@hpanel.test'],
            [
                'name' => 'Client User',
                'password' => $password,
                'email_verified_at' => now(),
                'role' => User::ROLE_CLIENT,
                'approval_status' => User::APPROVAL_APPROVED,
                'parent_reseller_id' => null,
                'reseller_profile_status' => null,
            ]
        );
        $client->syncRoles([$client->role]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Hosting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Apply middleware to restrict access to authenticated admins only.
     */
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'admin']);
    // }

    /**
     * Display the admin dashboard with user list and statistics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // User list for the table
        $users = User::latest()->get();

        // Summary stats for future dashboard cards
        $totalUsers     = $users->count();
        $activeUsers    = $users->where('is_active', true)->count();
        $suspendedUsers = $users->where('is_active', false)->count();
        $adminUsers     = $users->where('is_admin', true)->count();
        $hostings = Hosting::latest()->take(5)->get(); // Optional: limit to 5 recent hostings


        return view('admin.dashboard', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'suspendedUsers',
            'adminUsers',
            'hostings' // ✅ add this
        ));
    }
}

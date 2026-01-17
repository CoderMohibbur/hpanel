Step-1: php artisan tinker

<!-- Admin email and password -->
\App\Models\User::create([
  'name' => 'Admin',
  'email' => 'admin@example.com',
  'password' => 'password',
  'role' => 'super_admin',
  'approval_status' => 'approved',
]);
<!-- Admin email and password -->


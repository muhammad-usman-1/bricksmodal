<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::find(52);
if($user) {
    echo 'User found: ' . $user->name . ' (' . $user->email . ')' . PHP_EOL;
    $user->load('roles');
    echo 'Roles: ' . $user->roles->pluck('title')->join(', ') . PHP_EOL;
    echo 'Role IDs: ' . $user->roles->pluck('id')->join(', ') . PHP_EOL;

    // Check role_user table
    $roleUsers = \Illuminate\Support\Facades\DB::table('role_user')->where('user_id', 52)->get();
    echo 'Role-User table entries: ' . $roleUsers->count() . PHP_EOL;
    foreach($roleUsers as $ru) {
        echo '  User 52 -> Role ' . $ru->role_id . PHP_EOL;
    }
} else {
    echo 'User 52 not found' . PHP_EOL;
}
?>

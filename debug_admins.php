<?php
$admins = \App\Models\User::whereHas('roles', function($query) {
    $query->whereIn('title', ['admin', 'superadmin', 'creative']);
})->with('roles')->get();

echo "Found " . $admins->count() . " admins:\n";
foreach ($admins as $admin) {
    echo " - " . $admin->email . " (Roles: " . $admin->roles->pluck('title')->implode(', ') . ")\n";
}

$template = \App\Models\NotificationTemplate::where('key', 'admin_shoot_application')->first();
if ($template) {
    echo "Template 'admin_shoot_application' exists and is " . ($template->is_active ? 'Active' : 'Inactive') . "\n";
} else {
    echo "Template 'admin_shoot_application' NOT FOUND\n";
}

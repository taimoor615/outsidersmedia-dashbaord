<?php

// ─── SECURITY TOKEN ──────────────────────────────────────────────────────────
// Only runs if ?token=... matches. Change this after use or delete the file.
define('SECRET_TOKEN', 'b73e00834a5157e2cfaea824b41e0f56');

if (!isset($_GET['token']) || $_GET['token'] !== SECRET_TOKEN) {
    http_response_code(403);
    die('Forbidden.');
}

// ─── BOOTSTRAP LARAVEL ───────────────────────────────────────────────────────
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ─── CREATE ADMIN ────────────────────────────────────────────────────────────
$email    = 'tech@outsidersmedia.com';
$name     = 'Tech Admin';
$password = 'Admin@2026!';

$existing = \App\Models\User::where('email', $email)->first();

if ($existing) {
    echo "<b style='color:orange'>User already exists:</b> {$existing->name} ({$existing->email}) — role: {$existing->role}";
} else {
    \App\Models\User::create([
        'name'     => $name,
        'email'    => $email,
        'password' => \Illuminate\Support\Facades\Hash::make($password),
        'role'     => 'admin',
    ]);
    echo "<b style='color:green'>✅ Admin created successfully!</b><br><br>";
    echo "Email: <b>{$email}</b><br>";
    echo "Password: <b>{$password}</b><br><br>";
    echo "<b style='color:red'>⚠️ DELETE this file from your server immediately after seeing this message.</b>";
}

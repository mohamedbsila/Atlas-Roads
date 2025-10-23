<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST CONFIGURATION LARAVEL ===\n\n";
echo "GEMINI_API_KEY: " . (env('GEMINI_API_KEY') ? "✅ Présente" : "❌ Absente") . "\n";
echo "GEMINI_MODEL: " . env('GEMINI_MODEL', 'non défini') . "\n";
echo "APP_ENV: " . env('APP_ENV') . "\n";
echo "APP_DEBUG: " . (env('APP_DEBUG') ? 'true' : 'false') . "\n";
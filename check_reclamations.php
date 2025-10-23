<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illware\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Get status counts
echo "Reclamation status counts:\n";
$statusCounts = DB::table('reclamations')
    ->select('statut', DB::raw('count(*) as count'))
    ->groupBy('statut')
    ->get();

foreach ($statusCounts as $row) {
    echo "- {$row->statut}: {$row->count}\n";
}

// Get a sample of reclamations
echo "\nSample of reclamations (first 5):\n";
$reclamations = DB::table('reclamations')
    ->select('id', 'titre', 'statut', 'created_at')
    ->orderBy('id', 'desc')
    ->limit(5)
    ->get();

foreach ($reclamations as $rec) {
    echo "ID: {$rec->id}, Title: {$rec->titre}, Status: {$rec->statut}, Created: {$rec->created_at}\n";
}

// Check if there are any reclamations with 'en_attente' or 'en_cours' status
$pendingCount = DB::table('reclamations')
    ->whereIn('statut', ['en_attente', 'en_cours'])
    ->count();

echo "\nTotal reclamations with status 'en_attente' or 'en_cours': {$pendingCount}\n";

// Check if there are any solutions in the solutions table
$solutionsCount = DB::table('solutions')->count();
echo "Total solutions in database: {$solutionsCount}\n";

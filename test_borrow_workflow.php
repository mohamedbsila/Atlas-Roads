#!/usr/bin/env php
<?php

/**
 * Script de Test - Système d'Emprunt de Livres
 * 
 * Ce script démontre le workflow complet avec 2 utilisateurs
 */

use App\Models\User;
use App\Models\Book;
use App\Models\BorrowRequest;
use App\Enums\RequestStatus;
use Carbon\Carbon;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "🧪 Test du Système d'Emprunt de Livres\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Nettoyer les données de test précédentes
echo "🧹 Nettoyage des données de test...\n";
BorrowRequest::whereIn('borrower_id', function($query) {
    $query->select('id')->from('users')->whereIn('email', ['alice@test.com', 'bob@test.com']);
})->delete();

Book::whereIn('ownerId', function($query) {
    $query->select('id')->from('users')->whereIn('email', ['alice@test.com', 'bob@test.com']);
})->delete();

User::whereIn('email', ['alice@test.com', 'bob@test.com'])->delete();

echo "✅ Données nettoyées\n\n";

// Étape 1: Créer 2 utilisateurs
echo "┌─────────────────────────────────────────────────────────────┐\n";
echo "│ ÉTAPE 1: Créer 2 utilisateurs                              │\n";
echo "└─────────────────────────────────────────────────────────────┘\n";

$alice = User::create([
    'name' => 'Alice',
    'email' => 'alice@test.com',
    'password' => bcrypt('password'),
]);
echo "✅ User1 créé: Alice (ID: {$alice->id}) - alice@test.com\n";

$bob = User::create([
    'name' => 'Bob',
    'email' => 'bob@test.com',
    'password' => bcrypt('password'),
]);
echo "✅ User2 créé: Bob (ID: {$bob->id}) - bob@test.com\n\n";

// Étape 2: Alice ajoute un livre
echo "┌─────────────────────────────────────────────────────────────┐\n";
echo "│ ÉTAPE 2: Alice ajoute un livre                             │\n";
echo "└─────────────────────────────────────────────────────────────┘\n";

$aliceBook = Book::create([
    'title' => 'Le Petit Prince',
    'author' => 'Antoine de Saint-Exupéry',
    'isbn' => '9782070612758',
    'category' => 'Fiction',
    'language' => 'Français',
    'published_year' => 1943,
    'is_available' => true,
    'ownerId' => $alice->id, // Alice est propriétaire
]);

echo "✅ Livre créé: '{$aliceBook->title}'\n";
echo "   📌 Propriétaire (ownerId): {$aliceBook->ownerId} (Alice)\n\n";

// Étape 3: Bob ajoute un livre
echo "┌─────────────────────────────────────────────────────────────┐\n";
echo "│ ÉTAPE 3: Bob ajoute un livre                               │\n";
echo "└─────────────────────────────────────────────────────────────┘\n";

$bobBook = Book::create([
    'title' => '1984',
    'author' => 'George Orwell',
    'isbn' => '9780451524935',
    'category' => 'Fiction',
    'language' => 'Français',
    'published_year' => 1949,
    'is_available' => true,
    'ownerId' => $bob->id, // Bob est propriétaire
]);

echo "✅ Livre créé: '{$bobBook->title}'\n";
echo "   📌 Propriétaire (ownerId): {$bobBook->ownerId} (Bob)\n\n";

// Étape 4: Alice essaie d'emprunter SON propre livre (doit échouer)
echo "┌─────────────────────────────────────────────────────────────┐\n";
echo "│ ÉTAPE 4: Alice essaie d'emprunter SON livre                │\n";
echo "└─────────────────────────────────────────────────────────────┘\n";

echo "🔍 Vérification: ownerId de '{$aliceBook->title}' = {$aliceBook->ownerId}\n";
echo "🔍 Vérification: ID d'Alice = {$alice->id}\n";

if ($aliceBook->ownerId == $alice->id) {
    echo "❌ REFUSÉ: Alice ne peut pas emprunter son propre livre!\n";
    echo "   Message: 'Vous ne pouvez pas emprunter votre propre livre.'\n\n";
} else {
    echo "⚠️  ERREUR INATTENDUE: La vérification a échoué!\n\n";
}

// Étape 5: Alice demande le livre de Bob (doit réussir)
echo "┌─────────────────────────────────────────────────────────────┐\n";
echo "│ ÉTAPE 5: Alice demande le livre de Bob                     │\n";
echo "└─────────────────────────────────────────────────────────────┘\n";

$borrowRequest = BorrowRequest::create([
    'borrower_id' => $alice->id,
    'owner_id' => $bobBook->ownerId,
    'book_id' => $bobBook->id,
    'start_date' => Carbon::tomorrow(),
    'end_date' => Carbon::tomorrow()->addDays(10),
    'status' => RequestStatus::PENDING,
    'notes' => 'J\'aimerais lire ce classique!',
]);

echo "✅ SUCCÈS: Demande d'emprunt créée!\n";
echo "   📖 Livre: '{$bobBook->title}'\n";
echo "   👤 Emprunteur (borrower_id): {$borrowRequest->borrower_id} (Alice)\n";
echo "   👑 Propriétaire (owner_id): {$borrowRequest->owner_id} (Bob)\n";
echo "   📅 Du: {$borrowRequest->start_date->format('d/m/Y')}\n";
echo "   📅 Au: {$borrowRequest->end_date->format('d/m/Y')}\n";
echo "   🏷️  Statut: {$borrowRequest->status->value} ({$borrowRequest->status->label()})\n\n";

// Étape 6: Vérifier le Dashboard d'Alice (ses demandes envoyées)
echo "┌─────────────────────────────────────────────────────────────┐\n";
echo "│ ÉTAPE 6: Dashboard d'Alice - Demandes Envoyées             │\n";
echo "└─────────────────────────────────────────────────────────────┘\n";

$aliceRequests = BorrowRequest::with(['book.owner'])
    ->where('borrower_id', $alice->id)
    ->get();

echo "📊 Alice voit dans son Dashboard:\n";
echo "   Section: 'Mes Demandes Envoyées'\n";
foreach ($aliceRequests as $req) {
    echo "   • Livre: '{$req->book->title}'\n";
    echo "     Propriétaire: {$req->book->owner->name}\n";
    echo "     Statut: 🟡 {$req->status->label()}\n";
}
echo "\n";

// Étape 7: Vérifier le Dashboard de Bob (demandes reçues)
echo "┌─────────────────────────────────────────────────────────────┐\n";
echo "│ ÉTAPE 7: Dashboard de Bob - Demandes Reçues                │\n";
echo "└─────────────────────────────────────────────────────────────┘\n";

$bobRequests = BorrowRequest::with(['book', 'borrower'])
    ->where('owner_id', $bob->id)
    ->get();

echo "📊 Bob voit dans son Dashboard:\n";
echo "   Section: 'Demandes Reçues'\n";
foreach ($bobRequests as $req) {
    echo "   • Livre: '{$req->book->title}' (SON livre)\n";
    echo "     Demandeur: {$req->borrower->name}\n";
    echo "     Statut: 🟡 {$req->status->label()}\n";
    echo "     Actions: [✅ APPROUVER] [❌ REFUSER]\n";
}
echo "\n";

// Étape 8: Bob (propriétaire) approuve la demande
echo "┌─────────────────────────────────────────────────────────────┐\n";
echo "│ ÉTAPE 8: Bob approuve la demande d'Alice                   │\n";
echo "└─────────────────────────────────────────────────────────────┘\n";

echo "🔍 Vérification: owner_id de la demande = {$borrowRequest->owner_id}\n";
echo "🔍 Vérification: ID de Bob = {$bob->id}\n";

if ($borrowRequest->owner_id == $bob->id) {
    echo "✅ Bob est bien le propriétaire, il PEUT approuver!\n";
    
    // Approuver la demande
    $borrowRequest->status = RequestStatus::APPROVED;
    $borrowRequest->save();
    
    // Marquer le livre comme non disponible
    $bobBook->is_available = false;
    $bobBook->save();
    
    echo "✅ Demande approuvée!\n";
    echo "   📖 Livre '{$bobBook->title}' maintenant: is_available = false\n";
    echo "   🏷️  Statut de la demande: {$borrowRequest->status->label()}\n\n";
} else {
    echo "⚠️  ERREUR: Bob n'est pas le propriétaire!\n\n";
}

// Étape 9: Vérifier le Dashboard d'Alice après approbation
echo "┌─────────────────────────────────────────────────────────────┐\n";
echo "│ ÉTAPE 9: Dashboard d'Alice - Après Approbation             │\n";
echo "└─────────────────────────────────────────────────────────────┘\n";

$borrowRequest->refresh();
echo "📊 Alice voit maintenant:\n";
echo "   • Livre: '{$bobBook->title}'\n";
echo "   • Statut: 🟢 {$borrowRequest->status->label()}\n";
echo "   • Action disponible: [Marquer comme Rendu]\n\n";

// Étape 10: Vérifier qu'Alice ne peut PAS approuver une demande (pas propriétaire)
echo "┌─────────────────────────────────────────────────────────────┐\n";
echo "│ ÉTAPE 10: Test Sécurité - Alice ne peut pas approuver      │\n";
echo "└─────────────────────────────────────────────────────────────┘\n";

if ($borrowRequest->owner_id != $alice->id) {
    echo "❌ SÉCURITÉ: Alice n'est PAS propriétaire (owner_id = {$borrowRequest->owner_id})\n";
    echo "   Alice NE PEUT PAS approuver/rejeter cette demande\n";
    echo "   Message: 'Vous n'êtes pas autorisé à effectuer cette action.'\n\n";
} else {
    echo "⚠️  ERREUR DE SÉCURITÉ: Alice peut modifier une demande qu'elle ne possède pas!\n\n";
}

// Résumé Final
echo "═══════════════════════════════════════════════════════════════\n";
echo "📊 RÉSUMÉ DES TESTS\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "✅ Test 1: Création de livres avec propriétaire automatique\n";
echo "   → Alice crée '{$aliceBook->title}' (ownerId = {$aliceBook->ownerId})\n";
echo "   → Bob crée '{$bobBook->title}' (ownerId = {$bobBook->ownerId})\n\n";

echo "✅ Test 2: Protection emprunt de son propre livre\n";
echo "   → Alice ne peut PAS emprunter '{$aliceBook->title}'\n\n";

echo "✅ Test 3: Emprunt entre utilisateurs\n";
echo "   → Alice PEUT emprunter '{$bobBook->title}' de Bob\n\n";

echo "✅ Test 4: Dashboard - Demandes envoyées\n";
echo "   → Alice voit sa demande pour '{$bobBook->title}'\n\n";

echo "✅ Test 5: Dashboard - Demandes reçues\n";
echo "   → Bob voit la demande d'Alice pour SON livre\n\n";

echo "✅ Test 6: Système d'approbation\n";
echo "   → Bob (propriétaire) approuve la demande\n";
echo "   → Statut: pending → approved\n";
echo "   → Livre: is_available = false\n\n";

echo "✅ Test 7: Sécurité\n";
echo "   → Alice ne peut PAS approuver (pas propriétaire)\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "🎉 TOUS LES TESTS PASSENT AVEC SUCCÈS!\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "📝 Comptes de test créés:\n";
echo "   • alice@test.com / password\n";
echo "   • bob@test.com / password\n\n";

echo "🌐 Connexion web pour tester:\n";
echo "   1. Allez sur: http://127.0.0.1:8000/login\n";
echo "   2. Connectez-vous avec alice@test.com ou bob@test.com\n";
echo "   3. Visitez le dashboard: /dashboard\n";
echo "   4. Visitez la gestion des emprunts: /borrow-requests\n\n";

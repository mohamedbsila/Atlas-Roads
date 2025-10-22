#!/usr/bin/env php
<?php

/**
 * Script pour créer des utilisateurs de test pour Stripe
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "🎯 Création des Utilisateurs de Test pour Stripe\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Supprimer les anciens utilisateurs de test s'ils existent
echo "🧹 Nettoyage des anciens utilisateurs de test...\n";
User::whereIn('email', ['alice@stripe-test.com', 'bob@stripe-test.com'])->delete();
echo "✅ Nettoyage terminé\n\n";

// Créer Alice (propriétaire de livres)
echo "👩 Création d'Alice (propriétaire)...\n";
$alice = User::create([
    'name' => 'Alice Martin',
    'email' => 'alice@stripe-test.com',
    'password' => bcrypt('password123')
]);
echo "✅ Alice créée - ID: {$alice->id}\n";
echo "   📧 Email: alice@stripe-test.com\n";
echo "   🔑 Mot de passe: password123\n\n";

// Créer Bob (emprunteur)
echo "👨 Création de Bob (emprunteur)...\n";
$bob = User::create([
    'name' => 'Bob Dupont',
    'email' => 'bob@stripe-test.com',
    'password' => bcrypt('password123')
]);
echo "✅ Bob créé - ID: {$bob->id}\n";
echo "   📧 Email: bob@stripe-test.com\n";
echo "   🔑 Mot de passe: password123\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "✅ TERMINÉ! Utilisateurs de test créés avec succès!\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "📝 PROCHAINES ÉTAPES:\n\n";
echo "1️⃣  Connectez-vous avec Alice:\n";
echo "    http://127.0.0.1:8000/login\n";
echo "    Email: alice@stripe-test.com\n";
echo "    Mot de passe: password123\n\n";

echo "2️⃣  Créez un livre (Alice):\n";
echo "    http://127.0.0.1:8000/books/create\n";
echo "    Titre: Le Petit Prince\n";
echo "    Auteur: Antoine de Saint-Exupéry\n";
echo "    Prix: 20\n\n";

echo "3️⃣  Déconnectez Alice et connectez Bob:\n";
echo "    Email: bob@stripe-test.com\n";
echo "    Mot de passe: password123\n\n";

echo "4️⃣  Bob emprunte le livre:\n";
echo "    → Redirection automatique vers Stripe\n";
echo "    → Utilisez la carte: 4242 4242 4242 4242\n";
echo "    → Date: 12/25, CVC: 123\n\n";

echo "5️⃣  Vérifiez le paiement:\n";
echo "    http://127.0.0.1:8000/payments\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "💳 CARTE DE TEST STRIPE:\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "Numéro:      4242 4242 4242 4242\n";
echo "Date:        12/25 (ou n'importe quelle date future)\n";
echo "CVC:         123 (ou n'importe quel code)\n";
echo "Code postal: 12345\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "🎉 Bon test!\n\n";

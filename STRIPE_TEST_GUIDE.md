# 🧪 Script de Test Stripe - Pas à Pas

## 🎯 Objectif
Tester l'intégration Stripe complète avec un scénario réel.

---

## ⚙️ Pré-requis

### 1. Serveur démarré
```bash
php artisan serve
```

### 2. Clés Stripe configurées dans `.env`
```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
```

### 3. Base de données à jour
```bash
php artisan migrate
```

---

## 📝 TEST 1: Emprunt avec Paiement Stripe

### Étape 1: Créer les utilisateurs de test
```bash
php artisan tinker
```

Dans Tinker, copiez-collez:
```php
// Créer Alice (propriétaire du livre)
$alice = \App\Models\User::create([
    'name' => 'Alice Martin',
    'email' => 'alice@stripe-test.com',
    'password' => bcrypt('password123')
]);

// Créer Bob (emprunteur)
$bob = \App\Models\User::create([
    'name' => 'Bob Dupont',
    'email' => 'bob@stripe-test.com',
    'password' => bcrypt('password123')
]);

echo "✅ Alice ID: " . $alice->id . "\n";
echo "✅ Bob ID: " . $bob->id . "\n";

exit
```

### Étape 2: Alice crée un livre
1. **Navigateur**: http://127.0.0.1:8000/login
2. **Se connecter**: alice@stripe-test.com / password123
3. **Aller sur**: http://127.0.0.1:8000/books/create
4. **Remplir le formulaire**:
   - Titre: `Le Petit Prince`
   - Auteur: `Antoine de Saint-Exupéry`
   - ISBN: `978-0156012195`
   - Catégorie: `Fiction`
   - Langue: `French`
   - Prix: **20** ← IMPORTANT !
   - Année: `1943`
   - ✅ Cocher "Disponible"
5. **Cliquer**: "Add Book"

**✅ Résultat attendu**: "Book has been successfully added!"

### Étape 3: Bob emprunte le livre d'Alice
1. **Se déconnecter** (Alice)
2. **Se connecter**: bob@stripe-test.com / password123
3. **Aller sur**: http://127.0.0.1:8000 (page d'accueil)
4. **Trouver**: "Le Petit Prince"
5. **Cliquer**: "Emprunter"
6. **Remplir le formulaire**:
   - Date de début: `2025-10-25` (demain ou plus tard)
   - Date de fin: `2025-11-10`
   - Email: `bob@stripe-test.com`
   - Notes: `Test Stripe integration`
7. **Cliquer**: "Submit Request"

**🔄 Redirection automatique vers Stripe Checkout**

### Étape 4: Page Stripe Checkout
Vous devriez voir:
- **Nom du produit**: Le Petit Prince
- **Description**: Emprunt du livre: Le Petit Prince
- **Montant**: **$5.00** (20 ÷ 4)
- **Email**: bob@stripe-test.com (pré-rempli)

**Entrer les informations de carte**:
```
Email:          bob@stripe-test.com
Numéro:         4242 4242 4242 4242
Date:           12/25
CVC:            123
Nom:            Bob Dupont
Code postal:    12345
```

**Cliquer**: "Pay"

### Étape 5: Vérification du paiement
**🔄 Redirection automatique vers**: http://127.0.0.1:8000/payments/success

**✅ Résultat attendu**: 
- Message vert: "Paiement effectué avec succès! 🎉"
- Redirection vers `/payments`

### Étape 6: Vérifier dans la liste des paiements
**Page**: http://127.0.0.1:8000/payments

**Section "My Payments"** (Bob):
- ✅ Livre: Le Petit Prince
- ✅ Type: Borrow
- ✅ Total: $5.00
- ✅ Per Day: $0.0056 (environ)
- ✅ Status: Badge vert "Paid"
- ✅ Action: "✅ stripe"

**🎉 TEST 1 RÉUSSI !**

---

## 📝 TEST 2: Achat avec Paiement Stripe

### Étape 1: Se connecter avec Bob
bob@stripe-test.com / password123

### Étape 2: Aller sur la page d'accueil
http://127.0.0.1:8000

### Étape 3: Cliquer sur "Voir" sur un livre d'Alice
(Par exemple "Le Petit Prince")

### Étape 4: Cliquer sur "Acheter définitivement"
**Note**: Le bouton apparaît uniquement si:
- ✅ Le livre a un prix
- ✅ Le livre est disponible
- ✅ Le livre n'appartient pas à Bob

**🔄 Redirection automatique vers Stripe Checkout**

### Étape 5: Page Stripe Checkout
Vous devriez voir:
- **Nom du produit**: Le Petit Prince
- **Description**: Achat définitif du livre: Le Petit Prince
- **Montant**: **$20.00** (prix complet)

**Entrer la carte de test**:
```
Numéro:  4242 4242 4242 4242
Date:    12/26
CVC:     456
```

**Cliquer**: "Pay"

### Étape 6: Vérification
**✅ Résultat attendu**:
- Message: "Paiement effectué avec succès! 🎉"
- Le livre appartient maintenant à Bob
- Dans `/books`, Bob peut voir "Le Petit Prince" dans sa liste

### Étape 7: Vérifier le transfert de propriété
```bash
php artisan tinker
```

```php
$livre = \App\Models\Book::where('title', 'Le Petit Prince')->first();
$bob = \App\Models\User::where('email', 'bob@stripe-test.com')->first();

echo "Propriétaire du livre: " . $livre->ownerId . "\n";
echo "ID de Bob: " . $bob->id . "\n";
echo "Match: " . ($livre->ownerId === $bob->id ? "OUI ✅" : "NON ❌") . "\n";

exit
```

**✅ Résultat attendu**: "Match: OUI ✅"

**🎉 TEST 2 RÉUSSI !**

---

## 📝 TEST 3: Vérification dans Stripe Dashboard

### Étape 1: Aller sur Stripe Dashboard
https://dashboard.stripe.com/test/payments

### Étape 2: Vérifier les paiements
Vous devriez voir:
- ✅ 2 paiements réussis
- ✅ Montants: $5.00 et $20.00
- ✅ Statut: "Succeeded"
- ✅ Email: bob@stripe-test.com

### Étape 3: Cliquer sur un paiement
**Détails visibles**:
- Payment Intent ID: `pi_...`
- Customer Email: bob@stripe-test.com
- Amount: $5.00 ou $20.00
- Metadata:
  - `payment_id`: 1 ou 2
  - `book_id`: ...
  - `type`: borrow ou purchase

**🎉 TEST 3 RÉUSSI !**

---

## 📝 TEST 4: Test de Carte Déclinée

### Étape 1: Créer une nouvelle demande d'emprunt
(Suivre les étapes du TEST 1, étapes 1-3)

### Étape 2: Sur Stripe Checkout, utiliser cette carte
```
Numéro:  4000 0000 0000 0002
Date:    12/25
CVC:     123
```

### Étape 3: Cliquer "Pay"
**✅ Résultat attendu**: 
- ❌ Message d'erreur Stripe: "Your card was declined"
- Le paiement n'est PAS créé
- Retour possible avec le bouton "←"

**🎉 TEST 4 RÉUSSI !**

---

## 📝 TEST 5: Test d'Annulation

### Étape 1: Créer une nouvelle demande d'emprunt

### Étape 2: Sur Stripe Checkout, cliquer sur "←" (retour)

### Étape 3: Vérification
**✅ Résultat attendu**:
- Redirection vers `/payments/cancel/{payment_id}`
- Message: "Paiement annulé. Vous pouvez réessayer plus tard."
- Le paiement reste en statut "pending"

**🎉 TEST 5 RÉUSSI !**

---

## 📝 TEST 6: Webhook Stripe (Optionnel)

### Prérequis: Installer Stripe CLI
```bash
# Windows (avec Scoop)
scoop bucket add stripe https://github.com/stripe/scoop-stripe-cli.git
scoop install stripe

# Ou télécharger depuis: https://github.com/stripe/stripe-cli/releases
```

### Étape 1: Authentifier Stripe CLI
```bash
stripe login
```

### Étape 2: Écouter les webhooks
```bash
stripe listen --forward-to localhost:8000/stripe/webhook
```

**✅ Résultat**: Un webhook secret apparaît (whsec_...)

### Étape 3: Mettre à jour `.env`
```env
STRIPE_WEBHOOK_SECRET=whsec_... (copier la valeur)
```

### Étape 4: Redémarrer le serveur
```bash
php artisan serve
```

### Étape 5: Faire un paiement test
(Suivre TEST 1 ou TEST 2)

### Étape 6: Vérifier dans le terminal Stripe CLI
Vous devriez voir:
```
2025-10-22 15:30:45  --> checkout.session.completed [evt_...]
2025-10-22 15:30:45  <-- [200] POST http://localhost:8000/stripe/webhook
```

**🎉 TEST 6 RÉUSSI !**

---

## 📊 Résumé des Tests

| Test | Description | Statut |
|------|-------------|--------|
| TEST 1 | Emprunt avec paiement Stripe | ✅ |
| TEST 2 | Achat avec paiement Stripe | ✅ |
| TEST 3 | Vérification Stripe Dashboard | ✅ |
| TEST 4 | Carte déclinée | ✅ |
| TEST 5 | Annulation de paiement | ✅ |
| TEST 6 | Webhook Stripe | ✅ (optionnel) |

---

## 🎉 Félicitations !

Si tous les tests sont passés, votre intégration Stripe est **100% fonctionnelle** ! 🚀

### Prochaines étapes
1. Obtenir vos **vraies clés Stripe** (live)
2. Configurer le webhook en production
3. Tester avec une **vraie carte bancaire**
4. Lancer en production ! 🎊

---

## 🐛 Dépannage

### Erreur: "Invalid API Key"
- Vérifiez `.env`: les clés commencent bien par `pk_test_` et `sk_test_`
- Redémarrez le serveur: `php artisan serve`

### Pas de redirection vers Stripe
- Vérifiez les logs: `storage/logs/laravel.log`
- Assurez-vous que le prix du livre est > 0

### Webhook non reçu
- Vérifiez que Stripe CLI écoute sur le bon port
- Vérifiez le `STRIPE_WEBHOOK_SECRET` dans `.env`

### Paiement bloqué sur "pending"
- Vérifiez la console de votre navigateur (F12)
- Essayez de marquer manuellement comme payé

---

**Bon test ! 🧪✨**

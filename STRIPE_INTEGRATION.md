# 💳 Intégration Stripe - Guide Complet

## 🎯 Vue d'Ensemble

Votre système de bibliothèque Atlas Roads est maintenant intégré avec **Stripe** pour gérer les paiements réels lors de l'achat ou de l'emprunt de livres.

---

## ✅ Ce qui a été fait

### 1. **Installation du package Stripe** ✔️
- Package `stripe/stripe-php v18.0.0` installé via Composer
- Bibliothèque officielle Stripe pour PHP

### 2. **Configuration des clés API** ✔️
Fichiers modifiés:
- `.env` - Ajout des clés Stripe
- `config/services.php` - Configuration du service Stripe

### 3. **Migration de la base de données** ✔️
Nouvelle migration créée: `2025_10_22_034331_add_stripe_fields_to_payments_table`

Champs ajoutés à la table `payments`:
- `stripe_payment_intent_id` - ID du PaymentIntent Stripe
- `stripe_session_id` - ID de la session Checkout
- `stripe_customer_id` - ID du client Stripe

### 4. **Contrôleur de paiement amélioré** ✔️
`app/Http/Controllers/PaymentController.php` - Nouvelles méthodes:
- `purchase()` - Crée une session Stripe pour achat
- `createBorrowCheckoutSession()` - Crée une session Stripe pour emprunt
- `createStripeCheckoutSession()` - Méthode privée générique
- `success()` - Gère le retour après paiement réussi
- `cancel()` - Gère l'annulation du paiement
- `webhook()` - Webhook pour confirmation de paiement

### 5. **Routes créées** ✔️
```php
POST   /stripe/webhook              # Webhook Stripe (sans auth)
GET    /payments/success            # Page de succès
GET    /payments/cancel/{payment}   # Page d'annulation
POST   /borrow-requests/{id}/pay    # Payer un emprunt
```

### 6. **Interface utilisateur** ✔️
- Bouton "💳 Pay with Stripe" dans `/payments`
- Redirection automatique vers Stripe Checkout lors d'un emprunt
- Messages de succès/erreur après paiement

---

## 🔑 Configuration des Clés Stripe

### Étape 1: Créer un compte Stripe
1. Allez sur [https://stripe.com](https://stripe.com)
2. Créez un compte gratuit
3. Accédez au **Dashboard**

### Étape 2: Récupérer vos clés API
1. Dans le Dashboard Stripe, allez dans **Développeurs** > **Clés API**
2. Copiez vos clés de **test** (elles commencent par `pk_test_` et `sk_test_`)

### Étape 3: Mettre à jour le fichier `.env`
```env
STRIPE_KEY=pk_test_51QItZbGrQjUWxLQE...VotreClePubique
STRIPE_SECRET=sk_test_51QItZbGrQjUWxLQE...VotreCleSecrete
STRIPE_WEBHOOK_SECRET=whsec_...VotreSecretWebhook
```

⚠️ **Important**: Utilisez les clés de **test** pour le développement !

---

## 🎬 Comment ça fonctionne

### Workflow d'Emprunt avec Stripe

```
1. User clique "Emprunter ce livre"
   ↓
2. Formulaire d'emprunt soumis
   ↓
3. BorrowRequest créé avec status "PENDING"
   ↓
4. Payment créé avec type "borrow" et status "pending"
   ↓
5. Session Stripe Checkout créée automatiquement
   ↓
6. User redirigé vers Stripe pour payer (carte bancaire)
   ↓
7. Paiement effectué ou annulé
   ↓
8. User redirigé vers /payments/success ou /payments/cancel
   ↓
9. Payment status mis à jour: "pending" → "paid"
   ↓
10. Webhook Stripe confirme le paiement (sécurité supplémentaire)
```

### Workflow d'Achat avec Stripe

```
1. User clique "Acheter définitivement"
   ↓
2. Payment créé avec type "purchase" et status "pending"
   ↓
3. Session Stripe Checkout créée
   ↓
4. User redirigé vers Stripe
   ↓
5. Paiement effectué
   ↓
6. Payment status → "paid"
   ↓
7. Transfert de propriété du livre (ownerId → borrower_id)
   ↓
8. Annulation des demandes d'emprunt actives
```

---

## 🧪 Tester avec Stripe (Mode Test)

### Cartes de test Stripe

Pour tester les paiements, utilisez ces numéros de carte:

| Numéro | Résultat |
|--------|----------|
| `4242 4242 4242 4242` | ✅ Paiement réussi |
| `4000 0000 0000 0002` | ❌ Carte déclinée |
| `4000 0000 0000 9995` | ❌ Fonds insuffisants |

**Détails de test**:
- **Date d'expiration**: N'importe quelle date future (ex: 12/25)
- **CVC**: N'importe quel code à 3 chiffres (ex: 123)
- **Code postal**: N'importe lequel (ex: 12345)

### Test complet

1. **Démarrer le serveur**:
   ```bash
   php artisan serve
   ```

2. **Créer 2 utilisateurs**:
   - Alice (alice@test.com)
   - Bob (bob@test.com)

3. **Scénario de test**:
   ```
   Alice ajoute un livre "1984" à 20$
   Bob emprunte le livre
   → Redirection vers Stripe
   → Montant: 5$ (20$/4)
   → Payer avec 4242 4242 4242 4242
   → Succès!
   → Bob retourne sur /payments
   → Status: "Paid" ✅
   ```

---

## 🔒 Sécurité

### Webhook Stripe
Le webhook est configuré pour vérifier la signature des événements Stripe:
```php
$event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
```

### Configuration du Webhook
1. Dans Stripe Dashboard → **Développeurs** > **Webhooks**
2. Ajouter un endpoint: `https://votre-domaine.com/stripe/webhook`
3. Sélectionner l'événement: `checkout.session.completed`
4. Copier le **Signing secret** et le mettre dans `.env`:
   ```env
   STRIPE_WEBHOOK_SECRET=whsec_...
   ```

⚠️ **Important pour le développement local**:
Pour tester les webhooks en local, utilisez **Stripe CLI**:
```bash
stripe listen --forward-to localhost:8000/stripe/webhook
```

---

## 💰 Montants et Devises

### Configuration actuelle
- **Devise**: USD (dollars américains)
- **Emprunt**: Prix du livre ÷ 4
- **Achat**: Prix complet du livre

### Changer la devise
Modifiez dans `PaymentController.php`:
```php
'currency' => 'eur', // Pour euros
```

Devises supportées par Stripe: USD, EUR, GBP, CAD, CHF, etc.

---

## 📊 Données Stripe dans la base

Exemple de paiement avec Stripe:
```
Payment {
  id: 1,
  borrower_id: 2,
  book_id: 5,
  amount_total: 5.00,
  status: 'paid',
  method: 'stripe',
  stripe_payment_intent_id: 'pi_3ABC123...',
  stripe_session_id: 'cs_test_a1b2c3...',
  stripe_customer_id: 'cus_ABC123...',
  paid_at: '2025-10-22 15:30:00'
}
```

---

## 🎨 Personnalisation Stripe Checkout

### Ajouter un logo
```php
'images' => [$book->image_url],
```

### Personnaliser les couleurs
Dans Stripe Dashboard → **Paramètres** > **Branding**

### Ajouter des métadonnées
```php
'metadata' => [
    'payment_id' => $payment->id,
    'book_id' => $book->id,
    'type' => $payment->type,
    'user_email' => $user->email,
],
```

---

## 🚀 Passer en Production

### Checklist de production

- [ ] Remplacer les clés de test par les clés **live** (pk_live_, sk_live_)
- [ ] Configurer le webhook en production (https obligatoire)
- [ ] Activer l'authentification 3D Secure (recommandé)
- [ ] Vérifier les montants minimums (0.50$ pour USD)
- [ ] Tester avec une vraie carte bancaire
- [ ] Configurer les emails de confirmation Stripe
- [ ] Mettre à jour les URLs de success/cancel avec votre domaine

### URLs de production
```php
'success_url' => 'https://atlas-roads.com/payments/success?session_id={CHECKOUT_SESSION_ID}',
'cancel_url' => 'https://atlas-roads.com/payments/cancel/{payment}',
```

---

## 🛠️ Dépannage

### Erreur "Invalid API Key"
→ Vérifiez que vos clés dans `.env` sont correctes

### Erreur "Amount must be at least 0.50 usd"
→ Le montant du livre est trop bas (minimum 2$ pour emprunt)

### Webhook non reçu
→ Vérifiez le `STRIPE_WEBHOOK_SECRET` dans `.env`
→ Utilisez Stripe CLI pour tester en local

### Paiement bloqué sur "pending"
→ Le webhook n'a pas été déclenché
→ Marquez manuellement comme payé ou vérifiez les logs Stripe

---

## 📖 Documentation Stripe

- [Stripe PHP Documentation](https://stripe.com/docs/api?lang=php)
- [Stripe Checkout Guide](https://stripe.com/docs/payments/checkout)
- [Testing Stripe](https://stripe.com/docs/testing)
- [Webhooks Guide](https://stripe.com/docs/webhooks)

---

## ✨ Fonctionnalités Avancées (Optionnelles)

### 1. Remboursements automatiques
```php
$refund = \Stripe\Refund::create([
    'payment_intent' => $payment->stripe_payment_intent_id,
]);
```

### 2. Abonnements mensuels
Pour les utilisateurs premium avec emprunts illimités

### 3. Paiements récurrents
Pour les emprunts de longue durée

### 4. Multi-devises
Détecter automatiquement la devise selon le pays de l'utilisateur

---

## 📞 Support

En cas de problème:
1. Consultez les logs Laravel: `storage/logs/laravel.log`
2. Vérifiez les événements Stripe: Dashboard → **Développeurs** > **Événements**
3. Testez avec Stripe CLI: `stripe events list`

---

**🎉 Votre système de paiement Stripe est opérationnel !**

Les utilisateurs peuvent maintenant:
- ✅ Payer leurs emprunts par carte bancaire
- ✅ Acheter des livres de manière sécurisée
- ✅ Recevoir des confirmations de paiement
- ✅ Voir l'historique de leurs transactions

Bon développement ! 🚀

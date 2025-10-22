# 🚀 Configuration Rapide Stripe - 5 Minutes

## ⚡ Démarrage Express

### Étape 1: Obtenez vos clés Stripe (2 minutes)

1. Allez sur: https://dashboard.stripe.com/register
2. Créez un compte gratuit
3. Une fois connecté, allez dans **Développeurs** > **Clés API**
4. Vous verrez 2 clés de test:
   - **Clé publiable** (commence par `pk_test_...`)
   - **Clé secrète** (commence par `sk_test_...`) - Cliquez sur "Révéler la clé de test"

### Étape 2: Mettez à jour votre fichier `.env` (1 minute)

Ouvrez: `Atlas-Roads\.env`

Remplacez ces lignes (à la fin du fichier):
```env
STRIPE_KEY=pk_test_51QItZbGrQjUWxLQEyourkeywillgohere
STRIPE_SECRET=sk_test_51QItZbGrQjUWxLQEyoursecretwillgohere
STRIPE_WEBHOOK_SECRET=whsec_yourwebhooksecretwillgohere
```

Par vos vraies clés:
```env
STRIPE_KEY=pk_test_51ABC123... (votre clé publiable)
STRIPE_SECRET=sk_test_51ABC123... (votre clé secrète)
STRIPE_WEBHOOK_SECRET=whsec_... (on configure ça après)
```

### Étape 3: Démarrez le serveur (30 secondes)

```bash
php artisan serve
```

### Étape 4: Testez ! (1 minute)

1. Allez sur: http://127.0.0.1:8000/login
2. Connectez-vous avec: `admin@softui.com` / `secret`
3. Allez sur la page d'accueil: http://127.0.0.1:8000
4. Cliquez sur "Emprunter" sur un livre
5. Remplissez le formulaire et soumettez

**Vous serez redirigé vers Stripe !** 🎉

---

## 💳 Carte de Test Stripe

Pour payer lors du test, utilisez cette carte:

```
Numéro:    4242 4242 4242 4242
Date:      12/25 (n'importe quelle date future)
CVC:       123 (n'importe quel code)
Code postal: 12345 (n'importe lequel)
```

**Résultat**: Paiement réussi ✅

---

## 🎯 Test Complet (3 minutes)

### Scénario: Bob emprunte le livre d'Alice

```bash
# 1. Créer 2 utilisateurs de test
php artisan tinker

# Dans Tinker:
\App\Models\User::create([
    'name' => 'Alice',
    'email' => 'alice@test.com',
    'password' => bcrypt('password')
]);

\App\Models\User::create([
    'name' => 'Bob',
    'email' => 'bob@test.com',
    'password' => bcrypt('password')
]);

exit
```

**Maintenant sur le navigateur:**

1. **Connexion d'Alice** (alice@test.com / password)
   - Créer un livre: "1984" - Prix: 20$
   - Se déconnecter

2. **Connexion de Bob** (bob@test.com / password)
   - Cliquer sur "Emprunter" sur le livre "1984"
   - Remplir les dates d'emprunt
   - Soumettre

3. **Page Stripe Checkout**
   - Montant affiché: **$5.00** (20$/4)
   - Entrer la carte: `4242 4242 4242 4242`
   - Payer

4. **Retour sur le site**
   - Message: "Paiement effectué avec succès! 🎉"
   - Aller dans `/payments`
   - Statut: **Paid** ✅
   - Méthode: **stripe** 💳

**C'EST TOUT !** 🚀

---

## 🔧 Configuration Webhook (Optionnel - pour production)

### En développement local: Pas besoin !
Le système fonctionne déjà sans webhook grâce à la redirection `success_url`.

### Pour la production:

1. Dans Stripe Dashboard → **Développeurs** > **Webhooks**
2. Cliquer sur **+ Ajouter un endpoint**
3. URL: `https://votre-domaine.com/stripe/webhook`
4. Événements: Sélectionner `checkout.session.completed`
5. Copier le **Signing secret** (commence par `whsec_...`)
6. Mettre à jour `.env`:
   ```env
   STRIPE_WEBHOOK_SECRET=whsec_ABC123...
   ```

---

## 🧪 Autres Cartes de Test

| Carte | Résultat |
|-------|----------|
| 4242 4242 4242 4242 | ✅ Succès |
| 4000 0000 0000 0002 | ❌ Carte déclinée |
| 4000 0000 0000 9995 | ❌ Fonds insuffisants |
| 4000 0025 0000 3155 | ⚠️ Authentification 3D Secure requise |

---

## ❓ FAQ Rapide

**Q: Ça coûte combien ?**  
R: Gratuit en mode test ! En production: 2.9% + 0.30€ par transaction.

**Q: Je dois créer une vraie entreprise ?**  
R: Non ! Stripe fonctionne pour les particuliers aussi.

**Q: C'est sécurisé ?**  
R: Oui ! Stripe est certifié PCI-DSS Level 1 (norme bancaire).

**Q: Les cartes sont sauvegardées sur mon serveur ?**  
R: Non ! Les données bancaires ne passent jamais par votre serveur.

**Q: Je peux tester sans carte ?**  
R: Oui ! Utilisez les cartes de test Stripe (4242...).

---

## 🎉 Résultat Final

Maintenant quand un utilisateur:
- **Emprunte un livre** → Paiement Stripe de 25% du prix
- **Achète un livre** → Paiement Stripe du prix complet

Tout est automatique:
- ✅ Création du paiement
- ✅ Redirection vers Stripe
- ✅ Confirmation du paiement
- ✅ Mise à jour du statut
- ✅ Transfert de propriété (pour achats)

**Prêt à tester ?** Lancez `php artisan serve` ! 🚀

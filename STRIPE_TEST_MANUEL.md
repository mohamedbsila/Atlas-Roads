# 🧪 Guide de Test Manuel Stripe - VERSION CORRIGÉE

## ✅ Prérequis
- ✅ Utilisateurs créés (Alice et Bob)
- ✅ Serveur Laravel démarré : http://127.0.0.1:8000
- ✅ Clés Stripe configurées dans `.env`

---

## 🎯 TEST COMPLET : Emprunt avec Paiement Stripe

### **Étape 1 : Alice crée un livre** 👩

1. **Allez sur** : http://127.0.0.1:8000/login

2. **Connectez-vous avec Alice** :
   ```
   Email: alice@stripe-test.com
   Mot de passe: password123
   ```

3. **Créez un livre** : http://127.0.0.1:8000/books/create

4. **Remplissez le formulaire** :
   - **Titre** : `Le Petit Prince`
   - **Auteur** : `Antoine de Saint-Exupéry`
   - **ISBN** : `978-0156012195`
   - **Catégorie** : `Fiction`
   - **Langue** : `French`
   - **Prix** : `20` ⭐
   - **Année** : `1943`
   - ✅ **Cochez "Is Available"**

5. **Cliquez** sur "Add Book"

**✅ Résultat** : "Book has been successfully added!"

---

### **Étape 2 : Bob demande l'emprunt** 👨

1. **Déconnectez-vous** (Alice)

2. **Connectez Bob** :
   ```
   Email: bob@stripe-test.com
   Mot de passe: password123
   ```

3. **Allez sur** : http://127.0.0.1:8000

4. **Trouvez "Le Petit Prince"** et cliquez sur **"📚 Emprunter ce livre"**

5. **Une fenêtre modale s'ouvre !**

6. **Remplissez UNIQUEMENT ces 2 champs** :
   
   ```
   Date de début:  2025-10-25  (demain ou plus tard)
   Date de fin:    2025-11-10  (après la date de début)
   ```
   
   **Message (optionnel)**: Laissez vide ou écrivez ce que vous voulez

7. **Cliquez** sur **"✅ Envoyer la demande"**

**🔄 REDIRECTION AUTOMATIQUE VERS STRIPE CHECKOUT !**

---

### **Étape 3 : Payer sur Stripe** 💳

Vous êtes maintenant sur la page de paiement Stripe qui affiche :

- **Produit** : Le Petit Prince
- **Description** : Emprunt du livre: Le Petit Prince
- **Montant** : **$5.00** (20 ÷ 4)

**Entrez la carte de test** :

```
┌──────────────────────────────────────┐
│ Numéro de carte: 4242 4242 4242 4242│
│ Date d'expiration: 12/25             │
│ CVC: 123                             │
│ Nom sur la carte: Bob Dupont         │
│ Code postal: 12345                   │
└──────────────────────────────────────┘
```

**Cliquez** sur **"Pay"** (ou "Payer")

---

### **Étape 4 : Confirmation** ✅

**Redirection automatique** vers : http://127.0.0.1:8000/payments/success

**Message affiché** :
```
✅ Paiement effectué avec succès! 🎉
```

Puis redirection vers `/payments`

---

### **Étape 5 : Vérification** 🔍

**Vous êtes sur** : http://127.0.0.1:8000/payments

**Section "My Payments"** (Bob) :

| Colonne | Valeur |
|---------|--------|
| Book | Le Petit Prince |
| Type | Borrow |
| Total | $5.00 |
| Per Day | $0.0056 |
| Status | Badge vert "Paid" ✅ |
| Action | "✅ stripe" |

**🎉 TEST RÉUSSI !**

---

## 📋 Résumé des Champs du Formulaire

Le formulaire d'emprunt contient **SEULEMENT** :

1. ✅ **Date de début** (obligatoire)
2. ✅ **Date de fin** (obligatoire)
3. ✅ **Message** (optionnel)

**Il n'y a PAS de champ email !**

---

## 💳 Carte de Test Stripe

**Pour réussir le paiement** :
```
4242 4242 4242 4242
```

**Pour tester un échec** :
```
4000 0000 0000 0002  → Carte déclinée
4000 0000 0000 9995  → Fonds insuffisants
```

---

## 🔍 Vérifier dans Stripe Dashboard

1. Allez sur : https://dashboard.stripe.com/test/payments
2. Vous verrez votre paiement de **$5.00**
3. Status : "Succeeded"
4. Metadata contient : `payment_id`, `book_id`, `type`

---

## ⚠️ Points Importants

1. **Le formulaire a SEULEMENT 2 champs obligatoires** (dates)
2. **La redirection vers Stripe est AUTOMATIQUE** après soumission
3. **Le montant est calculé automatiquement** (prix ÷ 4)
4. **N'oubliez pas de configurer vos clés Stripe** dans `.env`

---

## 🎉 Félicitations !

Votre intégration Stripe fonctionne parfaitement ! 🚀💳

**Questions ?** Consultez `STRIPE_INTEGRATION.md` pour plus de détails.

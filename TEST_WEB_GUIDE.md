# 🚀 Guide de Test Rapide - Interface Web

## 📋 Prérequis

Assure-toi que ton serveur Laravel est démarré:
```bash
php artisan serve
```

L'application sera disponible sur: `http://127.0.0.1:8000`

---

## 🧪 Test Complet en 10 Minutes

### 🔧 Préparation: Créer 2 utilisateurs de test

Ouvre un terminal PowerShell et exécute:

```powershell
php artisan tinker
```

Puis copie-colle:

```php
// Créer Alice
\App\Models\User::create([
    'name' => 'Alice',
    'email' => 'alice@test.com',
    'password' => bcrypt('password')
]);

// Créer Bob
\App\Models\User::create([
    'name' => 'Bob',
    'email' => 'bob@test.com',
    'password' => bcrypt('password')
]);

exit
```

---

## 📖 Scénario de Test

### PARTIE 1: Alice crée son livre

1. **Connexion d'Alice**
   - Va sur: `http://127.0.0.1:8000/login`
   - Email: `alice@test.com`
   - Password: `password`
   - Clique sur **Se connecter**

2. **Ajouter un livre**
   - Va sur: `http://127.0.0.1:8000/books/create`
   - Remplis le formulaire:
     - Titre: `Le Petit Prince`
     - Auteur: `Antoine de Saint-Exupéry`
     - ISBN: `9782070612758`
     - Catégorie: `Fiction`
     - Langue: `Français`
     - Année: `1943`
   - Clique sur **Ajouter**
   - ✅ Le livre est créé avec `ownerId = Alice`

3. **Vérifier dans le Dashboard**
   - Va sur: `http://127.0.0.1:8000/dashboard`
   - Tu devrais voir: "Aucune demande envoyée récemment"
   - Normal: Alice n'a rien emprunté encore

---

### PARTIE 2: Bob crée son livre

1. **Déconnexion d'Alice**
   - Clique sur le profil en haut à droite
   - Clique sur **Se déconnecter**

2. **Connexion de Bob**
   - Va sur: `http://127.0.0.1:8000/login`
   - Email: `bob@test.com`
   - Password: `password`
   - Clique sur **Se connecter**

3. **Ajouter un livre**
   - Va sur: `http://127.0.0.1:8000/books/create`
   - Remplis le formulaire:
     - Titre: `1984`
     - Auteur: `George Orwell`
     - ISBN: `9780451524935`
     - Catégorie: `Fiction`
     - Langue: `Français`
     - Année: `1949`
   - Clique sur **Ajouter**
   - ✅ Le livre est créé avec `ownerId = Bob`

---

### PARTIE 3: Alice essaie d'emprunter SON propre livre

1. **Se reconnecter comme Alice**
   - Déconnecte Bob
   - Connecte-toi avec `alice@test.com`

2. **Aller sur la page d'accueil**
   - Va sur: `http://127.0.0.1:8000/`
   - Tu devrais voir les 2 livres:
     - "Le Petit Prince" (ton livre)
     - "1984" (livre de Bob)

3. **Essayer d'emprunter "Le Petit Prince"**
   - Clique sur "Le Petit Prince"
   - Clique sur le bouton **Emprunter**
   - Remplis les dates
   - Clique sur **Envoyer la demande**
   - ❌ **RÉSULTAT ATTENDU**: 
     ```
     ❌ Erreur: "Vous ne pouvez pas emprunter votre propre livre."
     ```

---

### PARTIE 4: Alice emprunte le livre de Bob

1. **Emprunter "1984"**
   - Va sur la page d'accueil: `http://127.0.0.1:8000/`
   - Clique sur "1984" (livre de Bob)
   - Clique sur **Emprunter**
   - Remplis:
     - Date début: `2025-10-19` (demain)
     - Date fin: `2025-10-30` (dans 12 jours)
     - Notes: `J'aimerais lire ce classique!`
   - Clique sur **Envoyer la demande**
   - ✅ **RÉSULTAT ATTENDU**: 
     ```
     ✅ Succès: "Demande d'emprunt envoyée avec succès!"
     ```

2. **Vérifier dans le Dashboard d'Alice**
   - Va sur: `http://127.0.0.1:8000/dashboard`
   - Section: **"Mes demandes envoyées"**
   - Tu devrais voir:
     ```
     📖 Livre: 1984
     👤 chez Bob
     📅 Du 19/10/2025 au 30/10/2025
     🟡 Statut: En attente
     ```

3. **Vérifier la page complète des demandes**
   - Clique sur **Voir tout** (lien dans le dashboard)
   - Ou va sur: `http://127.0.0.1:8000/borrow-requests`
   - Onglet: **"Mes Demandes"**
   - Tu devrais voir la carte complète avec:
     - Livre: 1984
     - Propriétaire: Bob
     - Statut: 🟡 En attente
     - Bouton: [❌ Annuler]

---

### PARTIE 5: Bob voit la demande et l'approuve

1. **Se reconnecter comme Bob**
   - Déconnecte Alice
   - Connecte-toi avec `bob@test.com`

2. **Vérifier le Dashboard de Bob**
   - Va sur: `http://127.0.0.1:8000/dashboard`
   - Section: **"Demandes reçues"**
   - Tu devrais voir:
     ```
     📖 Livre: 1984
     👤 par Alice
     📅 Du 19/10/2025 au 30/10/2025
     🟡 Statut: En attente
     ```

3. **Aller sur la page complète**
   - Clique sur **Voir tout**
   - Ou va sur: `http://127.0.0.1:8000/borrow-requests`
   - Clique sur l'onglet: **"Demandes Reçues"**
   - Tu devrais voir:
     ```
     📖 Livre: 1984 (Votre livre)
     👤 Demandeur: Alice
     📧 Email: alice@test.com
     📅 Du 19/10/2025 au 30/10/2025
     🟡 Statut: En attente
     
     [✅ APPROUVER] [❌ REFUSER]
     ```

4. **Approuver la demande**
   - Clique sur le bouton vert **✅ APPROUVER**
   - Confirme dans la popup
   - ✅ **RÉSULTAT ATTENDU**: 
     ```
     ✅ Succès: "Demande approuvée avec succès!"
     ```
   - Le statut devient: 🟢 Approuvé
   - Le livre "1984" est maintenant marqué comme non disponible

---

### PARTIE 6: Alice voit l'approbation

1. **Se reconnecter comme Alice**
   - Déconnecte Bob
   - Connecte-toi avec `alice@test.com`

2. **Vérifier le Dashboard**
   - Va sur: `http://127.0.0.1:8000/dashboard`
   - Section: **"Mes demandes envoyées"**
   - Tu devrais voir:
     ```
     📖 Livre: 1984
     👤 chez Bob
     📅 Du 19/10/2025 au 30/10/2025
     🟢 Statut: Approuvé ✅
     ```

3. **Vérifier la page complète**
   - Va sur: `http://127.0.0.1:8000/borrow-requests`
   - Onglet: **"Mes Demandes"**
   - Tu devrais voir:
     - Statut: 🟢 Approuvé
     - Nouveau bouton: [✅ Marquer comme Rendu]
     - Le bouton "Annuler" a disparu

---

## ✅ Checklist de Vérification

Après avoir suivi toutes les étapes, vérifie:

- [ ] Alice peut créer un livre → devient propriétaire
- [ ] Bob peut créer un livre → devient propriétaire
- [ ] Alice **NE PEUT PAS** emprunter "Le Petit Prince" (son livre)
- [ ] Alice **PEUT** emprunter "1984" (livre de Bob)
- [ ] Dashboard d'Alice affiche sa demande envoyée
- [ ] Dashboard de Bob affiche la demande reçue
- [ ] Bob (propriétaire) peut approuver la demande
- [ ] Alice voit le changement de statut (En attente → Approuvé)
- [ ] Le livre "1984" est marqué comme non disponible

---

## 🐛 Tests de Sécurité Supplémentaires

### Test 1: Alice ne peut pas approuver une demande qu'elle a faite

1. Connecte-toi comme Alice
2. Va sur: `http://127.0.0.1:8000/borrow-requests`
3. Onglet: "Mes Demandes"
4. **VÉRIFICATION**: Il n'y a PAS de bouton "Approuver"
5. ✅ Alice peut seulement "Marquer comme Rendu" ou "Annuler"

### Test 2: Bob ne peut pas modifier les demandes d'Alice

1. Connecte-toi comme Bob
2. Va sur: `http://127.0.0.1:8000/borrow-requests`
3. Onglet: "Mes Demandes"
4. **VÉRIFICATION**: Bob ne voit PAS les demandes d'Alice
5. ✅ Chaque utilisateur voit uniquement SES propres demandes

---

## 📊 État de la Base de Données

Après tous les tests, tu peux vérifier avec Tinker:

```powershell
php artisan tinker
```

```php
// Voir les livres
\App\Models\Book::select('id', 'title', 'author', 'ownerId', 'is_available')->get();

// Voir les demandes d'emprunt
\App\Models\BorrowRequest::with(['borrower:id,name', 'book:id,title', 'owner:id,name'])
    ->select('id', 'borrower_id', 'owner_id', 'book_id', 'status')
    ->get();
```

**Résultat attendu:**

```
Books:
  id  | title           | author              | ownerId | is_available
  1   | Le Petit Prince | Saint-Exupéry       | 1       | true
  2   | 1984            | George Orwell       | 2       | false

BorrowRequests:
  id | borrower | book    | owner | status
  1  | Alice    | 1984    | Bob   | approved
```

---

## 🎉 Félicitations!

Si tous les tests passent, ton système d'emprunt fonctionne **parfaitement**! 🚀

### Fonctionnalités Validées:

✅ Propriété automatique des livres  
✅ Protection contre l'emprunt de son propre livre  
✅ Emprunt entre utilisateurs différents  
✅ Dashboard avec demandes envoyées et reçues  
✅ Système d'approbation (propriétaire = admin du livre)  
✅ Sécurité: seul le propriétaire peut approuver/rejeter  
✅ Gestion de la disponibilité des livres  

---

## 🔄 Pour Nettoyer et Recommencer

Si tu veux refaire les tests:

```powershell
php artisan tinker
```

```php
// Supprimer les demandes
\App\Models\BorrowRequest::truncate();

// Supprimer les livres
\App\Models\Book::truncate();

// Supprimer les users de test
\App\Models\User::whereIn('email', ['alice@test.com', 'bob@test.com'])->delete();

exit
```

Puis recommence depuis l'étape "Créer 2 utilisateurs de test".

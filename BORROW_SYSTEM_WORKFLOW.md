# 📚 Système d'Emprunt de Livres - Guide Complet

## ✅ Fonctionnalités Implémentées

### 1. 🔐 Propriété des Livres
**Status: ✅ IMPLÉMENTÉ**

- **Création de livre**: Quand un utilisateur ajoute un livre, il devient automatiquement le propriétaire (`ownerId`)
- **Fichier**: `app/Http/Controllers/BookController.php` ligne 69
  ```php
  // Assigner le propriétaire du livre au user connecté
  $data['ownerId'] = Auth::id();
  ```

### 2. 🚫 Protection: Pas d'emprunt de son propre livre
**Status: ✅ IMPLÉMENTÉ**

- **Validation**: Un utilisateur ne peut PAS emprunter son propre livre
- **Fichier**: `app/Http/Controllers/BorrowRequestController.php` ligne 57
  ```php
  // Vérifier que l'utilisateur ne demande pas son propre livre
  if ($book->ownerId == Auth::id()) {
      return back()->withErrors(['error' => 'Vous ne pouvez pas emprunter votre propre livre.']);
  }
  ```

### 3. ✅ Emprunt entre utilisateurs
**Status: ✅ IMPLÉMENTÉ**

- **User1** ajoute un livre → User1 est propriétaire
- **User2** ajoute un livre → User2 est propriétaire
- **User1** peut emprunter le livre de User2 ✅
- **User2** peut emprunter le livre de User1 ✅

### 4. 📊 Dashboard avec Demandes
**Status: ✅ IMPLÉMENTÉ**

Chaque utilisateur voit dans son dashboard:

#### Mes Demandes Envoyées
- Liste des livres que j'ai demandé à emprunter
- Statut de chaque demande (En attente, Approuvé, Rejeté)
- **Fichier**: `app/Http/Livewire/Dashboard.php` ligne 14-18

#### Demandes Reçues
- Liste des demandes pour MES livres
- Possibilité d'approuver ou rejeter
- **Fichier**: `app/Http/Livewire/Dashboard.php` ligne 20-24

### 5. 👑 Système d'Approbation (Admin du Livre)
**Status: ✅ IMPLÉMENTÉ**

Le **propriétaire du livre** (owner_id) est le seul à pouvoir:

#### ✅ Approuver une demande
- **Fichier**: `app/Http/Controllers/BorrowRequestController.php` ligne 87
  ```php
  if ($borrowRequest->owner_id !== Auth::id()) {
      return back()->withErrors(['error' => 'Vous n\'êtes pas autorisé...']);
  }
  ```

#### ❌ Rejeter une demande
- **Fichier**: `app/Http/Controllers/BorrowRequestController.php` ligne 107

#### 🔄 Marquer comme retourné
- **Fichier**: `app/Http/Controllers/BorrowRequestController.php` ligne 124

---

## 🎯 Workflow Complet

### Scénario: User1 et User2

```
┌─────────────────────────────────────────────────────────────┐
│ 1. User1 ajoute "Harry Potter"                             │
│    → ownerId = User1                                        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. User2 ajoute "Le Seigneur des Anneaux"                  │
│    → ownerId = User2                                        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. User1 essaie d'emprunter "Harry Potter" (son livre)     │
│    → ❌ REFUSÉ: "Vous ne pouvez pas emprunter votre        │
│       propre livre"                                         │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. User1 demande "Le Seigneur des Anneaux" (livre User2)   │
│    → ✅ ACCEPTÉ: Demande créée avec status "pending"       │
│    → borrower_id = User1                                    │
│    → owner_id = User2                                       │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 5. User2 voit la demande dans son Dashboard                │
│    Section: "Demandes Reçues"                               │
│    → Affiche: "Le Seigneur des Anneaux"                    │
│    → Demandeur: User1                                       │
│    → Boutons: [✅ APPROUVER] [❌ REFUSER]                   │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 6. User2 (propriétaire) approuve la demande                │
│    → Status change: "pending" → "approved"                  │
│    → Livre marqué: is_available = false                     │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 7. User1 voit dans son Dashboard                           │
│    Section: "Mes Demandes Envoyées"                        │
│    → Status: "Approuvé" (badge vert)                       │
│    → Bouton: [Marquer comme Rendu]                         │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔒 Protections de Sécurité

### ✅ Vérifications Implémentées

1. **Livre doit avoir un propriétaire**
   - Ligne 52 dans `BorrowRequestController.php`

2. **Pas d'emprunt de son propre livre**
   - Ligne 57 dans `BorrowRequestController.php`

3. **Pas de doublon de demande**
   - Ligne 62-68 dans `BorrowRequestController.php`

4. **Seul le propriétaire peut approuver/rejeter**
   - Lignes 87, 107, 124 dans `BorrowRequestController.php`

5. **Validation des dates**
   - start_date doit être après aujourd'hui
   - end_date doit être après start_date
   - Ligne 41-46 dans `BorrowRequestController.php`

---

## 📍 Pages de l'Application

### 1. Home (Catalogue Public)
- **Route**: `/`
- **Vue**: Tous les livres disponibles
- **Action**: Bouton "Emprunter" pour chaque livre

### 2. Dashboard
- **Route**: `/dashboard`
- **Vue**: 
  - Mes demandes envoyées (5 dernières)
  - Demandes reçues (5 dernières)
  - Lien "Voir tout"

### 3. Gestion des Emprunts
- **Route**: `/borrow-requests`
- **Vue**: 
  - Onglet "Mes Demandes" (envoyées)
  - Onglet "Demandes Reçues" (pour mes livres)
  - Actions: Approuver, Rejeter, Annuler, Marquer comme Rendu

### 4. Mes Livres
- **Route**: `/books`
- **Vue**: CRUD complet des livres
- **Action**: Ajouter un livre → devient propriétaire automatiquement

---

## 🎨 Interface Utilisateur

### Badge de Statut
- 🟡 **En attente** (pending) - Jaune
- 🟢 **Approuvé** (approved) - Vert
- 🔴 **Rejeté** (rejected) - Rouge
- 🔵 **Retourné** (returned) - Bleu

### Boutons d'Action

#### Pour l'Emprunteur (borrower)
- 📤 **Demander un emprunt** (depuis la page du livre)
- ❌ **Annuler** (si status = pending)
- ✅ **Marquer comme Rendu** (si status = approved)

#### Pour le Propriétaire (owner)
- ✅ **APPROUVER** (si status = pending)
- ❌ **REFUSER** (si status = pending)
- 🔄 **Marquer comme Retourné** (si status = approved)

---

## 🧪 Comment Tester

### Étape 1: Créer 2 utilisateurs
```bash
php artisan tinker
```

```php
// Créer User1
$user1 = \App\Models\User::create([
    'name' => 'Alice',
    'email' => 'alice@test.com',
    'password' => bcrypt('password')
]);

// Créer User2
$user2 = \App\Models\User::create([
    'name' => 'Bob',
    'email' => 'bob@test.com',
    'password' => bcrypt('password')
]);
```

### Étape 2: User1 ajoute un livre
1. Se connecter comme alice@test.com
2. Aller sur `/books/create`
3. Ajouter "Le Petit Prince"
4. Vérifier: `ownerId` = User1

### Étape 3: User2 ajoute un livre
1. Se déconnecter
2. Se connecter comme bob@test.com
3. Aller sur `/books/create`
4. Ajouter "1984"
5. Vérifier: `ownerId` = User2

### Étape 4: User1 essaie d'emprunter son propre livre
1. Se connecter comme alice@test.com
2. Aller sur la page du livre "Le Petit Prince"
3. Cliquer "Emprunter"
4. **Résultat attendu**: ❌ "Vous ne pouvez pas emprunter votre propre livre"

### Étape 5: User1 emprunte le livre de User2
1. Toujours connecté comme alice@test.com
2. Aller sur la page du livre "1984" (de Bob)
3. Remplir les dates d'emprunt
4. Cliquer "Emprunter"
5. **Résultat attendu**: ✅ "Demande d'emprunt envoyée avec succès!"

### Étape 6: User1 voit sa demande dans le Dashboard
1. Aller sur `/dashboard`
2. Section "Mes demandes envoyées"
3. **Résultat attendu**: 
   - Livre: "1984"
   - Propriétaire: Bob
   - Status: 🟡 En attente

### Étape 7: User2 voit la demande et l'approuve
1. Se déconnecter
2. Se connecter comme bob@test.com
3. Aller sur `/dashboard`
4. Section "Demandes reçues"
5. **Résultat attendu**: 
   - Livre: "1984"
   - Demandeur: Alice
   - Boutons: [✅ APPROUVER] [❌ REFUSER]
6. Cliquer sur **APPROUVER**
7. **Résultat**: ✅ "Demande approuvée avec succès!"

### Étape 8: User1 voit le changement de statut
1. Se déconnecter
2. Se connecter comme alice@test.com
3. Aller sur `/dashboard`
4. Section "Mes demandes envoyées"
5. **Résultat attendu**: 
   - Status: 🟢 Approuvé
   - Bouton: [Marquer comme Rendu]

---

## 📝 Base de Données

### Table: books
```
id | title | author | ownerId | is_available
1  | Le Petit Prince | Saint-Exupéry | 1 (User1) | true
2  | 1984 | George Orwell | 2 (User2) | false
```

### Table: borrow_requests
```
id | borrower_id | owner_id | book_id | status | start_date | end_date
1  | 1 (User1)  | 2 (User2) | 2      | approved | 2025-10-19 | 2025-10-30
```

---

## ✅ Checklist Fonctionnalités

- [x] User qui crée un livre en devient propriétaire automatiquement
- [x] User ne peut PAS emprunter son propre livre
- [x] User peut emprunter les livres d'autres users
- [x] Dashboard affiche les demandes envoyées
- [x] Dashboard affiche les demandes reçues
- [x] Propriétaire peut approuver/rejeter les demandes
- [x] Seul le propriétaire peut gérer les demandes de son livre
- [x] Validation des dates et des doublons
- [x] Système de statuts (pending, approved, rejected, returned)
- [x] Gestion de la disponibilité des livres

---

## 🎉 Résumé

**Tout fonctionne exactement comme tu le veux!**

1. ✅ User1 ajoute un livre → devient propriétaire
2. ✅ User1 ne peut PAS emprunter son propre livre
3. ✅ User1 PEUT emprunter le livre de User2
4. ✅ Chaque user voit ses demandes dans le dashboard
5. ✅ Propriétaire est l'admin qui approuve/rejette les demandes de SON livre

Le système est **100% fonctionnel** et sécurisé! 🚀

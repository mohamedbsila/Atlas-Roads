# Option d'Achat Définitif - Documentation

## Vue d'ensemble
En plus du système d'emprunt de livres, les utilisateurs connectés peuvent maintenant **acheter définitivement** un livre en effectuant un paiement du prix complet. Après paiement, le livre devient automatiquement leur propriété et les demandes d'emprunt en cours sont annulées.

---

## Fonctionnement

### 1. **Type de paiement**
La table `payments` contient maintenant une colonne `type` avec deux valeurs possibles :
- **`borrow`** : Paiement pour un emprunt (montant = prix/4, avec montant par jour).
- **`purchase`** : Paiement pour un achat définitif (montant = prix complet, aucun montant par jour).

Le champ `borrow_request_id` est désormais **nullable** pour supporter les achats (qui ne sont pas liés à une demande d'emprunt).

### 2. **Workflow d'achat**

#### Démarrer un achat
- Sur les vues de livre (`books/show.blade.php` et `books/show-public.blade.php`), un bouton **"Acheter définitivement"** apparaît si :
  - L'utilisateur est connecté
  - Le livre est disponible
  - L'utilisateur n'est pas déjà propriétaire du livre
  - Le livre a un prix défini

- Lorsque l'utilisateur clique sur ce bouton, un paiement de type `purchase` est créé dans la table `payments` :
  - `amount_total` = prix complet du livre
  - `amount_per_day` = null
  - `type` = 'purchase'
  - `status` = 'pending'
  - `borrow_request_id` = null

- L'utilisateur est redirigé vers la page **Payments** pour finaliser le paiement.

#### Payer l'achat
- Dans l'onglet **My Payments** de la page Payments, l'acheteur voit le paiement en attente avec type "Purchase".
- Il clique sur **"Mark as Paid"** pour simuler le paiement.
- Une fois marqué comme payé :
  - Le **`ownerId`** du livre est transféré automatiquement au nouveau propriétaire (l'acheteur).
  - Le livre redevient **disponible** (`is_available = true`).
  - Toutes les **demandes d'emprunt en attente ou approuvées** pour ce livre sont automatiquement **annulées** (status mis à `REJECTED`).

---

## Modifications Techniques

### Base de données
- **Migration** : `2025_10_19_000200_alter_payments_add_type_and_nullable_borrow_request.php`
  - Ajout colonne `type` (default 'borrow')
  - `borrow_request_id` rendu nullable

### Modèles
- **`Payment`** :
  - Ajout de `'type'` dans `$fillable`
  - Helpers : `isPurchase()` et `isBorrow()`

### Contrôleurs
- **`PaymentController@purchase(Book $book)`** :
  - Nouvelle méthode pour créer un paiement de type `purchase`
  - Validations : prix valide, pas d'achat de son propre livre, livre disponible, pas de paiement d'achat en cours.

- **`PaymentController@markAsPaid(Payment $payment, Request $request)`** :
  - Si type = 'purchase', après paiement :
    - Transfert de propriété (`$book->ownerId = $payment->borrower_id`)
    - Livre remis disponible
    - Annulation des demandes d'emprunt en attente/approuvées pour ce livre

- **`BorrowRequestController@store()` & `approve()`** :
  - Ajout de `'type' => 'borrow'` lors de la création de paiements pour emprunts.

### Routes
- **POST `/books/{book}/purchase`** (middleware `auth`) → `PaymentController@purchase`

### Vues
- **`books/show.blade.php`** & **`show-public.blade.php`** :
  - Bouton "Acheter définitivement" conditionnel (utilisateur connecté, livre disponible, non propriétaire, prix présent).

- **`payments/index.blade.php`** :
  - Colonne **"Type"** ajoutée dans les deux tableaux.
  - `amount_per_day` affichée seulement si type = 'borrow'.

---

## Utilisation
1. Connectez-vous en tant qu'utilisateur.
2. Accédez à la page d'un livre disponible avec un prix défini.
3. Si vous n'êtes pas le propriétaire, cliquez sur **"Acheter définitivement (montant)"**.
4. Rendez-vous dans l'onglet **Payments** du dashboard, onglet **"My Payments"**.
5. Cliquez sur **"Mark as Paid"** pour simuler le paiement.
6. Le livre devient maintenant votre propriété, visible dans **"My Books"**.
7. Toutes les demandes d'emprunt en cours pour ce livre sont automatiquement annulées.

---

## Limitations / Notes
- Le paiement est **simulé** (marquage manuel). Pour un vrai workflow de paiement, intégrez une passerelle de paiement externe.
- Une fois acheté, le livre ne peut plus être emprunté par d'autres utilisateurs (sauf si le nouveau propriétaire le rend disponible pour emprunt ultérieurement).
- Les paiements de type `purchase` n'ont pas de `borrow_request_id` et ne sont pas liés à une demande d'emprunt.

---

## Tests
- Testez en créant un livre avec prix, connectez-vous avec un autre utilisateur, achetez le livre, vérifiez le transfert de propriété.
- Créez une demande d'emprunt sur un livre, puis achetez-le avec un autre utilisateur : la demande d'emprunt doit être annulée automatiquement.

---

Fin de la documentation d'achat.

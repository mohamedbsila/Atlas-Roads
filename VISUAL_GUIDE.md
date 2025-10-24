# 🎨 Système d'Emprunt - Vue d'Ensemble Visuelle

## 📊 Architecture du Système

```
┌──────────────────────────────────────────────────────────────────┐
│                         🌐 INTERFACE WEB                         │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────┐  ┌──────────────┐  ┌──────────────────────┐   │
│  │   🏠 HOME   │  │  📊 DASHBOARD│  │  🔄 GESTION EMPRUNTS │   │
│  │  Catalogue  │  │  Statistiques│  │  Liste Complète      │   │
│  │  de livres  │  │  Aperçus     │  │  Actions             │   │
│  └─────────────┘  └──────────────┘  └──────────────────────┘   │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
                              ↕
┌──────────────────────────────────────────────────────────────────┐
│                      🎮 CONTRÔLEURS                              │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────┐         ┌──────────────────────────────┐  │
│  │  BookController  │         │  BorrowRequestController     │  │
│  │  ───────────────│         │  ────────────────────────    │  │
│  │  • store()       │         │  • store() [Créer demande]   │  │
│  │  • update()      │         │  • approve() [Approuver]     │  │
│  │  • destroy()     │         │  • reject() [Rejeter]        │  │
│  │                  │         │  • markAsReturned()          │  │
│  │  ownerId = Auth  │         │  • cancel() [Annuler]        │  │
│  └──────────────────┘         └──────────────────────────────┘  │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
                              ↕
┌──────────────────────────────────────────────────────────────────┐
│                         📦 MODÈLES                               │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────┐    ┌──────────────────┐    ┌──────────────┐  │
│  │    User      │    │  BorrowRequest   │    │     Book     │  │
│  │  ──────────  │    │  ──────────────  │    │  ──────────  │  │
│  │  id          │◄───│  borrower_id     │    │  id          │  │
│  │  name        │    │  owner_id        │───►│  title       │  │
│  │  email       │    │  book_id         │───►│  author      │  │
│  │              │    │  start_date      │    │  ownerId     │──┐│
│  │              │    │  end_date        │    │  is_available│  ││
│  │              │    │  status          │    │              │  ││
│  │              │    │  notes           │    │              │  ││
│  └──────────────┘    └──────────────────┘    └──────────────┘  ││
│                                                                  ││
│  └──────────────────────────────────────────────────────────────┘│
└──────────────────────────────────────────────────────────────────┘
                              ↕
┌──────────────────────────────────────────────────────────────────┐
│                      💾 BASE DE DONNÉES                          │
├──────────────────────────────────────────────────────────────────┤
│  • users                                                         │
│  • books (avec ownerId → users.id)                               │
│  • borrow_requests (borrower_id, owner_id, book_id)             │
└──────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Flux de Données - Création de Demande

```
┌──────────────────────────────────────────────────────────────────┐
│  USER 1 (Alice)                    USER 2 (Bob)                  │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  1. Ajoute "Le Petit Prince"      2. Ajoute "1984"              │
│     │                                  │                         │
│     ↓                                  ↓                         │
│  BookController::store()           BookController::store()       │
│     │                                  │                         │
│     ↓                                  ↓                         │
│  ownerId = Alice (ID: 1)          ownerId = Bob (ID: 2)         │
│                                                                  │
│  ─────────────────────────────────────────────────────────────  │
│                                                                  │
│  3. Essaie d'emprunter                                           │
│     "Le Petit Prince"                                            │
│     │                                                            │
│     ↓                                                            │
│  BorrowRequestController::store()                                │
│     │                                                            │
│     ├─► Vérification: book->ownerId == Auth::id() ?             │
│     │                                                            │
│     └─► ❌ OUI → REFUSÉ                                          │
│         Message: "Vous ne pouvez pas emprunter                   │
│                   votre propre livre."                           │
│                                                                  │
│  ─────────────────────────────────────────────────────────────  │
│                                                                  │
│  4. Demande "1984" (livre de Bob)                               │
│     │                                                            │
│     ↓                                                            │
│  BorrowRequestController::store()                                │
│     │                                                            │
│     ├─► Vérification: book->ownerId == Auth::id() ?             │
│     │   NON → OK ✅                                              │
│     │                                                            │
│     ├─► Vérification: Demande active existante ?                │
│     │   NON → OK ✅                                              │
│     │                                                            │
│     └─► BorrowRequest::create([                                 │
│           'borrower_id' => 1 (Alice),                            │
│           'owner_id' => 2 (Bob),                                 │
│           'book_id' => 2 (1984),                                 │
│           'status' => 'pending'                                  │
│         ])                                                       │
│                                                                  │
│  ─────────────────────────────────────────────────────────────  │
│                                                                  │
│  5. Alice → Dashboard                  6. Bob → Dashboard       │
│     Section: "Mes Demandes"               Section: "Reçues"     │
│                                                                  │
│     📖 1984                                📖 1984               │
│     👤 chez Bob                            👤 par Alice          │
│     🟡 En attente                          🟡 En attente        │
│                                            [✅] [❌]             │
│                                                                  │
│  ─────────────────────────────────────────────────────────────  │
│                                                                  │
│                                        7. Bob clique "Approuver" │
│                                            │                     │
│                                            ↓                     │
│                                        BorrowRequestController   │
│                                        ::approve()               │
│                                            │                     │
│                                            ├─► Vérif: owner_id   │
│                                            │   == Auth::id() ?   │
│                                            │   OUI ✅            │
│                                            │                     │
│                                            ├─► Status = approved │
│                                            │                     │
│                                            └─► is_available=false│
│                                                                  │
│  ─────────────────────────────────────────────────────────────  │
│                                                                  │
│  8. Alice voit le changement                                     │
│     🟢 Approuvé                                                  │
│     [Marquer comme Rendu]                                        │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

---

## 🔐 Matrice de Permissions

```
┌─────────────────────────────────────────────────────────────────┐
│                       QUI PEUT FAIRE QUOI ?                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ACTION                    │  EMPRUNTEUR  │  PROPRIÉTAIRE      │
│                            │  (borrower)  │  (owner)           │
│  ──────────────────────────┼──────────────┼────────────────    │
│                            │              │                    │
│  📤 Créer une demande      │      ✅      │       ❌           │
│     (pour un autre livre)  │              │                    │
│                            │              │                    │
│  📤 Créer une demande      │      ❌      │       ❌           │
│     (pour son propre livre)│   BLOQUÉ     │    BLOQUÉ          │
│                            │              │                    │
│  ❌ Annuler sa demande     │      ✅      │       ❌           │
│     (si pending)           │   (sienne)   │                    │
│                            │              │                    │
│  ✅ Approuver une demande  │      ❌      │       ✅           │
│                            │              │   (son livre)      │
│                            │              │                    │
│  ❌ Rejeter une demande    │      ❌      │       ✅           │
│                            │              │   (son livre)      │
│                            │              │                    │
│  🔄 Marquer comme Retourné │      ❌      │       ✅           │
│                            │              │   (son livre)      │
│                            │              │                    │
│  ✅ Marquer comme Rendu    │      ✅      │       ❌           │
│     (confirmer retour)     │   (sienne)   │                    │
│                            │              │                    │
│  👀 Voir ses demandes      │      ✅      │       ✅           │
│     envoyées               │   (siennes)  │   (siennes)        │
│                            │              │                    │
│  👀 Voir demandes reçues   │      ❌      │       ✅           │
│     (pour ses livres)      │              │   (ses livres)     │
│                            │              │                    │
└─────────────────────────────────────────────────────────────────┘
```

---

## 📋 États et Transitions

```
┌──────────────────────────────────────────────────────────────────┐
│                    CYCLE DE VIE D'UNE DEMANDE                    │
└──────────────────────────────────────────────────────────────────┘

                    ┌─────────────────┐
                    │   🟡 PENDING    │
                    │  (En attente)   │
                    └────────┬────────┘
                             │
                ┌────────────┴────────────┐
                │                         │
                ↓                         ↓
     ┌──────────────────┐      ┌──────────────────┐
     │  🟢 APPROVED     │      │   🔴 REJECTED    │
     │  (Approuvé)      │      │   (Rejeté)       │
     └────────┬─────────┘      └──────────────────┘
              │                         │
              ↓                         │
     ┌──────────────────┐              │
     │  🔵 RETURNED     │              │
     │  (Retourné)      │              │
     └──────────────────┘              │
              │                         │
              └─────────┬───────────────┘
                        ↓
                  ╔═══════════╗
                  ║    FIN    ║
                  ╚═══════════╝

┌──────────────────────────────────────────────────────────────────┐
│  TRANSITIONS POSSIBLES                                           │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  🟡 PENDING → 🟢 APPROVED                                        │
│     Qui: Propriétaire (owner)                                    │
│     Action: Clic sur [✅ APPROUVER]                              │
│     Effet: Livre devient indisponible (is_available = false)     │
│                                                                  │
│  🟡 PENDING → 🔴 REJECTED                                        │
│     Qui: Propriétaire (owner)                                    │
│     Action: Clic sur [❌ REFUSER]                                │
│     Effet: Aucun (livre reste disponible)                        │
│                                                                  │
│  🟡 PENDING → 🔴 REJECTED (Annulation)                           │
│     Qui: Emprunteur (borrower)                                   │
│     Action: Clic sur [Annuler]                                   │
│     Effet: Aucun (utilise le status rejected)                    │
│                                                                  │
│  🟢 APPROVED → 🔵 RETURNED                                       │
│     Qui: Propriétaire (owner) ou Emprunteur (borrower)          │
│     Action: Clic sur [Marquer comme Retourné/Rendu]             │
│     Effet: Livre redevient disponible (is_available = true)      │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

---

## 🎨 Interface Dashboard

```
┌──────────────────────────────────────────────────────────────────┐
│  📊 DASHBOARD - VUE D'ALICE                                      │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │  Demandes d'emprunt                          [Voir tout →] │ │
│  │  Dernières activités                                       │ │
│  └────────────────────────────────────────────────────────────┘ │
│                                                                  │
│  ┌─────────────────────────────┬──────────────────────────────┐ │
│  │  MES DEMANDES ENVOYÉES      │  DEMANDES REÇUES             │ │
│  ├─────────────────────────────┼──────────────────────────────┤ │
│  │                             │                              │ │
│  │  📚 1984                    │  📚 Le Petit Prince          │ │
│  │  chez Bob                   │  par Charlie                 │ │
│  │  Du 19/10 au 30/10          │  Du 20/10 au 25/10           │ │
│  │  🟢 Approuvé                │  🟡 En attente               │ │
│  │                             │  [✅ APPROUVER] [❌ REFUSER] │ │
│  │  ─────────────────────────  │  ──────────────────────────  │ │
│  │                             │                              │ │
│  │  📚 Le Comte de Monte-Cristo│  📚 Le Petit Prince          │ │
│  │  chez David                 │  par Emma                    │ │
│  │  Du 25/10 au 05/11          │  Du 22/10 au 28/10           │ │
│  │  🟡 En attente              │  🟢 Approuvé                 │ │
│  │  [❌ Annuler]               │                              │ │
│  │                             │                              │ │
│  └─────────────────────────────┴──────────────────────────────┘ │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

---

## 🔍 Recherche et Filtrage

```
┌──────────────────────────────────────────────────────────────────┐
│  🏠 PAGE D'ACCUEIL - CATALOGUE                                   │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  🔍 Recherche: [_________________]  Catégorie: [Toutes ▼]       │
│                                                                  │
│  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐    │
│  │  📖 Livre 1    │  │  📖 Livre 2    │  │  📖 Livre 3    │    │
│  │  1984          │  │  Le Petit...   │  │  Fondation     │    │
│  │  George Orwell │  │  Saint-Exupéry │  │  Isaac Asimov  │    │
│  │                │  │                │  │                │    │
│  │  👤 Bob        │  │  👤 Alice      │  │  👤 Charlie    │    │
│  │  ✅ Disponible │  │  ❌ Emprunté   │  │  ✅ Disponible │    │
│  │                │  │                │  │                │    │
│  │  [Emprunter]   │  │  [Emprunter]   │  │  [Emprunter]   │    │
│  │                │  │  (désactivé)   │  │                │    │
│  └────────────────┘  └────────────────┘  └────────────────┘    │
│      ↓ Clic                                                      │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  📝 Formulaire de Demande d'Emprunt                      │   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │  Livre: 1984                                             │   │
│  │  Propriétaire: Bob                                       │   │
│  │                                                          │   │
│  │  Date début: [2025-10-19]                                │   │
│  │  Date fin:   [2025-10-30]                                │   │
│  │  Notes:      [J'aimerais lire ce livre...]              │   │
│  │                                                          │   │
│  │              [❌ Annuler]  [✅ Envoyer la demande]       │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

---

## 📊 Statistiques Temps Réel

```
┌──────────────────────────────────────────────────────────────────┐
│  📈 STATISTIQUES (Dashboard)                                     │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────┐  ┌──────────────────┐                     │
│  │  📤  5           │  │  📥  3           │                     │
│  │  Demandes        │  │  Demandes        │                     │
│  │  Envoyées        │  │  Reçues          │                     │
│  └──────────────────┘  └──────────────────┘                     │
│                                                                  │
│  ┌──────────────────┐  ┌──────────────────┐                     │
│  │  🟡  2           │  │  🟢  2           │                     │
│  │  En attente      │  │  Approuvées      │                     │
│  └──────────────────┘  └──────────────────┘                     │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

---

## 🎯 Résumé Visuel

```
              ┌──────────────────────────────────┐
              │   USER AJOUTE UN LIVRE           │
              │   ownerId = User automatique ✅  │
              └────────────┬─────────────────────┘
                           │
              ┌────────────▼─────────────────────┐
              │  LIVRE DISPONIBLE AU CATALOGUE   │
              │  Visible par tous les users      │
              └────────────┬─────────────────────┘
                           │
              ┌────────────▼─────────────────────┐
              │  AUTRE USER DEMANDE L'EMPRUNT    │
              │  Validation automatique ✅       │
              └────────────┬─────────────────────┘
                           │
              ┌────────────▼─────────────────────┐
              │  DEMANDE CRÉÉE (status: pending) │
              │  • borrower_id = Demandeur       │
              │  • owner_id = Propriétaire       │
              └─────┬──────────────────┬─────────┘
                    │                  │
        ┌───────────▼──────────┐   ┌──▼──────────────────┐
        │  DASHBOARD           │   │  DASHBOARD          │
        │  DEMANDEUR           │   │  PROPRIÉTAIRE       │
        │  "Mes Demandes"      │   │  "Reçues"           │
        │  🟡 En attente       │   │  🟡 En attente      │
        │                      │   │  [✅] [❌]          │
        └──────────────────────┘   └──┬──────────────────┘
                                      │
                         ┌────────────▼────────────────┐
                         │  PROPRIÉTAIRE DÉCIDE        │
                         │                             │
                         │  ✅ Approuver               │
                         │  • status = approved        │
                         │  • is_available = false     │
                         │                             │
                         │  ❌ Rejeter                 │
                         │  • status = rejected        │
                         └─────────────────────────────┘
```

---

## 🚀 Points Clés à Retenir

1. **Propriété Automatique**
   - Chaque livre créé appartient au user connecté
   - Pas besoin de sélectionner manuellement

2. **Protection Native**
   - Impossible d'emprunter son propre livre
   - Vérifié avant chaque demande

3. **Rôles Dynamiques**
   - Emprunteur (borrower) pour les demandes qu'on fait
   - Propriétaire (owner) pour les demandes reçues

4. **Dashboard Centralisé**
   - Toutes les infos en un seul endroit
   - Aperçu rapide + lien vers détails

5. **Sécurité Stricte**
   - Seul le propriétaire peut approuver/rejeter
   - Vérifications à chaque étape

6. **Interface Intuitive**
   - Badges colorés pour les statuts
   - Boutons contextuels selon le rôle
   - Messages d'erreur clairs

---

## ✅ TOUT FONCTIONNE !

Le système est **100% opérationnel** et répond **exactement** 
à tes besoins ! 🎉

Consulte `TEST_WEB_GUIDE.md` pour tester dès maintenant.

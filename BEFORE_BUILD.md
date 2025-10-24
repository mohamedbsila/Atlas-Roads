# ⚠️ ACTION REQUISE AVANT LE PROCHAIN BUILD

## 🔄 Mise à jour du mot de passe MySQL

Le mot de passe root MySQL a été changé de `rootpass` à `123456789`.

### 🚨 **IMPORTANT**: Exécutez ces commandes sur votre serveur Jenkins:

```bash
# Arrêter et supprimer l'ancien conteneur MySQL
docker stop atlas-mysql
docker rm atlas-mysql

# Optionnel: Supprimer l'ancien volume si vous voulez une DB propre
docker volume rm atlas-roads_mysql_data 2>/dev/null || true

# Supprimer aussi l'ancien conteneur de l'app s'il existe
docker stop atlas-app 2>/dev/null || true
docker rm atlas-app 2>/dev/null || true
```

### ✅ Ensuite, lancez le build Jenkins #34

Le pipeline va:
1. ✅ Créer un nouveau conteneur MySQL avec le password `123456789`
2. ✅ Builder l'image Docker Laravel
3. ✅ Déployer l'application sur le port 8000
4. ✅ Tout garder actif (pas d'arrêt après le build)

### 🎯 Résultat Final

Après le build, vous aurez:
- **MySQL** sur port 3306 (actif en permanence)
- **Nexus** sur port 8081 (actif en permanence)  
- **Laravel App** sur port 8000 (actif en permanence)

Tous avec `--restart unless-stopped` pour redémarrage automatique!

---

**Une fois fait, vous pouvez supprimer ce fichier.**


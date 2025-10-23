# 🚀 Atlas Roads - Deployment Guide

## 📋 Overview

Ce projet utilise **Jenkins CI/CD** pour déployer automatiquement l'application Laravel avec Docker.

## 🐳 Architecture Docker

- **MySQL** (port 3306) - Base de données
- **Nexus** (port 8081) - Repository manager
- **Laravel App** (port 8000) - Application web

## 🔧 Configuration

### MySQL Credentials
- **Root Password**: `123456789`
- **Database**: `atlas_roads`
- **App User**: `laravel`
- **App Password**: `laravel123`

### Nexus Credentials
- **Username**: `admin`
- **Password**: Récupéré via `docker exec atlas-nexus cat /nexus-data/admin.password`

## 🚀 Déploiement Automatique

### Via Jenkins Pipeline

1. **Push sur GitHub** déclenche automatiquement le build
2. Le pipeline Jenkins:
   - ✅ Lance MySQL et Nexus
   - ✅ Installe les dépendances (Composer + NPM)
   - ✅ Build les assets frontend
   - ✅ Exécute les tests
   - ✅ Construit l'image Docker
   - ✅ Déploie l'application sur le port 8000
   - ✅ Garde tous les conteneurs actifs

3. **Accédez à l'application**: `http://localhost:8000`

### Conteneurs Persistants

Les conteneurs **NE SONT PAS ARRÊTÉS** après le build:
- Démarrent automatiquement avec `--restart unless-stopped`
- Survivent aux redémarrages du serveur
- Sont mis à jour uniquement lors d'un nouveau build

## 📊 Commandes Utiles

### Vérifier le statut
```bash
docker ps --filter "name=atlas-"
```

### Voir les logs
```bash
# Application Laravel
docker logs -f atlas-app

# MySQL
docker logs -f atlas-mysql

# Nexus
docker logs -f atlas-nexus
```

### Redémarrer un service
```bash
docker restart atlas-app
docker restart atlas-mysql
docker restart atlas-nexus
```

### Arrêter tout
```bash
docker stop atlas-app atlas-mysql atlas-nexus
```

### Supprimer tout (⚠️ ATTENTION: supprime les données)
```bash
docker stop atlas-app atlas-mysql atlas-nexus
docker rm atlas-app atlas-mysql atlas-nexus
docker network rm atlas-network
docker volume rm atlas-roads_mysql_data atlas-roads_nexus_data
```

## 🔄 Mise à Jour de l'Application

1. **Commitez vos changements**:
   ```bash
   git add .
   git commit -m "Your changes"
   git push origin main
   ```

2. **Jenkins build automatiquement** et déploie la nouvelle version

3. **L'application est rechargée** sans downtime grâce à Docker

## 🐛 Troubleshooting

### L'application ne démarre pas
```bash
# Vérifier les logs
docker logs atlas-app --tail 100

# Vérifier MySQL
docker exec atlas-mysql mysqladmin ping -h localhost

# Se connecter à MySQL
docker exec -it atlas-mysql mysql -uroot -p123456789
```

### Erreur 500
```bash
# Vérifier les permissions
docker exec atlas-app ls -la /var/www/html/storage

# Régénérer le cache
docker exec atlas-app php artisan config:clear
docker exec atlas-app php artisan cache:clear
docker exec atlas-app php artisan view:clear
```

### Réinitialiser la base de données
```bash
docker exec atlas-app php artisan migrate:fresh --seed
```

## 📦 Déploiement Manuel (sans Jenkins)

Si vous voulez déployer manuellement:

```bash
# Build l'image
docker build -t atlas-laravel:latest .

# Lancez avec docker-compose
docker-compose up -d

# Ou lancez manuellement
docker run -d \
  --name atlas-app \
  --network atlas-network \
  -p 8000:80 \
  -e DB_HOST=atlas-mysql \
  -e DB_DATABASE=atlas_roads \
  -e DB_USERNAME=laravel \
  -e DB_PASSWORD=laravel123 \
  atlas-laravel:latest
```

## 🔐 Sécurité

⚠️ **IMPORTANT pour la production:**

1. Changez tous les mots de passe par défaut
2. Utilisez des secrets Jenkins pour les credentials
3. Activez HTTPS avec un reverse proxy (nginx)
4. Configurez un firewall
5. Activez les backups automatiques de MySQL

## 📞 Support

En cas de problème, vérifiez:
1. Les logs Jenkins
2. Les logs Docker (`docker logs`)
3. La connexion réseau (`docker network inspect atlas-network`)
4. L'espace disque (`df -h`)


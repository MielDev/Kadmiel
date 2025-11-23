# Déploiement sur Render avec Docker

Ce guide explique comment déployer cette application sur Render.com en utilisant Docker.

## Prérequis

- Un compte [Render](https://render.com/)
- Un compte [GitHub](https://github.com/)
- Docker installé en local (pour les tests)

## Structure des fichiers

```
Kadmiel/
├── Dockerfile
├── docker-compose.yml
├── docker/
│   └── 000-default.conf
├── forms/
│   └── contact.php
├── email-templates/
│   └── contact-email.html
└── .dockerignore
```

## Déploiement sur Render

1. **Poussez votre code sur GitHub**

   ```bash
   git add .
   git commit -m "Préparation pour le déploiement Docker"
   git push origin main
   ```

2. **Connectez-vous à Render**
   - Allez sur [Render Dashboard](https://dashboard.render.com/)
   - Cliquez sur "New +" puis sélectionnez "Web Service"

3. **Configurez le service**
   - Connectez votre compte GitHub
   - Sélectionnez votre dépôt
   - Nommez votre service (ex: `kadmiel-portfolio`)
   - Sélectionnez la région la plus proche de votre public cible
   - Choisissez "Docker" comme environnement
   - Laissez les champs "Build Command" et "Start Command" vides
   - Sélectionnez le plan gratuit (ou un plan payant pour de meilleures performances)
   - Activez "Auto-Deploy" si vous voulez que le site se mette à jour automatiquement

4. **Variables d'environnement**
   - `APACHE_DOCUMENT_ROOT`: `/var/www/html`
   - `PHP_INI_MEMORY_LIMIT`: `256M`
   - `UPLOAD_MAX_FILESIZE`: `64M`
   - `POST_MAX_SIZE`: `64M`

5. **Cliquez sur "Create Web Service"**

## Configuration du domaine personnalisé (optionnel)

1. Allez dans les paramètres de votre service sur Render
2. Cliquez sur "Custom Domains"
3. Ajoutez votre domaine et suivez les instructions pour configurer les DNS

## Test local avec Docker

1. **Construisez l'image Docker**

   ```bash
   docker-compose build
   ```

2. **Démarrez les conteneurs**

   ```bash
   docker-compose up -d
   ```

3. **Accédez à l'application**
   - Ouvrez <http://localhost:8080> dans votre navigateur

4. **Arrêtez les conteneurs**

   ```bash
   docker-compose down
   ```

## Dépannage

- **Les images ne s'affichent pas**
  - Vérifiez que les chemins des images commencent par `/`
  - Vérifiez les permissions des fichiers

- **Le formulaire ne fonctionne pas**
  - Vérifiez les logs dans le tableau de bord Render
  - Assurez-vous que le fichier `contact.php` a les bonnes permissions (755)

- **Erreurs de déploiement**
  - Vérifiez que tous les fichiers nécessaires sont commités
  - Vérifiez les logs de build dans le tableau de bord Render

## Sécurité

- Ne stockez jamais d'informations sensibles dans le code source
- Utilisez des variables d'environnement pour les informations sensibles
- Mettez à jour régulièrement vos dépendances

## Support

Pour toute question ou problème, veuillez ouvrir une issue sur GitHub ou contacter l'administrateur.

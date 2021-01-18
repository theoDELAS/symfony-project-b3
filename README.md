# Project Symfony de 3ème année à Ynov Informatique

## Prerequisites ✅

- **PHP 7.4** installed on your machine
- **Composer** installed on your machine
- **NodeJS and NPM** installed on your machine
- **Symfony CLI** installed on your machine

## Setup 🚀

```sh
git clone https://github.com/theoDELAS/symfony-project-b3
cd symfony-project-b3
composer install
npm install
php bin/console d:d:c
php bin/console d:m:m
php bin/console d:f:l
npm run build
symfony serve
```

Maintenant, il faut configurer le serveur Mercure pour le système de chat. Le projet n'étant pas abouti, il faut télécharger manuellement la version adéquat à votre OS sur ce lien : https://github.com/dunglas/mercure/releases/tag/v0.9.0

Décompressez et copiez le contenu du dossier et insérez le tout dans le dossier `/bin/` de votre application Symfony.

Insérez ces lignes dans votre fichier `.env` (variables d'environnement du serveur Mercure)

```
MERCURE_PUBLISH_URL=http://localhost:3000/.well-known/mercure
MERCURE_JWT_TOKEN=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdfX0.7O7cu4UGyIvZ_RnrEdKm4BMZUZcb5uNOyGc1HqALNmQ
MERCURE_SECRET_KEY=konshensx
```

Sous Linux, exécutez cette commande pour lancer le serveur Mercure

```sh
JWT_KEY=konshensx ADDR=localhost:3000 CORS_ALLOWED_ORIGINS=localhost:8000 ./bin/mercure
```

Ou sous Windows :

```sh
JWT_KEY=konshensx ADDR=localhost:3000 CORS_ALLOWED_ORIGINS=localhost:8000 ./bin/mercure.exe
```

🎉 You can now visit **localhost:8000** and enjoy with the website!

## Fonctionnalités

- Système d'inscription/connexion ✅
- CRUD photos ⏳
- Choix des photos mises en avant ❌
- Like ✅
- Commentaire ✅
- Système de chat en temps réel ⏳
- Gestion des paramètres utilisateur (gestion photo de profil, mot de passe, informations personnelles, etc) ✅
- Retouche de photos avant publication ❌
- Back office : système de rôles ✅
- Sécurisation des routes ✅
- Site responsive ⏳
- UX/UI agréable ✅

## Feedback

Projet intéressant mais malheureusement pas assez de temps pour réaliser un projet complet (projets, cours, alternance...).

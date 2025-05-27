# StageManager - MVP (Produit Minimum Viable)

Bienvenue dans la première version (MVP) de StageManager, une application Laravel conçue pour gérer les stages, les projets, les encadrants et les stagiaires.

## Fonctionnalités Incluses dans ce MVP

Ce MVP se concentre sur les fonctionnalités essentielles pour démarrer :

*   **Authentification :** Connexion et enregistrement des utilisateurs.
*   **Gestion des Rôles :**
    *   Admin : Peut gérer les utilisateurs (admins, encadrants, stagiaires) et les projets.
    *   Encadrant : Peut voir ses projets assignés, gérer les tâches de ses projets et évaluer (simplement) les stagiaires.
    *   Stagiaire : Peut voir ses projets et tâches assignés, et mettre à jour le statut de ses tâches.
*   **Module Admin :**
    *   Dashboard simple.
    *   CRUD complet pour les Utilisateurs (avec distinction du type de stagiaire).
    *   CRUD complet pour les Projets.
    *   Assignation d'un Encadrant à un projet.
    *   Assignation de Stagiaires à un projet (via la page de détail du projet).
*   **Module Encadrant :**
    *   Dashboard listant les projets supervisés.
    *   Vue détaillée des projets (avec liste des tâches et stagiaires).
    *   CRUD complet pour les Tâches au sein d'un projet.
    *   Formulaire simple pour l'évaluation des stagiaires (stockage basique, fonctionnalité à enrichir en V2).
*   **Module Stagiaire :**
    *   Dashboard listant les projets et tâches assignés.
    *   Vue détaillée des projets assignés.
    *   Liste des tâches assignées.
    *   Possibilité de mettre à jour le statut d'une tâche ("A faire", "En cours", "Terminé", "Bloqué").
*   **Fonctionnalités Transversales :**
    *   Système de rôles et permissions de base (via `spatie/laravel-permission`).
    *   Middlewares pour protéger les routes par rôle.
    *   Notifications internes simples (stockées en base de données, affichage basique à implémenter en V2).
    *   Layouts Blade et style de base avec Bootstrap (via `laravel/ui`).

## Prérequis

*   PHP >= 8.1
*   Composer
*   Node.js & NPM
*   Un serveur de base de données (MySQL, PostgreSQL, SQLite...). Ce MVP est configuré par défaut pour utiliser **SQLite** pour une installation facile.

## Installation

1.  **Cloner/Extraire le projet :**
    *   Téléchargez et décompressez l'archive ZIP fournie dans le répertoire de votre choix.
    *   Ouvrez un terminal et naviguez jusqu'au répertoire du projet (`cd StageManager`).

2.  **Installer les dépendances PHP :**
    ```bash
    composer install
    ```

3.  **Installer les dépendances JavaScript :**
    ```bash
    npm install
    ```

4.  **Compiler les assets frontend :**
    ```bash
    npm run build
    ```
    *(Pour le développement, vous pouvez utiliser `npm run dev`)*

5.  **Configurer l'environnement :**
    *   Copiez le fichier d'exemple `.env.example` vers `.env` :
        ```bash
        cp .env.example .env
        ```
    *   Générez une clé d'application unique :
        ```bash
        php artisan key:generate
        ```
    *   **Configuration de la base de données :** Le fichier `.env` est pré-configuré pour utiliser une base de données **SQLite**. Assurez-vous que l'extension PHP SQLite est activée (`php-sqlite3`). Le fichier de base de données sera créé automatiquement dans `database/database.sqlite`.
        *   Si vous préférez utiliser MySQL ou un autre SGBD, modifiez les variables `DB_*` dans le fichier `.env` en conséquence (créez la base de données manuellement si nécessaire).

6.  **Exécuter les migrations et les seeders :**
    *   Cela créera les tables de la base de données et les remplira avec des données de démonstration (utilisateurs, projets, tâches).
    ```bash
    php artisan migrate --seed
    ```

7.  **Lancer le serveur de développement :**
    ```bash
    php artisan serve
    ```
    L'application sera accessible par défaut à l'adresse `http://127.0.0.1:8000`.

## Comptes de Test

Les seeders créent les utilisateurs suivants (mot de passe par défaut : `password`) :

*   **Admin :** `admin@stagemanager.test`
*   **Encadrants :** 5 utilisateurs générés aléatoirement (ex: `encadrant1@example.com`, etc. - vérifiez la base de données ou la sortie de la commande `php artisan db:seed` pour les emails exacts).
*   **Stagiaires :** 10 utilisateurs générés aléatoirement (5 diplômés, 5 en cours de formation) (ex: `stagiaire1@example.com`, etc. - vérifiez la base de données ou la sortie de la commande `php artisan db:seed` pour les emails exacts).

## Structure du Projet (Simplifiée)

*   `app/Http/Controllers/` : Contient les contrôleurs organisés par rôle (Admin, Encadrant, Stagiaire).
*   `app/Http/Middleware/` : Contient les middlewares pour la gestion des rôles.
*   `app/Models/` : Contient les modèles Eloquent (User, Project, Task, Evaluation, Notification).
*   `database/factories/` : Contient les factories pour générer des données de test.
*   `database/migrations/` : Contient les migrations de la base de données.
*   `database/seeders/` : Contient les seeders pour remplir la base de données.
*   `resources/views/` : Contient les vues Blade, organisées par rôle et module.
    *   `layouts/app.blade.php` : Layout principal.
*   `routes/web.php` : Définit les routes web de l'application, groupées par rôle.
*   `config/` : Fichiers de configuration (ex: `permission.php` pour Spatie).
*   `.env` : Fichier de configuration de l'environnement (base de données, etc.).

## Prochaines Étapes (V2)

La version complète inclura :

*   Tableaux de bord plus détaillés avec statistiques et graphiques.
*   Système de permissions plus avancé.
*   Centre de notifications complet (temps réel, multi-canaux).
*   Exports PDF/Excel.
*   Journal d'activités.
*   Recherche globale.
*   Paramètres système.
*   Chat temps réel.
*   Gestion des fichiers (upload, versioning).
*   API REST complète avec documentation Swagger.
*   Tests unitaires et fonctionnels.
*   Améliorations UI/UX (mode sombre, multi-langue, PWA).
*   Sécurité renforcée (2FA).

N'hésitez pas si vous avez des questions ou des retours sur ce MVP !


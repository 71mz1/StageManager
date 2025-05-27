# Document Explicatif - StageManager MVP

Ce document décrit la structure du projet Laravel "StageManager" (version MVP) et le fonctionnement de ses composants principaux.

## 1. Structure Générale du Projet (Laravel MVC)

L'application suit l'architecture Modèle-Vue-Contrôleur (MVC) standard de Laravel :

*   **Modèles (`app/Models/`) :** Représentent les données de l'application et interagissent avec la base de données. Les modèles principaux de ce MVP sont :
    *   `User.php` : Gère les utilisateurs et leurs rôles (via `spatie/laravel-permission`). Inclut les relations vers les projets (supervisés ou assignés) et les tâches.
    *   `Project.php` : Gère les projets de stage, avec leurs détails et relations (Encadrant, Stagiaires, Tâches).
    *   `Task.php` : Gère les tâches associées à un projet, avec leurs détails et relations (Projet, Assigné).
    *   `Role.php` & `Permission.php` (de Spatie) : Gérés via le package `spatie/laravel-permission`.
    *   `Evaluation.php` : Modèle simple pour les évaluations (fonctionnalité basique dans le MVP).
    *   `Notification.php` : Modèle pour les notifications internes simples (stockage en BDD).

*   **Vues (`resources/views/`) :** Responsables de la présentation de l'interface utilisateur. Elles sont écrites en Blade, le moteur de template de Laravel, et organisées par rôle :
    *   `layouts/app.blade.php` : Le layout principal qui contient la structure commune (navbar, footer, inclusion des CSS/JS).
    *   `auth/` : Vues pour l'authentification (login, register) fournies par `laravel/ui`.
    *   `admin/` : Vues spécifiques à l'administrateur (dashboard, gestion utilisateurs, gestion projets).
    *   `encadrant/` : Vues spécifiques à l'encadrant (dashboard, détails projets, gestion tâches, évaluation simple).
    *   `stagiaire/` : Vues spécifiques au stagiaire (dashboard, détails projets, liste tâches, mise à jour statut tâche).
    *   `home.blade.php` : Page d'accueil après connexion (redirige vers le dashboard approprié).

*   **Contrôleurs (`app/Http/Controllers/`) :** Gèrent la logique applicative, reçoivent les requêtes HTTP, interagissent avec les modèles et retournent les vues appropriées. Ils sont organisés par rôle :
    *   `Auth/` : Contrôleurs d'authentification (fournis par `laravel/ui`).
    *   `Admin/` : Contrôleurs pour les fonctionnalités Admin (DashboardController, UserController, ProjectController).
    *   `Encadrant/` : Contrôleurs pour les fonctionnalités Encadrant (DashboardController, ProjectController, TaskController, EvaluationController - basique).
    *   `Stagiaire/` : Contrôleurs pour les fonctionnalités Stagiaire (DashboardController, ProjectController, TaskController).
    *   `HomeController.php` : Contrôleur simple pour la page d'accueil post-connexion.

*   **Routes (`routes/web.php`) :** Définissent les URL de l'application et les associent aux actions des contrôleurs. Les routes sont groupées par préfixe (`/admin`, `/encadrant`, `/stagiaire`) et protégées par les middlewares d'authentification (`auth`) et de rôle (`role.admin`, `role.encadrant`, `role.stagiaire`).

*   **Middlewares (`app/Http/Middleware/`) :** Filtrent les requêtes HTTP. Les middlewares spécifiques créés pour ce projet (`AdminMiddleware`, `EncadrantMiddleware`, `StagiaireMiddleware`) vérifient le rôle de l'utilisateur connecté avant d'autoriser l'accès aux routes protégées. Ils sont enregistrés dans `app/Http/Kernel.php`.

*   **Migrations (`database/migrations/`) :** Permettent de définir et de modifier le schéma de la base de données de manière versionnée.

*   **Seeders (`database/seeders/`) :** Permettent de remplir la base de données avec des données initiales ou de test (Rôles, Utilisateurs, Projets, Tâches).

*   **Factories (`database/factories/`) :** Utilisées par les seeders pour générer des instances de modèles avec des données fictives.

*   **Configuration (`config/`) :** Contient les fichiers de configuration de l'application (base de données, mail, services, permissions Spatie dans `config/permission.php`).

*   **Assets (`resources/js/`, `resources/sass/`, `public/`) :** Fichiers JavaScript et CSS. Ce MVP utilise Bootstrap (via `laravel/ui`) et Vite pour la compilation des assets.

## 2. Fonctionnement des Pages Principales (MVP)

### 2.1 Authentification

*   **Login (`/login`) :** Formulaire standard pour se connecter. Utilise `Auth/LoginController`.
*   **Register (`/register`) :** Formulaire standard pour créer un compte (par défaut, crée un utilisateur sans rôle spécifique ; l'Admin doit assigner les rôles ensuite). Utilise `Auth/RegisterController`.
*   **Logout :** Géré via un lien dans la barre de navigation, poste vers la route `logout`.

### 2.2 Tableau de Bord (Dashboard)

Après connexion, l'utilisateur est redirigé vers `/home` (`HomeController`), qui le redirige ensuite vers le tableau de bord correspondant à son rôle principal :

*   **Admin (`/admin/dashboard`) :**
    *   **Contrôleur :** `Admin/DashboardController`
    *   **Vue :** `admin/dashboard.blade.php`
    *   **Fonctionnement :** Affiche un message de bienvenue et des liens rapides vers la gestion des utilisateurs et des projets.
*   **Encadrant (`/encadrant/dashboard`) :**
    *   **Contrôleur :** `Encadrant/DashboardController`
    *   **Vue :** `encadrant/dashboard.blade.php` (Vue basique à créer si non faite)
    *   **Fonctionnement :** Affiche la liste des projets supervisés par l'encadrant connecté.
*   **Stagiaire (`/stagiaire/dashboard`) :**
    *   **Contrôleur :** `Stagiaire/DashboardController`
    *   **Vue :** `stagiaire/dashboard.blade.php`
    *   **Fonctionnement :** Affiche un résumé des projets et des tâches actives assignés au stagiaire connecté.

### 2.3 Module Admin

*   **Gestion des Utilisateurs (`/admin/users`) :**
    *   **Contrôleur :** `Admin/UserController` (méthodes `index`, `create`, `store`, `edit`, `update`, `destroy`)
    *   **Vues :** `admin/users/index.blade.php`, `create.blade.php`, `edit.blade.php`
    *   **Fonctionnement :** Permet à l'Admin de lister, créer, modifier et supprimer des utilisateurs. Lors de la création/modification, l'Admin assigne les rôles (Admin, Encadrant, Stagiaire). Si le rôle "Stagiaire" est sélectionné, un champ supplémentaire apparaît pour définir le type de stagiaire.
*   **Gestion des Projets (`/admin/projects`) :**
    *   **Contrôleur :** `Admin/ProjectController` (méthodes `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`, et méthodes pour assigner/retirer stagiaires/encadrant)
    *   **Vues :** `admin/projects/index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`
    *   **Fonctionnement :** Permet à l'Admin de lister, créer, modifier et supprimer des projets. La vue `show` affiche les détails du projet, l'encadrant assigné et la liste des stagiaires assignés. Elle inclut des formulaires pour assigner un encadrant (parmi les utilisateurs ayant le rôle Encadrant) et pour ajouter/retirer des stagiaires (parmi les utilisateurs ayant le rôle Stagiaire).

### 2.4 Module Encadrant

*   **Liste des Projets (`/encadrant/projects`) :**
    *   **Contrôleur :** `Encadrant/ProjectController` (méthode `index`)
    *   **Vue :** `encadrant/projects/index.blade.php` (Vue basique à créer si non faite)
    *   **Fonctionnement :** Affiche la liste des projets supervisés par l'encadrant connecté.
*   **Détails d'un Projet (`/encadrant/projects/{project}`) :**
    *   **Contrôleur :** `Encadrant/ProjectController` (méthode `show`)
    *   **Vue :** `encadrant/projects/show.blade.php` (Vue basique à créer si non faite)
    *   **Fonctionnement :** Affiche les détails du projet, la liste des stagiaires assignés et la liste des tâches du projet. Contient des liens/boutons pour créer/modifier/supprimer des tâches pour ce projet.
*   **Gestion des Tâches (`/encadrant/projects/{project}/tasks/...`) :**
    *   **Contrôleur :** `Encadrant/TaskController` (méthodes `create`, `store`, `edit`, `update`, `destroy`)
    *   **Vues :** `encadrant/tasks/create.blade.php`, `edit.blade.php` (Vues basiques à créer si non faites)
    *   **Fonctionnement :** Permet à l'Encadrant de créer, modifier et supprimer des tâches *au sein d'un projet spécifique*. Le formulaire de création/modification permet d'assigner la tâche à un stagiaire *parmi ceux assignés au projet*.
*   **Évaluation Simple (`/encadrant/evaluations/...`) :**
    *   **Contrôleur :** `Encadrant/EvaluationController` (méthodes basiques `create`, `store`)
    *   **Vues :** `encadrant/evaluations/create.blade.php` (Vue basique à créer si non faite)
    *   **Fonctionnement (MVP) :** Un formulaire très simple (accessible depuis la page projet ou stagiaire) permet à l'encadrant de laisser un commentaire d'évaluation pour un stagiaire sur un projet. Le stockage est basique dans la table `evaluations`. (Fonctionnalité à développer en V2).

### 2.5 Module Stagiaire

*   **Liste des Projets (`/stagiaire/projects`) :**
    *   **Contrôleur :** `Stagiaire/ProjectController` (méthode `index`)
    *   **Vue :** `stagiaire/projects/index.blade.php`
    *   **Fonctionnement :** Affiche la liste des projets auxquels le stagiaire connecté est assigné.
*   **Détails d'un Projet (`/stagiaire/projects/{project}`) :**
    *   **Contrôleur :** `Stagiaire/ProjectController` (méthode `show`)
    *   **Vue :** `stagiaire/projects/show.blade.php`
    *   **Fonctionnement :** Affiche les détails du projet et la liste des tâches *assignées au stagiaire connecté* pour ce projet.
*   **Liste des Tâches (`/stagiaire/tasks`) :**
    *   **Contrôleur :** `Stagiaire/TaskController` (méthode `index`)
    *   **Vue :** `stagiaire/tasks/index.blade.php`
    *   **Fonctionnement :** Affiche toutes les tâches assignées au stagiaire connecté, tous projets confondus.
*   **Détails d'une Tâche (`/stagiaire/tasks/{task}`) :**
    *   **Contrôleur :** `Stagiaire/TaskController` (méthode `show`)
    *   **Vue :** `stagiaire/tasks/show.blade.php`
    *   **Fonctionnement :** Affiche les détails de la tâche. Inclut un formulaire simple (dropdown ou boutons) permettant au stagiaire de mettre à jour le statut de sa tâche (`A faire`, `En cours`, `Terminé`, `Bloqué`). La mise à jour est gérée par la méthode `updateStatus` du contrôleur.

## 3. Composants Transversaux

*   **Barre de Navigation (`layouts/app.blade.php`) :** Affiche les liens pertinents en fonction du rôle de l'utilisateur connecté (`Admin`, `Encadrant`, `Stagiaire`). Affiche également le nom de l'utilisateur et un lien de déconnexion.
*   **Notifications (`app/Models/Notification.php`, migration associée) :** Une table `simple_notifications` est créée pour stocker des messages simples (ex: "Une nouvelle tâche vous a été assignée"). La logique de création et d'affichage de ces notifications est à implémenter en V2, mais la structure de base est prête.
*   **Système de Permissions (`spatie/laravel-permission`) :** Utilisé pour définir les rôles (`Admin`, `Encadrant`, `Stagiaire`) et les assigner aux utilisateurs. Les middlewares de rôle utilisent ce package pour vérifier les accès.

Ce document fournit une vue d'ensemble du MVP. Des fonctionnalités plus avancées et des améliorations sont prévues pour la V2.

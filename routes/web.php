<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// إضافة route جديد للlogout
Route::get('/logout', function() {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;

// Routes d'authentification générées par Laravel UI
Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Groupe de routes pour l'administration, protégées par authentification et rôle Admin
Route::middleware(['auth', 'role.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Routes pour la gestion des utilisateurs (CRUD basique pour MVP)
    Route::resource('users', AdminUserController::class);
    
    // Routes pour la gestion des projets (CRUD basique pour MVP)
    Route::resource('projects', AdminProjectController::class);
    
    // Routes pour la gestion des stagiaires dans les projets
    Route::post('projects/{project}/add-stagiaire', [AdminProjectController::class, 'addStagiaire'])
        ->name('projects.add-stagiaire');
    Route::delete('projects/{project}/remove-stagiaire/{user}', [AdminProjectController::class, 'removeStagiaire'])
        ->name('projects.remove-stagiaire');
    
    // Routes supplémentaires pour l'assignation (à définir plus tard si nécessaire pour MVP)
    // Route::post('projects/{project}/assign-encadrant', [AdminProjectController::class, 'assignEncadrant'])->name('projects.assignEncadrant');
    // Route::post('projects/{project}/assign-stagiaires', [AdminProjectController::class, 'assignStagiaires'])->name('projects.assignStagiaires');
});

// Ajouter ici les routes pour Encadrant et Stagiaire plus tard...



use App\Http\Controllers\Encadrant\DashboardController as EncadrantDashboardController;
use App\Http\Controllers\Encadrant\ProjectController as EncadrantProjectController;
use App\Http\Controllers\Encadrant\TaskController as EncadrantTaskController;
use App\Http\Controllers\Encadrant\EvaluationController as EncadrantEvaluationController;

// Groupe de routes pour les Encadrants, protégées par authentification et rôle Encadrant (ou Admin)
Route::middleware(["auth", "role.encadrant"])->prefix("encadrant")->name("encadrant.")->group(function () {
    Route::get("/dashboard", [EncadrantDashboardController::class, "index"])->name("dashboard");
    
    // Routes pour la consultation des projets assignés
    Route::get("/projects", [EncadrantProjectController::class, "index"])->name("projects.index");
    Route::get("/projects/{project}", [EncadrantProjectController::class, "show"])->name("projects.show"); // Voir détails, tâches, stagiaires

    // Routes pour la gestion des tâches (CRUD)
    Route::resource("projects.tasks", EncadrantTaskController::class)->except(["index"]); // Tasks are shown within project context

    // Routes pour l'évaluation simple des stagiaires (MVP)
    Route::get("/projects/{project}/evaluations/create", [EncadrantEvaluationController::class, "create"])->name("evaluations.create");
    Route::post("/projects/{project}/evaluations", [EncadrantEvaluationController::class, "store"])->name("evaluations.store");

    // Project routes
    Route::get('/projects', [\App\Http\Controllers\Encadrant\ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [\App\Http\Controllers\Encadrant\ProjectController::class, 'show'])->name('projects.show');
    
    // Task routes
    Route::post('/tasks', [\App\Http\Controllers\Encadrant\TaskController::class, 'store'])->name('tasks.store');
    Route::get('/projects/{project}/tasks/{task}', [\App\Http\Controllers\Encadrant\TaskController::class, 'show'])->name('tasks.show');
    Route::put('/tasks/{task}', [\App\Http\Controllers\Encadrant\TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [\App\Http\Controllers\Encadrant\TaskController::class, 'destroy'])->name('tasks.destroy');
    
    // Task messages routes
    Route::get('/projects/{project}/tasks/{task}/messages', [\App\Http\Controllers\Encadrant\TaskController::class, 'getMessages'])->name('tasks.messages');
    Route::post('/projects/{project}/tasks/{task}/messages', [\App\Http\Controllers\Encadrant\TaskController::class, 'storeMessage'])->name('tasks.messages.store');
});

// Ajouter ici les routes pour Stagiaire plus tard...



use App\Http\Controllers\Stagiaire\DashboardController as StagiaireDashboardController;
use App\Http\Controllers\Stagiaire\ProjectController as StagiaireProjectController;
use App\Http\Controllers\Stagiaire\TaskController as StagiaireTaskController;

// Groupe de routes pour les Stagiaires, protégées par authentification et rôle Stagiaire (ou Encadrant/Admin)
Route::middleware(["auth", "role.stagiaire"])->prefix("stagiaire")->name("stagiaire.")->group(function () {
    Route::get("/dashboard", [StagiaireDashboardController::class, "index"])->name("dashboard");

    // Voir les projets assignés
    Route::get("/projects", [StagiaireProjectController::class, "index"])->name("projects.index");
    Route::get("/projects/{project}", [StagiaireProjectController::class, "show"])->name("projects.show"); // Voir détails et tâches

    // Voir les tâches assignées et mettre à jour le statut
    Route::get("/tasks", [StagiaireTaskController::class, "index"])->name("tasks.index"); // Liste de toutes les tâches assignées
    Route::get("/tasks/{task}", [StagiaireTaskController::class, "show"])->name("tasks.show");
    Route::patch("/tasks/{task}/update-status", [StagiaireTaskController::class, "updateStatus"])->name("tasks.updateStatus");
});

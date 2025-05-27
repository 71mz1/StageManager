<?php

namespace App\Http\Controllers\Encadrant;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects supervised by the encadrant.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $encadrant = Auth::user();
        $projects = $encadrant->projectsSupervised()->withCount("stagiaires", "tasks")->latest()->paginate(10);
        return view("encadrant.projects.index", compact("projects"));
    }

    /**
     * Display the specified project details.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        // Ensure the logged-in encadrant is supervising this project
        Gate::authorize("view", $project);

        // Eager load relationships with error handling
        $project->load([
            'stagiaires' => function($query) {
                $query->select('users.id', 'name', 'email');
            },
            'tasks' => function($query) {
                $query->with(['assignee' => function($q) {
                    $q->select('id', 'name');
                }]);
            }
        ]);

        return view("encadrant.projects.show", compact("project"));
    }
}

// Note: A ProjectPolicy should be created to handle authorization (Gate::authorize)
// php artisan make:policy ProjectPolicy --model=Project

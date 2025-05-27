<?php

namespace App\Http\Controllers\Stagiaire;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects assigned to the stagiaire.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stagiaire = Auth::user();
        $projects = $stagiaire->projectsAssigned()->withCount("tasks")->latest()->paginate(10);
        return view("stagiaire.projects.index", compact("projects"));
    }

    /**
     * Display the specified project details.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        $stagiaire = Auth::user();
        // Ensure the stagiaire is assigned to this project
        if (!$stagiaire->projectsAssigned()->where("project_id", $project->id)->exists()) {
             abort(403, "Vous n'êtes pas assigné à ce projet.");
        }

        // Load tasks assigned specifically to this stagiaire within this project
        $project->load(["encadrant"]);
        $tasks = $project->tasks()->where("assignee_id", $stagiaire->id)->latest()->get();

        return view("stagiaire.projects.show", compact("project", "tasks"));
    }
}

// Note: Requires views in resources/views/stagiaire/projects/


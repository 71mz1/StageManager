<?php

namespace App\Http\Controllers\Stagiaire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the stagiaire dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        // Pour le MVP, récupérer les projets et tâches assignés
        $projects = $user->projectsAssigned()->withCount("tasks")->latest()->get();
        $tasks = $user->tasksAssigned()->whereIn("status", ["A faire", "En cours"])->orderBy("priority")->latest()->get();
        
        // Les statistiques plus avancées viendront en V2
        return view("stagiaire.dashboard", compact("projects", "tasks"));
    }
}


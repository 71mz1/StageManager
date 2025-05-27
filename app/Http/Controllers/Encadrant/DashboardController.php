<?php

namespace App\Http\Controllers\Encadrant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the encadrant dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        // Pour le MVP, récupérer les projets assignés à l'encadrant
        $projects = $user->projectsSupervised()->withCount('stagiaires')->latest()->paginate(10);
        // Les statistiques plus avancées viendront en V2
        return view('encadrant.dashboard.index', compact('projects'));
    }
}

// Note: Need to add the 'projectsSupervised' relationship to the User model.

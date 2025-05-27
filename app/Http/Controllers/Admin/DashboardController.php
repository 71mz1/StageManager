<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Use 'Stagiaire' instead of 'intern' to match the role name in the database
            $internsCount = User::role('Stagiaire')->count();
        } catch (\Exception $e) {
            // If role doesn't exist, set count to 0
            $internsCount = 0;
        }

        $data = [
            'usersCount' => User::count(),
            'projectsCount' => Project::count(),
            'internsCount' => $internsCount,
            'users' => User::with('roles')->latest()->take(5)->get(),
            'projects' => Project::with('encadrant')->latest()->take(5)->get(),
        ];

        return view("admin.dashboard", $data);
    }
}


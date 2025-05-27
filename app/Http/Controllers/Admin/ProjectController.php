<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $projects = Project::with('encadrant')
            ->when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();
            
        return view("admin.projects.index", compact("projects"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get users with the 'Encadrant' role to populate the supervisor dropdown
        $encadrants = User::role("Encadrant")->pluck("name", "id");
        $stagiaires = User::role("Stagiaire")->get();
        return view("admin.projects.create", compact("encadrants", "stagiaires"));

    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        "name" => "required|string|max:255",
        "description" => "required|string",
        "start_date" => "required|date",
        "end_date" => "required|date|after_or_equal:start_date",
        "priority" => "required|string|in:Basse,Normale,Haute",
        "encadrant_id" => "nullable|exists:users,id",
        "stagiaires" => "nullable|array",
        "stagiaires.*" => "exists:users,id",
    ]);

    $project = Project::create($request->except('stagiaires'));
    
    if ($request->has('stagiaires')) {
        $project->stagiaires()->sync($request->stagiaires);
    }

    return redirect()->route("admin.projects.index")->with("success", "Projet créé avec succès.");
}

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Load relationships for the show view
        $project->load("encadrant", "stagiaires"); 
        $stagiaires = User::role("Stagiaire")->get(); // Get potential interns to assign
        return view("admin.projects.show", compact("project", "stagiaires"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $encadrants = User::role("Encadrant")->pluck("name", "id");
        return view("admin.projects.edit", compact("project", "encadrants"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "description" => "required|string",
            "start_date" => "required|date",
            "end_date" => "required|date|after_or_equal:start_date",
            "priority" => "required|string|in:Basse,Normale,Haute",
            "encadrant_id" => "nullable|exists:users,id",
        ]);

        $project->update($request->all());

        return redirect()->route("admin.projects.index")->with("success", "Projet mis à jour avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route("admin.projects.index")->with("success", "Projet supprimé avec succès.");
    }

    // MVP - Basic assignment functionality within the show/edit views might be simpler.
    // Advanced assignment logic can be added later.
    // Example for assigning stagiaires (needs a form in the view)
    public function assignStagiaire(Request $request, Project $project, User $user)
    {
        // If stagiaires array is provided in request, sync all stagiaires
        if ($request->has('stagiaires')) {
            $stagiaireIds = User::whereIn("id", $request->input("stagiaires", []))
                               ->role("Stagiaire")
                               ->pluck("id");
            $project->stagiaires()->sync($stagiaireIds);
            return redirect()->route("admin.projects.show", $project)
                           ->with("success", "Stagiaires assignés avec succès.");
        }
        
        // Handle single user assignment (existing logic)
        if ($user) {
            // Remove user from current project
            if ($user->currentProject) {
                // Record completion of previous project
                DB::table('project_user_history')->insert([
                    'user_id' => $user->id,
                    'project_id' => $user->current_project_id,
                    'started_at' => $user->currentProject->pivot->created_at,
                    'completed_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        
            // Assign new project
            $user->current_project_id = $project->id;
            $user->save();
        
            // Record start of new project
            DB::table('project_user_history')->insert([
                'user_id' => $user->id,
                'project_id' => $project->id,
                'started_at' => now(),
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        
            return redirect()->back()->with('success', 'تم تعيين المتدرب بنجاح');
            if ($user->currentProject) {
                // Log completion of previous project
                DB::table('project_user_history')->insert([
                    'user_id' => $user->id,
                    'project_id' => $user->current_project_id,
                    'started_at' => $user->currentProject->pivot->created_at,
                    'completed_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        
            // Assign new project
            $user->current_project_id = $project->id;
            $user->save();
        
            // Log start of new project
            DB::table('project_user_history')->insert([
                'user_id' => $user->id,
                'project_id' => $project->id,
                'started_at' => now(),
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        
            return redirect()->back()->with('success', 'تم تعيين المتدرب بنجاح');
        
        }
        
        return redirect()->back()->with('error', 'No stagiaires or user specified');
    }
}

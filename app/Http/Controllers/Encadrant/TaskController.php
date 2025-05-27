<?php

namespace App\Http\Controllers\Encadrant;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    // Note: Tasks are managed within the context of a project for Encadrants.
    // The index method is handled by ProjectController@show.

    /**
     * Show the form for creating a new task within a project.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function create(Project $project)
    {
        Gate::authorize("update", $project); // Encadrant must be able to update the project to add tasks
        $stagiaires = $project->stagiaires()->pluck("name", "id"); // Get interns assigned to this project
        return view("encadrant.tasks.create", compact("project", "stagiaires"));
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:A faire,En cours,Terminé,Bloqué',
            'duration' => 'nullable|string|max:100',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        // Verify the project exists and the user is the encadrant
        $project = Project::findOrFail($validated['project_id']);
        Gate::authorize('update', $project);

        // If assignee is provided, verify they are a stagiaire in this project
        if (isset($validated['assignee_id'])) {
            if (!$project->stagiaires()->where('users.id', $validated['assignee_id'])->exists()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['assignee_id' => 'Le stagiaire sélectionné ne fait pas partie de ce projet.']);
            }
        }

        // Create the task
        $task = new Task($validated);
        $task->project_id = $project->id;
        $task->save();

        return redirect()
            ->route('encadrant.projects.show', $project->id)
            ->with('success', 'La tâche a été créée avec succès.');
    }

    /**
     * Display the specified task.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @return \Illuminate\View\View
     */
    public function show(Project $project, Task $task)
    {
        Gate::authorize("view", $project);
        // Ensure task belongs to the project
        if ($task->project_id !== $project->id) {
            abort(404);
        }
        $task->load("assignee");
        return view("encadrant.tasks.show", compact("project", "task"));
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @return \Illuminate\View\View
     */
    public function edit(Project $project, Task $task)
    {
        Gate::authorize("update", $project);
        if ($task->project_id !== $project->id) {
            abort(404);
        }
        $stagiaires = $project->stagiaires()->pluck("name", "id");
        return view("encadrant.tasks.edit", compact("project", "task", "stagiaires"));
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:A faire,En cours,Terminé,Bloqué',
            'duration' => 'nullable|string|max:100',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        // Verify the user is the encadrant of the task's project
        Gate::authorize('update', $task->project);

        // If assignee is being updated, verify they are a stagiaire in this project
        if (isset($validated['assignee_id'])) {
            if (!$task->project->stagiaires()->where('users.id', $validated['assignee_id'])->exists()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['assignee_id' => 'Le stagiaire sélectionné ne fait pas partie de ce projet.']);
            }
        }

        $task->update($validated);

        return redirect()
            ->route('encadrant.projects.show', $task->project_id)
            ->with('success', 'La tâche a été mise à jour avec succès.');
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        // Verify the user is the encadrant of the task's project
        Gate::authorize('update', $task->project);
        
        $projectId = $task->project_id;
        $task->delete();

        return redirect()
            ->route('encadrant.projects.show', $projectId)
            ->with('success', 'La tâche a été supprimée avec succès.');
    }
}

// Note: Requires views in resources/views/encadrant/tasks/
// Note: Requires ProjectPolicy for Gate authorization

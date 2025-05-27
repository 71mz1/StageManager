<?php

namespace App\Http\Controllers\Stagiaire;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks assigned to the stagiaire.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stagiaire = Auth::user();
        $tasks = $stagiaire->tasksAssigned()->with("project")->latest()->paginate(15);
        return view("stagiaire.tasks.index", compact("tasks"));
    }

    /**
     * Display the specified task.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\View\View
     */
    public function show(Task $task)
    {
        // Ensure the task is assigned to the logged-in stagiaire
        if ($task->assignee_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas assigné à cette tâche.");
        }
        $task->load("project.encadrant"); // Load project and its supervisor
        return view("stagiaire.tasks.show", compact("task"));
    }

    /**
     * Update the status of the specified task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Task $task)
    {
        // Ensure the task is assigned to the logged-in stagiaire
        if ($task->assignee_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas assigné à cette tâche.");
        }

        $request->validate([
            "status" => "required|string|in:A faire,En cours,Terminé,Bloqué", // Allow stagiaire to update status
        ]);

        $task->update(["status" => $request->status]);

        // TODO: Add notification for the encadrant about the status update

        // Redirect back to the task list or task details page
        return redirect()->route("stagiaire.tasks.show", $task)->with("success", "Statut de la tâche mis à jour.");
        // Or redirect to the project view: return redirect()->route("stagiaire.projects.show", $task->project_id)->with("success", "Statut de la tâche mis à jour.");
    }
}

// Note: Requires views in resources/views/stagiaire/tasks/


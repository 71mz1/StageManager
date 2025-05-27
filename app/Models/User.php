<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // Importer le trait HasRoles
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    // Ajouter le trait HasRoles
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type', // Ajouter le champ 'type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the color associated with the user's role.
     *
     * @return string
     */
    public function getRoleColor()
    {
        $role = $this->roles->first();
        
        if (!$role) {
            return 'secondary';
        }
        
        switch ($role->name) {
            case 'Admin':
                return 'danger';
            case 'Encadrant':
                return 'primary';
            case 'Stagiaire':
                return 'success';
            default:
                return 'secondary';
        }
    }

    /**
     * Get the projects that this user is supervising.
     */
    public function projectsSupervised()
    {
        return $this->hasMany(Project::class, 'encadrant_id');
    }

    /**
     * Get the projects that this user is a stagiaire in.
     */
    public function projectsAsStagiaire()
    {
        return $this->belongsToMany(Project::class, 'project_stagiaire', 'user_id', 'project_id')
            ->withTimestamps();
    }

    /**
     * Get the tasks assigned to the user (Stagiaire).
     */
    public function tasksAssigned(): HasMany
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }
    // إضافة علاقة المشروع الحالي
    public function currentProject(){
        return $this->belongsTo(Project::class, 'current_project_id');
    }

    // إضافة علاقة المشاريع السابقة
    public function previousProjects(){
        return $this->belongsToMany(Project::class, 'project_user_history')
            ->withPivot(['started_at', 'completed_at'])
            ->withTimestamps();
    }
    // In app/Models/User.php
public function completeCurrentProject()
{
    if (!$this->current_project_id) return;

    DB::table('project_user_history')
        ->where('user_id', $this->id)
        ->where('project_id', $this->current_project_id)
        ->whereNull('completed_at')
        ->update(['completed_at' => now()]);

    $this->current_project_id = null;
    $this->save();
}
}

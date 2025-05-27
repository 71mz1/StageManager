<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Scope;
use App\Models\Task;
use App\Models\User;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'priority',
        'encadrant_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the encadrant (supervisor) associated with the project.
     */
    public function encadrant(): BelongsTo
    {
        // Assuming 'encadrant_id' references the 'id' on the 'users' table
        return $this->belongsTo(User::class, 'encadrant_id');
    }

    /**
     * The stagiaires (interns) that belong to the project.
     */
    public function stagiaires(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_stagiaire')
            ->withTimestamps();
    }

    /**
     * The current stagiaires (interns) that belong to the project.
     */
    public function currentStagiaires(): HasMany
    {
        return $this->hasMany(User::class, 'current_project_id');
    }

    /**
     * The previous stagiaires (interns) that belong to the project.
     */
    public function previousStagiaires(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user_history')
            ->withPivot(['started_at', 'completed_at'])
            ->withTimestamps();
    }

    /**
     * Get the tasks for the project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Scope a query to only include active projects.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('end_date', '>=', now())
            ->orWhereNull('end_date');
    }

    /**
     * Scope a query to only include completed projects.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('end_date', '<', now());
    }
}

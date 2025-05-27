<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'duration',
        'priority',
        'status',
        'assignee_id',
    ];

    /**
     * Get the project that the task belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user (stagiaire) assigned to the task.
     */
    public function assignee(): BelongsTo
    {
        // Assuming 'assignee_id' references the 'id' on the 'users' table
        return $this->belongsTo(User::class, 'assignee_id');
    }

    /**
     * Get all messages for the task.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(TaskMessage::class)->with('user')->latest();
    }

    /**
     * Get all users involved in this task (assignee and message senders).
     */
    public function getParticipantsAttribute()
    {
        $participants = collect();
        
        // Add assignee if exists
        if ($this->assignee) {
            $participants->push($this->assignee);
        }
        
        // Add message senders
        $messageSenders = $this->messages->pluck('user')->unique('id');
        $participants = $participants->merge($messageSenders);
        
        return $participants->unique('id');
    }
}

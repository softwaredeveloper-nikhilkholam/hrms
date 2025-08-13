<?php

// app/Project.php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // The table associated with the model.
    protected $table = 'projects';

    // The attributes that are mass assignable.
    protected $fillable = [
        'name', 'description', 'start_date', 'end_date', 'completed_date',
        'requested_by', 'active', 'updated_by'
    ];

    // Get the modules for the project.
    public function modules()
    {
        return $this->hasMany(Module::class, 'project_id');
    }

    // Get the sub-modules for the project.
    public function subModules()
    {
        return $this->hasMany(SubModule::class, 'project_id');
    }

    // Get the credentials for the project.
    public function credentials()
    {
        return $this->hasMany(ProjectCredential::class, 'project_id');
    }

    // Get the tasks for the project.
    public function developerTasks()
    {
        return $this->hasMany(DeveloperTask::class, 'project_id');
    }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';

    protected $fillable = [
        'project_id', 'name', 'description', 'start_date', 'end_date',
        'completed_date', 'requested_by', 'active', 'updated_by'
    ];

    // Get the project that owns the module.
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Get the sub-modules for the module.
    public function subModules()
    {
        return $this->hasMany(SubModule::class, 'module_id');
    }

    // Get the tasks for the module.
    public function developerTasks()
    {
        return $this->hasMany(DeveloperTask::class, 'module_id');
    }
}
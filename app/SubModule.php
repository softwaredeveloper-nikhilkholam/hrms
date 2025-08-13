<?php

// app/SubModule.php
namespace App;

use Illuminate\Database\Eloquent\Model;

class SubModule extends Model
{
    protected $table = 'sub_modules';

    protected $fillable = [
        'project_id', 'module_id', 'name', 'description',
        'requested_by', 'active', 'updated_by'
    ];

    // Get the project that owns the sub-module.
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Get the module that owns the sub-module.
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    // Get the tasks for the sub-module.
    public function developerTasks()
    {
        return $this->hasMany(DeveloperTask::class, 'sub_module_id');
    }
}
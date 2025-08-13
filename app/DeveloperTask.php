<?php


// app/DeveloperTask.php
namespace App;

use Illuminate\Database\Eloquent\Model;

class DeveloperTask extends Model
{
    protected $table = 'developer_tasks';

    protected $fillable = [
        'project_id', 'module_id', 'sub_module_id', 'emp_id',
        'task', 'description', 'updated_by'
    ];

    // Get the project that owns the task.
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Get the module that owns the task.
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    // Get the sub-module that owns the task.
    public function subModule()
    {
        return $this->belongsTo(SubModule::class, 'sub_module_id');
    }

    // Get the developer that is assigned to the task.
    public function developer()
    {
        return $this->belongsTo(Developer::class, 'emp_id');
    }
}
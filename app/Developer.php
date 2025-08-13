<?php


// app/Developer.php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    protected $table = 'developers';

    protected $fillable = [
        'emp_id', 'skills', 'rating', 'per_day', 'other_details',
        'active', 'updated_by'
    ];

    // Get the tasks assigned to the developer.
    public function developerTasks()
    {
        return $this->hasMany(DeveloperTask::class, 'emp_id');
    }
}
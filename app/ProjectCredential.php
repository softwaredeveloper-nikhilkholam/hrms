<?php


// app/ProjectCredential.php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectCredential extends Model
{
    protected $table = 'project_credentials';

    protected $fillable = [
        'project_id', 'type', 'username', 'password',
        'path', 'port', 'attachmentFile', 'updated_by'
    ];

    // Get the project that owns the credential.
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
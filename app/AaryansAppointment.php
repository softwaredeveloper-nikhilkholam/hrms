<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AaryansAppointment extends Model
{
    protected $fillable = [
        'to',
        'priority',
        'raisedById',
        'requestedDate',
        'agenda',
        'participants',
        'status',
        'mom',
        'updatedBy'
        // 'user_id', // Uncomment if you add user_id to the table
    ];

    // Cast 'participants' to array so Laravel handles JSON encoding/decoding automatically
    protected $casts = [
        'participants' => 'array',
    ];

    // If you added user_id, you might have a relationship here
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'site_id',
        'conversation_id',
        'visitor_id',
        'subject',
        'description',
        'status',
        'priority',
    ];
}

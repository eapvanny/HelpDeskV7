<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'status_id',
        'department_id',
        'priority_id',
        'description',
        'agent_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subject',
        'status_id',
        'department_id',
        'priority_id',
        'description',
        'agent_id',
        'user_id',
        'employee_name',
        'id_card',
        'request_status',
        'receiver',
        'photo',
        'date'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function messages()
{
    return $this->hasMany(ChatMessage::class, 'ticket_id');
}


}

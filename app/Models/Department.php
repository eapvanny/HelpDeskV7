<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
      'code',
      'name',
      'name_in_latin',
      'abbreviation'
    ];

    public function ticket()
    {
      return $this->hasMany(Ticket::class);
    }
    public function user()
    {
      return $this->hasMany(User::class);
    }
    
}

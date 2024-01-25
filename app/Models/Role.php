<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traints\UsesUuid; 

class Role extends Model
{
    use HasFactory, UsesUuid;
    protected $fillable=['name'];

    public function users()
    {
        return $this->hasMany(user::class, 'role_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traints\UsesUuid; 

class Campaign extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = ['title','required','address','description','colected','image'];

    protected $primaryKey = 'id';
}

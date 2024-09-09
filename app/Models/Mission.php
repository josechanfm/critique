<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    public function stages(){
        return $this->hasMany(Stage::class)->with('tasks');
    }
    public function members(){
        return $this->belongsToMany(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable=['title', 'content', 'user_id', 'stage_id'];

    
    public function stage(){
        return $this->belongsTo(Stage::class);
    }
    public function parent(){
        return $this->belongsTo(static::class,'blog_id')->orderBy('created_at');
    }
    public function children(){
        return $this->hasMany(static::class,'blog_id')->orderBy('created_at');
    }
}

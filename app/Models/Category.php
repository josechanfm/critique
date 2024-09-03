<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable=['title_zh','title_en','title_pt'];

    public function items(){
        return $this->hasMany(CategoryItem::class)->with('accounts');
    }
}
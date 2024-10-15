<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;

class Media extends Model implements HasMedia
{

    use InteractsWithMedia;

    protected $fillable = ['title', 'description', 'link'];
    protected $with=['media'];


    public function children()
    {
        return $this->hasMany(Media::class, 'model_id');
    }
}

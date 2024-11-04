<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Stage extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $fillable=['mission_id','code','title','remark','description','content'];
    protected $casts=['content'=>'json'];
    protected $with=['media'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('files');
    }
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }
    
    public function mission(){
        return $this->belongsTo(Mission::class);
    }
    public function tasks(){
        return $this->hasMany(Task::class);
    }


    public function blogs(){
        return $this->hasMany(Blog::class);
    }
}

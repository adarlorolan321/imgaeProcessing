<?php

namespace App\Models\Resolution;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Resolution extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        "date", "term", "description", "title", "photo", "status", "resolution_no"
    ];

    protected $appends = [
        'file_url',
        
    ];

    // protected $hidden = [
    //     'media'
    // ];

    public function getFileUrlAttribute()
    {
      $media = $this->getMedia('resolutions');
    
    if ($media->isEmpty()) {
        return null;
    }
    
    $urls = $media->map(function ($item) {
        return $item->getUrl();
    });
    
    return $urls;
    }

    

    
}

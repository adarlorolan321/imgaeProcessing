<?php

namespace App\Models\Ordinance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ordinance extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        "status","title","description","date","term","ordinance_no","photo",
    ];
    protected $appends = [
        'file_url',
        
    ];
    public function getFileUrlAttribute()
    {
      $media = $this->getMedia('ordinances');
    
    if ($media->isEmpty()) {
        return null;
    }
    
    $urls = $media->map(function ($item) {
        return $item->getUrl();
    });
    
    return $urls;
    }

}

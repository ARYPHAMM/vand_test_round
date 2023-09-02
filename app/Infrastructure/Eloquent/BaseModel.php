<?php

namespace App\Infrastructure\Eloquent;

use App\Infrastructure\Eloquent\Language\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class BaseModel extends Model implements HasMedia
{
    use HasMediaTrait;
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
            ->singleFile();
        $this
            ->addMediaCollection('image')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(220)
            ->nonQueued()
            ->quality(40)
            ->sharpen(10);
        $this->addMediaConversion('medium')
            ->width(368)
            ->height(232)
            ->nonQueued()
            ->quality(40)
            ->sharpen(10);
    }
    public function getCreatedAtAttribute($date)
    {
        return $date;
    }
    public function getUpdatedAtAttribute($date)
    {
        return $date;
    }
}

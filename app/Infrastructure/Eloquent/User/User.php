<?php

namespace App\Infrastructure\Eloquent\User;

use App\Infrastructure\Eloquent\Product\Product;
use App\Infrastructure\Eloquent\Store\Store;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, HasMedia, MustVerifyEmail
{
    use Notifiable;
    use HasMediaTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
        'phone_number',
        'email_verified_at',
        'id'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_number' => 'string'
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function stories()
    {
        return $this->hasMany(Store::class);
    }
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
            ->singleFile();
        $this
            ->addMediaCollection('front_of_card')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
            ->singleFile();
        $this
            ->addMediaCollection('back_of_card')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
            ->singleFile();
        $this
            ->addMediaCollection('business_license')
            ->singleFile();
    }
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(config('image.thumb.width'))
            ->height(config('image.thumb.height'))
            ->nonQueued()
            ->sharpen(10);
        $this->addMediaConversion('medium')
            ->width(config('image.medium.width'))
            ->height(config('image.medium.height'))
            ->nonQueued()
            ->sharpen(10);
    }
    public function getFileByKey($key)
    {
    }
}

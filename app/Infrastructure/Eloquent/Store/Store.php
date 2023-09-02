<?php

namespace App\Infrastructure\Eloquent\Store;

use App\Infrastructure\Eloquent\BaseModel;
use App\Infrastructure\Eloquent\Product\Product;
use App\Infrastructure\Eloquent\User\User;

class Store extends BaseModel
{
  protected $table = "stories";
  protected $primaryKey = 'id';
  protected $guarded = [];
  public function products()
  {
    return $this->hasMany(Product::class, 'store_id', 'id');
  }
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
  public function scopeSearch($query)
  {
    $search = @request()->search;
    $data_filter = request()->only('user_id');
    foreach ($data_filter as $key => $value) {
      if (!is_null($value))
        $query->where($key, $value);
    }
    if(!is_null($search))
    {
       $query->where('name', 'like', "%{$search}%");
       $query->orWhere('address', 'like', "%{$search}%");
    }
  
  }
  
}

<?php

namespace App\Infrastructure\Eloquent\Product;

use App\Infrastructure\Eloquent\BaseModel;
use App\Infrastructure\Eloquent\Store\Store;

class Product extends BaseModel
{
  protected $table = "products";
  protected $primaryKey = 'id';
  protected $guarded = [];
  public function store()
  {
    return $this->belongsTo(Store::class, 'store_id', 'id');
  }
  public function scopeSearch($query)
  {
    $search = @request()->search;
    if (request()->has('user_id') && !is_null(request()->user_id)) {
      $query->byUserId();
    }
    $data_filter = request()->only('store_id','is_active');
    foreach ($data_filter as $key => $value) {
      if (!is_null($value))
        $query->where($key, $value);
    }
    if (!is_null($search))
      $query->where('name', 'like', "%{$search}%");
  }
  public function scopeByUserId($query)
  {
    $user_id = @request()->user_id;
    $query->whereHas('store', function ($query1) use ($user_id) {
      $query1->where('user_id', $user_id);
    });
  }
}

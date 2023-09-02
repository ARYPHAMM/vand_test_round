<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductPaginate;
use App\Http\Resources\Product\ProductResource;
use App\Infrastructure\Eloquent\Product\Product;
use App\Infrastructure\Eloquent\Store\Store;
use App\Infrastructure\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
  protected $repo;
  public function __construct(ProductRepositoryInterface $repo)
  {
    $this->repo = $repo;
  }
  public function indexOfMe(Request $request)
  {
    $user = auth('api')->user();
    $request->merge(['user_id' => $user->id]);
    $items = $this->repo->findAllPagi($request, 'created_at', 'desc');
    return new ProductPaginate($items);
  }
  public function index(Request $request)
  {
    $request->merge(['is_active' => 1]);
    $items = $this->repo->findAllPagi($request, 'created_at', 'desc');
    return new ProductPaginate($items);
  }
  public function detail($id)
  {
    $result = $this->repo->find($id);
    if ($result)
      return new ProductResource($result);
    return apiError('Data not found');
  }
  public function create(Request $request)
  {
    $user = auth('api')->user();
    $validator = $this->repo->canValidate([
      'name' => 'required',
      'price' => ['nullable', 'integer', 'min:1'],
      'description' => 'required',
      'is_active' => 'required',
      'store_id' => [
        'required', 'exists:stories,id',
        function ($attribute, $value, $fail) use ($user) {
          $Complaint = Store::where('id', request()->store_id)->where('user_id', '!=', $user->id)->count();
          if ($Complaint >= 1)
            $fail('You do not own this store');
        }
      ]
    ], []);
    if ($validator)
      return apiError($validator->errors()->first(), $validator->errors(), 422);
    $data = $request->only('name', 'price', 'description', 'is_active', 'store_id');
    DB::beginTransaction();
    try {
      $item = new Product();
      $this->repo->store($item, $data);
      $item->save();
      if ($request->has('avatar') && !is_null($request->avatar))
        if (!updateFileByKey($item, 'avatar', $request->avatar))
          return apiError("Lỗi hình ảnh");
      DB::commit();
      return apiOk(new ProductResource($item));
    } catch (\Throwable $th) {
      //throw $th;
      DB::rollBack();
      return apiError("Error");
    }
  }
  public function update($id, Request $request)
  {
    $user = auth('api')->user();
    $validator = $this->repo->canValidate([
      'name' => 'required',
      'price' => ['nullable', 'integer', 'min:1'],
      'description' => 'required',
      'is_active' => 'required',
      'store_id' => [
        'required', 'exists:stories,id',
        function ($attribute, $value, $fail) use ($user) {
          $Complaint = Store::where('id', request()->store_id)->where('user_id', '!=', $user->id)->count();
          if ($Complaint >= 1)
            $fail('You do not own this store');
        }
      ]
    ], []);
    if ($validator)
      return apiError($validator->errors()->first(), $validator->errors(), 422);
    request()->merge(['user_id' => $user->id]);
    $data = $request->only('name', 'price', 'description', 'is_active', 'store_id');
    $item = Product::query()->byUserId()->where('id', $id)->first();
    
    if ($item) {
      DB::beginTransaction();
      try {
        $this->repo->store($item, $data);
        $item->save();
        if ($request->has('avatar') && !is_null($request->avatar))
          if (!updateFileByKey($item, 'avatar', $request->avatar))
            return apiError("Lỗi hình ảnh");
        DB::commit();
        return apiOk(new ProductResource($item));
      } catch (\Throwable $th) {
        //throw $th;
        DB::rollBack();
        return apiError("Error");
      }
    }else{
      return apiError('Data not nound');
    }
  }
  public function delete($id)
  {
    $user = auth('api')->user();
    request()->merge(['user_id' => $user->id]);
    $item = Product::query()->byUserId()->where('id', $id)->first();
    if ($item) {
      $this->repo->remove($item);
      return apiOk(true);
    } else {
      return apiError('Data not found');
    }
  }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Store\StorePaginate;
use App\Http\Resources\Store\StoreResource;
use App\Infrastructure\Eloquent\Store\Store;
use App\Infrastructure\Repositories\Store\StoreRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
  protected $repo;
  public function __construct(StoreRepositoryInterface $repo)
  {
    $this->repo = $repo;
  }
  public function indexOfMe(Request $request)
  {
    $user = auth('api')->user();
    $request->merge(['user_id' => $user->id]);
    $items = $this->repo->findAllPagi($request, 'created_at', 'desc');
    return new StorePaginate($items);
  }
  public function index(Request $request)
  {
    $items = $this->repo->findAllPagi($request, 'created_at', 'desc');
    return new StorePaginate($items);
  }
  public function detail($id)
  {
    $result = $this->repo->find($id);
    if ($result)
      return new StoreResource($result);
    return apiError('Data not found');
  }
  public function create(Request $request)
  {
    $validator = $this->repo->canValidate([
      'name' => 'required'
    ], []);
    if ($validator)
      return apiError($validator->errors()->first(), $validator->errors(), 422);
    $user = auth('api')->user();
    $data = $request->only('name', 'address', 'description');
    $data = array_merge($data,['user_id' => $user->id]);
    DB::beginTransaction();
    try {
      $item = new Store();
      $this->repo->store($item, $data);
      $item->save();
      if ($request->has('avatar') && !is_null($request->avatar))
        if (!updateFileByKey($item, 'avatar', $request->avatar))
          return apiError("Lỗi hình ảnh");
      DB::commit();
      return apiOk(new StoreResource($item));
    } catch (\Throwable $th) {
      //throw $th;
      DB::rollBack();
    }
  }
  public function update($id, Request $request)
  {
    $validator = $this->repo->canValidate([
      'name' => 'required'
    ], []);
    if ($validator)
      return apiError($validator->errors()->first(), $validator->errors(), 422);
    $user = auth('api')->user();
    $data = $request->only('name', 'address', 'description');
    DB::beginTransaction();
    try {
      $item = $user->stories()->where('id', $id)->first();
      if ($item) {
        $this->repo->store($item, $data);
        $item->save();
        if ($request->has('avatar') && !is_null($request->avatar))
          if (!updateFileByKey($item, 'avatar', $request->avatar))
            return apiError("Lỗi hình ảnh");
        DB::commit();
        return apiOk(new StoreResource($item));
      } else {
        return apiError('Data not found');
      }
    } catch (\Throwable $th) {
      //throw $th;
      DB::rollBack();
    }
  }
  public function delete($id)
  {
    $user = auth('api')->user();
    $item = $user->stories()->where('id', $id)->first();
    if ($item) {
      $this->repo->remove($item);
      return apiOk(true);
    } else {
      return apiError('Data not found');
    }
  }
}

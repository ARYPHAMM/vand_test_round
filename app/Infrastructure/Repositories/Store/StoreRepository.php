<?php

namespace App\Infrastructure\Repositories\Store;

use App\Infrastructure\Eloquent\Store\Store;
use App\Infrastructure\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class StoreRepository extends BaseRepository implements StoreRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Store::class;
    }
    public function store(Store $item, array $data): void
    {
        DB::transaction(function () use ($item, $data) {
            $item->fill($data);
        });
    }
    public function remove(Store $item)
    {
        deleteSingleFile($item, 'avatar');
        $item->delete();
    }
}

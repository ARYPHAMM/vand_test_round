<?php

namespace App\Infrastructure\Repositories\Product;

use App\Infrastructure\Eloquent\Product\Product;
use App\Infrastructure\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Product::class;
    }
    public function store(Product $item, array $data): void
    {
        DB::transaction(function () use ($item, $data) {
            $item->fill($data);
        });
    }
    public function remove(Product $item): void
    {
        deleteSingleFile($item, 'avatar');
        $item->delete();
    }
}

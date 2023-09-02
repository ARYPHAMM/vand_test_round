<?php

namespace App\Infrastructure\Repositories\Product;

use App\Infrastructure\Eloquent\Product\Product;
use App\Infrastructure\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface ProductRepositoryInterface extends RepositoryInterface
{

    public function store(Product $item, array $data): void;
    public function remove(Product $item): void;
   
}

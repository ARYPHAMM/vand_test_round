<?php

namespace App\Infrastructure\Repositories\Store;

use App\Infrastructure\Eloquent\Store\Store;
use App\Infrastructure\Repositories\RepositoryInterface;

interface StoreRepositoryInterface extends RepositoryInterface
{
    public function store(Store $item, array $data): void;
    public function remove(Store $store);
}

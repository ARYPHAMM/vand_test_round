<?php

namespace App\Infrastructure\Repositories\User;


use App\Infrastructure\Eloquent\User\User;
use App\Infrastructure\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;


class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return User::class;
    }
    public function store(User $item, array $data): void
    {
        DB::transaction(function () use ($item, $data) {
            $item->fill($data);
        });
    }
}

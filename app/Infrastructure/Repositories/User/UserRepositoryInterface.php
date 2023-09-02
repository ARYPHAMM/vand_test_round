<?php

namespace App\Infrastructure\Repositories\User;

use App\Infrastructure\Eloquent\User\User;
use App\Infrastructure\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{

  public function store(User $item, array $data): void;
}

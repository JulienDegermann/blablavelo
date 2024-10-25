<?php

namespace App\Domain\User\Contrat;

use App\Domain\User\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findOneBy(array $criteria, ?array $orderBy = null);
}

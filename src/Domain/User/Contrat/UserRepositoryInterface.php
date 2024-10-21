<?php

namespace App\Domain\User\Contrat;

use App\Domain\User\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

}
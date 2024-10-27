<?php

namespace App\Domain\User\Contrat;

use App\Domain\User\User;

interface GetUserFromTokenInterface
{
    public function __invoke(string $token): User;
}
<?php

namespace App\Domain\User\Contrat;

use App\Domain\User\User;

interface CheckUserIsVerifiedInterface
{
    public function __invoke(User $user): bool|string;
}
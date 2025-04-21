<?php

namespace App\Domain\Ride\Contrat;

use App\Domain\User\User;

interface FindMyRidesInterface
{
    public function __invoke(User $user): array;
}
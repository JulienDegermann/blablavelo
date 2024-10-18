<?php

namespace App\Domain\Ride\Contrat;

use App\Domain\Ride\UseCase\FindRides\FindRidesInput;
use App\Domain\User\User;

interface FindRidesInterface
{
    public function __invoke(FindRidesInput $input, User $user): array;
}
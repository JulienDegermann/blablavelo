<?php

namespace App\Domain\Ride\Contrat;

use App\Domain\Ride\Ride;
use App\Domain\Ride\UseCase\RemoveRide\RemoveRideInput;

interface RemoveRideInterface
{
    public function __invoke(RemoveRideInput $input): Ride;
}
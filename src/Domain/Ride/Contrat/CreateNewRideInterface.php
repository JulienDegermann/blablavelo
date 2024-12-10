<?php

namespace App\Domain\Ride\Contrat;

use App\Domain\Ride\Ride;
use App\Domain\Ride\UseCase\CreateRide\NewRideInput;
use App\Domain\Ride\UseCase\CreateRide\RideOutput;

interface CreateNewRideInterface{
    public function __invoke(NewRideInput $input): Ride;
}
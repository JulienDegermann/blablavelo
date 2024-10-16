<?php

namespace App\Domain\Ride\Contrat;

use App\Domain\Ride\UseCase\NewRideInput;
use App\Domain\Ride\UseCase\RideOutput;

interface CreateNewRideInterface{
    public function __invoke(NewRideInput $input): RideOutput;
}
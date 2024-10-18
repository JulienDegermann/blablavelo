<?php

namespace App\Domain\Ride\Contrat;

use App\Domain\Ride\Ride;

interface RideDetailsInterface
{
    public function __invoke(int $id): Ride;
}
<?php

namespace App\Domain\Ride\UseCase\RideDetails;

use App\Domain\Ride\Contrat\RideDetailsInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
use App\Domain\Ride\Ride;

final class RideDetails implements RideDetailsInterface
{
    public function __construct(
        private RideRepositoryInterface $rideRepo,
    )
    {
    }

    public function __invoke(int $id): Ride
    {
        $ride = $this->rideRepo->find($id);

        return $ride;
    }
}
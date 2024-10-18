<?php

namespace App\Domain\Ride\UseCase\CreateRide;

use App\Domain\Ride\Contrat\CreateNewRideInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
use App\Domain\Ride\Ride;

final class CreateNewRide implements CreateNewRideInterface
{
    public function __construct(private readonly RideRepositoryInterface $rideRepo)
    {
    }

    public function __invoke(NewRideInput $input): Ride
    {
        $ride = new Ride(
            $input->getCreator(),
            $input->getTitle(),
            $input->getDescription(),
            $input->getStartDate(),
            $input->getMaxParticipants(),
            $input->getAscent(),
            $input->getDistance(),
            $input->getAverageSpeed(),
            $input->getPractice(),
            $input->getMind(),
            $input->getStartCity()
        );

        $this->rideRepo->save($ride);

        return $ride;
    }
}
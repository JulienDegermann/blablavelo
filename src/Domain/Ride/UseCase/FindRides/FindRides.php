<?php

namespace App\Domain\Ride\UseCase\FindRides;

use App\Domain\Ride\Contrat\FindRidesInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
use App\Domain\User\User;

final class FindRides implements FindRidesInterface
{
    public function __construct(
        private readonly RideRepositoryInterface $rideRepo,
    )
    {
    }

    public function __invoke(FindRidesInput $input, User $user): array
    {
        $rides = ($this->rideRepo)->rideFilter(
            $input->getPractice(),
            $input->getMind(),
            $input->getDepartment(),
            $input->getStartDate(),
            $input->getMinDistance(),
            $input->getMaxDistance(),
            $input->getMinParticipants(),
            $input->getMaxParticipants(),
            $input->getMinAverageSpeed(),
            $input->getMaxAverageSpeed(),
            $input->getMinAscent(),
            $input->getMaxAscent()
        );

        $nextRides = array_filter($rides, function ($ride) use ($user) {
            return $ride->getCreator() !== $user;
        });

        return $nextRides;
    }
}
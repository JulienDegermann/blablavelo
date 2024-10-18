<?php

namespace App\Domain\Ride\UseCase\FindMyRides;

use App\Domain\Ride\Contrat\FindMyRidesInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
use App\Domain\User\User;
use App\Infrastructure\Repository\UserRepository;
use DateTimeImmutable;

final class FindMyRides implements FindMyRidesInterface
{
    public function __construct(private readonly RideRepositoryInterface $rideRepo)
    {
    }

    public function __invoke(User $user): array
    {
        $myRides = [];

        $rides = $this->rideRepo->myRides($user);

        $myNextRides = array_filter($rides, function ($ride) use ($user) {
            return $ride->getParticipants()->contains($user) && $ride->getStartDate() > new DateTimeImmutable('now');
        });
        $myRides['myNextRides'] = $myNextRides;

        $myCreatedRides = array_filter($rides, function ($ride) use ($user) {
            return $ride->getCreator() === $user;
        });
        $myRides['myCreatedRides'] = $myCreatedRides;

        $myRides['allMyRides'] = $rides;

        return $myRides;
    }
}

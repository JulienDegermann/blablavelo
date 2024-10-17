<?php

namespace App\Domain\Ride\UseCase\RemoveRide;

use App\Domain\Ride\Contrat\RemovedRideNotifierServiceInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;

class RemoveRide
{
    public function __construct(
        private readonly RideRepositoryInterface $rideRepository,
        private readonly RemovedRideNotifierServiceInterface $notify
    )
    {
    }

    public function __invoke($input): void
    {
        $ride = $this->rideRepository->findById($input->getId());

        foreach ($ride->getParticipants() as $participant) {
            if ($participant === $ride->getCreator()) {
                return;
            }
            $this->notify($participant, $ride);
        }

        $this->rideRepository->remove($ride);
    }
}
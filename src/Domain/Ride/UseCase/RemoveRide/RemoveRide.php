<?php

namespace App\Domain\Ride\UseCase\RemoveRide;

use App\Domain\Ride\Contrat\RemovedRideNotifierServiceInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
use http\Exception\InvalidArgumentException;
use Symfony\Component\Mime\Email;
use App\Domain\Ride\Contrat\RemoveRideInterface;

class RemoveRide implements RemoveRideInterface
{
    public function __construct(
        private readonly RideRepositoryInterface             $rideRepository,
        private readonly RemovedRideNotifierServiceInterface $notifier
    )
    {
    }

    public function __invoke(RemoveRideInput $input): void
    {
        $ride = $this->rideRepository->find($input->getId());

        if ($input->getUser() !== $ride->getCreator()) {
            throw new InvalidArgumentException("Tu n'as pas créé cette sortie.");
        }

        foreach ($ride->getParticipants() as $participant) {
            if ($participant !== $ride->getCreator()) {
                ($this->notifier)($participant, $ride);
            }
        }

        $this->rideRepository->remove($ride);
    }
}
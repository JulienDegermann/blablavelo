<?php

namespace App\Domain\Ride\UseCase\RemoveRide;

use App\Domain\Ride\Contrat\RemovedRideNotifierServiceInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
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

    public function __invoke(RemoveRideInput $input): Email
    {
        $email = new Email();
        $ride = $this->rideRepository->find($input->getId());

        foreach ($ride->getParticipants() as $participant) {
            if ($participant !== $ride->getCreator()) {
                ($this->notifier)($participant, $ride);
            }
        }

        $this->rideRepository->remove($ride);

        return $email;
    }
}
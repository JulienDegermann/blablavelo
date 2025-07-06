<?php

namespace App\Domain\Ride\UseCase\RemoveRide;

use InvalidArgumentException;
use Symfony\Component\Mime\Email;


use App\Domain\Ride\Contrat\RemoveRideInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
use App\Domain\Ride\Contrat\RemovedRideNotifierServiceInterface;
use App\Infrastructure\Service\Messages\EmailMessages\RemoveRideEmailMessage\RemoveRidePublisherInterface;

class RemoveRide implements RemoveRideInterface
{
    public function __construct(
        private readonly RideRepositoryInterface             $rideRepository,
        private readonly RemoveRidePublisherInterface $notifier
    )
    {
    }

    public function __invoke(RemoveRideInput $input): void
    {
        $ride = $this->rideRepository->find($input->getId());

        if ($input->getUser() !== $ride->getCreator()) {
            throw new InvalidArgumentException("Tu n'as pas créé cette sortie.");
        }

        ($this->notifier)($ride->getId());

        $this->rideRepository->remove($ride);
    }
}
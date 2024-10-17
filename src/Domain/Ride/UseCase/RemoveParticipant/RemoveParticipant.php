<?php

namespace App\Domain\Ride\UseCase\RemoveParticipant;

use App\Infrastructure\Repository\RideRepository;
use http\Exception\InvalidArgumentException;
use Liip\ImagineBundle\Exception\Config\Filter\NotFoundException;

final class RemoveParticipant
{
    public function __construct(
        private readonly RideRepository                    $rideRepo,
        private readonly RemovedParticipantNotifierService $notifier
    )
    {
    }

    public function __invoke(RemoveParticipantInput $input): void
    {
        $ride = $this->rideRepo->find($input->getRideId());

        if (!$ride) {
            throw new NotFoundException("Oups! Aucune sortie n'a été trouvée.");
        }

        if (!($input->getParticipant() instanceof User)) {
            throw new InvalidArgumentException("Oups! L'utilisateur n'est pas correct.");
        }

        if (!($ride->getParticipants()->contains($input->getParticipant()))) {
            throw new InvalidArgumentException("Oups! L'utilisateur n'est pas trouvé.");
        }

        // update ride
        $ride->removeParticipant($input->getParticipant());
        $this->rideRepo->save($ride);

        // notify ride's creator
        $this->notifier->notify($ride, $input->getParticipant());
    }
}
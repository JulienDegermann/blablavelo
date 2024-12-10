<?php

namespace App\Domain\Ride\UseCase\RemoveParticipant;

use App\Domain\Ride\Contrat\RemovedParticipantNotifierServiceInterface;
use App\Domain\Ride\Contrat\RemoveParticipantInterface;
use App\Domain\Ride\Ride;
use App\Domain\User\User;
use App\Infrastructure\Repository\RideRepository;
use http\Exception\InvalidArgumentException;
use Liip\ImagineBundle\Exception\Config\Filter\NotFoundException;

final class RemoveParticipant implements RemoveParticipantInterface
{
    public function __construct(
        private readonly RideRepository                             $rideRepo,
        private readonly RemovedParticipantNotifierServiceInterface $notifier
    )
    {
    }

    public function __invoke(RemoveParticipantInput $input): Ride
    {
        $ride = $this->rideRepo->find($input->getRideId());

        if (!$ride) {
            throw new NotFoundException("Oups! La sortie n'est pas trouvée.");
        }

        if (!($input->getParticipant() instanceof User)) {
            throw new InvalidArgumentException("Oups! L'utilisateur n'est pas trouvé.");
        }

        if (!($ride->getParticipants()->contains($input->getParticipant()))) {
            throw new InvalidArgumentException("Oups! L'utilisateur n'est pas trouvé.");
        }

        // update ride
        $ride->removeParticipant($input->getParticipant());
        $this->rideRepo->save($ride);

        // notify ride's creator
        ($this->notifier)($ride, $input->getParticipant());

        return $ride;
    }
}
<?php

namespace App\Domain\Ride\UseCase\RemoveParticipant;

use App\Domain\Ride\Ride;
use App\Domain\User\User;
use InvalidArgumentException;
use App\Infrastructure\Repository\RideRepository;
use App\Domain\Ride\Contrat\RemoveParticipantInterface;
use Liip\ImagineBundle\Exception\Config\Filter\NotFoundException;
use App\Infrastructure\Service\Messages\EmailMessages\RemoveParticipantEmailMessage\RemoveParticipantPublisherInterface;

final class RemoveParticipant implements RemoveParticipantInterface
{
    public function __construct(
        private readonly RideRepository                             $rideRepo,
        private readonly RemoveParticipantPublisherInterface $notifier
    ) {}

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

        // notify ride's creator
        ($this->notifier)($ride->getId(), ['participant_id' => $input->getParticipant()->getId()]);


        // update ride
        $ride->removeParticipant($input->getParticipant());
        $this->rideRepo->save($ride);


        return $ride;
    }
}

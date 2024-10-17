<?php

namespace App\Domain\Ride\UseCase\AddParticipant;

use App\Domain\Ride\Contrat\AddParticipantInterface;
use App\Domain\Ride\Contrat\AddParticipantNotifierServiceInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
use App\Domain\Ride\Ride;
use App\Domain\User\User;
use http\Exception\InvalidArgumentException;
use Liip\ImagineBundle\Exception\Config\Filter\NotFoundException;

final class AddParticipant implements AddParticipantInterface
{
    public function __construct(
        private readonly RideRepositoryInterface                $rideRepo,
        private readonly AddParticipantNotifierServiceInterface $notifier
    )
    {
    }

    public function __invoke(AddParticipantInput $input): Ride
    {
        $ride = $this->rideRepo->find($input->getRideId());

        if (!$ride) {
            throw new NotFoundException("Oups! La sortie n'existe pas!");
        }

        if (!($input->getParticipant() instanceof User)) {
            throw new NotFoundException("L'utilisateur n'existe pas!");
        }

        if (($ride->getParticipants()->contains($input->getParticipant()))) {
            throw new InvalidArgumentException("L'utilisateur est déjà inscrit à cette sortie !");
        }

        $ride->addParticipant($input->getParticipant());
        $this->rideRepo->save($ride);

        ($this->notifier)($ride, $input->getParticipant());

        return $ride;
    }
}
<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\RemoveParticipantEmailMessage;

use App\Domain\Ride\Ride;
use App\Domain\User\User;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
use App\Domain\User\Contrat\UserRepositoryInterface;
use App\Domain\Ride\Contrat\RemovedParticipantNotifierServiceInterface;
use App\Infrastructure\Service\Messages\EmailMessages\RemoveParticipantEmailMessage\RemoveParticipantConsumerInterface;


final class RemoveParticipantConsumer implements RemoveParticipantConsumerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly RideRepositoryInterface $rideRepo,
        private readonly UserRepositoryInterface $userRepo,
        private readonly RemovedParticipantNotifierServiceInterface $notifier,
        private readonly EntityManagerInterface $em
    ) {}

    /**
     * forwards datas to RemoveParticipantNotifierService
     * @param int $ride_id - id of de Ride
     * @param int $participant_id - id of de User who canceled inscription
     */
    public function __invoke(
        int $ride_id,
        int $participant_id,
    ): void {
        $this->logger->info('========= remove participant ========');
        $this->em->clear();

        $ride = ($this->rideRepo)->find($ride_id) ?? null;
        $participant = ($this->userRepo)->find($participant_id) ?? null;


        if (!$ride instanceof Ride) {
            throw new InvalidArgumentException('Ride not found');
        }

        if (!$participant instanceof User) {
            throw new InvalidArgumentException('Participant not found');
        }

        ($this->notifier)($ride, $participant);
    }
}

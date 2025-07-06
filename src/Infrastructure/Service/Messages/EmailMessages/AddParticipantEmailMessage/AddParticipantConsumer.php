<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\AddParticipantEmailMessage;

use App\Domain\Ride\Ride;
use App\Domain\User\User;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
use App\Domain\User\Contrat\UserRepositoryInterface;
use App\Domain\Ride\Contrat\AddParticipantNotifierServiceInterface;
use App\Infrastructure\Service\Messages\EmailMessages\AddParticipantEmailMessage\AddParticipantConsumerInterface;
use Doctrine\ORM\EntityManagerInterface;


/**
 * Class AddParticipantConsumer - forwards datas to notifier service
 */
final class AddParticipantConsumer implements AddParticipantConsumerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly RideRepositoryInterface $rideRepo,
        private readonly UserRepositoryInterface $userRepo,
        private readonly AddParticipantNotifierServiceInterface $notifier,
        private readonly EntityManagerInterface $em
    ) {}

    /**
     * Forwards datas from consumer to notifier service
     * @param int $ride_id - id of the Ride
     * @param int $participant_id - id of the User who canceled inscription
     * @return void
     */
    public function __invoke(
        int $ride_id,
        int $participant_id
    ): void {
        $this->logger->info('============== add participant ==============');

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

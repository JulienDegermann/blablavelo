<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\RemoveRideEmailMessage;

use App\Domain\Ride\Ride;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
use App\Domain\Ride\Contrat\RemovedRideNotifierServiceInterface;


final class RemoveRideConsumer implements RemoveRideConsumerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly RideRepositoryInterface $repo,
        private readonly RemovedRideNotifierServiceInterface $notifier,
        private readonly EntityManagerInterface $em

    ) {}


    public function __invoke(int $ride_id): void
    {
        $this->logger->info('========= remove ride ==========');
        $this->em->clear();

        $ride = ($this->repo)->find($ride_id) ?? null;

        if (!$ride instanceof Ride) {
            throw new InvalidArgumentException('Ride not found');
        }

        foreach ($ride->getParticipants() as $participant) {
            ($this->notifier)($participant, $ride);
        }
    }
}

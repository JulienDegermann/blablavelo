<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\EmailVerificationEmailMessage;

use App\Domain\User\User;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Service\NotifierService\UseCase\EmailVerificationNotifierService;

/**
 * Class EmailVerificationConsumer - service that forwards datas to EmailVerificationNotifierService
 */
final class EmailVerificationConsumer implements EmailVerificationConsumerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly EmailVerificationNotifierService $notifier,
        private readonly UserRepository $repo,
        private readonly EntityManagerInterface $em
    ) {}

    /**
     * forwards proper datas to notifier service
     * @param int $user_id - id of the user to verify email
     * @return void
     */
    public function __invoke(int $user_id): void
    {
        $this->logger->info('=========== verification email BONJORU ==============');
        $this->logger->info('=========== verification email 15 SECONDES ==============');
        $this->em->clear();
        $this->logger->info('user id consumer : ' . $user_id);
        $user = ($this->repo)->find($user_id) ?? null;
        if (!$user) {
            $this->logger->info('======= no user ======');
        }
        $this->em->refresh($user);
        $this->logger->info($user->getEmail());

        if (!($user instanceof User)) {
            throw new InvalidArgumentException('User not found');
        }
        ($this->notifier)($user);
    }
}

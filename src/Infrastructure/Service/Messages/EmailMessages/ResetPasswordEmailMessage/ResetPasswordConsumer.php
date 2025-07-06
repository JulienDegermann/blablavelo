<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\ResetPasswordEmailMessage;

use App\Domain\User\User;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure\Repository\UserRepository;
use App\Domain\User\Contrat\PasswordRecoveryNotifierServiceInterface;
use App\Infrastructure\Service\NotifierService\UseCase\PasswordRecoveryNotifierService;
use App\Infrastructure\Service\Messages\EmailMessages\ResetPasswordEmailMessage\ResetPasswordConsumerInterface;

final class ResetPasswordConsumer implements ResetPasswordConsumerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly UserRepository $repo,
        private readonly PasswordRecoveryNotifierServiceInterface $notifier,
        private readonly EntityManagerInterface $em

    ) {}


    public function __invoke(int $user_id): void
    {
        $this->logger->info('======= reset password ======');

        $this->em->clear();
        $user = ($this->repo)->find($user_id) ?? null;


        if (!($user instanceof User)) {
            throw new InvalidArgumentException('User not found');
        }
        ($this->notifier)($user);
    }
}

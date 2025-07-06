<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\DeleteAccountEmailMessage;

use App\Domain\User\User;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;
use App\Infrastructure\Service\NotifierService\UseCase\DeleteAccountNotifierService;

/**
 * Class DeleteAccountConsumer - send email to confirm account deletion
 */
final class DeleteAccountConsumer implements DeleteAccountConsumerInterface
{
    public function __construct(
        private readonly DeleteAccountNotifierService $notifier,
        private readonly LoggerInterface $logger,

    ) {}

    public function __invoke(
        array $user_datas,
    ): void {
        $this->logger->info("============== delete account =================");

        if (!$user_datas) {
            throw new InvalidArgumentException('User not found');
        }
        $user = new User();
        $user->setNameNumber($user_datas['name_number'])->setEmail($user_datas['email']);

        if (!$user instanceof User) {
            throw new InvalidArgumentException('User not found');
        }

        ($this->notifier)($user);
    }
}

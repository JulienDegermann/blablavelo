<?php

namespace App\Domain\User\UseCase\SendRecoveryUrl;

use App\Domain\User\User;
use InvalidArgumentException;
use App\Infrastructure\Repository\UserRepository;
use App\Domain\User\Contrat\SendRecoveryUrlInterface;
use App\Infrastructure\Service\Messages\EmailMessages\ResetPasswordEmailMessage\ResetPasswordPublisherInterface;

final class SendRecoveryUrl implements SendRecoveryUrlInterface
{

    public function __construct(
        private readonly UserRepository $userRepo,
        private readonly ResetPasswordPublisherInterface $notifier,

    ) {}
    
    public function __invoke(SendRecoveryUrlInput $input): User
    {
        $user = $this->userRepo->findOneBy(['email' => $input->getEmail()]);

        if ($user === null) {
            throw new InvalidArgumentException('Un utilisateur avec cet email n\'existe pas'); // change this message
        }

        if ($user instanceof User) {
            ($this->notifier)($user->getId());
            return $user;
        } else {
            $user = new User();
        }
        return $user;
    }
}

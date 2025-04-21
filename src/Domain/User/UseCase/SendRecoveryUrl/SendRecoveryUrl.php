<?php

namespace App\Domain\User\UseCase\SendRecoveryUrl;

use App\Domain\User\Contrat\PasswordRecoveryNotifierServiceInterface;
use App\Domain\User\Contrat\SendRecoveryUrlInterface;
use App\Domain\User\User;
use App\Infrastructure\Repository\UserRepository;
use InvalidArgumentException;

final class SendRecoveryUrl implements SendRecoveryUrlInterface
{

    public function __construct(
        private readonly UserRepository $userRepo,
        private readonly PasswordRecoveryNotifierServiceInterface $notifier,

    ) {}
    public function __invoke(SendRecoveryUrlInput $input): User
    {
        $user = $this->userRepo->findOneBy(['email' => $input->getEmail()]);

        if ($user === null) {
            throw new InvalidArgumentException('Un utilisateur avec cet email n\'existe pas'); // change this message
        }

        if ($user instanceof User) {
            $this->notifier->notify($user);
            return $user;
        } else {
            $user = new User();
        }
        return $user;
    }
}

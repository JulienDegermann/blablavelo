<?php

namespace App\Domain\User\UseCase\DeleteAccount;

use App\Domain\User\Contrat\DeleteAccountInterface;
use App\Domain\User\Contrat\DeleteAccountNotifierServiceInterface;
use App\Domain\User\Contrat\UserRepositoryInterface;
use App\Domain\User\User;

final class DeleteAccount implements DeleteAccountInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepo,
        private readonly DeleteAccountNotifierServiceInterface $notifier
    ) {}

    public function __invoke(User $user): string
    {
        $user = $this->userRepo->find($user->getId());

        if (!$user instanceof User) {
            throw new \Exception('Utilisateur inexistant.');
        }

        $this->userRepo->remove($user);

        ($this->notifier)($user);

        return 'Ton compte a été supprimé avec succès. À bientôt 🚴';
    }
}

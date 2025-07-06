<?php

namespace App\Domain\User\UseCase\DeleteAccount;

use Error;
use App\Domain\User\User;
use App\Domain\User\Contrat\DeleteAccountInterface;
use App\Domain\User\Contrat\UserRepositoryInterface;
use App\Infrastructure\Service\Messages\EmailMessages\DeleteAccountEmailMessage\DeleteAccountPublisherInterface;

final class DeleteAccount implements DeleteAccountInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepo,
        private readonly DeleteAccountPublisherInterface $notifier

    ) {}

    public function __invoke(User $user): string
    {
        $user = $this->userRepo->find($user->getId());

        if (!$user instanceof User) {
            throw new \Exception('Utilisateur inexistant.');
        }
        try {

            ($this->notifier)($user->getId(), ['user' => [
                'name_number' => $user->getNameNumber(),
                'email' => $user->getEmail(),
            ]]);
        } catch (Error $e) {
            dd($e);
        }

        $this->userRepo->remove($user);


        return 'Ton compte a été supprimé avec succès. À bientôt 🚴';
    }
}

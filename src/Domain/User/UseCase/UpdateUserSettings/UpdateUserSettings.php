<?php

namespace App\Domain\User\UseCase\UpdateUserSettings;

use App\Domain\User\Contrat\UpdateUserSettingsInterface;
use App\Domain\User\Contrat\UpdateUserSettingsNotifierServiceInterface;
use App\Domain\User\Contrat\UserRepositoryInterface;
use App\Domain\User\User;

final class UpdateUserSettings implements UpdateUserSettingsInterface
{
    public function __construct(
        private readonly UserRepositoryInterface                    $userRepo,
        private readonly UpdateUserSettingsNotifierServiceInterface $notifier
    ) {}

    public function __invoke(UpdateUserSettingsInput $input, User $user): User
    {
        $prevEmail = $user->getEmail();

        $user
            ->setDepartment($input->getDepartment())
            ->setMind($input->getMind())
            ->setPractice($input->getPractice());

        if ($prevEmail !== $input->getEmail()) {
            $user
                ->setEmail($input->getEmail())
                ->setIsVerified(false);
        }
        
        $this->userRepo->save($user);

        if ($prevEmail !== $input->getEmail()) {
            $this->notifier->notify($user);
        }

        return $user;
    }
}

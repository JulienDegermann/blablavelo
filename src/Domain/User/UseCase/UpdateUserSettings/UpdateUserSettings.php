<?php

namespace App\Domain\User\UseCase\UpdateUserSettings;

use App\Domain\User\Contrat\UpdateUserSettingsInterface;
use App\Domain\User\Contrat\UpdateUserSettingsNotifierServiceInterface;
use App\Domain\User\Contrat\UserRepositoryInterface;
use App\Domain\User\User;
use App\Infrastructure\Service\Messages\EmailMessages\EmailVerificationEmailMessage\EmailVerificationPublisherInterface;
use Psr\Log\LoggerInterface;

final class UpdateUserSettings implements UpdateUserSettingsInterface
{
    public function __construct(
        private readonly UserRepositoryInterface                    $userRepo,
        private readonly EmailVerificationPublisherInterface $notifier,
        private readonly LoggerInterface $logger
    ) {}

    public function __invoke(UpdateUserSettingsInput $input, User $user): string
    {
        $prevEmail = $user->getEmail();

        $user
            ->setDepartment($input->getDepartment())
            ->setMind($input->getMind())
            ->setPractice($input->getPractice());

        // avoid use other email user's
        // if ($this->userRepo->findOneBy(['email' => $input->getEmail()])) {
        //     throw new InvalidArgumentException('Email invalide.');
        // }
        if ($prevEmail !== $input->getEmail()) {
            $user
                ->setEmail($input->getEmail())
                ->setIsVerified(false);
        }

        $this->userRepo->save($user);
        if ($prevEmail !== $input->getEmail()) {
            $refresh = $this->userRepo->find($user->getId());

            $this->logger->info('======= PUBLISHER EMAIL from DB : ' . $refresh->getEmail());
            ($this->notifier)($user->getId());
        }

        return "Ton profil a été mis à jour avec succès. 👍";
    }
}

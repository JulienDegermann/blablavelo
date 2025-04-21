<?php

namespace App\Domain\User\UseCase\ResetPassword;

use App\Domain\User\Contrat\ResetPasswordInterface;
use App\Domain\User\Contrat\UserRepositoryInterface;
use App\Domain\User\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ResetPassword implements ResetPasswordInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UserRepositoryInterface $userRepo
    ) {}
    public function __invoke(ResetPasswordInput $input): User
    {
        $user = $input->getUser();

        $user->setPassword($this->hasher->hashPassword($user, $input->getPassword()));
        $this->userRepo->save($user);

        return $user;
    }
}

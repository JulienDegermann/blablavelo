<?php

namespace App\Domain\User\UseCase\CreateUser;

use App\Domain\User\Contrat\CreateUserInterface;
use App\Domain\User\Contrat\CreateUserNotifierServiceInterface;
use App\Domain\User\Contrat\UserRepositoryInterface;
use App\Domain\User\User;
use InvalidArgumentException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


/**
 * Class CreateUser - create a new user, save it and notify the user for e-mail validation
 * @implements CreateUserInterface
 * @return User 
 */
final class CreateUser implements CreateUserInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepo,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly  CreateUserNotifierServiceInterface $notifier
    ) {}



    public function __invoke(CreateUserInput $input): User
    {

        $user = new User();

        if ($input->getRGPDConscents() === false || !($input->getRGPDConscents())) {
            throw new InvalidArgumentException('Vous devez accepter les conditions d\'utilisation pour continuer.');
        }

        // if combo email + password matches with an existing user, connect the user ?
        $registedUser = $this->userRepo->findOneBy(['email' => $input->getEmail(), 'nameNumber' => $input->getNameNumber()]);
        if ($registedUser) {
            throw new InvalidArgumentException('Identifiants invalides. Veuillez réessayer.');
        }

        // conditions for password
        // conditions for email
        // conditions for username


        $user
            ->setEmail($input->getEmail())
            ->setNameNumber($input->getNameNumber())
            ->setPassword($this->hasher->hashPassword($user, $input->getPassword()));

        $this->userRepo->save($user);

        
        $this->notifier->notify($user);


        return $user;
    }
}

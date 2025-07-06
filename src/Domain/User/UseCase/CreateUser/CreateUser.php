<?php

namespace App\Domain\User\UseCase\CreateUser;

use App\Domain\User\User;
use InvalidArgumentException;
use App\Domain\User\Contrat\CreateUserInterface;
use App\Domain\User\Contrat\UserRepositoryInterface;
use App\Domain\User\UseCase\CreateUser\CreateUserInput;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Infrastructure\Service\Messages\EmailMessages\EmailVerificationEmailMessage\EmailVerificationPublisherInterface;

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
        private readonly  EmailVerificationPublisherInterface $notifier
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

        // conditions for username
        if (
            gettype($input->getNameNumber()) !== 'string' ||
            strlen($input->getNameNumber()) < 3 ||
            strlen($input->getNameNumber()) > 50
        ) {
            throw new InvalidArgumentException('Nom d\'utilisateur invalide. Il doit contenir entre 3 et 50 caractères.');
        }

        if (preg_match('/[^a-z_\-0-9]/i', $input->getNameNumber())) {
            throw new InvalidArgumentException('Nom d\'utilisateur invalide. Il doit contenir uniquement des lettres, des chiffres, des tirets et des underscores.');
        }


        // conditions for email
        if (
            !filter_var($input->getEmail(), FILTER_VALIDATE_EMAIL) ||
            strlen($input->getEmail()) < 3 ||
            strlen($input->getEmail()) > 255 ||
            !preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-]+)*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)$/', $input->getEmail())
        ) {
            throw new InvalidArgumentException('Adresse e-mail invalide. Elle doit correspondre au format "sample@email.com".');
        }

        // conditions for password
        if (
            strlen($input->getPassword()) < $x = 12 ||
            strlen($input->getPassword()) < $y = 255
        ) {
            throw new InvalidArgumentException("Mot de passe invalide. Il doit contenir entre $x et $y caractères.");
        }

        if (!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{12,}$/', $input->getPassword())) {
            throw new InvalidArgumentException('Mot de passe invalide. Il doit contenir au moins 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial (&, @, #, $, !, ?, *, %).');
        }

        if (
            $this->userRepo->findOneBy(['email' => $input->getEmail()]) ||
            $this->userRepo->findOneBy(['nameNumber' => $input->getNameNumber()])
        ) {
            throw new InvalidArgumentException('Identifiants invalides. Esssaye avec de nouveaux.');
        }


        $user
            ->setEmail($input->getEmail())
            ->setNameNumber($input->getNameNumber())
            ->setPassword($this->hasher->hashPassword($user, $input->getPassword()));

        $this->userRepo->save($user);

        ($this->notifier)($user->getId());

        return $user;
    }
}

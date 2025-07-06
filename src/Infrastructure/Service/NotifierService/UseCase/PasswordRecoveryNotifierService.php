<?php

namespace App\Infrastructure\Service\NotifierService\UseCase;

use App\Domain\User\Contrat\JWTTokenGeneratorServiceInterface;
use App\Domain\User\Contrat\PasswordRecoveryNotifierServiceInterface;
use App\Domain\User\User;
use Symfony\Component\Mime\Email;
use App\Domain\User\Contrat\SendNewValidationTokenInterface;
use App\Infrastructure\Service\NotifierService\NotifierConfig;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PasswordRecoveryNotifierService implements PasswordRecoveryNotifierServiceInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly JWTTokenGeneratorServiceInterface $JWTGenerator,
        private readonly NotifierConfig $config,
        private LoggerInterface $logger
    ) {}

    public function __invoke(User $user): void
    {
        $this->logger->error('CALLED HERE');
        $userName = $user->getNameNumber();

        $url = $this->urlGenerator->generate('app_pwd_reset', ['token' => ($this->JWTGenerator)(['user_id' => $user->getId()])], UrlGeneratorInterface::ABSOLUTE_URL);
        $text = "Bonjour $userName, \n\nVous avez demandé à réinitialiser votre mot de passe. Pour cela, veuillez cliquer sur le lien ci-dessous (valide 1 heure) : ";
        $text .= "\n $url";
        $text .= $this->config->getSignature();

        $email = (new Email())
            ->from($this->config->getFrom())
            ->to($user->getEmail())
            ->subject('Réinitialisation de votre mot de passe.')
            ->text($text);

        $this->mailer->send($email);
    }
}

<?php

namespace App\Infrastructure\Service\NotifierService\UseCase;

use App\Domain\User\Contrat\CreateUserNotifierServiceInterface;
use App\Domain\User\Contrat\JWTTokenGeneratorServiceInterface;
use App\Domain\User\Contrat\SendNewValidationTokenInterface;
use App\Domain\User\User;
use App\Infrastructure\Service\NotifierService\NotifierConfig;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SendNewValidationToken implements SendNewValidationTokenInterface
{
    public function __construct(
        private readonly CreateUserNotifierServiceInterface $notifier,
        private readonly MailerInterface $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly JWTTokenGeneratorServiceInterface $JWTGenerator,
        private readonly NotifierConfig $config
    ) {}

    public function __invoke(User $user): Email
    {
        $userName = $user->getNameNumber();

        $url = $this->urlGenerator->generate('app_email_verify', ['token' => ($this->JWTGenerator)(['user_id' => $user->getId()])], UrlGeneratorInterface::ABSOLUTE_URL);
        $text = "Bonjour $userName, \n\n tu as demandé un nouveau lien d'activation. Clique sur le lien ci-dessous (valide 1 heure) : ";
        $text .= "\n $url";
        $text .= $this->config->getSignature();

        $email = (new Email())
            ->from($this->config->getFrom())
            ->to($user->getEmail())
            ->subject('Validation addresse e-mail.')
            ->text($text);

        $this->mailer->send($email);

        return $email;
    }
}

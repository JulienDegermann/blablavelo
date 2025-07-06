<?php

namespace App\Infrastructure\Service\NotifierService\UseCase;

use App\Domain\User\User;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use App\Domain\User\Contrat\JWTTokenGeneratorServiceInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Domain\User\Contrat\CreateUserNotifierServiceInterface;
use App\Domain\User\Contrat\EmailVerificationNotifierServiceInterface;
use App\Infrastructure\Service\NotifierService\NotifierConfigInterface;

final class EmailVerificationNotifierService implements EmailVerificationNotifierServiceInterface
{
    public function __construct(
        private readonly MailerInterface                   $mailer,
        private readonly NotifierConfigInterface           $notifierConfig,
        private readonly UrlGeneratorInterface             $urlGenerator,
        private readonly JWTTokenGeneratorServiceInterface $JWTTokenGenerator
    ) {}

    public function __invoke(User $user): Email
    {
        $email = new Email();

        $datas = [
            'user_id' => $user->getId(),
        ];

        $url = $this->urlGenerator->generate('app_email_verify', ['token' => ($this->JWTTokenGenerator)($datas)], UrlGeneratorInterface::ABSOLUTE_URL);
        $userName = $user->getNameNumber();
        $text = "Bonjour $userName, Bienvenue sur Blabla Vélo. Pour utiliser l'application, merci de valider ton e-mail avec le lien ci-dessous : \n";

        $text .= $url;
        $text .= $this->notifierConfig->getSignature();


        $email
            ->from($this->notifierConfig->getFrom())
            ->subject("Bienvenue sur Blaba Vélo 🚴")
            ->to($user->getEmail())
            ->text($text);

        $this->mailer->send($email);

        return $email;
    }
}

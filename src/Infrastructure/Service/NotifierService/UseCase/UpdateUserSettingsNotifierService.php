<?php

namespace App\Infrastructure\Service\NotifierService\UseCase;

use App\Domain\User\Contrat\JWTTokenGeneratorServiceInterface;
use App\Domain\User\Contrat\UpdateUserSettingsNotifierServiceInterface;
use App\Domain\User\User;
use App\Infrastructure\Service\NotifierService\NotifierConfigInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class UpdateUserSettingsNotifierService implements UpdateUserSettingsNotifierServiceInterface
{
    public function __construct(
        private readonly MailerInterface                   $mailer,
        private readonly NotifierConfigInterface           $notifierConfig,
        private readonly UrlGeneratorInterface             $urlGenerator,
        private readonly JWTTokenGeneratorServiceInterface $JWTTokenGenerator
    )
    {
    }

    public function notify(User $user): Email
    {
        $email = new Email();

        // generate url with JWT token
        $datas = [];
        $datas['user_id'] = $user->getId();
        $url = $this->urlGenerator->generate('app_email_verify', ['token' => ($this->JWTTokenGenerator)($datas)], UrlGeneratorInterface::ABSOLUTE_URL);

        $userName = $user->getNameNumber();
        $text = "Bonjour $userName, l'email de ton compte a été modifié. \n Merci de valider cette nouvelle adresse via le lien ci-dessous pour réactiver ton compte : \n ";
        $text .= $url;
        $text .= $this->notifierConfig->getSignature();

        $email
            ->from($this->notifierConfig->getFrom())
            ->subject("Mise à jour de l'e-mail")
            ->to($user->getEmail())
            ->text($text);

        $this->mailer->send($email);

        return $email;
    }
}
<?php

namespace App\Infrastructure\Service\NotifierService\UseCase;

use App\Domain\User\Contrat\DeleteAccountNotifierServiceInterface;
use App\Domain\User\User;
use App\Infrastructure\Service\NotifierService\NotifierConfigInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class DeleteAccountNotifierService implements DeleteAccountNotifierServiceInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly NotifierConfigInterface $config
    ) {}
    public function __invoke(User $user): Email
    {
        $userName = $user->getNameNumber();
        $userEmail = $user->getEmail();

        $text = "Bonjour $userName, ton compte Blabla Vélo a été supprimé avec succès. \n À bientôt 🚴.";
        $text .= $this->config->getSignature();

        $email = (new Email())
            ->from($this->config->getFrom())
            ->to($userEmail)
            ->subject('Suppression de compte')
            ->text($text);

        $this->mailer->send($email);
    
        return new Email();
    }
}

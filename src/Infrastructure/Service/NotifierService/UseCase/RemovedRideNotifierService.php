<?php

namespace App\Infrastructure\Service\NotifierService\UseCase;

use App\Domain\Ride\Contrat\RemovedRideNotifierServiceInterface;
use App\Domain\Ride\Ride;
use App\Domain\User\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class RemovedRideNotifierService implements RemovedRideNotifierServiceInterface
{
    public function __construct(
        private readonly MailerInterface $mailer)
    {
    }

    public function __invoke(User $participant, Ride $ride): Email
    {
        $email = new Email;
        $email
            ->from('env(MAILER_FROM_ADDRESS)')
            ->to($participant->getEmail())
            ->subject('Important : sortie annulée')
            ->text("Bonjour $participant->getUserName(), la sortie du $ride->getStartDate(), organisée par $ride->getCreator(), a été annulée. N'hésite pas à retourner sur l'application pour trouver une nouvelle sortie ! L'équipe Blabla Vélo. ");
        $this->mailer->send($email);

        return $email;
    }
}
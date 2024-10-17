<?php

namespace App\Infrastructure\Service\NotifierService;

use App\Domain\Ride\Contrat\RemovedParticipantNotifierServieInterface;
use App\Domain\Ride\Ride;
use App\Domain\User\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RemovedParticipantNotifierService implements RemovedParticipantNotifierServieInterface
{
    public function __construct(
        private readonly MailerInterface         $mailer,
        private readonly NotifierConfigInterface $notifierConfig)
    {
    }

    public
    function __invoke(Ride $ride, User $participant): Email
    {
        $email = new Email();

        $creatorEmail = $ride->getCreator()->getEmail();
        $creatorUserName = $ride->getCreator()->getNameNumber();
        $participantUserName = $participant->getNameNumber();
        $date = $ride->getStartDate()->format('dd/MM/yyyy');
        $title = $ride->getTitle();

        $textBody = "Bonjour $creatorUserName, Nous t'informons que $participantUserName s'est désinscrit à ta sortie $title du $date).";
        $textBody .= "\n" . $this->notifierConfig->getSignature();

        $email
            ->from($this->notifierConfig->getFrom())
            ->to($creatorEmail)
            ->subject("$title : un membre a annulé.")
            ->text($textBody);

        $this->mailer->send($email);

        return $email;
    }
}
<?php

namespace App\Infrastructure\Service\NotifierService\UseCase;

use App\Domain\Ride\Contrat\AddParticipantNotifierServiceInterface;
use App\Domain\Ride\Ride;
use App\Domain\User\User;
use App\Infrastructure\Service\NotifierService\NotifierConfigInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class AddParticipantNotifierService implements AddParticipantNotifierServiceInterface
{
    public function __construct(
        private readonly NotifierConfigInterface $notifierConfig,
        private readonly MailerInterface         $mailer,

    )
    {
    }

    public function __invoke(Ride $ride, User $participant): Email
    {
        $email = new Email();

        $creatorUserName = $ride->getCreator()->getNameNumber();
        $creatorEmail = $ride->getCreator()->getEmail();
        $participantUserName = $participant->getNameNumber();
        $title = $ride->getTitle();
        $startDate = $ride->getStartDate()->format('d-m-Y');

        $textBody = "Bonjour $creatorUserName, \n\n $participantUserName s'est inscrit à ta sortie \"$title\" du $startDate.";
        $textBody .= $this->notifierConfig->getSignature();

        $email
            ->from($this->notifierConfig->getFrom())
            ->to($creatorEmail)
            ->subject("$title : Un membre s'est inscrit.")
            ->text($textBody);

        $this->mailer->send($email);

        return $email;
    }
}
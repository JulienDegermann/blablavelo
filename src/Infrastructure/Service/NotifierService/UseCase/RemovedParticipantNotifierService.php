<?php

namespace App\Infrastructure\Service\NotifierService\UseCase;

use App\Domain\Ride\Contrat\RemovedParticipantNotifierServiceInterface;
use App\Domain\Ride\Ride;
use App\Domain\User\User;
use App\Infrastructure\Service\NotifierService\NotifierConfigInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RemovedParticipantNotifierService implements RemovedParticipantNotifierServiceInterface
{
    public function __construct(
        private readonly MailerInterface         $mailer,
        private readonly NotifierConfigInterface $notifierConfig
    )
    {
    }

    public
    function __invoke(Ride $ride, User $participant): Email
    {
        $email = new Email();

        $participantUserName = $participant->getNameNumber();
        $creatorEmail = $ride->getCreator()->getEmail();
        $creatorUserName = $ride->getCreator()->getNameNumber();
        $startDate = $ride->getStartDate()->format('d-m-Y');
        $title = $ride->getTitle();

        $textBody = "Bonjour $creatorUserName, $participantUserName s'est désinscrit à ta sortie $title du $startDate).";
        $textBody .= $this->notifierConfig->getSignature();

        $email
            ->from($this->notifierConfig->getFrom())
            ->to($creatorEmail)
            ->subject("$title : un membre a annulé.")
            ->text($textBody);

        $this->mailer->send($email);

        return $email;
    }
}
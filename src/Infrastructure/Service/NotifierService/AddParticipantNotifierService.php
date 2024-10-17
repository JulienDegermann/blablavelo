<?php

namespace App\Infrastructure\Service\NotifierService;

use App\Domain\Ride\Contrat\AddParticipantNotifierServiceInterface;
use App\Domain\Ride\Ride;
use App\Domain\User\User;
use Symfony\Component\Mime\Email;

final class AddParticipantNotifierService implements AddParticipantNotifierServiceInterface
{
    public function __construct(private readonly NotifierConfigInterface $notifierConfig)
    {
    }

    public function __invoke(Ride $ride, User $participant): Email
    {
        $email = new Email();

        $creatorUserName = $ride->getCreator()->getNameNumber();
        $creatorEmail = $ride->getCreator()->getEmail();
        $participantUserName = $participant->getNameNumber();
        $title = $ride->getTitle();

        $textBody = "Bonjour $creatorUserName, $participantUserName s'est inscrit à ta sortie $title.";
        $textBody .= "\n" . $this->notifierConfig->getSignature();

        $email
            ->from($this->notifierConfig->getFrom())
            ->to($creatorEmail)
            ->subject("$title : Un membre s'est inscrit.")
            ->text($textBody);

        return $email;
    }
}
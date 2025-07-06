<?php

namespace App\Infrastructure\Service\NotifierService\UseCase;

use App\Domain\Ride\Contrat\RemovedRideNotifierServiceInterface;
use App\Domain\Ride\Ride;
use App\Domain\User\User;
use App\Infrastructure\Service\NotifierService\NotifierConfig;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * class RemoveRideNotifierService - Send an e-mail to ride's particpant when it's canceled
 */
final class RemovedRideNotifierService implements RemovedRideNotifierServiceInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly NotifierConfig  $notifierConfig
    ) {}

    /**
     * Send an e-mail to ride's partipant when it's canceled
     * @param User $participant - user who will recieve the mail
     * @param Ride $ride - ride which has been canceled
     * @return Email - the mail that will be sent
     */
    public function __invoke(User $participant, Ride $ride): Email
    {
        $email = new Email;

        $startDate = $ride->getStartDate()->format('d-m-Y');
        $creator = $ride->getCreator()->getNameNumber();
        $userName = $participant->getNameNumber();

        $textBody = "Bonjour $userName, la sortie du $startDate, organisée par $creator, a été annulée. N'hésite pas à retourner sur l'application pour trouver une nouvelle sortie !";
        $textBody .= $this->notifierConfig->getSignature();

        $email
            ->from($this->notifierConfig->getFrom())
            ->to($participant->getEmail())
            ->subject('Important : sortie annulée')
            ->text($textBody);
        $this->mailer->send($email);

        return $email;
    }
}

<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\AddParticipantEmailMessage;

use App\Infrastructure\Service\Messages\EmailMessages\SendEmailPublisherAbstract;
use App\Infrastructure\Service\Messages\EmailMessages\AddParticipantEmailMessage\AddParticipantPublisherInterface;

final class AddParticipantPublisher extends SendEmailPublisherAbstract implements AddParticipantPublisherInterface
{
    public function getType(): string
    {
        return 'add_participant';
    }
}

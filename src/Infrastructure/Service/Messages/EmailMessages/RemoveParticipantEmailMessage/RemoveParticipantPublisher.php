<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\RemoveParticipantEmailMessage;

use App\Infrastructure\Service\Messages\EmailMessages\SendEmailPublisherAbstract;
use App\Infrastructure\Service\Messages\EmailMessages\RemoveParticipantEmailMessage\RemoveParticipantPublisherInterface;


final class RemoveParticipantPublisher extends SendEmailPublisherAbstract implements RemoveParticipantPublisherInterface
{
    public function getType(): string
    {
        return 'remove_participant';
    }
}

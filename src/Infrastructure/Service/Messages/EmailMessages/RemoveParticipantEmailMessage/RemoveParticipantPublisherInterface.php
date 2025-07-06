<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\RemoveParticipantEmailMessage;

interface RemoveParticipantPublisherInterface
{
    public function getType(): string;
}

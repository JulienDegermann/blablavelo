<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\AddParticipantEmailMessage;

interface AddParticipantPublisherInterface
{
    public function getType(): string;
}

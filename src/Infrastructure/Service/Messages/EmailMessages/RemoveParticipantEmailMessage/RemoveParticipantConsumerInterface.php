<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\RemoveParticipantEmailMessage;

interface RemoveParticipantConsumerInterface
{
    public function __invoke(
        int $ride_id,
        int $participant_id
    ): void;
}

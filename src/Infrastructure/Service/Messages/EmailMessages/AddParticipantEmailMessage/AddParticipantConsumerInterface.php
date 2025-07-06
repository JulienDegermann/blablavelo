<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\AddParticipantEmailMessage;

interface AddParticipantConsumerInterface
{
    public function __invoke(
        int $ride_id,
        int $participant_id
    ): void;
}

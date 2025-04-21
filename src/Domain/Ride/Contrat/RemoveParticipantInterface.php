<?php

namespace App\Domain\Ride\Contrat;

use App\Domain\Ride\Ride;
use App\Domain\Ride\UseCase\RemoveParticipant\RemoveParticipantInput;

interface RemoveParticipantInterface
{
    public function __invoke(RemoveParticipantInput $input): Ride;
}
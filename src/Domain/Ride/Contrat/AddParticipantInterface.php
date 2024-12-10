<?php

namespace App\Domain\Ride\Contrat;
use App\Domain\Ride\Ride;
use App\Domain\Ride\UseCase\AddParticipant\AddParticipantInput;

interface AddParticipantInterface
{
    public function __invoke(AddParticipantInput $input): Ride;
}
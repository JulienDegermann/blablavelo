<?php

namespace App\Domain\Ride\UseCase\RemoveParticipant;

final class RemoveParticipantInput
{
    private int $rideId;

    private User $participant;

    public function getParticipant(): User
    {
        return $this->participant;
    }

    public function getRideId(): int
    {
        return $this->rideId;
    }
}